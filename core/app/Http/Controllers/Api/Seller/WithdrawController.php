<?php

namespace App\Http\Controllers\Api\Seller;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\ActionNotification;
use App\Models\AdminNotification;
use App\Models\Transaction;
use App\Models\Withdrawal;
use App\Models\WithdrawMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WithdrawController extends Controller
{
    public function withdrawMethods()
    {
        $methods = WithdrawMethod::where('status', Status::ENABLE)->get();
        $notify[] = 'Withdrawal methods';
        return response()->json([
            'remark' => 'withdraw_methods',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'methods' => $methods,
            ]
        ]);
    }

    public function withdrawStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'method_code' => 'required',
            'amount' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'remark' => 'validation_error',
                'status' => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $method = WithdrawMethod::where('id', $request->method_code)->where('status', Status::ENABLE)->first();
        if (!$method) {
            $notify[] = 'Phương thức rút tiền không tồn tại';
            return response()->json([
                'remark' => 'method_not_found',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $seller = auth()->user();

        if ($request->amount < $method->min_limit) {
            $notify[] = 'Số tiền rút tối thiểu là ' . showAmount($method->min_limit);
            return response()->json([
                'remark' => 'min_limit_error',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }
        if ($request->amount > $method->max_limit) {
            $notify[] = 'Số tiền rút tối đa là ' . showAmount($method->max_limit);
            return response()->json([
                'remark' => 'max_limit_error',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        if ($request->amount > $seller->balance) {
            $notify[] = 'Số dư không đủ để rút tiền';
            return response()->json([
                'remark' => 'insufficient_balance',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $charge = $method->fixed_charge + ($request->amount * $method->percent_charge / 100);
        $afterCharge = $request->amount - $charge;
        $finalAmount = $afterCharge * $method->rate;

        $withdraw = new Withdrawal();
        $withdraw->method_id = $method->id;
        $withdraw->seller_id = $seller->id;
        $withdraw->amount = $request->amount;
        $withdraw->currency = $method->currency;
        $withdraw->rate = $method->rate;
        $withdraw->charge = $charge;
        $withdraw->final_amount = $finalAmount;
        $withdraw->after_charge = $afterCharge;
        $withdraw->trx = getTrx();
        $withdraw->save();

        $notify[] = 'Yêu cầu rút tiền đã được khởi tạo';
        return response()->json([
            'remark' => 'withdraw_initiated',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'withdraw' => $withdraw,
                'form' => $method->form // Send form structure for next step
            ]
        ]);
    }

    public function withdrawSubmit(Request $request)
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

        $withdraw = Withdrawal::with('method')->where('trx', $request->trx)->where('status', Status::PAYMENT_INITIATE)->where('seller_id', auth()->id())->first();

        if (!$withdraw) {
            $notify[] = 'Yêu cầu rút tiền không hợp lệ';
            return response()->json([
                'remark' => 'invalid_request',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $method = $withdraw->method;
        $formData = @$method->form->form_data ?? [];

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

        $seller = auth()->user();
        if ($withdraw->amount > $seller->balance) {
            $notify[] = 'Số dư không đủ';
            return response()->json([
                'remark' => 'insufficient_balance',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $withdraw->status = Status::PAYMENT_PENDING;
        $withdraw->withdraw_information = $userData;
        $withdraw->save();

        $seller->balance -= $withdraw->amount;
        $seller->save();

        $transaction = new Transaction();
        $transaction->seller_id = $withdraw->seller_id;
        $transaction->amount = $withdraw->amount;
        $transaction->post_balance = $seller->balance;
        $transaction->charge = $withdraw->charge;
        $transaction->trx_type = '-';
        $transaction->details = 'Rút tiền qua ' . $withdraw->method->name;
        $transaction->trx = $withdraw->trx;
        $transaction->remark = 'withdraw';
        $transaction->save();

        $notify[] = 'Yêu cầu rút tiền đã được gửi phê duyệt';
        return response()->json([
            'remark' => 'withdraw_submitted',
            'status' => 'success',
            'message' => ['success' => $notify],
        ]);
    }

    public function history()
    {
        $withdrawals = Withdrawal::where('seller_id', auth()->id())
            ->where('status', '!=', Status::PAYMENT_INITIATE)
            ->with('method')
            ->latest()
            ->paginate(getPaginate());

        $notify[] = 'Withdrawal history';
        return response()->json([
            'remark' => 'withdraw_history',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'withdrawals' => $withdrawals,
            ]
        ]);
    }
}
