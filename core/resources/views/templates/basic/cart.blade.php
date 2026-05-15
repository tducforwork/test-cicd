@extends(activeTemplate() . 'layouts.frontend')
@section('content')
    <div class="cart-page container pb-[100px] mt-4">
        {{-- Breadcrumb --}}
        <div class="cart-page__breadcrumb flex items-center gap-[12px] py-[12px] text-[#606060] text-base">
            <a href="{{ route('home') }}"
                class="cart-page__breadcrumb-item cursor-pointer text-decoration-none text-[#606060] hover:text-[#ff6f0f]">Home</a>
            <img src="{{ asset('assets/images/frontend/kviet/detail-product/img.png') }}" class="w-2" alt="arrow" />
            <p class="cart-page__breadcrumb-item cart-page__breadcrumb-item--active text-[#292929] m-0">Cart</p>
        </div>

        <div class="cart-page__wrapper flex flex-col gap-[40px] mt-4">
            @if ($data->count() > 0)
                {{-- Table Header --}}
                <div
                    class="cart-header bg-white shadow-[0_1px_13px_0_rgba(0,0,0,0.05)] rounded-lg flex items-center py-6 px-[40px] text-[#272343] font-medium hidden md:flex">
                    <div class="w-2/5">@lang('Product')</div>
                    <div class="w-1/5 text-center">@lang('Price')</div>
                    <div class="w-1/5 text-center">@lang('Quantity')</div>
                    <div class="w-1/5 text-right">@lang('Subtotal')</div>
                </div>

                @php
                    $subtotal = 0;
                    $productCategories = [];
                    $couponProducts = $data->pluck('product_id')->unique()->toArray();
                @endphp

                <div class="flex flex-col gap-4">
                    @foreach ($data as $item)
                        @php
                            $productCategories[] = $item->product->categories->pluck('id')->toArray();
                            if (!empty($item->attributes)) {
                                $s_price = App\Models\AssignProductAttribute::priceAfterAttribute($item->product, $item->attributes);
                            } else {
                                $s_price = $item->product->discount_price > 0 ? $item->product->discount_price : $item->product->base_price;
                            }
                            $subtotal += $s_price * $item['quantity'];
                        @endphp

                        <div
                            class="cart-item cart-row bg-white shadow-[0_1px_13px_0_rgba(0,0,0,0.05)] rounded-lg flex flex-col md:flex-row md:items-center py-[16px] px-[20px] md:px-[32px] text-[#272343] gap-4 md:gap-0 relative">
                            {{-- Remove Button Mobile & PC --}}


                            {{-- Product Info --}}
                            <div class="w-full md:w-2/5 flex items-center gap-4">

                                <div class="cart-item__image-wrap relative w-[70px] h-[70px] bg-[#f8f9fa] rounded-lg shrink-0">
                                    <div data-id="{{ $item->id }}"
                                        class="remove-cart-item absolute -top-2 -right-2 md:-top-3 md:-left-3 md:right-auto z-10 w-[24px] h-[24px] bg-[#DE4944] rounded-full flex items-center justify-center cursor-pointer hover:bg-red-600 transition-colors shadow-md">
                                        <span class="text-white text-sm font-bold leading-none -mt-0.5 pointer-events-none">×</span>
                                    </div>
                                    <a href="{{ route('product.detail', $item->product->slug) }}">
                                        <img src="{{ getImage(getFilePath('product') . '/thumb_' . @$item->product->main_image, getFileSize('product')) }}"
                                            class="w-full h-full object-contain rounded-lg p-1" alt="product" />
                                    </a>
                                </div>
                                <div class="flex flex-col">
                                    <a href="{{ route('product.detail', $item->product->slug) }}"
                                        class="text-sm font-medium leading-relaxed max-w-[400px] line-clamp-2 hover:text-[#ff6f0f] text-decoration-none text-[#272343]">
                                        {{ strLimit(__($item->product->name), 100) }}
                                    </a>

                                </div>
                            </div>

                            {{-- Price Mobile Layout Helper --}}
                            <div class="w-full md:w-3/5 flex items-center justify-between md:justify-start">
                                {{-- Price --}}
                                <div class="w-1/3 md:w-1/3 text-left md:text-center font-normal text-sm md:text-base">
                                    <span class="md:hidden text-gray-500 text-xs block mb-1">@lang('Price')</span>
                                    {{ showAmount($s_price) }}
                                </div>

                                {{-- Quantity Selector --}}
                                <div class="w-1/3 md:w-1/3 flex justify-center">
                                    <div
                                        class="product-detail__quantity quantity flex items-center justify-between bg-white border border-[#AAAAAA] rounded-[8px] h-[40px] md:h-[48px] w-[76px] md:w-[86px] px-2 md:px-3 outline-none">
                                        <input type="number" data-id="{{ $item->id }}" data-price="{{ $s_price }}"
                                            class="qty integer-validation border-0 text-[16px] md:text-[18px] font-normal text-[#272343] text-center p-0 m-0 bg-transparent w-full focus:ring-0 outline-none select-none pointer-events-none"
                                            min="1" step="1" value="{{ $item['quantity'] }}" readonly>
                                        <style>
                                            input[type="number"]::-webkit-inner-spin-button,
                                            input[type="number"]::-webkit-outer-spin-button {
                                                -webkit-appearance: none;
                                                margin: 0;
                                            }

                                            input[type="number"] {
                                                -moz-appearance: textfield;
                                            }
                                        </style>
                                        <div class="flex flex-col items-center gap-1 justify-center h-full">
                                            <img src="{{ asset('assets/images/frontend/kviet/up-arrow.png') }}"
                                                class="qtybutton inc w-[12px] md:w-[16px] h-[12px] md:h-[16px] cursor-pointer hover:opacity-100" />
                                            <img src="{{ asset('assets/images/frontend/kviet/down-arrow.png') }}"
                                                class="qtybutton dec w-[12px] md:w-[16px] h-[12px] md:h-[16px] cursor-pointer hover:opacity-100" />
                                        </div>
                                    </div>
                                </div>

                                {{-- Subtotal --}}
                                <div
                                    class="w-1/3 md:w-1/3 text-right font-semibold text-[#cc0001] md:text-[#272343] text-sm md:text-base">
                                    <span
                                        class="md:hidden text-gray-500 text-xs block mb-1 text-[#272343] font-normal">@lang('Subtotal')</span>
                                    <span
                                        class="total_price_display text-inherit">{{ showAmount($s_price * $item['quantity']) }}</span>
                                    <span class="total_price hidden">{{ getAmount($s_price * $item['quantity'], 2) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Cart Total --}}
                <div class="cart-total flex flex-col items-end gap-[20px] md:gap-[40px] mt-4">
                    <div
                        class="flex items-center gap-12 md:gap-24 bg-white md:bg-transparent shadow-sm md:shadow-none p-4 md:p-0 rounded-lg w-full md:w-auto justify-between md:justify-end">
                        <span class="text-[16px] md:text-[20px] text-[#272343]">@lang('Subtotal'):</span>
                        <span class="text-[20px] md:text-[24px] font-bold text-[#cc0001]">
                            <span id="cartSubtotalDisplay" class="text-inherit">{{ showAmount($subtotal) }}</span>
                            <span id="cartSubtotal" class="hidden">{{ getAmount($subtotal, 2) }}</span>
                        </span>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto mt-2 md:mt-0">
                        <a href="{{ route('home') }}"
                            class="w-full sm:w-auto bg-[#F4F4F4] text-[#272343] py-[16px] px-8 rounded-[8px] font-medium text-[16px] leading-[20px] hover:bg-[#e2e2e2] transition-colors text-center text-decoration-none">
                            @lang('Continue Shopping')
                        </a>
                        @php
                        @endphp
                        <a href="{{ auth()->check()
                            ? route('user.checkout')
                            : route('user.login', ['cart_session_id' => session('session_id')]) }}"
                            class="w-full sm:w-[336px] bg-[#ff6f0f] text-white py-[16px] rounded-[8px] font-medium text-[16px] leading-[20px] hover:bg-orange-600 transition-colors shadow-lg text-center block text-decoration-none">
                            @lang('Proceed to checkout')
                        </a>
                    </div>
                </div>

            @else
                <div class="alert alert-warning alert-dismissible fade show mb-5" role="alert">
                    <strong>{{ __($emptyMessage) }}</strong>
                </div>
                <div class="flex justify-center mt-3">
                    <a href="{{ route('home') }}"
                        class="bg-[#ff6f0f] text-white py-[16px] px-8 rounded-[8px] font-medium transition-colors shadow-lg block text-center text-decoration-none hover:bg-orange-600">@lang('Continue Shopping')</a>
                </div>
            @endif
        </div>
    </div>

@endsection
@push('script')
    <script>
        'use strict';
        (function ($) {
            var cartSubtotal = parseFloat('{{ $subtotal ?? 0 }}');
            sessionStorage.setItem('subtotal', cartSubtotal);

            // Replicate PHP showAmount for frontend JS updates
            function displayCurrencyHelper(amount) {
                var sym = '{{ gs("cur_sym") }}';
                var text = '{{ __(gs("cur_text")) }}';
                var format = {{ gs("currency_format") }};

                var formatted = parseFloat(amount).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                if (format == 1) return sym + formatted + text;
                else if (format == 2) return formatted + text;
                else return sym + formatted;
            }

            $('.quantity input[type=number]').on('keyup', function () {
                updateCart($(this), this.value)
            });

            $('.qtybutton').on('click', function () {
                var couponAmount = parseFloat($('#couponAmount').text() || 0).toFixed(2);
                if (couponAmount > 0) {
                    notify('error', 'You have applied a coupon on your cart. If you want to update your cart, at first remove the coupon.');
                    return false;
                }
                var oldValue = $(this).parents('.cart-row').find('input[type=number]').val();

                if ($(this).hasClass('inc')) {
                    var qty = parseFloat(oldValue) + 1;
                } else {
                    if (oldValue > 1) {
                        var qty = parseFloat(oldValue) - 1;
                    } else {
                        qty = 1;
                    }
                }
                $(this).parents('.cart-row').find('input[type=number]').val(qty);
                updateCart($(this), qty)
            });

            $('.remove-cart-item').on('click', function() {
                // Trình xử lý AJAX xoá ngầm nằm ở script/main.blade.php
                // Ở trang Giỏ hàng này, ta sẽ tự động reload lại trang sau 500ms để cập nhật giá tiền mới nhất
                setTimeout(function() {
                    location.reload();
                }, 500);
            });

            function updateCart(obj, qty) {
                var parent = obj.parents('.cart-row');
                var sub_total = formatNumber($('#cartSubtotal').text());
                var prev_total = formatNumber(parent.find('.total_price').text());
                var price = formatNumber(parent.find('input[type=number]').data('price'));
                var total = qty * price;
                var dif = total - parseFloat(prev_total);
                var subtotal = (parseFloat(sub_total) + parseFloat(dif));
                var id = $(parent).find('input[type=number]').data('id');
                var data = {
                    quantity: qty
                };

                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    url: "{{ route('update-cart-item', '') }}" + "/" + id,
                    method: "post",
                    data: data,
                    success: function (response) {
                        if (response.error) {
                            $('.quantity input[type=number]').val(response.qty)
                            notify('error', response.error);
                        } else {
                            $('#cartSubtotal').text(parseFloat(subtotal).toFixed(2));
                            $('#cartSubtotalDisplay').text(displayCurrencyHelper(subtotal));
                            sessionStorage.setItem('subtotal', subtotal.toFixed(2));
                            parent.find('.total_price').text(parseFloat(total.toFixed(2)));
                            parent.find('.total_price_display').text(displayCurrencyHelper(total));
                            getCartTotal();
                            getCartData();
                            $('#finalTotal').text(parseFloat((subtotal - parseFloat($('#couponAmount').text() || 0)).toFixed(2)));
                        }
                    }
                });
            }

            $(document).on('keydown', 'input[name=coupon_code]', function (e) {
                if (e.key === 'Enter') {
                    applyCoupon();
                }
            });

            $(document).on('click', 'button[name=coupon_apply]', function () {
                applyCoupon();
            });

            function applyCoupon() {
                var code = $('input[name=coupon_code]').val();
                var subtotal = formatNumber($('#cartSubtotal').text());

                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    url: `{{ route('applyCoupon') }}`,
                    method: "POST",
                    data: {
                        code: code,
                        subtotal: subtotal,
                        categories: @json(@$productCategories),
                        products: @json(@$couponProducts)
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#couponAmount').text(response.amount);
                            $('#finalTotal').text(parseFloat((subtotal - response.amount).toFixed(2)));

                            $('.couponCode').text(response.coupon_code);

                            $('.coupon-amount-total').removeClass('d-none').hide().show('300');
                            getCartData();
                            notify('success', response.success);
                        } else if (response.error) {
                            notify('error', response.error);
                        } else {
                            notify('error', response);
                        }
                    }
                });
            }

            function formatNumber(number) {
                if (typeof (number) != 'string') {
                    return number;
                }
                return parseFloat(number.replace(/,/g, ''));
            }
        })(jQuery);
    </script>
@endpush