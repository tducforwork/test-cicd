@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="bg-[#F7F7F7]">
    <main class="container mx-auto pb-32 pt-10">
        <div class="flex flex-col lg:flex-row items-start gap-6">
            <!-- Sidebar -->
            <aside class="w-full lg:w-[312px] shrink-0">
                @include('seller.partials.sidebar')
            </aside>

            <!-- Main panel -->
            <main class="flex flex-col items-start gap-6 flex-1 min-w-0 w-full">

                <!-- Page heading -->
                <div class="flex items-center gap-4 md:gap-6">
                    <a href="{{ route('seller.purchases.index', 'all') }}" class="shrink-0 flex items-center justify-center w-10 h-10 rounded-[8px] border-[1px] border-[solid] border-[#D4D4D4] bg-[#FFF] shadow-[inset_0_1px_2px_0_rgba(255,255,255,0.40),inset_0_-1px_2px_0_rgba(0,0,0,0.24),0_1px_2px_0_rgba(0,0,0,0.08)] hover:bg-gray-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M16.75 10C16.75 10.4142 16.4142 10.75 16 10.75L6.31078 10.75L9.0307 13.4696C9.32361 13.7625 9.32363 14.2374 9.03075 14.5303C8.73787 14.8232 8.263 14.8232 7.97009 14.5304L3.96969 10.5304C3.82902 10.3897 3.75 10.1989 3.75 10C3.75 9.80107 3.82902 9.6103 3.96969 9.46964L7.97009 5.46964C8.263 5.17676 8.73787 5.17679 9.03075 5.4697C9.32363 5.7626 9.32361 6.23748 9.0307 6.53036L6.31078 9.25L16 9.25C16.4142 9.25 16.75 9.58579 16.75 10Z" fill="#272343" />
                        </svg>
                    </a>
                    <h1 class="text-[#272343] text-[24px] font-semibold leading-[normal]">@lang('Chi tiết đơn hàng')</h1>
                </div>

                <!-- Order summary card -->
                <div class="flex flex-col items-start gap-6 p-4 md:p-6 w-full bg-white rounded-[8px]">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 p-4 md:p-6 w-full bg-[#fff6f0] rounded-[8px]">
                        <div class="flex flex-col gap-2 md:gap-[8px]">
                            <div class="text-[#272343] text-[20px] font-bold leading-[28px]">
                                #{{ $order->order_number }}
                            </div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <div class="text-[#475156] text-[14px] font-normal leading-[20px] whitespace-nowrap">
                                    {{ $order->orderDetail->sum('quantity') }} @lang('Sản phẩm')
                                </div>
                                <div class="text-gray-700">•</div>
                                <div class="text-[#475156] text-[14px] font-normal leading-[20px]">
                                    @lang('Đặt lúc') {{ showDateTime($order->created_at, 'd M, Y \\a\\t h:i A') }}
                                </div>
                            </div>
                        </div>
                        <div class="text-[#FF383C] text-[28px] font-semibold leading-[32px]">
                            {{ showAmount($order->total_amount) }}
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="flex flex-col gap-[8px] w-full">
                        <div class="flex items-center gap-1">
                            <div class="text-[#272343] text-[14px] font-semibold leading-[24px] tracking-[-0.14px]">@lang('Trạng thái đơn hàng')</div>
                        </div>
                        <div class="flex items-center gap-2.5 px-3 py-3 w-full rounded-[12px] border-[1px] border-[solid] border-[#D4D4D4] bg-[#FFF]">
                            <div class="flex-1 font-medium text-[#272343] text-sm leading-6">
                                @php echo $order->statusBadge() @endphp
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products card -->
                <div class="flex flex-col items-start gap-6 p-4 md:p-6 w-full bg-white rounded-lg">
                    <div class="text-[#303030] text-[20px] font-semibold leading-[32px] tracking-[-0.4px]">@lang('Sản phẩm')</div>

                    <div class="w-full rounded-lg overflow-hidden">
                        <!-- Table header -->
                        <div class="hidden sm:flex items-center px-3 py-0">
                            <div class="w-[200px] md:w-[280px] flex items-center p-2">
                                <div class="text-[#8A8A8A] text-[13px] font-semibold leading-[16px] tracking-[-0.13px]">@lang('Sản phẩm')</div>
                            </div>
                            <div class="flex items-center justify-between flex-1">
                                <div class="flex items-center p-2 flex-1">
                                    <div class="text-[#8A8A8A] text-[13px] font-semibold leading-[16px] tracking-[-0.13px]">@lang('Giá')</div>
                                </div>
                                <div class="flex items-center p-2 flex-1">
                                    <div class="text-[#8A8A8A] text-[13px] font-semibold leading-[16px] tracking-[-0.13px]">@lang('Số lượng')</div>
                                </div>
                                <div class="flex items-center p-2 flex-1">
                                    <div class="text-[#8A8A8A] text-[13px] font-semibold leading-[16px] tracking-[-0.13px]">@lang('Tổng')</div>
                                </div>
                            </div>
                        </div>
                        <div class="h-px bg-[#f1f1f1] rounded-full"></div>

                        @php $subtotal = 0; @endphp
                        @foreach ($order->orderDetail as $data)
                        @php
                        $details = json_decode($data->details);
                        $offer_price = $details->offer_amount ?? 0;
                        $extra_price = 0;
                        if ($details->variants) {
                            foreach ($details->variants as $item) {
                                $extra_price += $item->price;
                            }
                        }
                        $base_price = $data->base_price + $extra_price;
                        $item_total = ($base_price - $offer_price) * $data->quantity;
                        $subtotal += $item_total;
                        @endphp
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4 px-1 py-4 sm:py-2">
                            <div class="flex items-center sm:w-[200px] md:w-[280px] gap-0 w-full">
                                <div class="p-2 shrink-0">
                                    <div class="w-16 h-16 md:w-20 md:h-20 rounded-lg overflow-hidden bg-gray-100">
                                        <img src="{{ getImage(getFilePath('product') . '/' . @$data->product->main_image, getFileSize('product')) }}" alt="{{ __($data->product->name) }}" class="w-full h-full object-cover">
                                    </div>
                                </div>
                                <div>
                                    <p class="overflow-hidden text-[#272343] text-[14px] font-medium leading-[24px]">
                                        {{ __($data->product->name) }}
                                    </p>
                                    @if ($data->details && $details->variants)
                                        @foreach ($details->variants as $item)
                                        <p class="text-[#8a8a8a] text-xs mt-0.5">{{ __($item->name) }}: <b>{{ __($item->value) }}</b></p>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="flex sm:flex-1 flex-row flex-wrap gap-x-4 gap-y-1 px-2 sm:px-0 w-full sm:w-auto">
                                <div class="flex items-center gap-2 sm:hidden">
                                    <span class="text-[#8a8a8a] text-xs">@lang('Giá:'):</span>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:flex-1 sm:items-center w-full">
                                    <div class="flex flex-col items-start justify-center p-0 sm:p-2 sm:flex-1">
                                        <div class="inline-flex flex-col items-center justify-center px-1.5 rounded-[8px] bg-[#EAF4FF] overflow-hidden">
                                            <div class="text-[#303030] text-center text-[15px] font-semibold leading-[24px] tracking-[-0.15px]">
                                                {{ showAmount($data->base_price - $offer_price) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center py-1 sm:p-2 sm:flex-1">
                                        <div class="flex-1 font-normal text-[#8a8a8a] text-sm">x{{ $data->quantity }}</div>
                                    </div>
                                    <div class="flex items-center py-1 sm:p-2 sm:flex-1">
                                        <div class="text-[#272343] text-[14px] font-semibold leading-[24px] tracking-[-0.14px]">{{ showAmount($item_total) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (!$loop->last)
                        <div class="h-px bg-[#f1f1f1] rounded-full"></div>
                        @endif
                        @endforeach
                    </div>

                    <!-- Total -->
                    <div class="flex flex-col w-full gap-4">
                        <div class="h-px bg-[#272343]"></div>
                        <div class="flex items-start justify-between w-full">
                            <div class="text-[#272343] text-[20px] font-bold leading-[24px]">@lang('Tổng:'):</div>
                            <div class="text-[#272343] text-right text-[20px] font-bold leading-[24px]">{{ showAmount($subtotal) }}</div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary card -->
                <div class="flex flex-col items-start gap-6 p-4 md:p-6 w-full bg-white rounded-lg">
                    <div class="text-[#272343] text-[20px] font-bold leading-[150%]">@lang('Tóm tắt đơn hàng')</div>
                    <div class="flex flex-col gap-3 w-full">
                        <div class="flex items-center justify-between">
                            <span class="text-[#6b7280] text-sm">@lang('Subtotal')</span>
                            <span class="text-[#272343] text-sm font-semibold">{{ showAmount($subtotal) }}</span>
                        </div>
                        @if ($order->appliedCoupon)
                        <div class="flex items-center justify-between">
                            <span class="text-[#6b7280] text-sm">@lang('Mã giảm giá') ({{ $order->appliedCoupon->coupon->coupon_code }})</span>
                            <span class="text-red-500 text-sm font-semibold">-{{ showAmount($order->appliedCoupon->amount) }}</span>
                        </div>
                        @endif
                        <div class="flex items-center justify-between">
                            <span class="text-[#6b7280] text-sm">@lang('Phí vận chuyển')</span>
                            <span class="text-[#272343] text-sm font-semibold">{{ showAmount($order->shipping_charge) }}</span>
                        </div>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                            <span class="text-[#272343] text-[16px] font-bold">@lang('Tổng cộng')</span>
                            <span class="text-[#FF383C] text-[20px] font-bold">{{ showAmount($order->total_amount) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address card -->
                <div class="flex flex-col items-start gap-6 p-4 md:p-6 w-full bg-white rounded-lg">
                    <div class="text-[#272343] text-[20px] font-bold leading-[150%]">@lang('Địa chỉ giao hàng')</div>
                    <div class="flex flex-col gap-4 md:gap-[16px] w-full">
                        @php $shippingAddr = json_decode($order->shipping_address); @endphp
                        <div class="flex items-center gap-1 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M6 21V19C6 17.9391 6.42143 16.9217 7.17157 16.1716C7.92172 15.4214 8.93913 15 10 15H14C15.0609 15 16.0783 15.4214 16.8284 16.1716C17.5786 16.9217 18 17.9391 18 19V21M8 7C8 8.06087 8.42143 9.07828 9.17157 9.82843C9.92172 10.5786 10.9391 11 12 11C13.0609 11 14.0783 10.5786 14.8284 9.82843C15.5786 9.07828 16 8.06087 16 7C16 5.93913 15.5786 4.92172 14.8284 4.17157C14.0783 3.42143 13.0609 3 12 3C10.9391 3 9.92172 3.42143 9.17157 4.17157C8.42143 4.92172 8 5.93913 8 7Z" stroke="#CCCCCC" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="text-[#272343] text-[16px] font-normal leading-[normal]">
                                {{ @$shippingAddr->firstname }} {{ @$shippingAddr->lastname }}
                            </div>
                        </div>
                        <div class="flex items-center gap-1 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.8413 9.85567C7.63237 13.7539 10.814 16.8432 14.7633 18.5187L14.7753 18.5237L15.5393 18.8637C16.0112 19.0741 16.5412 19.1157 17.0401 18.9815C17.5391 18.8473 17.9766 18.5455 18.2793 18.1267L19.5533 16.3637C19.5907 16.3117 19.6069 16.2474 19.5983 16.184C19.5897 16.1206 19.5572 16.0628 19.5073 16.0227L17.2833 14.2277C17.2571 14.2065 17.2268 14.1909 17.1943 14.1817C17.1619 14.1725 17.1279 14.17 17.0945 14.1743C17.061 14.1786 17.0288 14.1896 16.9997 14.2067C16.9707 14.2238 16.9453 14.2466 16.9253 14.2737L16.0593 15.4417C15.9573 15.5795 15.8111 15.6782 15.6452 15.7213C15.4792 15.7644 15.3035 15.7494 15.1473 15.6787C12.189 14.3372 9.81873 11.9669 8.4773 9.00867C8.4066 8.85246 8.39157 8.67675 8.43469 8.5108C8.47781 8.34486 8.5765 8.1987 8.7143 8.09667L9.8813 7.22967C9.90841 7.20963 9.9312 7.18431 9.94827 7.15523C9.96535 7.12616 9.97637 7.09393 9.98067 7.06048C9.98496 7.02704 9.98245 6.99307 9.97328 6.96062C9.96411 6.92817 9.94847 6.89792 9.9273 6.87167L8.1333 4.64767C8.09316 4.5978 8.03541 4.56523 7.97197 4.55667C7.90853 4.54811 7.84422 4.56422 7.7923 4.60167L6.0193 5.88167C5.5977 6.18578 5.2944 6.62649 5.16096 7.1289C5.02752 7.63132 5.07216 8.16444 5.2873 8.63767L5.8413 9.85567Z" fill="#CCCCCC" />
                            </svg>
                            <div class="text-[#272343] text-[16px] font-normal leading-[normal]">
                                {{ @$shippingAddr->mobile }}
                            </div>
                        </div>
                        <div class="flex items-start gap-1 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M12 3.75C9.10875 3.75 6.75 6.10875 6.75 9C6.75 11.637 8.71275 13.8135 11.25 14.1795V21H12.75V14.1795C15.2872 13.8135 17.25 11.637 17.25 9C17.25 6.10875 14.8912 3.75 12 3.75ZM12 5.25C14.0798 5.25 15.75 6.92025 15.75 9C15.75 11.0798 14.0798 12.75 12 12.75C9.92025 12.75 8.25 11.0798 8.25 9C8.25 6.92025 9.92025 5.25 12 5.25ZM12 6C10.35 6 9 7.35 9 9H10.5C10.5 8.16225 11.1623 7.5 12 7.5V6Z" fill="#CCCCCC" />
                            </svg>
                            <p class="text-[#272343] text-[16px] font-normal leading-[normal]">
                                {{ @$shippingAddr->address }}, {{ @$shippingAddr->ward }}, {{ @$shippingAddr->province }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Payment Info card -->
                @if (isset($order->deposit) && $order->deposit->status != 0)
                <div class="flex flex-col items-start gap-6 p-4 md:p-6 w-full bg-white rounded-lg">
                    <div class="text-[#272343] text-[20px] font-bold leading-[150%]">@lang('Thông tin thanh toán')</div>
                    <div class="flex flex-col gap-3 w-full">
                        <div class="flex items-center justify-between">
                            <span class="text-[#6b7280] text-sm">@lang('Phương thức')</span>
                            <span class="text-[#272343] text-sm font-medium">
                                @if ($order->deposit->method_code == 0)
                                COD
                                @else
                                {{ __($order->deposit->gateway->name) }}
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[#6b7280] text-sm">@lang('Phí thanh toán')</span>
                            <span class="text-[#272343] text-sm font-semibold">{{ showAmount($order->deposit->charge) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[#6b7280] text-sm">@lang('Tổng thanh toán')</span>
                            <span class="text-[#272343] text-sm font-semibold">{{ showAmount($order->deposit->amount + $order->deposit->charge) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[#6b7280] text-sm">@lang('Trạng thái')</span>
                            <span class="text-sm font-medium">
                                @php echo $order->paymentBadge() @endphp
                            </span>
                        </div>
                    </div>
                </div>
                @endif

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
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        border-width: 0.7px;
        border-style: solid;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
        border-color: #fcd34d;
    }

    .badge-primary {
        background: #dbeafe;
        color: #1e40af;
        border-color: #93c5fd;
    }

    .badge-dark {
        background: #f3f4f6;
        color: #374151;
        border-color: #9ca3af;
    }

    .badge-success {
        background: #d1fae5;
        color: #047857;
        border-color: #6ee7b7;
    }

    .badge-danger {
        background: #fce7f3;
        color: #9d174d;
        border-color: #f9a8d4;
    }
</style>
@endpush