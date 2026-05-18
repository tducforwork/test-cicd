@extends($activeTemplate . 'layouts.frontend')
@section('content')
<div class="bg-[#F7F7F7]">
    <main class="container mx-auto pb-32 pt-10 ">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar -->
            <aside class="w-full lg:w-[312px] shrink-0">
                @include($activeTemplate . 'user.partials.sidebar')
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 min-w-0">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <h1 class="text-2xl font-semibold text-[#272343]">@lang('Dashboard')</h1>
                    @if(!auth()->user()->is_seller)
                    <a href="{{ route('user.become.seller') }}"
                        class="flex px-[18px] py-[10px] justify-center items-center gap-[8px] rounded-[12px] text-[#FFF] text-[15px] font-bold leading-[24px] tracking-[-0.15px] bg-[#FF6F0F] [box-shadow:0_1px_2px_0_rgba(255,_255,_255,_0.40)_inset,_0_-1px_2px_0_rgba(0,_0,_0,_0.24)_inset,_0_1px_2px_0_rgba(0,_0,_0,_0.08)]">
                        @lang('Up as Seller')
                    </a>
                    @endif
                </div>

                <!-- Profile Card -->
                <div class="bg-white rounded-[8px] p-6 mb-6">
                    <h2 class="text-xl md:text-[20px] font-bold text-[#272343] mb-6">@lang('Hello'), {{ auth()->user()->fullname }}</h2>

                    <div class="flex flex-col lg:flex-row gap-6 md:gap-[36px]">
                        <img src="{{ getAvatar(getFilePath('userProfile') . '/' . auth()->user()->image, auth()->user()->fullname ?? auth()->user()->username) }}" alt="Avatar"
                            class="w-16 h-16 lg:w-[70px] lg:h-[70px] rounded-full object-cover border-[2px] border-[#ccc]">

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 flex-1">
                            <div>
                                <p class="text-sm md:text-[16px] font-semibold text-[#272343] mb-1">@lang('Full name')</p>
                                <p class="text-[#272343]/70 text-[16px]">{{ auth()->user()->fullname }}</p>
                            </div>
                            <div>
                                <p class="text-sm md:text-[16px] font-semibold text-[#272343] mb-1">@lang('Phone')</p>
                                <p class="text-[#272343]/70 text-[16px]">{{ auth()->user()->mobile }}</p>
                            </div>
                            <div>
                                <p class="text-sm md:text-[16px] font-semibold text-[#272343] mb-1">@lang('Email')</p>
                                <p class="text-[#272343]/70 text-[16px]">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Orders Card -->
                <div class="bg-white rounded-[8px] p-6">
                    <h2 class="text-xl md:text-[20px] leading-relaxed font-bold text-[#272343] mb-6">@lang('Orders')</h2>

                    <!-- Desktop Table -->
                    <div class="hidden md:block">
                        <div class="flex flex-col gap-4">
                            @forelse($orders->take(10) as $order)
                            @php $isRegFee = $order->remark === 'seller_registration_fee'; @endphp
                            <div class="flex items-center justify-between p-5 border rounded-[12px] border-[#E6E6E6] hover:bg-gray-50 transition-colors bg-white">
                                <div class="w-[20%]">
                                    <div class="flex gap-[6px] flex-col">
                                        <span class="overflow-hidden text-[#333] overflow-ellipsis text-[14px] font-bold leading-[20px]">#{{ $order->order_number }}</span>
                                        <span class="overflow-hidden text-[#666] overflow-ellipsis text-[14px] not-italic font-bold leading-[20px]">@lang('Order')</span>
                                    </div>
                                </div>
                                <div class="w-[20%] text-center">
                                    <div class="flex gap-[6px] justify-center text-center flex-col">
                                        <span class="overflow-hidden  text-[#333] overflow-ellipsis text-[14px] font-bold leading-[20px]">{{ showAmount($order->total_amount) }}</span>
                                        <span class="overflow-hidden text-[#666] overflow-ellipsis text-[14px] not-italic font-bold leading-[20px]">@lang('Total Amount')</span>
                                    </div>
                                </div>
                                @if(!$isRegFee)
                                <div class="w-[20%] text-center">
                                    <div class="flex gap-[6px] justify-center flex-col">
                                        <span class="overflow-hidden text-[#333] overflow-ellipsis text-[14px] font-bold leading-[20px]">{{ $order->orderDetail->count() }}</span>
                                        <span class="overflow-hidden text-[#666] overflow-ellipsis text-[14px] not-italic font-bold leading-[20px]">@lang('Products')</span>
                                    </div>
                                </div>
                                <div class="w-[20%] text-center">
                                    <div class="flex gap-[6px] justify-center flex-col">
                                        <span class="overflow-hidden text-[#333] overflow-ellipsis text-[14px] font-bold leading-[20px]">{{ $order->statusName() }}</span>
                                        <span class="overflow-hidden text-[#666] overflow-ellipsis text-[14px] not-italic font-bold leading-[20px]">@lang('Order Status')</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <a href="{{ route('user.order.details', $order->order_number) }}"
                                        class="overflow-hidden text-[#2563EB] align-middle overflow-ellipsis text-[14px] font-bold leading-[20px]">@lang('View Order')</a>
                                </div>
                                @else
                                <div class="w-[20%] text-center">
                                    <div class="flex gap-[8px] justify-center">
                                        {!! $order->paymentBadge() !!}
                                    </div>
                                    <span class="overflow-hidden text-[#666] overflow-ellipsis text-[12px] not-italic font-bold leading-[20px]">@lang('Payment Status')</span>
                                </div>
                                @endif
                            </div>
                            @empty
                            <div class="py-12 text-center text-gray-400 border border-[#E6E6E6] rounded-[12px]">
                                @lang('No orders found')
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="md:hidden space-y-4">
                        @forelse($orders->take(10) as $order)
                        @php $isRegFee = $order->remark === 'seller_registration_fee'; @endphp
                        <div class="border border-gray-200 rounded-xl p-4 space-y-3">
                            <div>
                                <span class="font-bold text-gray-800">#{{ $order->order_number }}</span>
                                <span class="overflow-hidden text-[#666] overflow-ellipsis text-[14px] not-italic font-bold leading-[20px]">Order</span>
                                @if(!$isRegFee)
                                <div class="text-[12px] font-bold text-[#666] mt-1">{{ $order->statusName() }}</div>
                                @endif
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div><span class="text-gray-500">@lang('Amount'):</span> <span
                                        class="font-semibold">{{ showAmount($order->total_amount) }}</span></div>
                                @if(!$isRegFee)
                                <div><span class="text-gray-500">@lang('Products'):</span> <span class="font-semibold">{{ $order->orderDetail->count() }}</span>
                                </div>
                                @else
                                <div><span class="text-gray-500">@lang('Payment'):</span> {!! $order->paymentBadge() !!}</div>
                                @endif
                            </div>
                            @if(!$isRegFee)
                            <a href="{{ route('user.order.details', $order->order_number) }}"
                                class="block text-center bg-blue-50 text-kviet-blue font-bold text-sm py-2 rounded-lg hover:bg-blue-100 transition-colors">
                                @lang('View Order')
                            </a>
                            @endif
                        </div>
                        @empty
                        <div class="py-12 text-center text-gray-400">
                            @lang('Không tìm thấy đơn hàng nào')
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('style')
<style>
    /* Ensure stat card colors aren't purged if they are dynamic */
    .bg-blue-50 {
        background-color: #eff6ff;
    }

    .text-blue-600 {
        color: #2563eb;
    }

    .group-hover\:bg-blue-600:hover {
        background-color: #2563eb !important;
    }

    .bg-yellow-50 {
        background-color: #fefce8;
    }

    .text-yellow-600 {
        color: #ca8a04;
    }

    .group-hover\:bg-yellow-600:hover {
        background-color: #ca8a04 !important;
    }

    .bg-indigo-50 {
        background-color: #eef2ff;
    }

    .text-indigo-600 {
        color: #4f46e5;
    }

    .group-hover\:bg-indigo-600:hover {
        background-color: #4f46e5 !important;
    }

    .bg-purple-50 {
        background-color: #faf5ff;
    }

    .text-purple-600 {
        color: #9333ea;
    }

    .group-hover\:bg-purple-600:hover {
        background-color: #9333ea !important;
    }

    .bg-green-50 {
        background-color: #f0fdf4;
    }

    .text-green-600 {
        color: #16a34a;
    }

    .group-hover\:bg-green-600:hover {
        background-color: #16a34a !important;
    }

    .bg-red-50 {
        background-color: #fef2f2;
    }

    .text-red-600 {
        color: #dc2626;
    }

    .group-hover\:bg-red-600:hover {
        background-color: #dc2626 !important;
    }

    /* Custom badge styles if needed to override Bootstrap */
    .badge {
        display: inline-block;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
    }

    .badge--warning {
        background-color: #ffc107;
        color: #000;
    }

    .badge--primary {
        background-color: #0d6efd;
    }

    .badge--success {
        background-color: #198754;
    }

    .badge--danger {
        background-color: #dc3545;
    }

    .badge--dark {
        background-color: #212529;
    }
</style>
@endpush
