@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="bg-[#F7F7F7]">
        <main class="container mx-auto pb-32 pt-10">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Sidebar -->
                <aside class="w-full lg:w-[312px] shrink-0">
                    @include($activeTemplate . 'user.partials.sidebar')
                </aside>

                <!-- Main panel -->
                <div class="flex flex-col gap-6 flex-1 min-w-0">
                    <!-- Title + search -->
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        <h1 class="font-semibold text-[#272343] text-2xl">
                            @lang('All Orders')
                        </h1>
                    </div>

                    <!-- Stats grid -->
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-[24px]">
                        <!-- New order -->
                        <div class="flex flex-col gap-2 p-6 bg-white rounded-[16px] border-[1px] border-[solid] border-[#E6E6E6] [box-shadow:0_1px_2px_0_rgba(128,_128,_128,_0.10)]">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center p-[10px] bg-[#E6FFE4] rounded-[7px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
                                        <path opacity="0.2" d="M3.54657 7.99316C3.46893 8.12651 3.42825 8.27815 3.42871 8.43245V18.9967C3.42953 19.1493 3.47058 19.299 3.5477 19.4307C3.62482 19.5624 3.7353 19.6714 3.868 19.7467L13.2966 25.0503C13.4237 25.1229 13.568 25.16 13.7144 25.1575L13.8109 13.7146L3.54657 7.99316Z" fill="#2DB324" />
                                        <path d="M24.0001 18.9964V8.43214C23.9993 8.27954 23.9583 8.12986 23.8812 7.99818C23.804 7.86651 23.6935 7.75749 23.5609 7.68214L14.1323 2.37857C14.0052 2.30522 13.8611 2.2666 13.7144 2.2666C13.5677 2.2666 13.4236 2.30522 13.2966 2.37857L3.868 7.68214C3.7353 7.75749 3.62482 7.86651 3.5477 7.99818C3.47057 8.12986 3.42953 8.27954 3.42871 8.43214V18.9964C3.42953 19.149 3.47057 19.2987 3.5477 19.4304C3.62482 19.5621 3.7353 19.6711 3.868 19.7464L13.2966 25.05C13.4236 25.1233 13.5677 25.162 13.7144 25.162C13.8611 25.162 14.0052 25.1233 14.1323 25.05L23.5609 19.7464C23.6935 19.6711 23.804 19.5621 23.8812 19.4304C23.9583 19.2987 23.9993 19.149 24.0001 18.9964V18.9964Z" stroke="#2DB324" stroke-width="1.71429" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M18.9641 16.3397V10.7683L8.57129 5.03613" stroke="#2DB324" stroke-width="1.71429" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M23.8826 7.99316L13.8112 13.7146L3.54688 7.99316" stroke="#2DB324" stroke-width="1.71429" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M13.8103 13.7139L13.7139 25.1567" stroke="#2DB324" stroke-width="1.71429" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <span class="text-[#272343] text-[16px] font-semibold leading-[20px]">@lang('New order')</span>
                            </div>
                            <span class="text-[#272343] text-[30px] font-normal leading-[48px]">{{ $stats['new'] ?? 0 }}</span>
                        </div>
                        <!-- Shipping -->
                        <div class="flex flex-col gap-2 p-6 bg-white rounded-[16px] border-[1px] border-[solid] border-[#E6E6E6] [box-shadow:0_1px_2px_0_rgba(128,_128,_128,_0.10)]">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center p-[10px] bg-[#D0E1FF] rounded-[7px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="26" viewBox="0 0 36 26" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M29.6 6.4H25.6V3.2C25.6 1.44 24.16 0 22.4 0H3.2C1.44 0 0 1.44 0 3.2V17.6C0 19.36 1.44 20.8 3.2 20.8C3.2 23.456 5.344 25.6 8 25.6C10.656 25.6 12.8 23.456 12.8 20.8H22.4C22.4 23.456 24.544 25.6 27.2 25.6C29.856 25.6 32 23.456 32 20.8H33.6C34.48 20.8 35.2 20.08 35.2 19.2V13.872C35.2 13.184 34.976 12.512 34.56 11.952L30.88 7.04C30.576 6.64 30.096 6.4 29.6 6.4ZM8.00039 22.3992C7.12039 22.3992 6.40039 21.6792 6.40039 20.7992C6.40039 19.9192 7.12039 19.1992 8.00039 19.1992C8.88039 19.1992 9.60039 19.9192 9.60039 20.7992C9.60039 21.6792 8.88039 22.3992 8.00039 22.3992ZM29.5996 8.80078L32.7356 12.8008H25.5996V8.80078H29.5996ZM27.1996 22.3992C26.3196 22.3992 25.5996 21.6792 25.5996 20.7992C25.5996 19.9192 26.3196 19.1992 27.1996 19.1992C28.0796 19.1992 28.7996 19.9192 28.7996 20.7992C28.7996 21.6792 28.0796 22.3992 27.1996 22.3992Z" fill="#1F38DB" />
                                    </svg>
                                </div>
                                <span class="text-[#272343] text-[16px] font-semibold leading-[20px]">@lang('Shipping')</span>
                            </div>
                            <span class="text-[#272343] text-[30px] font-normal leading-[48px]">{{ $stats['shipping'] ?? 0 }}</span>
                        </div>
                        <!-- Cancelled -->
                        <div class="flex flex-col gap-2 p-6 bg-white rounded-[16px] border-[1px] border-[solid] border-[#E6E6E6] [box-shadow:0_1px_2px_0_rgba(128,_128,_128,_0.10)]">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center p-[10px] bg-[#FFDEDE] rounded-[7px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                        <path d="M5.68889 13.3333L8.88889 10.1333L12.0889 13.3333L13.3333 12.0889L10.1333 8.88889L13.3333 5.68889L12.0889 4.44444L8.88889 7.64445L5.68889 4.44444L4.44444 5.68889L7.64445 8.88889L4.44444 12.0889L5.68889 13.3333ZM8.88889 17.7778C7.65926 17.7778 6.5037 17.5444 5.42222 17.0778C4.34074 16.6111 3.4 15.9778 2.6 15.1778C1.8 14.3778 1.16667 13.437 0.7 12.3556C0.233333 11.2741 0 10.1185 0 8.88889C0 7.65926 0.233333 6.5037 0.7 5.42222C1.16667 4.34074 1.8 3.4 2.6 2.6C3.4 1.8 4.34074 1.16667 5.42222 0.7C6.5037 0.233333 7.65926 0 8.88889 0C10.1185 0 11.2741 0.233333 12.3556 0.7C13.437 1.16667 14.3778 1.8 15.1778 2.6C15.9778 3.4 16.6111 4.34074 17.0778 5.42222C17.5444 6.5037 17.7778 7.65926 17.7778 8.88889C17.7778 10.1185 17.5444 11.2741 17.0778 12.3556C16.6111 13.437 15.9778 14.3778 15.1778 15.1778C14.3778 15.9778 13.437 16.6111 12.3556 17.0778C11.2741 17.5444 10.1185 17.7778 8.88889 17.7778Z" fill="#DF4C4C" />
                                    </svg>
                                </div>
                                <span class="text-[#272343] text-[16px] font-semibold leading-[20px]">@lang('Cancelled')</span>
                            </div>
                            <span class="text-[#272343] text-[30px] font-normal leading-[48px]">{{ $stats['cancelled'] ?? 0 }}</span>
                        </div>
                        <!-- Success -->
                        <div class="flex flex-col gap-2 p-6 bg-white rounded-[16px] border-[1px] border-[solid] border-[#E6E6E6] [box-shadow:0_1px_2px_0_rgba(128,_128,_128,_0.10)]">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center p-[10px] bg-[#D1FFE1] rounded-[7px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M10.4622 11.7822L15.9867 6.25778L14.6178 4.84L10.4622 8.99555L8.36 6.94222L6.99111 8.31111L10.4622 11.7822ZM5.86667 15.6444C5.32889 15.6444 4.86852 15.453 4.48556 15.07C4.10259 14.687 3.91111 14.2267 3.91111 13.6889V1.95556C3.91111 1.41778 4.10259 0.957407 4.48556 0.574444C4.86852 0.191482 5.32889 0 5.86667 0H17.6C18.1378 0 18.5981 0.191482 18.9811 0.574444C19.3641 0.957407 19.5556 1.41778 19.5556 1.95556V13.6889C19.5556 14.2267 19.3641 14.687 18.9811 15.07C18.5981 15.453 18.1378 15.6444 17.6 15.6444H5.86667ZM1.95556 19.5556C1.41778 19.5556 0.957407 19.3641 0.574444 18.9811C0.191482 18.5981 0 18.1378 0 17.6V3.91111H1.95556V17.6H15.6444V19.5556H1.95556Z" fill="#39C568" />
                                    </svg>
                                </div>
                                <span class="text-[#272343] text-[16px] font-semibold leading-[20px]">@lang('Success')</span>
                            </div>
                            <span class="text-[#272343] text-[30px] font-normal leading-[48px]">{{ $stats['delivered'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="bg-white rounded-[8px] p-4 md:p-6">
                        <h2 class="text-xl md:text-[20px] leading-relaxed font-bold text-[#272343] mb-4 md:mb-6">@lang('Orders')</h2>
                        
                        <!-- Mobile Cards -->
                        <div class="flex flex-col gap-3 md:hidden">
                            @forelse ($orders as $order)
                                <div class="border border-[#E6E6E6] rounded-[12px] bg-white overflow-hidden">
                                    <!-- Order Header - Clickable to toggle -->
                                    <div class="p-4 cursor-pointer hover:bg-gray-50 transition-colors" onclick="toggleOrder('mobile-{{ $order->id }}')">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center gap-2">
                                                <div class="flex flex-col">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-[14px] font-bold text-[#333]">#{{ $order->order_number }}</span>
                                                        <span class="text-[12px] px-2 py-0.5 rounded-full @if($order->payment_status == 1) bg-green-100 text-green-700 @else bg-yellow-100 text-yellow-700 @endif">
                                                            {{ $order->payment_status == 1 ? 'Paid' : 'COD' }}
                                                        </span>
                                                    </div>
                                                    <span class="text-[#6b7280] text-[11px] mt-1">{{ showDateTime($order->created_at, 'd M Y, H:i') }}</span>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[#2563EB] text-[14px] font-bold">{{ $order->computed_status_name }}</span>
                                                <svg class="w-4 h-4 transition-transform duration-200" id="arrow-mobile-{{ $order->id }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2 text-sm mt-3 pt-3 border-t border-gray-100">
                                            <div>
                                                <span class="text-[#666] text-[12px]">Tổng cộng:</span>
                                                <span class="font-bold text-[#333] ml-1">{{ showAmount($order->total_amount) }}</span>
                                            </div>
                                            <div>
                                                <span class="text-[#666] text-[12px]">SubOrders:</span>
                                                <span class="font-bold text-[#333] ml-1">{{ $order->subOrders->count() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- SubOrders - Collapsible -->
                                    <div id="mobile-{{ $order->id }}" class="hidden border-t">
                                        @foreach($order->subOrders as $sub)
                                            <div class="p-4 bg-gray-50 border-b border-gray-100 last:border-b-0">
                                                <div class="flex items-start justify-between mb-3">
                                                    <div class="flex flex-col gap-1">
                                                        <div class="flex items-center gap-2">
                                                            @if($sub->seller_id == 0)
                                                                <span class="font-bold text-[#333] text-[13px]">Kviet Shop</span>
                                                                <span class="px-1.5 py-0.5 bg-gray-200 text-[#666] text-[9px] font-bold rounded uppercase">Admin</span>
                                                            @else
                                                                <span class="font-bold text-[#333] text-[13px]">{{ $sub->seller->shop->name ?? $sub->seller->fullname }}</span>
                                                                <span class="px-1.5 py-0.5 bg-blue-100 text-blue-600 text-[9px] font-bold rounded uppercase">Seller</span>
                                                            @endif
                                                        </div>
                                                        <span class="text-[#999] text-[11px]">#{{ $sub->order_number }} • {{ $sub->orderDetail->sum('quantity') }} @lang('products')</span>
                                                    </div>
                                                    <span class="badge badge-{{ $sub->status }} text-[10px]">{!! $sub->badgeHtml !!}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                        <div class="flex">
                                            @if($order->computed_status == \App\Constants\Status::ORDER_PENDING && $order->payment_status != \App\Constants\Status::PAYMENT_SUCCESS)
                                                <form action="{{ route('user.order.cancel', $order->order_number) }}" method="POST" class="flex-1" onsubmit="return confirm('@lang('Are you sure to cancel this order?')')">
                                                    @csrf
                                                    <button type="submit" class="w-full py-2.5 bg-red-50 text-red-600 font-bold text-sm hover:bg-red-600 hover:text-white transition-colors border-r border-white">
                                                        @lang('Cancel Order')
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('user.order.details', $order->order_number) }}" class="flex-1 flex items-center justify-center py-2.5 bg-[#eaf4ff] text-[#2563EB] font-bold text-sm hover:bg-[#FF6F0F] hover:text-white transition-colors">
                                                @lang('View details')
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="py-8 text-center text-[#6b7280] bg-white border border-[#E6E6E6] rounded-[12px] text-sm">
                                    @lang('No orders found')
                                </div>
                            @endforelse
                        </div>

                        <!-- Desktop Table -->
                        <div class="hidden md:block">
                            @foreach ($orders as $order)
                                <div class="mb-4 border border-[#E6E6E6] rounded-[12px] overflow-hidden">
                                    <!-- Order Header -->
                                    <div class="flex items-center justify-between p-4 bg-gray-50 cursor-pointer hover:bg-gray-100 transition-colors" onclick="toggleOrder('desktop-{{ $order->id }}')">
                                        <div class="flex items-center gap-6">
                                            <div>
                                                <span class="text-[#333] text-[14px] font-bold">#{{ $order->order_number }}</span>
                                                <span class="text-[#666] text-[12px] ml-2">@lang('Order')</span>
                                            </div>
                                            <span class="text-[#6b7280] text-[12px]">{{ showDateTime($order->created_at, 'd M Y, H:i') }}</span>
                                            <span class="text-[12px] px-2 py-0.5 rounded-full @if($order->payment_status == 1) bg-green-100 text-green-700 @else bg-yellow-100 text-yellow-700 @endif">
                                                {{ $order->payment_status == 1 ? 'Paid' : 'COD' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-6">
                                            <span class="text-[#333] text-[14px] font-bold">{{ showAmount($order->total_amount) }}</span>
                                            <span class="text-[#2563EB] text-[14px] font-bold">{{ $order->computed_status_name }}</span>
                                            <span class="text-[#6b7280] text-[12px]">{{ $order->subOrders->count() }} sub-orders</span>
                                            <svg class="w-5 h-5 transition-transform duration-200 text-gray-400" id="arrow-desktop-{{ $order->id }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <!-- SubOrders - Collapsible -->
                                    <div id="desktop-{{ $order->id }}" class="hidden">
                                        <div class="p-4 space-y-3">
                                            @foreach($order->subOrders as $sub)
                                                <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:border-gray-300 transition-colors">
                                                    <div class="flex items-center gap-4">
                                                        <div class="flex items-center gap-2">
                                                            @if($sub->seller_id == 0)
                                                                <div>
                                                                    <span class="font-semibold text-[#333] block">Kviet Shop</span>
                                                                    <span class="text-[#999] text-[11px]">Admin</span>
                                                                </div>
                                                            @else
                                                                <div>
                                                                    <span class="font-semibold text-[#333] block">{{ $sub->seller->shop->name ?? $sub->seller->fullname }}</span>
                                                                    <span class="text-[#999] text-[11px]">Seller</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="h-8 w-px bg-gray-200"></div>
                                                        <span class="text-[#666] text-[12px]">#{{ $sub->order_number }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-4">
                                                        <div class="flex gap-1">
                                                            
                                                            @if($sub->orderDetail->count() > 4)
                                                                <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center text-[#666] text-[11px] font-bold">
                                                                    +{{ $sub->orderDetail->count() - 4 }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="h-8 w-px bg-gray-200"></div>
                                                        <span class="text-[#333] text-[13px] font-semibold">{{ $sub->orderDetail->sum('quantity') }} @lang('products')</span>
                                                        <div class="h-8 w-px bg-gray-200"></div>
                                                        <span class="badge badge-{{ $sub->status }}">{!! $sub->badgeHtml !!}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="border-t px-4 py-3 flex justify-end gap-3">
                                            @if($order->computed_status == \App\Constants\Status::ORDER_PENDING && $order->payment_status != \App\Constants\Status::PAYMENT_SUCCESS)
                                                <form action="{{ route('user.order.cancel', $order->order_number) }}" method="POST" onsubmit="return confirm('@lang('Are you sure to cancel this order?')')">
                                                    @csrf
                                                    <button type="submit" class="flex items-center justify-center px-4 py-2 bg-red-100 text-red-600 font-semibold text-[13px] rounded-lg hover:bg-red-600 hover:text-white transition-colors">
                                                        @lang('Cancel Order')
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('user.order.details', $order->order_number) }}" class="flex items-center justify-center px-4 py-2 bg-[#2563EB] text-white font-semibold text-[13px] rounded-lg hover:bg-[#1d4ed8] transition-colors">
                                                @lang('View full details')
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($orders->hasPages())
                            <div class="mt-6">
                                {{ paginateLinks($orders) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection

@push('script')
<script>
    function toggleOrder(orderId) {
        const content = document.getElementById(orderId);
        const isMobile = orderId.startsWith('mobile-');
        const orderIdNum = orderId.replace('mobile-', '').replace('desktop-', '');
        const arrowMobile = document.getElementById('arrow-mobile-' + orderIdNum);
        const arrowDesktop = document.getElementById('arrow-desktop-' + orderIdNum);
        
        content.classList.toggle('hidden');
        
        if (arrowMobile) {
            arrowMobile.classList.toggle('rotate-180');
        }
        if (arrowDesktop) {
            arrowDesktop.classList.toggle('rotate-180');
        }
    }
</script>
@endpush

@push('style')
<style>
    .badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 500;
    }

    .badge-0 {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-2 {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-3 {
        background: #cffafe;
        color: #0e7490;
    }

    .badge-4 {
        background: #e0e7ff;
        color: #3730a3;
    }

    .badge-1 {
        background: #d1fae5;
        color: #047857;
    }

    .badge-5 {
        background: #d1fae5;
        color: #047857;
    }

    .badge-6 {
        background: #fee2e2;
        color: #dc2626;
    }

    .badge-9 {
        background: #fce7f3;
        color: #be185d;
    }
    
    .rotate-180 {
        transform: rotate(180deg);
    }
</style>
@endpush
