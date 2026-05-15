<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\SupportTicketManager;

class TicketController extends Controller
{
    use SupportTicketManager;

    public function __construct()
    {
        $this->column       = 'user_id';
        $this->userType     = 'user';
        $this->apiRequest   = true;
        $this->redirectLink = 'api.ticket.view'; // Dummy link for trait logic if needed

        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
    }
}
