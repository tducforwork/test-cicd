<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Province;

class ProfileController extends Controller
{
    public function profile()
    {
        $pageTitle = "Profile Setting";
        $user = auth()->user();
        $provinces = Province::orderBy('name')->get();
        return view('Template::user.profile_setting', compact('pageTitle', 'user', 'provinces'));
    }

    public function submitProfile(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:80',
            'image' => ['image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'birth_date' => 'nullable|date',
            'province_id' => 'required|integer|exists:provinces,id',
            'ward_id' => 'required|integer|exists:wards,id',
            'address' => 'required|string',
        ]);

        $user = auth()->user();

        $nameParts = explode(' ', $request->fullname, 2);
        $user->firstname = $nameParts[0];
        $user->lastname = isset($nameParts[1]) ? $nameParts[1] : '';

        if ($request->hasFile('image')) {
            $user->image = fileUploader($request->image, getFilePath('userProfile'), getFileSize('userProfile'), @$user->image);
        }

        $user->birth_date = $request->birth_date;
        $user->province_id = $request->province_id;
        $user->ward_id = $request->ward_id;
        $user->address = $request->address;

        $user->save();
        $notify[] = ['success', 'Profile updated successfully'];

        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Cập nhật thông tin hồ sơ thành công!']);
        }

        return back()->withNotify($notify);
    }

    public function submitAddress(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'province_id' => 'required|integer|exists:provinces,id',
            'ward_id' => 'required|integer|exists:wards,id',
        ]);

        $user = auth()->user();

        $user->address = $request->address;
        $user->province_id = $request->province_id;
        $user->ward_id = $request->ward_id;

        $user->save();
        $notify[] = ['success', 'Address updated successfully'];

        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Address updated successfully']);
        }

        return back()->withNotify($notify);
    }

    public function changePassword()
    {
        $pageTitle = 'Change Password';
        return view('Template::user.password', compact('pageTitle'));
    }

    public function submitPassword(Request $request)
    {

        $passwordValidation = Password::min(6);
        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', $passwordValidation]
        ]);

        $user = auth()->user();
        if (Hash::check($request->current_password, $user->password)) {
            $password = Hash::make($request->password);
            $user->password = $password;
            $user->save();
            $notify[] = ['success', 'Password changed successfully'];
            
            if ($request->ajax()) {
                return response()->json(['status' => 'success', 'message' => 'Password changed successfully']);
            }
            
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'The password doesn\'t match!'];
            
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'The current password doesn\'t match!'], 400);
            }
            
            return back()->withNotify($notify);
        }
    }
}
