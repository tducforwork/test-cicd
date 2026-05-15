<?php

namespace App\Http\Controllers\Gateway\Momo;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;
use App\Lib\CurlRequest;
use App\Models\Deposit;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    /*
     * MoMo Gateway Process
     */
    public static function process($deposit)
    {
        $momoAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);

        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create"; // Sandbox URL
        //$endpoint = "https://payment.momo.vn/v2/gateway/api/create"; // Production URL

        $partnerCode = $momoAcc->partner_code;
        $accessKey = $momoAcc->access_key;
        $secretKey = $momoAcc->secret_key;
        $orderInfo = "Thanh toán đơn hàng #" . $deposit->trx;
        $redirectUrl = route('user.deposit.momo.return');
        $ipnUrl = route('ipn.' . $deposit->gateway->alias);
        $amount = round($deposit->final_amount);
        $orderId = $deposit->trx . '_' . time();
        $requestId = $deposit->trx . '_' . time();
        $requestType = "payWithATM";
        $extraData = "";

        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;

        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );

        $result = CurlRequest::curlPostContent($endpoint, json_encode($data), ["Content-Type: application/json"]);
        $response = json_decode($result);

        if (isset($response->payUrl)) {
            $send['redirect'] = true;
            $send['redirect_url'] = $response->payUrl;
        } else {
            $send['error'] = true;
            $send['message'] = isset($response->message) ? $response->message : 'Không thể kết nối với MoMo.';
        }

        return json_encode($send);
    }

    public function ipn(Request $request)
    {
        $momo = \App\Models\Gateway::where('alias', 'Momo')->first();
        $gatewayCurrency = \App\Models\GatewayCurrency::where('method_code', $momo->code)->first();
        $momoAcc = json_decode($gatewayCurrency->gateway_parameter);
        $secretKey = $momoAcc->secret_key;
        $accessKey = $momoAcc->access_key;
        $amount = $request->amount;
        $extraData = $request->extraData;
        $message = $request->message;
        $orderId = $request->orderId;
        $orderInfo = $request->orderInfo;
        $orderType = $request->orderType;
        $partnerCode = $request->partnerCode;
        $payType = $request->payType;
        $requestId = $request->requestId;
        $responseTime = $request->responseTime;
        $resultCode = $request->resultCode;
        $transId = $request->transId;

        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&message=" . $message . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&orderType=" . $orderType . "&partnerCode=" . $partnerCode . "&payType=" . $payType . "&requestId=" . $requestId . "&responseTime=" . $responseTime . "&resultCode=" . $resultCode . "&transId=" . $transId;

        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        if (hash_equals($signature, $request->signature)) {
            $trx = explode('_', $orderId)[0];
            $deposit = Deposit::where('trx', $trx)->orderBy('id', 'DESC')->first();
            if ($deposit && $resultCode == 0 && $deposit->status == Status::PAYMENT_INITIATE) {
                $deposit->detail = $request->all();
                $deposit->save();
                PaymentController::userDataUpdate($deposit);
            }
        }

        return response()->json(['message' => 'Success']);
    }

    public function paymentReturn(Request $request)
    {
        $orderId = $request->orderId;
        $trx = explode('_', $orderId)[0];
        $deposit = Deposit::where('trx', $trx)->orderBy('id', 'DESC')->first();

        if (!$deposit) {
            $notify[] = ['error', 'Không tìm thấy thông tin đơn hàng.'];
            return redirect()->route('user.deposit.history')->withNotify($notify);
        }

        // Đã xử lý thành công rồi → redirect luôn, không báo lỗi
        if ($deposit->status == Status::PAYMENT_SUCCESS) {
            $notify[] = ['success', 'Thanh toán thành công'];
            return redirect()->route('user.deposit.history')->withNotify($notify);
        }

        $momoAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);
        $secretKey = $momoAcc->secret_key;
        $accessKey = $momoAcc->access_key;
        $amount = $request->amount;
        $extraData = $request->extraData;
        $message = $request->message;
        $orderInfo = $request->orderInfo;
        $orderType = $request->orderType;
        $partnerCode = $request->partnerCode;
        $payType = $request->payType;
        $requestId = $request->requestId;
        $responseTime = $request->responseTime;
        $resultCode = $request->resultCode;
        $transId = $request->transId;

        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&message=" . $message . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&orderType=" . $orderType . "&partnerCode=" . $partnerCode . "&payType=" . $payType . "&requestId=" . $requestId . "&responseTime=" . $responseTime . "&resultCode=" . $resultCode . "&transId=" . $transId;

        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        if (hash_equals($signature, $request->signature)) {
            if ($resultCode == 0) {
                PaymentController::userDataUpdate($deposit);
                $notify[] = ['success', 'Thanh toán thành công'];
                if ($deposit->order->remark == 'seller_registration_fee') {
                    return to_route('seller.home')->withNotify($notify);
                }
                return redirect()->route('user.deposit.history')->withNotify($notify);
            }
        }

        $notify[] = ['error', 'Thanh toán không thành công hoặc đã bị hủy.'];
        return redirect()->route('user.deposit.history')->withNotify($notify);
    }
}
