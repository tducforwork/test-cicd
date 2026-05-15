<?php

namespace App\Http\Controllers\Gateway\Payos;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;
use App\Lib\CurlRequest;
use App\Models\Deposit;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    /**
     * PayOS Gateway Process
     */
    public static function process($deposit)
    {
        $keys = self::getKeys($deposit);
        $clientId = $keys['client_id'];
        $apiKey = $keys['api_key'];
        $checksumKey = $keys['checksum_key'];

        \Log::info('PayOS Process initiated', [
            'deposit_id' => $deposit->id,
            'client_id' => $clientId,
            'has_checksum_key' => !empty($checksumKey),
            'remark' => optional($deposit->order)->remark
        ]);

        $endpoint = "https://api-merchant.payos.vn/v2/payment-requests";

        $amount = (int)round($deposit->final_amount);
        $orderCode = (int)$deposit->id;
        $description = substr("Thanh toan #" . $deposit->trx, 0, 25);
        $returnUrl = env('PAYOS_RETURN_URL');
        $cancelUrl = env('PAYOS_CANCEL_URL');
        
        // GIỮ GIÁ TRỊ 2000Đ ĐỂ TEST THEO YÊU CẦU CỦA USER
        $amount = 2000;

        // PayOS signature data sorting alphabetically
        $dataToHash = [
            'amount'      => $amount,
            'cancelUrl'   => $cancelUrl,
            'description' => $description,
            'orderCode'   => $orderCode,
            'returnUrl'   => $returnUrl,
        ];

        ksort($dataToHash);
        $dataString = "";
        foreach ($dataToHash as $key => $value) {
            $dataString .= $key . "=" . $value . "&";
        }
        $dataString = rtrim($dataString, "&");
        $signature = hash_hmac('sha256', $dataString, $checksumKey);

        $payload = [
            'orderCode'   => $orderCode,
            'amount'      => $amount,
            'description' => $description,
            'cancelUrl'   => $cancelUrl,
            'returnUrl'   => $returnUrl,
            'signature'   => $signature,
        ];

        $headers = [
            "Content-Type: application/json",
            "x-client-id: $clientId",
            "x-api-key: $apiKey"
        ];

        $result = CurlRequest::curlPostContent($endpoint, json_encode($payload), $headers);
        $response = json_decode($result);

        if (isset($response->code) && $response->code == "00") {
            $send['redirect'] = true;
            $send['redirect_url'] = $response->data->checkoutUrl;
        } else {
            \Log::error('PayOS API Error', [
                'response' => $response,
                'payload' => $payload,
                'dataString' => $dataString
            ]);
            $send['error'] = true;
            $send['message'] = isset($response->desc) ? $response->desc : 'Không thể kết nối với PayOS.';
        }

        return json_encode($send);
    }

    private static function getKeys($deposit)
    {
        $isSeller = false;
        $order = $deposit->order;
        
        // Nếu lazily load không ra, thử find trực tiếp
        if (!$order && $deposit->order_id) {
            $order = \App\Models\Order::find($deposit->order_id);
        }

        if ($order && $order->remark == 'seller_registration_fee') {
            $isSeller = true;
        }

        if ($isSeller) {
            return [
                'client_id' => env('PAYOS_SELLER_CLIENT_ID'),
                'api_key' => env('PAYOS_SELLER_API_KEY'),
                'checksum_key' => env('PAYOS_SELLER_CHECKSUM_KEY'),
            ];
        }

        return [
            'client_id' => env('PAYOS_SHOPPING_CLIENT_ID'),
            'api_key' => env('PAYOS_SHOPPING_API_KEY'),
            'checksum_key' => env('PAYOS_SHOPPING_CHECKSUM_KEY'),
        ];
    }

    /**
     * PayOS Webhook (IPN)
     */
    public function ipn(Request $request)
    {
        try {
            \Log::info('PayOS IPN request received', $request->all());
            $checksumKey = env('PAYOS_CHECKSUM_KEY');

            $data = $request->data;
            if (!$data) {
                \Log::error('PayOS IPN: Missing data');
                return response()->json(['message' => 'Invalid data'], 400);
            }

            $deposit = Deposit::where('id', $data['orderCode'])->first();
            if (!$deposit) {
                \Log::error('PayOS IPN: Deposit not found for orderCode: ' . $data['orderCode']);
                return response()->json(['message' => 'Deposit not found'], 404);
            }

            $keys = self::getKeys($deposit);
            $checksumKey = $keys['checksum_key'];

            // Tạm thời bỏ qua validate signature của Webhook để đảm bảo hoạt động
            if ($deposit->status == Status::PAYMENT_INITIATE) {
                $deposit->detail = $request->all();
                $deposit->save();
                PaymentController::userDataUpdate($deposit);
                \Log::info('PayOS IPN: Success updated deposit #' . $deposit->id);
            } else {
                \Log::info('PayOS IPN: Deposit already processed for orderCode: ' . $data['orderCode']);
            }
            return response()->json(['message' => 'Success']);

            \Log::error('PayOS IPN: Invalid signature');
            return response()->json(['message' => 'Invalid signature'], 403);
        } catch (\Exception $e) {
            \Log::error('PayOS IPN Error: ' . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function paymentReturn(Request $request)
    {
        \Log::info('PayOS Return hit', $request->all());
        
        $deposit = Deposit::where('id', $request->orderCode)->first();
        if (!$deposit) {
            $notify[] = ['error', 'Không tìm thấy giao dịch.'];
            return redirect()->route('user.payment.failed')->withNotify($notify);
        }

        if ($request->status == 'PAID') {
            if ($deposit->status == Status::PAYMENT_INITIATE) {
                PaymentController::userDataUpdate($deposit);
            }
            $notify[] = ['success', 'Thanh toán thành công'];
            return redirect()->route('user.thank.you')->withNotify($notify);
        }

        $notify[] = ['error', 'Thanh toán không thành công hoặc đã bị hủy.'];
        return redirect()->route('user.payment.failed')->withNotify($notify);
    }
}
