<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Frontend;
use App\Models\GeneralSetting;
use App\Models\Language;
use App\Models\ShippingMethod;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function generalSetting()
    {
        $setting = gs();
        $notify[] = 'General setting data';
        return response()->json([
            'remark' => 'general_setting',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'general_setting' => $setting,
            ]
        ]);
    }

    public function getCountries()
    {
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $notify[] = 'Country data';
        return response()->json([
            'remark' => 'country_data',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'countries' => $countries,
            ]
        ]);
    }

    public function getLanguage($key = null)
    {
        $languages = Language::get();
        $notify[] = 'Language data';
        return response()->json([
            'remark' => 'language_data',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'languages' => $languages,
            ]
        ]);
    }

    public function policies()
    {
        $policies = Frontend::where('data_keys', 'policy_pages.element')->get();
        $notify[] = 'Policy data';
        return response()->json([
            'remark' => 'policy_data',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'policies' => $policies,
            ]
        ]);
    }
}
