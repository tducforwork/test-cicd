<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Traits\SupportTicketManager;

class TicketController extends Controller
{
    use SupportTicketManager;

    public function __construct()
    {
        $this->column       = 'seller_id';
        $this->userType     = 'seller';
        $this->apiRequest   = true;
        $this->redirectLink = 'api.seller.ticket.view';

        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
    }
}
