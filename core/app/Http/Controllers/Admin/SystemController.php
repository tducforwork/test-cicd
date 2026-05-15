<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use App\Lib\CurlRequest;
use App\Lib\FileManager;
use App\Models\UpdateLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class SystemController extends Controller
{
    public function systemInfo()
    {
        $laravelVersion = app()->version();
        $timeZone = config('app.timezone');
        $pageTitle = __('Application Information');
        return view('admin.system.info', compact('pageTitle', 'laravelVersion', 'timeZone'));
    }

    public function optimize()
    {
        $pageTitle = __('Clear System Cache');
        return view('admin.system.optimize', compact('pageTitle'));
    }

    public function optimizeClear()
    {
        Artisan::call('optimize:clear');
        $notify[] = ['success', __('Cache cleared successfully')];
        return back()->withNotify($notify);
    }

    public function systemServerInfo()
    {
        $currentPHP = phpversion();
        $pageTitle = __('Server Information');
        $serverDetails = $_SERVER;
        return view('admin.system.server', compact('pageTitle', 'currentPHP', 'serverDetails'));
    }
}
