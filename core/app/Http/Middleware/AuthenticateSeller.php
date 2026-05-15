<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateSeller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = 'web')
    {
        if (Auth::guard($guard)->check()) {
            $user = Auth::guard($guard)->user();
            if ($user->is_seller) {
                if ($user->status == 0) {
                    Auth::guard($guard)->logout();
                    $notify[] = ['error', 'Your account is banned by the super admin'];
                    return redirect()->route('seller.login')->withNotify($notify);
                }
                return $next($request);
            }
        }

        return redirect()->route('seller.login');
    }
}
