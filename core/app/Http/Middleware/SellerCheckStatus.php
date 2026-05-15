<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SellerCheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('web')->check()) {
            $seller = seller();
            if ($seller->is_seller) {
                if ($seller->status  && $seller->ev  && $seller->sv  && (!$seller->ts || $seller->tv)) {
                    return $next($request);
                } else {
                    return to_route('seller.authorization');
                }
            }
        }

        abort(403);
    }
}
