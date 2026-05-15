<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function methods()
    {
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->with('method')->orderby('name')->get();

        $notify[] = 'Payment methods data';
        return response()->json([
            'remark' => 'payment_methods',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'methods' => $gatewayCurrency
            ]
        ]);
    }

    public function depositInsert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gateway' => 'required',
            'currency' => 'required',
            'order_number' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'remark' => 'validation_error',
                'status' => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $user = auth()->user();
        $order = Order::where('user_id', $user->id)->where('order_number', $request->order_number)->where('payment_status', Status::PAYMENT_INITIATE)->first();

        if (!$order) {
            $notify[] = 'Không tìm thấy đơn hàng hoặc đơn hàng đã được thanh toán';
            return response()->json([
                'remark' => 'order_not_found',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $gate = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->where('method_code', $request->gateway)->where('currency', $request->currency)->first();

        if (!$gate) {
            $notify[] = 'Phương thức thanh toán không hợp lệ';
            return response()->json([
                'remark' => 'invalid_gateway',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $amount = $order->total_amount;
        $charge = $gate->fixed_charge + ($amount * $gate->percent_charge / 100);
        $payable = $amount + $charge;
        $finalAmount = $payable;

        $deposit = new Deposit();
        $deposit->user_id = $user->id;
        $deposit->order_id = $order->id;
        $deposit->method_code = $gate->method_code;
        $deposit->method_currency = strtoupper($gate->currency);
        $deposit->amount = $amount;
        $deposit->charge = $charge;
        $deposit->rate = 1;
        $deposit->final_amount = $finalAmount;
        $deposit->btc_amount = 0;
        $deposit->btc_wallet = "";
        $deposit->trx = getTrx();
        $deposit->save();

        $notify[] = 'Yêu cầu thanh toán đã được khởi tạo';
        return response()->json([
            'remark' => 'deposit_inserted',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'deposit' => $deposit,
                'redirect_url' => route('api.deposit.confirm', ['trx' => $deposit->trx])
            ]
        ]);
    }

    public function appPaymentConfirm(Request $request)
    {
        $deposit = Deposit::where('trx', $request->trx)->where('status', Status::PAYMENT_INITIATE)->with('gateway')->first();

        if (!$deposit) {
            $notify[] = 'Không tìm thấy thông tin giao dịch';
            return response()->json([
                'remark' => 'deposit_not_found',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        if ($deposit->method_code >= 1000) {
            $notify[] = 'Vui lòng xác nhận thanh toán thủ công';
            return response()->json([
                'remark' => 'manual_payment',
                'status' => 'success',
                'message' => ['success' => $notify],
                'data' => [
                    'deposit' => $deposit,
                    'is_manual' => true
                ]
            ]);
        }

        $dirName = $deposit->gateway->alias;
        $new = 'App\\Http\\Controllers\\Gateway\\' . $dirName . '\\ProcessController';

        // Note: For API, we might need to return specific data based on gateway
        $data = $new::process($deposit);
        $data = json_decode($data);

        return response()->json([
            'remark' => 'payment_confirm',
            'status' => 'success',
            'data' => [
                'payment_data' => $data,
                'deposit' => $deposit
            ]
        ]);
    }

    public function manualDepositConfirm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trx' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'remark' => 'validation_error',
                'status' => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $deposit = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $request->trx)->first();
        if (!$deposit || $deposit->method_code < 1000) {
            $notify[] = 'Giao dịch không hợp lệ';
            return response()->json([
                'remark' => 'invalid_transaction',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $gatewayCurrency = $deposit->gatewayCurrency();
        $gateway = $gatewayCurrency->method;
        $formData = $gateway->form->form_data;

        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);

        $validator = Validator::make($request->all(), $validationRule);
        if ($validator->fails()) {
            return response()->json([
                'remark' => 'validation_error',
                'status' => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $userData = $formProcessor->processFormData($request, $formData);

        $deposit->detail = $userData;
        $deposit->status = Status::PAYMENT_PENDING;
        $deposit->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $deposit->user_id;
        $adminNotification->title = 'Yêu cầu thanh toán từ ' . $deposit->user->username;
        $adminNotification->click_url = urlPath('admin.deposit.details', $deposit->id);
        $adminNotification->save();

        $notify[] = 'Yêu cầu thanh toán của bạn đã được gửi và đang chờ duyệt';
        return response()->json([
            'remark' => 'manual_deposit_submitted',
            'status' => 'success',
            'message' => ['success' => $notify],
        ]);
    }
}
