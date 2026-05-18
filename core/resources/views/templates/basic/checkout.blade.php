@extends(activeTemplate() . 'layouts.frontend')
@section('content')
    <form action="{{ route('user.checkout-to-payment', 1) }}" method="post" class="checkout-form">
        @csrf
        <div class="checkout-page py-lg-5 py-4">
            <div class="container">
                <div class="checkout-grid">
                    <!-- Left Column -->
                    <div class="checkout-left">
                        <div class="checkout-card">
                            <h2 class="checkout-title"><i class="fa-solid fa-location-dot"></i> @lang('Billing Details')</h2>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">@lang('Last Name') <span class="text-danger">*</span></label>
                                    <input type="text" name="lastname" class="form-input" placeholder="Nhập họ"
                                        value="{{ auth()->user()->lastname ?? old('lastname') }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">@lang('First Name') <span class="text-danger">*</span></label>
                                    <input type="text" name="firstname" class="form-input" placeholder="Nhập tên"
                                        value="{{ auth()->user()->firstname ?? old('firstname') }}" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">@lang('Phone Number') <span class="text-danger">*</span></label>
                                    <input type="text" name="mobile" class="form-input" placeholder="Số điện thoại liên hệ"
                                        value="{{ auth()->user()->mobile ?? old('mobile') }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">@lang('Email') <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-input" placeholder="Địa chỉ email"
                                        value="{{ auth()->user()->email ?? old('email') }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('Address') <span class="text-danger">*</span></label>
                                <input type="text" name="address" class="form-input" placeholder="Số nhà, tên đường, phường/xã..."
                                    value="{{ auth()->user()->address ?? old('address') }}" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">@lang('Province') <span class="text-danger">*</span></label>
                                    <select name="province_id" class="form-input select-province" required>
                                        <option value="">@lang('Select Province')</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->id }}"
                                                {{ (auth()->user() && auth()->user()->province_id == $province->id) ? 'selected' : '' }}>
                                                {{ __($province->full_name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">@lang('Ward') <span class="text-danger">*</span></label>
                                    <select name="ward_id" class="form-input select-ward" required>
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

                            <div class="form-group">
                                <label class="form-label">@lang('Order Note') (@lang('Optional'))</label>
                                <textarea name="note" class="form-input" style="height: 100px; resize: none;"
                                    placeholder="Lưu ý cho người giao hàng..."></textarea>
                            </div>
                        </div>

                        <div class="checkout-card">
                            <h2 class="checkout-title"><i class="fa-solid fa-credit-card"></i> @lang('Payment Method')</h2>
                            <div class="payment-methods">
                                <label class="payment-method active">
                                    <input type="radio" name="payment" value="2" checked>
                                    <i class="fa-solid fa-truck-fast"></i>
                                    <div>
                                        <div style="font-weight: 700; margin-bottom: 2px;">@lang('COD')</div>
                                        <div style="font-size: 12px; color: var(--text-muted);">Thanh toán khi nhận hàng, kiểm tra hàng trước khi trả tiền</div>
                                    </div>
                                </label>
                                
                                <!-- Tạm comment các phương thức thanh toán khác chờ tích hợp thêm -->
                                <!--
                                <label class="payment-method">
                                    <input type="radio" name="payment" value="1">
                                    <i class="fa-solid fa-money-bill-transfer"></i>
                                    <div>
                                        <div style="font-weight: 700; margin-bottom: 2px;">Chuyển khoản ngân hàng (QR Code)</div>
                                        <div style="font-size: 12px; color: var(--text-muted);">Xử lý nhanh chóng, an toàn tuyệt đối</div>
                                    </div>
                                </label>
                                <label class="payment-method">
                                    <input type="radio" name="payment" value="3">
                                    <i class="fa-solid fa-wallet"></i>
                                    <div>
                                        <div style="font-weight: 700; margin-bottom: 2px;">Ví điện tử (Momo / ZaloPay)</div>
                                        <div style="font-size: 12px; color: var(--text-muted);">Ưu đãi hoàn tiền hấp dẫn</div>
                                    </div>
                                </label>
                                -->
                            </div>
                        </div>
                    </div>

                    <!-- Right Column (Sidebar Summary) -->
                    <div class="checkout-right">
                        <div class="checkout-card" style="position: sticky; top: 20px;">
                            <h2 class="checkout-title">@lang('Order Summary')</h2>
                            <div class="order-items">
                                @foreach ($data as $item)
                                    <div class="order-summary-item">
                                        <div class="order-summary-img">
                                            <img src="{{ getImage(getFilePath('product') . '/thumb_' . @$item->product->main_image, getFileSize('product')) }}"
                                                alt="product" />
                                        </div>
                                        <div class="order-summary-info">
                                            <div class="order-summary-name">{{ __($item->product->name) }}</div>
                                            @php
                                                $s_price = $item->product->discount_price > 0 ? $item->product->discount_price : $item->product->base_price;
                                                if (!empty($item->attributes)) {
                                                    $s_price = App\Models\AssignProductAttribute::priceAfterAttribute($item->product, $item->attributes);
                                                }
                                            @endphp
                                            <div class="order-summary-price">
                                                {{ showAmount($s_price) }} x {{ $item->quantity }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div style="margin-top: 25px;">
                                <div class="summary-row">
                                    <span>@lang('Subtotal')</span>
                                    <span id="cartSubtotal">0</span>
                                </div>
                                <div class="summary-row">
                                    <span>@lang('Shipping Charge')</span>
                                    <span id="shippingCharge">0</span>
                                </div>
                                
                                @if(session('coupon'))
                                <div class="summary-row" style="color: #10b981;">
                                    <span>Giảm giá Voucher ({{ session('coupon')['code'] }})</span>
                                    <span id="couponAmountDisplay">-{{ showAmount(session('coupon')['amount']) }}</span>
                                </div>
                                @else
                                <div class="summary-row" style="color: #10b981; display: none;" id="couponRow">
                                    <span>Giảm giá Voucher</span>
                                    <span id="couponAmountDisplay">0</span>
                                </div>
                                @endif

                                <div class="summary-total">
                                    <span>@lang('Total')</span>
                                    <span style="color: var(--accent);" id="cartTotal">0</span>
                                </div>
                            </div>

                            <button type="submit" class="btn-confirm">@lang('Place Order')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('style')
    <style>
        .checkout-page {
            background: #f8fafc;
        }

        .checkout-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 30px;
        }

        .checkout-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            margin-bottom: 20px;
        }

        .checkout-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--primary);
            border: none;
            padding: 0;
            background: none;
        }

        .checkout-title i {
            color: var(--accent);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-main);
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: inherit;
            transition: var(--transition);
            background: #fff;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }

        .payment-methods {
            display: grid;
            gap: 15px;
        }

        .payment-method {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            cursor: pointer;
            transition: var(--transition);
        }

        .payment-method:hover {
            border-color: var(--accent);
            background: #fffbeb;
        }

        .payment-method.active {
            border-color: var(--accent);
            background: #fffbeb;
            border-width: 2px;
        }

        .payment-method input {
            accent-color: var(--accent);
            width: 18px;
            height: 18px;
        }

        .payment-method i {
            font-size: 20px;
            width: 30px;
            text-align: center;
            color: var(--primary);
        }

        .order-summary-item {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border);
        }

        .order-summary-img {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .order-summary-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .order-summary-info {
            flex: 1;
        }

        .order-summary-name {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 4px;
            color: var(--text-main);
        }

        .order-summary-price {
            font-size: 14px;
            color: var(--accent);
            font-weight: 700;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
            color: var(--text-muted);
        }

        .summary-total {
            border-top: 1px solid var(--border);
            padding-top: 15px;
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
            font-weight: 800;
            font-size: 18px;
            color: var(--primary);
        }

        .btn-confirm {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 25px;
        }

        .btn-confirm:hover {
            background: #1e293b;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.1);
        }

        @media (max-width: 991px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }
    </style>
@endpush

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

                var shippingCharge = 0; 
                var couponAmount = {{ session('coupon')['amount'] ?? 0 }};
                var total = subTotal + shippingCharge - couponAmount;
                if (total < 0) {
                    total = 0;
                }

                $('#cartSubtotal').text(subTotal.toLocaleString() + `{{ gs('cur_sym') }}`);
                $('#shippingCharge').text(shippingCharge.toLocaleString() + `{{ gs('cur_sym') }}`);
                
                if (couponAmount > 0) {
                    $('#couponAmountDisplay').text(`-` + couponAmount.toLocaleString() + `{{ gs('cur_sym') }}`);
                }
                
                $('#cartTotal').text(total.toLocaleString() + `{{ gs('cur_sym') }}`);
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

            // Toggle payment method active class (Just in case other methods are uncommented later)
            $('.payment-method').on('click', function() {
                $('.payment-method').removeClass('active');
                $(this).addClass('active');
            });

        })(jQuery)
    </script>
@endpush
