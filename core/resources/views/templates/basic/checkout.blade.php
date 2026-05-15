@extends(activeTemplate() . 'layouts.frontend')
@section('content')
    <div class="checkout-page container pb-[100px]">
        {{-- Breadcrumb --}}
        <div class="checkout-page__breadcrumb flex items-center gap-[12px] py-[12px] text-[#606060] text-base">
            <a href="{{ route('home') }}" class="checkout-page__breadcrumb-item cursor-pointer">@lang('Home')</a>
            <img src="{{ asset('assets/images/frontend/kviet/detail-product/img.png') }}" class="w-2" alt="arrow" />
            <p class="checkout-page__breadcrumb-item checkout-page__breadcrumb-item--active text-[#292929]">
                @lang('CheckOut')</p>
        </div>

        <form action="{{ route('user.checkout-to-payment', 1) }}" method="post" class="checkout-form">
            @csrf
            <div class="checkout-page__layout grid grid-cols-12 gap-24 mt-3">
                {{-- Left Column: Billing Details --}}
                <div class="checkout-page__left col-span-12 lg:col-span-6">
                    <h2 class="text-[20px] font-medium text-[#272343] mb-[16px] leading-normal">@lang('Billing Details')
                    </h2>

                    <div class="flex flex-col gap-8">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-group flex flex-col gap-2">
                                <label class="text-base font-normal text-[#272343]">@lang('First Name')<span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="firstname"
                                    value="{{ auth()->user()->firstname ?? old('firstname') }}" required
                                    class="w-full bg-[#F3F4F5] outline-none border-none rounded-[4px] h-[50px] px-4 font-medium" />
                            </div>
                            <div class="form-group flex flex-col gap-2">
                                <label class="text-base font-normal text-[#272343]">@lang('Last Name')<span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="lastname"
                                    value="{{ auth()->user()->lastname ?? old('lastname') }}" required
                                    class="w-full bg-[#F3F4F5] outline-none border-none rounded-[4px] h-[50px] px-4 font-medium" />
                            </div>
                        </div>

                        <div class="form-group flex flex-col gap-2">
                            <label class="text-base font-normal text-[#272343]">@lang('Phone Number')<span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="mobile" value="{{ auth()->user()->mobile ?? old('mobile') }}"
                                required
                                class="w-full bg-[#F3F4F5] outline-none border-none rounded-[4px] h-[50px] px-4 font-medium" />
                        </div>

                        <div class="form-group flex flex-col gap-2">
                            <label class="text-base font-normal text-[#272343]">@lang('Email')<span
                                    class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ auth()->user()->email ?? old('email') }}"
                                required
                                class="w-full bg-[#F3F4F5] outline-none border-none rounded-[4px] h-[50px] px-4 font-medium" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-group flex flex-col gap-2">
                                <label class="text-base font-normal text-[#272343]">@lang('Province')<span
                                        class="text-red-500">*</span></label>
                                <select name="province_id" required
                                    class="w-full bg-[#F3F4F5] outline-none border-none rounded-[4px] h-[50px] px-4 font-medium appearance-none">
                                    <option value="">@lang('Select Province')</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}"
                                            {{ auth()->user() && auth()->user()->province_id == $province->id ? 'selected' : '' }}>
                                            {{ __($province->full_name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group flex flex-col gap-2">
                                <label class="text-base font-normal text-[#272343]">@lang('Ward')<span
                                        class="text-red-500">*</span></label>
                                <select name="ward_id" required
                                    class="w-full bg-[#F3F4F5] outline-none border-none rounded-[4px] h-[50px] px-4 font-medium appearance-none">
                                    <option value="">@lang('Select Ward')</option>
                                    @if (auth()->user() && auth()->user()->province_id)
                                        @foreach (\App\Models\Ward::where('province_id', auth()->user()->province_id)->orderBy('name')->get() as $ward)
                                            <option value="{{ $ward->id }}"
                                                {{ auth()->user()->ward_id == $ward->id ? 'selected' : '' }}>
                                                {{ __($ward->full_name) }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group flex flex-col gap-2">
                            <label class="text-base font-normal text-[#272343]">@lang('Address')<span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="address" value="{{ auth()->user()->address ?? old('address') }}"
                                required
                                class="w-full bg-[#F3F4F5] outline-none border-none rounded-[4px] h-[50px] px-4 font-medium" />
                        </div>

                        <div class="form-group flex flex-col gap-2">
                            <label class="text-base font-normal text-[#272343]">@lang('Order Note')
                                (@lang('Optional'))</label>
                            <textarea name="note"
                                class="w-full bg-[#F3F4F5] outline-none border-none rounded-[4px] min-h-[100px] px-4 py-2 font-medium"
                                placeholder="@lang('Notes about your order, e.g. special notes for delivery.')"></textarea>
                        </div>

                        <div class="flex items-center gap-3 mt-2">
                            <label class="relative flex items-center cursor-pointer group">
                                <input type="checkbox" checked class="sr-only peer" />
                                <div
                                    class="w-6 h-6 bg-[#F3F4F5] border border-gray-300 rounded-[4px] peer-checked:bg-[#DE4944] peer-checked:border-[#DE4944] transition-all flex items-center justify-center overflow-hidden">
                                    <span class="text-white text-[16px] font-extrabold leading-none mb-0.5">✓</span>
                                </div>
                                <span class="ml-3 text-base text-[#272343]">@lang('Save this information for faster check-out next time')</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Order Summary & Payment --}}
                <div class="checkout-page__right col-span-12 lg:col-span-6 flex flex-col gap-10">
                    {{-- Order Summary Card --}}
                    <div
                        class="order-summary bg-white shadow-[0_1px_13px_0_rgba(0,0,0,0.05)] rounded-2xl p-6 flex flex-col gap-8">
                        <div class="checkout-items-list flex flex-col gap-6 max-h-[400px] overflow-y-auto pr-2">
                            @foreach ($data as $item)
                                <div class="checkout-item flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-[70px] h-[70px] bg-[#f8f9fa] rounded-[4px] shrink-0 overflow-hidden border border-gray-100">
                                            <img src="{{ getImage(getFilePath('product') . '/thumb_' . @$item->product->main_image, getFileSize('product')) }}"
                                                class="w-full h-full object-contain" alt="product" />
                                        </div>
                                        <div class="flex flex-col">
                                            <p class="text-sm font-medium text-[#272343] line-clamp-2 leading-relaxed">
                                                {{ __($item->product->name) }}
                                            </p>
                                            <span class="text-xs text-gray-500">@lang('Qty'): {{ $item->quantity }}</span>
                                        </div>
                                    </div>
                                    @php
                                        $s_price =
                                            $item->product->discount_price > 0
                                                ? $item->product->discount_price
                                                : $item->product->base_price;
                                        if (!empty($item->attributes)) {
                                            $s_price = App\Models\AssignProductAttribute::priceAfterAttribute(
                                                $item->product,
                                                $item->attributes,
                                            );
                                        }
                                    @endphp
                                    <span
                                        class="text-sm font-semibold text-[#272343]">{{ showAmount($s_price * $item->quantity) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="summary-details flex flex-col gap-3 pt-4 ">
                            {{-- <div class="flex items-center justify-between">
                                <span class="text-base text-[#7A7A7A]">@lang('Subtotal'):</span>
                                <span class="text-base font-semibold text-[#272343]" id="cartSubtotal">0</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-base text-[#7A7A7A]">@lang('Shipping Charge'):</span>
                                <span class="text-base font-semibold text-red-500" id="shippingCharge">0</span>
                            </div> --}}
                            <div
                                class="flex items-center justify-between mt-2 pt-4 border-t-2 border-dashed border-gray-200">
                                <span class="text-[18px] font-semibold text-[#272343]">@lang('Total'):</span>
                                <span class="text-[24px] font-bold text-[#DE4944]" id="cartTotal">0</span>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Methods --}}
                    <div class="payment-methods flex flex-col gap-[32px]">
                        <h3 class="text-[20px] font-medium text-[#272343]">@lang('Payment Method')</h3>

                        <div
                            class="payment-options flex items-center justify-between bg-[#F8F9FA] p-4 rounded-xl border border-gray-100">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="payment" value="1" checked
                                    class="sr-only custom-radio" />
                                <div
                                    class="radio-box w-6 h-6 border-2 border-gray-300 rounded-full flex items-center justify-center transition-all relative bg-white">
                                    <div class="radio-dot w-3 h-3 bg-[#DE4944] rounded-full hidden"></div>
                                </div>
                                <img src="{{ asset('assets/images/frontend/kviet/otpay-card.png') }}" class="h-[36px]"
                                    alt="payos" />
                                <span class="text-[16px] text-[#272343] font-medium">PayOS (VietQR)</span>
                            </label>

                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="payment" value="2" class="sr-only custom-radio" />
                                <div
                                    class="radio-box w-6 h-6 border-2 border-gray-300 rounded-full flex items-center justify-center transition-all relative bg-white">
                                    <div class="radio-dot w-3 h-3 bg-[#DE4944] rounded-full hidden"></div>
                                </div>
                                <img src="{{ asset('assets/images/frontend/kviet/cod.png') }}" class="h-[36px]"
                                    alt="cod" />
                                <span class="text-[16px] text-[#272343] font-medium">@lang('COD')</span>
                            </label>
                        </div>

                        <button type="submit"
                            class="bg-[#FF6F0F] text-white w-full py-[16px] rounded-[4px] font-medium  leading-none transition-all shadow-lg active:scale-[0.98]">
                            @lang('Place Order')
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .custom-radio:checked+.radio-box {
            border-color: #DE4944 !important;
        }

        .custom-radio:checked+.radio-box .radio-dot {
            display: block !important;
        }
    </style>
@endsection

@push('script')
    <script>
        'use strict';
        (function($) {
            function updateTotals() {
                var subTotal = 0;
                @foreach ($data as $item)
                    @php
                        $calc_price = $item->product->discount_price > 0 ? $item->product->discount_price : $item->product->base_price;
                        if (!empty($item->attributes)) {
                            $calc_price = App\Models\AssignProductAttribute::priceAfterAttribute($item->product, $item->attributes);
                        }
                    @endphp
                    subTotal += {{ $calc_price * $item->quantity }};
                @endforeach

                var shippingCharge = 0; // Có thể lấy từ AJAX nếu có thay đổi dựa trên địa chỉ

                $('#cartSubtotal').text(`{{ gs('cur_sym') }}` + subTotal.toLocaleString());
                $('#shippingCharge').text(`{{ gs('cur_sym') }}` + shippingCharge.toLocaleString());
                $('#cartTotal').text( (subTotal + shippingCharge).toLocaleString() + `{{ gs('cur_sym') }}`);
            }

            updateTotals();

            $('select[name=province_id]').on('change', function() {
                var provinceId = $(this).val();
                var wardSelect = $('select[name=ward_id]');
                wardSelect.empty().append('<option value="">@lang('Select Ward')</option>');
                if (provinceId) {
                    $.get('{{ route('get.wards', '') }}/' + provinceId, function(data) {
                        $.each(data, function(index, ward) {
                            wardSelect.append('<option value="' + ward.id + '">' + ward
                                .full_name + '</option>');
                        });
                    });
                }
            });
        })(jQuery)
    </script>
@endpush
