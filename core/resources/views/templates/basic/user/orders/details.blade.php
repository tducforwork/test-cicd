@extends($activeTemplate . 'layouts.frontend')
@section('content')
<div class="bg-[#F7F7F7]">
    <main class="container mx-auto pb-32 pt-10">
        <div class="flex flex-col lg:flex-row items-start gap-6">
            <!-- Sidebar -->
            <aside class="w-full lg:w-[312px] shrink-0">
                @include($activeTemplate . 'user.partials.user_sidebar')
            </aside>

            <!-- Main Content -->
            <main class="profile-main-content flex-1 min-w-0">
                <!-- Page heading -->
                <div class="flex items-center gap-4 mb-6">
                    <a href="{{ route('user.orders', 'all') }}"
                        class="shrink-0 flex items-center justify-center w-10 h-10 rounded-[8px] border-[1px] border-[solid] border-[#D4D4D4] bg-[#FFF] [box-shadow:0_1px_2px_0_rgba(255,_255,_255,_0.40)_inset,_0_-1px_2px_0_rgba(0,_0,_0,_0.24)_inset,_0_1px_2px_0_rgba(0,_0,_0,_0.08)] hover:bg-gray-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M16.75 10C16.75 10.4142 16.4142 10.75 16 10.75L6.31078 10.75L9.0307 13.4696C9.32361 13.7625 9.32363 14.2374 9.03075 14.5303C8.73787 14.8232 8.263 14.8232 7.97009 14.5304L3.96969 10.5304C3.82902 10.3897 3.75 10.1989 3.75 10C3.75 9.80107 3.82902 9.6103 3.96969 9.46964L7.97009 5.46964C8.263 5.17676 8.73787 5.17679 9.03075 5.4697C9.32363 5.7626 9.32361 6.23748 9.0307 6.53036L6.31078 9.25L16 9.25C16.4142 9.25 16.75 9.58579 16.75 10Z"
                                fill="#272343" />
                        </svg>
                    </a>
                    <h1 class="text-[#272343] text-[24px] font-semibold">@lang('Chi tiết đơn hàng')</h1>
                </div>

                <!-- Order Detail Header -->
                <div class="order-detail-header flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-xl border border-gray-100 mb-6">
                    <div class="order-id-meta">
                        <h2 class="text-xl font-bold text-[#272343]">@lang('Đơn hàng') #{{ $order->order_number }}</h2>
                        <p class="text-sm text-gray-500 mt-1">@lang('Ngày đặt'): {{ showDateTime($order->created_at, 'd/m/Y H:i') }} | @lang('Tổng cộng'): <strong class="text-[#FF6F0F]">{{ showAmount($order->total_amount) }}</strong></p>
                    </div>
                    <div class="order-status-badge flex flex-col items-start md:items-end gap-3">
                        <span class="badge badge-{{ $order->computed_status }}">{!! $order->computed_status_name !!}</span>
                        @if($order->computed_status == \App\Constants\Status::ORDER_PENDING && $order->payment_status != \App\Constants\Status::PAYMENT_SUCCESS)
                            <form action="{{ route('user.order.cancel', $order->order_number) }}" method="POST" onsubmit="return confirm('@lang('Bạn có chắc chắn muốn hủy đơn hàng này không?')')">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm font-semibold rounded-lg px-4 py-2">@lang('Hủy đơn hàng')</button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Shipping & Payment & Transport Info -->
                <div class="order-summary-grid grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Shipping Address -->
                    @php $shippingAddr = json_decode($order->shipping_address); @endphp
                    <div class="summary-card bg-white p-6 rounded-xl border border-gray-100 flex flex-col gap-2">
                        <h4 class="text-sm font-bold text-[#272343] border-b border-gray-100 pb-2 mb-1">@lang('Địa chỉ nhận hàng')</h4>
                        <p class="text-sm text-[#272343]"><strong>{{ @$shippingAddr->firstname }} {{ @$shippingAddr->lastname }}</strong></p>
                        <p class="text-sm text-gray-600">{{ @$shippingAddr->mobile }}</p>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ @$shippingAddr->address }}, {{ @$shippingAddr->ward }}, {{ @$shippingAddr->province }}</p>
                    </div>

                    <!-- Payment info -->
                    <div class="summary-card bg-white p-6 rounded-xl border border-gray-100 flex flex-col gap-2">
                        <h4 class="text-sm font-bold text-[#272343] border-b border-gray-100 pb-2 mb-1">@lang('Thanh toán')</h4>
                        <p class="text-sm text-gray-600">@lang('Phương thức'): 
                            <strong>
                                @if (isset($order->deposit) && $order->deposit->method_code != 0)
                                    {{ __($order->deposit->gateway->name) }}
                                @else
                                    @lang('Thanh toán khi nhận hàng (COD)')
                                @endif
                            </strong>
                        </p>
                        <p class="text-sm text-gray-600 flex items-center gap-2">@lang('Trạng thái'): 
                            @php
                                $ps = $order->payment_status;
                                $pClass = $ps == 1 ? 'badge-1' : 'badge-2';
                                $pLabel = $ps == 1 ? __('Đã thanh toán') : __('Chờ thanh toán (COD)');
                            @endphp
                            <span class="badge {{ $pClass }} text-[11px]">{{ $pLabel }}</span>
                        </p>
                    </div>

                    <!-- General Shipping info -->
                    <div class="summary-card bg-white p-6 rounded-xl border border-gray-100 flex flex-col gap-2">
                        <h4 class="text-sm font-bold text-[#272343] border-b border-gray-100 pb-2 mb-1">@lang('Vận chuyển')</h4>
                        <p class="text-sm text-gray-600">@lang('Đơn vị'): <strong>Quảng Phát Express</strong></p>
                        <p class="text-sm text-gray-600">@lang('Phương thức'): <strong>@lang('Giao hàng tiêu chuẩn')</strong></p>
                        <p class="text-sm text-gray-500">@lang('Dự kiến nhận'): <strong>{{ showDateTime($order->created_at->addDays(3), 'd/m/Y') }}</strong></p>
                    </div>
                </div>

                <!-- Products Purchased heading -->
                <div class="content-header mb-4 mt-8">
                    <h3 class="text-lg font-bold text-[#272343]">@lang('Sản phẩm đã mua')</h3>
                </div>

                <!-- Sub-orders -->
                @foreach($order->subOrders as $subOrder)
                    <div class="shop-order-group bg-white rounded-xl border border-gray-100 overflow-hidden mb-6">
                        <div class="shop-header bg-gray-50 px-6 py-4 flex items-center justify-between border-b border-gray-100">
                            <div class="shop-name flex items-center gap-2 text-sm font-bold text-[#272343]">
                                <i class="fa-solid fa-store text-gray-400"></i>
                                @if($subOrder->seller_id == 0)
                                    <span>Kviet Shop</span>
                                    <span class="px-2 py-0.5 bg-gray-200 text-gray-600 text-[10px] font-bold rounded uppercase">Admin</span>
                                @else
                                    <span>{{ $subOrder->seller->shop->name ?? $subOrder->seller->fullname }}</span>
                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-600 text-[10px] font-bold rounded uppercase">Seller</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs text-gray-400 font-medium">#{{ $subOrder->order_number }}</span>
                                <span class="badge badge-{{ $subOrder->status }}">{!! $subOrder->badgeHtml !!}</span>
                            </div>
                        </div>
                        <div class="product-list p-6 divide-y divide-gray-100">
                            @foreach($subOrder->orderDetail as $item)
                                @php
                                    $details = json_decode($item->details);
                                    $offer_price = $details->offer_amount ?? 0;
                                    $extra_price = 0;
                                    if ($details->variants) {
                                        foreach ($details->variants as $variant) {
                                            $extra_price += $variant->price;
                                        }
                                    }
                                    $base_price = $item->base_price + $extra_price;
                                    $item_total = ($base_price - $offer_price) * $item->quantity;
                                @endphp
                                <div class="product-item py-4 first:pt-0 last:pb-0 flex items-start gap-4">
                                    <img src="{{ getImage(getFilePath('product') . '/' . @$item->product->main_image, getFileSize('product')) }}"
                                        alt="{{ __($item->product->name) }}" class="product-img w-20 h-20 rounded-lg object-cover bg-gray-50 border border-gray-100 flex-shrink-0">
                                    <div class="product-info flex-1 min-w-0">
                                        <h5 class="text-sm font-semibold text-[#272343] leading-snug line-clamp-2">{{ __($item->product->name) }}</h5>
                                        <p class="product-meta text-xs text-gray-400 mt-1">
                                            @if($item->product->brand)
                                                @lang('Thương hiệu'): <strong>{{ __($item->product->brand->name) }}</strong>
                                            @endif
                                            @if($item->product->brand && $item->product->categories->first())
                                                | 
                                            @endif
                                            @if($item->product->categories->first())
                                                @lang('Danh mục'): <strong>{{ __($item->product->categories->first()->name) }}</strong>
                                            @endif
                                        </p>
                                        @if($details->variants)
                                            <div class="text-[11px] text-gray-500 mt-2 flex flex-wrap gap-1.5">
                                                @foreach($details->variants as $variant)
                                                    <span class="bg-gray-100 rounded px-2 py-0.5 font-medium">{{ $variant->name }}: {{ $variant->value }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="product-price-qty text-right flex-shrink-0">
                                        <p class="p-price-each text-sm font-semibold text-[#272343]">{{ showAmount($base_price - $offer_price) }}</p>
                                        <p class="p-qty text-xs text-gray-400 mt-1">x{{ $item->quantity }}</p>
                                        <p class="p-total text-sm font-bold text-[#FF6F0F] mt-2">{{ showAmount($item_total) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Sub-order total sum -->
                        <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-100">
                            <span class="text-sm text-gray-500 font-medium">@lang('Tổng phụ đơn hàng con')</span>
                            <span class="text-base font-bold text-[#272343]">{{ showAmount($subOrder->total_amount) }}</span>
                        </div>
                    </div>
                @endforeach
            </main>
        </div>
    </main>
</div>
@endsection

@push('style')
<style>
    .badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-0 {
        background: #e9f5ff;
        color: #2563EB;
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

    .badge-1, .badge-5 {
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
</style>
@endpush