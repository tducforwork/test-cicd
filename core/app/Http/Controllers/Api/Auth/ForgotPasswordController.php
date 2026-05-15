<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function sendResetCodeEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'remark' => 'validation_error',
                'status' => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $user = User::where('email', $request->value)->orWhere('username', $request->value)->first();

        if (!$user) {
            $notify[] = 'Không tìm thấy người dùng';
            return response()->json([
                'remark' => 'user_not_found',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $code = verificationCode(6);
        $user->ver_code = $code;
        $user->save();

        notify($user, 'PASS_RESET_CODE', [
            'code' => $code
        ], ['email']);

        $notify[] = 'Mã xác thực đã được gửi tới email của bạn';
        return response()->json([
            'remark' => 'code_sent',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'email' => $user->email,
            ]
        ]);
    }

    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'remark' => 'validation_error',
                'status' => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $user = User::where('email', $request->email)->where('ver_code', $request->code)->first();

        if (!$user) {
            $notify[] = 'Mã xác thực không đúng';
            return response()->json([
                'remark' => 'invalid_code',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $notify[] = 'Xác thực mã thành công';
        return response()->json([
            'remark' => 'code_verified',
            'status' => 'success',
            'message' => ['success' => $notify],
        ]);
    }

    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'remark' => 'validation_error',
                'status' => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $user = User::where('email', $request->email)->where('ver_code', $request->token)->first();

        if (!$user) {
            $notify[] = 'Yêu cầu không hợp lệ hoặc đã hết hạn';
            return response()->json([
                'remark' => 'invalid_request',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->ver_code = null;
        $user->save();

        $notify[] = 'Đổi mật khẩu thành công';
        return response()->json([
            'remark' => 'password_reset_success',
            'status' => 'success',
            'message' => ['success' => $notify],
        ]);
    }
}
