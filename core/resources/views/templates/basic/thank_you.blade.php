@extends('Template::layouts.frontend')
@section('content')
    <div class="success-page py-lg-5 py-4">
        <div class="container">
            <div class="success-card mx-auto">
                <div class="success-icon">
                    <i class="fa-solid fa-check"></i>
                </div>
                <h1 class="success-title">@lang('Đặt Hàng Thành Công!')</h1>
                <p class="success-desc">
                    @lang('Cảm ơn bạn đã mua sắm tại Quảng Phát Logistic.')<br>
                    @lang('Đơn hàng của bạn đã được ghi nhận và đang trong quá trình xử lý.')
                </p>

                <div class="order-details-box text-start">
                    <div class="detail-row">
                        <span class="detail-label">@lang('Mã đơn hàng:')</span>
                        <span class="detail-value text-accent">#{{ $order->order_number }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">@lang('Ngày đặt:')</span>
                        <span class="detail-value">{{ showDateTime($order->created_at, 'd/m/Y') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">@lang('Phương thức thanh toán:')</span>
                        <span class="detail-value">
                            @if($order->deposit && $order->deposit->method_code == 0)
                                @lang('Thanh toán khi nhận hàng (COD)')
                            @else
                                {{ __(@$order->deposit->gateway->name ?? __('Thanh toán Online')) }}
                            @endif
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">@lang('Dự kiến giao hàng:')</span>
                        <span class="detail-value">{{ showDateTime($order->created_at->addDays(4), 'd/m/Y') }} -
                            {{ showDateTime($order->created_at->addDays(6), 'd/m/Y') }}</span>
                    </div>
                    <div class="detail-row pt-3 mt-2 border-top-dashed">
                        <span class="detail-label font-weight-bold">@lang('Tổng thanh toán:')</span>
                        <span class="detail-value text-accent font-size-lg">{{ showAmount($order->total_amount) }}</span>
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="{{ route('home') }}" class="btn-action btn-outline-custom">
                        <i class="fa-solid fa-arrow-left"></i> @lang('Về trang chủ')
                    </a>
                    <a href="{{ route('user.order.details', $order->order_number) }}" class="btn-action btn-primary-custom">
                        <i class="fa-solid fa-box-open"></i> @lang('Xem đơn hàng')
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .success-page {
            background: #f8fafc;
            min-height: 500px;
            display: flex;
            align-items: center;
        }

        .success-card {
            background: white;
            border-radius: 20px;
            padding: 50px 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
            max-width: 600px;
            width: 100%;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        /* Decorative top border */
        .success-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, #10b981, #059669);
        }

        .success-icon {
            width: 90px;
            height: 90px;
            background: #d1fae5;
            color: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 45px;
            margin: 0 auto 25px;
            animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        @keyframes popIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .success-title {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 15px;
        }

        .success-desc {
            color: #64748b;
            font-size: 15px;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .order-details-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid #e2e8f0;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .detail-row:last-child {
            margin-bottom: 0;
        }

        .border-top-dashed {
            border-top: 1px dashed #cbd5e1;
        }

        .detail-label {
            color: #64748b;
        }

        .detail-value {
            color: #0f172a;
            font-weight: 600;
        }

        .text-accent {
            color: #ffb800 !important;
        }

        .font-weight-bold {
            font-weight: 700 !important;
        }

        .font-size-lg {
            font-size: 18px !important;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .btn-action {
            padding: 14px 25px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 15px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
        }

        .btn-primary-custom {
             background: var(--primary);
                    color: white;
                    border: 2px solid var(--primary);
        }

        .btn-primary-custom:hover {
               background: #1e293b;
                    border-color: #1e293b;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
        }

        .btn-outline-custom {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--border);
        }

        .btn-outline-custom:hover {
            border-color: var(--primary);
            background: #f8fafc;
            transform: translateY(-2px);
        }

        @media (max-width: 575px) {
            .action-buttons {
                flex-direction: column-reverse;
                gap: 10px;
            }

            .btn-action {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endsection