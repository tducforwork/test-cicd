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
                @php
                    $subtotal = 0;
                    $productCategories = [];
                    $couponProducts = $data->pluck('product_id')->unique()->toArray();
                @endphp
                <div class="cart-grid" id="cartGrid" style="margin-top: 24px;">
                    <!-- Left Column: Products & Vouchers -->
                    <div class="cart-left">
                        <div class="cart-card">
                            <h1 class="cart-title">
                                <i class="fa-solid fa-cart-flatbed"></i> @lang('Giỏ hàng của bạn')
                            </h1>
                            <table class="cart-table">
                                <thead>
                                    <tr>
                                        <th>@lang('Sản phẩm')</th>
                                        <th>@lang('Đơn giá')</th>
                                        <th>@lang('Số lượng')</th>
                                        <th>@lang('Thành tiền')</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="cartTableBody">
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
                                        <tr class="cart-row" data-price="{{ $s_price }}" data-id="{{ $item->id }}">
                                            <td>
                                                <div class="cart-item-detail">
                                                    <div class="cart-item-img">
                                                        <a href="{{ route('product.detail', $item->product->slug) }}">
                                                            <img src="{{ getImage(getFilePath('product') . '/thumb_' . @$item->product->main_image, getFileSize('product')) }}"
                                                                alt="Product" />
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('product.detail', $item->product->slug) }}"
                                                            class="cart-item-name">{{ strLimit(__($item->product->name), 100) }}</a>
                                                        <div class="cart-item-meta">
                                                            @lang('Thương hiệu'):
                                                            <strong>{{ @$item->product->brand->name ?? 'Đang cập nhật' }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="price-val">{{ showAmount($s_price) }}</span></td>
                                            <td>
                                                <div class="qty-input quantity">
                                                    <button class="qty-btn cart-qty-btn dec minus"><i
                                                            class="fa-solid fa-minus"></i></button>
                                                    <input type="text" class="qty-val qty integer-validation"
                                                        data-id="{{ $item->id }}" data-price="{{ $s_price }}"
                                                        value="{{ $item['quantity'] }}" readonly />
                                                    <button class="qty-btn cart-qty-btn inc plus"><i
                                                            class="fa-solid fa-plus"></i></button>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="price-val row-total total_price_display">{{ showAmount($s_price * $item['quantity']) }}</span>
                                                <span
                                                    class="total_price hidden">{{ getAmount($s_price * $item['quantity'], 2) }}</span>
                                            </td>
                                            <td>
                                                <button class="remove-btn remove-cart-item" data-id="{{ $item->id }}">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Voucher Section -->
                        <div class="cart-card voucher-section">
                            <h2 class="voucher-title">
                                <i class="fa-solid fa-ticket"></i> @lang('Voucher ưu đãi')
                            </h2>

                            <div class="voucher-input-group">
                                <div class="voucher-input-wrapper w-100 position-relative">
                                    <input type="text" id="voucherCodeInput" name="coupon_code" class="w-100"
                                        placeholder="@lang('Nhập mã giảm giá (ví dụ: QP50, QP100...)')" 
                                        value="{{ session()->has('coupon') ? session('coupon')['code'] : '' }}" />
                                    <span id="clearVoucherBtn" class="clear-voucher-btn" 
                                        style="display: {{ session()->has('coupon') ? 'block' : 'none' }};">
                                        <i class="fa-solid fa-circle-xmark"></i>
                                    </span>
                                </div>
                                <button class="btn-apply-voucher" id="applyCodeBtn"
                                    name="coupon_apply">@lang('Áp dụng')</button>
                            </div>

                            <div class="voucher-list">
                                @if(isset($coupons) && $coupons->count() > 0)
                                    @foreach($coupons as $coupon)
                                        <div class="voucher-item dynamic-voucher {{ (session()->has('coupon') && session('coupon')['code'] == $coupon->coupon_code) ? 'selected' : '' }}" data-code="{{ $coupon->coupon_code }}">
                                            <div class="voucher-icon">
                                                @if($coupon->discount_type == 1)
                                                    <i class="fa-solid fa-percent"></i>
                                                @else
                                                    <i class="fa-solid fa-gift"></i>
                                                @endif
                                            </div>
                                            <div class="voucher-info">
                                                <h5>{{ __($coupon->coupon_name) }}</h5>
                                                <p>@lang('Mã'): <strong>{{ $coupon->coupon_code }}</strong> | @lang('Đơn từ'):
                                                    {{ showAmount($coupon->minimum_spend) }}
                                                </p>
                                            </div>
                                            <div class="voucher-check">
                                                <i class="fa-solid fa-circle-check"></i>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted w-100">@lang('Hiện tại không có mã giảm giá nào.')</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Summary -->
                    <div class="cart-right">
                        <div class="cart-card summary-card">
                            <h2 class="summary-title">@lang('Tổng tiền giỏ hàng')</h2>
                            <div class="summary-row">
                                <span>@lang('Tạm tính')</span>
                                <span id="cartSubtotalDisplay">{{ showAmount($subtotal) }}</span>
                                <span id="cartSubtotal" class="hidden">{{ getAmount($subtotal, 2) }}</span>
                            </div>
                            <div class="summary-row">
                                <span>@lang('Voucher giảm giá')</span>
                                <span class="summary-discount coupon-amount-total">- <span
                                        id="couponAmount">{{ session()->has('coupon') ? showAmount(session('coupon')['amount']) : showAmount(0) }}</span></span>
                            </div>
                            <div class="summary-row">
                                <span>@lang('Phí vận chuyển')</span>
                                <span>@lang('Miễn phí')</span>
                            </div>
                            <div class="summary-total">
                                <span>@lang('Tổng cộng')</span>
                                <span class="total-price-final"
                                    id="finalTotalDisplay">{{ showAmount($subtotal - (session()->has('coupon') ? session('coupon')['amount'] : 0)) }}</span>
                                <span id="finalTotal"
                                    class="hidden">{{ getAmount($subtotal - (session()->has('coupon') ? session('coupon')['amount'] : 0), 2) }}</span>
                            </div>
                            <a href="{{ auth()->check() ? route('user.checkout') : route('user.login', ['cart_session_id' => session('session_id')]) }}"
                                class="btn-checkout-final text-decoration-none">
                                @lang('Thanh toán ngay') <i class="fa-solid fa-arrow-right"></i>
                            </a>
                            <p style="text-align: center; font-size: 13px; color: #94a3b8; margin-top: 20px;">
                                <i class="fa-solid fa-shield-halved"></i> @lang('Thanh toán an toàn & bảo mật')
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty Cart State -->
                <div class="empty-cart" id="emptyCart" style="display: block;">
                    <i class="fa-solid fa-cart-circle-xmark"></i>
                    <h3>@lang('Giỏ hàng của bạn đang trống')</h3>
                    <p>@lang('Hãy dạo quanh và chọn cho mình những sản phẩm ưng ý nhé!')</p>
                    <a href="{{ route('home') }}" class="btn-back-shop">@lang('Quay lại cửa hàng')</a>
                </div>
            @endif
        </div>
    </div>

@endsection
@push('style')
    <style>
        .cart-grid {
            display: grid;
            grid-template-columns: 2.2fr 1fr;
            gap: 30px;
        }

        .cart-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            border: 1px solid #f1f5f9;
            margin-bottom: 25px;
        }

        .cart-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 25px;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .cart-title i {
            color: var(--accent);
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cart-table th {
            text-align: left;
            padding: 12px 15px;
            border-bottom: 1px solid #f1f5f9;
            color: #94a3b8;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .cart-table td {
            padding: 20px 15px;
            border-bottom: 1px solid #f8fafc;
            vertical-align: middle;
        }

        .cart-item-detail {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .cart-item-img {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            overflow: hidden;
            flex-shrink: 0;
            background: #f8fafc;
        }

        .cart-item-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cart-item-name {
            font-weight: 600;
            font-size: 15px;
            color: var(--primary);
            text-decoration: none;
            display: block;
            margin-bottom: 4px;
            transition: var(--transition);
        }

        .cart-item-name:hover {
            color: var(--accent);
        }

        .cart-item-meta {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .cart-item-meta strong {
            font-weight: 500;
            color: #64748b;
        }

        .qty-input {
            display: flex;
            align-items: center;
            border: 1px solid #f1f5f9;
            border-radius: 8px;
            width: fit-content;
            background: #f8fafc;
            padding: 1px;
        }

        .qty-btn {
            background: none;
            border: none;
            width: 36px;
            height: 36px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            transition: var(--transition);
            border-radius: 8px;
        }

        .qty-btn:hover {
            background: #f1f5f9;
            color: var(--primary);
        }

        .qty-val {
            width: 45px;
            text-align: center;
            border: none;
            font-weight: 700;
            font-size: 15px;
            outline: none;
            background: transparent;
        }

        .price-val {
            font-weight: 700;
            color: var(--primary);
            font-size: 15px;
            letter-spacing: -0.01em;
        }

        .remove-btn {
            color: #cbd5e1;
            cursor: pointer;
            transition: var(--transition);
            background: transparent;
            border: none;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .remove-btn:hover {
            color: #ef4444;
            border-color: #fee2e2;
            background: #fef2f2;
        }

        .voucher-section {
            margin-top: 40px;
        }

        .voucher-title {
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--primary);
        }

        .voucher-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
        }

        .voucher-item {
            border: 2px dashed #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 18px;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            background: #fff;
        }

        .voucher-item:hover {
            border-color: var(--accent);
            background: #fffbeb;
            transform: translateY(-3px);
        }

        .voucher-icon {
            width: 54px;
            height: 54px;
            background: #fff7ed;
            color: var(--accent);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .voucher-info h5 {
            margin: 0 0 6px;
            font-size: 15px;
            font-weight: 800;
        }

        .voucher-info p {
            margin: 0;
            font-size: 13px;
            color: #64748b;
        }

        .voucher-check {
            position: absolute;
            top: 15px;
            right: 15px;
            color: var(--accent);
            font-size: 18px;
            opacity: 0;
            transition: var(--transition);
        }

        .voucher-item:hover .voucher-check {
            opacity: 1;
        }

        .summary-card {
            position: sticky;
            top: 20px;
        }

        .summary-title {
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f1f5f9;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 18px;
            font-size: 15px;
            color: #64748b;
        }

        .summary-row span:last-child {
            font-weight: 700;
            color: var(--primary);
        }

        .summary-discount {
            color: #10b981 !important;
        }

        .summary-total {
            border-top: 2px solid #f1f5f9;
            padding-top: 25px;
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .summary-total span:first-child {
            font-size: 16px;
            font-weight: 700;
            color: #64748b;
        }

        .total-price-final {
            font-size: 28px;
            font-weight: 900;
            color: var(--accent);
        }

        .btn-checkout-final {
            width: 100%;
            padding: 14px 20px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 25px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-checkout-final:hover {
            background: #1e293b;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(15, 23, 42, 0.15);
            color: white;
        }

        .empty-cart {
            text-align: center;
            padding: 60px 0;
        }

        .empty-cart i {
            font-size: 80px;
            color: #e2e8f0;
            margin-bottom: 20px;
        }

        .empty-cart h3 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .btn-back-shop {
            display: inline-block;
            padding: 12px 30px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 700;
            margin-top: 20px;
        }

        .voucher-input-group {
            display: flex;
            gap: 12px;
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 1px dashed #e2e8f0;
        }

        .voucher-input-wrapper {
            position: relative;
            flex: 1;
        }

        .clear-voucher-btn {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
            font-size: 18px;
            transition: var(--transition);
            display: none;
        }

        .clear-voucher-btn:hover {
            color: var(--accent);
        }

        .voucher-item.selected {
            border-color: var(--accent);
            background: #fff8f3;
            border-style: solid;
        }

        .voucher-item.selected .voucher-check {
            opacity: 1;
        }

        .voucher-input-group input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #f1f5f9;
            border-radius: 12px;
            outline: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .voucher-input-group input:focus {
            border-color: var(--accent);
            background: #fffbeb;
        }

        .btn-apply-voucher {
            padding: 0 30px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-apply-voucher:hover {
            background: #1e293b;
            color: white;
        }

        @media (max-width: 991px) {
            .cart-grid {
                grid-template-columns: 1fr;
            }

            .cart-table th {
                display: none;
            }

            .cart-table td {
                display: block;
                width: 100%;
                border: none;
                padding: 10px 15px;
            }

            .cart-row {
                border-bottom: 1px solid #f1f5f9;
                display: flex;
                flex-direction: column;
                position: relative;
                padding: 15px 0;
            }

            .cart-table td:first-child {
                padding-top: 0;
                padding-right: 40px;
            }

            .cart-item-detail {
                flex-direction: row;
            }

            .remove-btn {
                position: absolute;
                top: 15px;
                right: 0;
            }

            .cart-table td:nth-child(2),
            .cart-table td:nth-child(3),
            .cart-table td:nth-child(4) {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .cart-table td:nth-child(2):before {
                content: "Đơn giá: ";
                font-weight: 600;
                color: #94a3b8;
            }

            .cart-table td:nth-child(3):before {
                content: "Số lượng: ";
                font-weight: 600;
                color: #94a3b8;
            }

            .cart-table td:nth-child(4):before {
                content: "Thành tiền: ";
                font-weight: 600;
                color: #94a3b8;
            }

            .cart-card {
                padding: 20px;
            }
        }
    </style>
@endpush

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

            $('.quantity input[type=text]').on('keyup', function () {
                updateCart($(this), this.value)
            });

            $('.cart-qty-btn').on('click', function () {
                console.log(333);

                var couponAmount = parseFloat($('#couponAmount').text() || 0).toFixed(2);
                if (couponAmount > 0) {
                    notify('error', 'Bạn đã áp dụng mã giảm giá. Vui lòng gỡ mã giảm giá nếu muốn cập nhật giỏ hàng.');
                    return false;
                }
                var oldValue = $(this).parents('.cart-row').find('input[type=text]').val();

                if ($(this).hasClass('inc')) {
                    var qty = parseFloat(oldValue) + 1;
                } else {
                    if (oldValue > 1) {
                        var qty = parseFloat(oldValue) - 1;
                    } else {
                        qty = 1;
                    }
                }
                $(this).parents('.cart-row').find('input[type=text]').val(qty);
                updateCart($(this), qty)
            });

            $('.remove-cart-item').on('click', function () {
                var parent = $(this).parents('.cart-row');
                var id = $(this).data('id');

                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    url: "{{ route('remove-cart-item', '') }}" + "/" + id,
                    method: "post",
                    success: function (response) {
                        if (response.error) {
                            notify('error', response.error);
                        } else {
                            setTimeout(function () {
                                location.reload();
                            }, 300);
                        }
                    }
                });
            });

            function updateCart(obj, qty) {
                console.log(222222222);
                var parent = obj.parents('.cart-row');
                var sub_total = formatNumber($('#cartSubtotal').text());
                var prev_total = formatNumber(parent.find('.total_price').text());
                var price = formatNumber(parent.find('input[type=text]').data('price'));
                var total = qty * price;
                var dif = total - parseFloat(prev_total);
                var subtotal = (parseFloat(sub_total) + parseFloat(dif));
                var id = $(parent).find('input[type=text]').data('id');
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
                            parent.find('input[type=text]').val(response.qty)
                            notify('error', response.error);
                        } else {
                            $('#cartSubtotal').text(parseFloat(subtotal).toFixed(2));
                            $('#cartSubtotalDisplay').text(displayCurrencyHelper(subtotal));
                            sessionStorage.setItem('subtotal', subtotal.toFixed(2));
                            parent.find('.total_price').text(parseFloat(total.toFixed(2)));
                            parent.find('.total_price_display').text(displayCurrencyHelper(total));

                            var couponAmt = parseFloat(formatNumber($('#couponAmount').text()) || 0);
                            $('#finalTotal').text(parseFloat((subtotal - couponAmt).toFixed(2)));
                            $('#finalTotalDisplay').text(displayCurrencyHelper(subtotal - couponAmt));

                            if (typeof getCartTotal === 'function') getCartTotal();
                            if (typeof getCartData === 'function') getCartData();
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
                    url: "{{ route('applyCoupon') }}",
                    method: "POST",
                    data: {
                        code: code,
                        subtotal: subtotal,
                        categories: $.parseJSON('{{ json_encode($productCategories) }}'),
                        products: $.parseJSON('{{ json_encode($couponProducts) }}')
                    },
                    success: function (response) {
                        if (response.success) {
                            notify('success', response.success);
                            $('#clearVoucherBtn').show();
                            setTimeout(function () {
                                location.reload();
                            }, 300);
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

            $('.dynamic-voucher').on('click', function () {
                var code = $(this).data('code');
                $('input[name=coupon_code]').val(code);

                $('.dynamic-voucher').removeClass('selected');
                $(this).addClass('selected');
                $('#clearVoucherBtn').show();
            });

            $(document).on('input', 'input[name=coupon_code]', function () {
                if ($(this).val().trim() !== '') {
                    $('#clearVoucherBtn').show();
                } else {
                    $('#clearVoucherBtn').hide();
                }
            });

            $(document).on('click', '#clearVoucherBtn', function () {
                var hasActiveSession = "{{ session()->has('coupon') ? '1' : '0' }}";
                var currentVal = $('input[name=coupon_code]').val().trim();
                var sessionCode = "{{ session()->has('coupon') ? session('coupon')['code'] : '' }}";

                if (hasActiveSession === "1" && currentVal === sessionCode) {
                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        url: "{{ route('removeCoupon') }}",
                        method: "POST",
                        success: function (response) {
                            if (response.success) {
                                notify('success', response.success);
                                setTimeout(function () {
                                    location.reload();
                                }, 300);
                            } else {
                                notify('error', response.error || 'Có lỗi xảy ra');
                            }
                        }
                    });
                } else {
                    $('input[name=coupon_code]').val('');
                    $('#clearVoucherBtn').hide();
                    $('.dynamic-voucher').removeClass('selected');
                }
            });
        })(jQuery);
    </script>
@endpush