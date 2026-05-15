<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'remark' => 'validation_error',
                'status' => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $login = $request->username;
        $column = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where($column, $login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            $notify[] = 'Thông tin đăng nhập không chính xác';
            return response()->json([
                'remark' => 'login_failed',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $tokenResult = $user->createToken('auth_token')->plainTextToken;
        $this->createUserLogin($user);

        $notify[] = 'Đăng nhập thành công';
        return response()->json([
            'remark' => 'login_success',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'user' => $user,
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
            ]
        ]);
    }

    protected function createUserLogin($user)
    {
        $userLogin = new UserLogin();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip = getRealIP();
        $userLogin->browser = osBrowser()['browser'];
        $userLogin->os = osBrowser()['os_platform'];
        $userLogin->country = @implode(',', getIpInfo()['country']);
        $userLogin->country_code = @implode(',', getIpInfo()['code']);
        $userLogin->city = @implode(',', getIpInfo()['city']);
        $userLogin->longitude = @implode(',', getIpInfo()['long']);
        $userLogin->latitude = @implode(',', getIpInfo()['lat']);
        $userLogin->save();
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        $notify[] = 'Đã đăng xuất thành công';
        return response()->json([
            'remark' => 'logout_success',
            'status' => 'success',
            'message' => ['success' => $notify],
        ]);
    }
}
