<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Constants\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function userInfo()
    {
        $user = auth()->user();
        $notify[] = 'User information';
        return response()->json([
            'remark' => 'user_info',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'user' => $user,
            ]
        ]);
    }

    public function submitProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'remark' => 'validation_error',
                'status' => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $user = auth()->user();

        if ($request->hasFile('image')) {
            try {
                $oldImg = $user->image;
                $user->image = fileUploader($request->image, getFilePath('userProfile'), getFileSize('userProfile'), $oldImg);
            } catch (\Exception $exp) {
                $notify[] = 'Không thể upload ảnh đại diện';
                return response()->json([
                    'remark' => 'upload_error',
                    'status' => 'error',
                    'message' => ['error' => $notify],
                ]);
            }
        }

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zip = $request->zip;
        $user->save();

        $notify[] = 'Cập nhật hồ sơ thành công';
        return response()->json([
            'remark' => 'profile_updated',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'user' => $user,
            ]
        ]);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'remark' => 'validation_error',
                'status' => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $user = auth()->user();
        if (Hash::check($request->current_password, $user->password)) {
            $user->password = Hash::make($request->password);
            $user->save();
            $notify[] = 'Đổi mật khẩu thành công';
            return response()->json([
                'remark' => 'password_changed',
                'status' => 'success',
                'message' => ['success' => $notify],
            ]);
        } else {
            $notify[] = 'Mật khẩu hiện tại không chính xác';
            return response()->json([
                'remark' => 'password_mismatch',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }
    }

    public function transactions()
    {
        $transactions = auth()->user()->transactions()->paginate(getPaginate());
        $notify[] = 'Transaction history';
        return response()->json([
            'remark' => 'transactions',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'transactions' => $transactions,
            ]
        ]);
    }

    public function depositHistory()
    {
        $deposits = auth()->user()->deposits()->with('gateway')->paginate(getPaginate());
        $notify[] = 'Deposit history';
        return response()->json([
            'remark' => 'deposits',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'deposits' => $deposits,
            ]
        ]);
    }

    public function pushNotifications()
    {
        $notifications = auth()->user()->notifications()->paginate(getPaginate());
        $notify[] = 'Push notifications';
        return response()->json([
            'remark' => 'notifications',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'notifications' => $notifications,
            ]
        ]);
    }

    public function pushNotificationsRead($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->read_at = now();
            $notification->save();
        }

        $notify[] = 'Notification marked as read';
        return response()->json([
            'remark' => 'notification_read',
            'status' => 'success',
            'message' => ['success' => $notify],
        ]);
    }

    public function addDeviceToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'remark' => 'validation_error',
                'status' => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $deviceToken = \App\Models\DeviceToken::where('token', $request->token)->first();

        if (!$deviceToken) {
            $deviceToken          = new \App\Models\DeviceToken();
            $deviceToken->user_id = auth()->id();
            $deviceToken->token   = $request->token;
            $deviceToken->is_app  = Status::YES;
            $deviceToken->save();
        }

        $notify[] = 'Device token saved successfully';
        return response()->json([
            'remark' => 'token_saved',
            'status' => 'success',
            'message' => ['success' => $notify],
        ]);
    }

    public function userDataSubmit(Request $request)
    {
        $user = auth()->user();
        if ($user->profile_complete == Status::YES) {
            $notify[] = 'Hồ sơ của bạn đã hoàn thiện';
            return response()->json([
                'remark' => 'already_completed',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $validator = Validator::make($request->all(), [
            'username'     => 'required|unique:users|min:6',
            'mobile'       => ['required', 'regex:/^([0-9]*)$/'],
            'province_id'  => 'required|integer|exists:provinces,id',
            'ward_id'      => 'required|integer|exists:wards,id',
            'address'      => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'remark' => 'validation_error',
                'status' => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        if (preg_match("/[^a-z0-9_]/", trim($request->username))) {
            $notify[] = 'Username chỉ được chứa chữ cái thường, số và dấu gạch dưới';
            return response()->json([
                'remark' => 'username_error',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $user->mobile       = $request->mobile;
        $user->username     = $request->username;
        $user->address      = $request->address;
        $user->province_id  = $request->province_id;
        $user->ward_id      = $request->ward_id;
        $user->profile_complete = Status::YES;
        $user->save();

        $notify[] = 'Hoàn thiện hồ sơ thành công';
        return response()->json([
            'remark' => 'profile_completed',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'user' => $user
            ]
        ]);
    }
}
