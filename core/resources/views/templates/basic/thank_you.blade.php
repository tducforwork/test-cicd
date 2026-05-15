@extends('Template::layouts.frontend')
@section('content')
<div class="thank-you-section padding-top padding-bottom">
    <div class="container">
        <div class="thank-you-wrapper text-center">
            <div class="success-animation-wrapper">
                <div class="checkmark-circle">
                    <div class="background"></div>
                    <div class="checkmark draw"></div>
                </div>
            </div>
            
            <h2 class="title mt-4">@lang('Cảm ơn bạn đã đặt hàng!')</h2>
            <p class="description mt-3">
                @lang('Đơn hàng của bạn đã được tiếp nhận thành công. Chúng tôi sẽ sớm liên hệ với bạn để xác nhận.')
            </p>
            
            <div class="order-info-card mt-5">
                <div class="card-body p-4">
                    <p class="order-number-label">@lang('Mã đơn hàng của bạn là:')</p>
                    <h3 class="order-number text--base">#{{ $order->order_number }}</h3>
                    <p class="mt-2 text-muted">@lang('Một email xác nhận đã được gửi đến') <strong>{{ auth()->user()->email }}</strong></p>
                </div>
            </div>
            
            <div class="action-buttons mt-5">
                <a href="{{ route('user.order.details', $order->order_number) }}" class="btn btn-outline--base me-3">
                    <i class="las la-file-invoice"></i> @lang('Xem chi tiết đơn hàng')
                </a>
                <a href="{{ route('home') }}" class="btn btn--base">
                    @lang('Tiếp tục mua sắm')
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .thank-you-wrapper {
        max-width: 600px;
        margin: 0 auto;
    }
    
    .success-animation-wrapper {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }
    
    .checkmark-circle {
        width: 100px;
        height: 100px;
        position: relative;
        display: inline-block;
        vertical-align: top;
    }
    
    .checkmark-circle .background {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: #ff6f0f;
        position: absolute;
    }
    
    .checkmark-circle .checkmark {
        display: block;
    }
    
    .checkmark-circle .checkmark.draw:after {
        animation-duration: 800ms;
        animation-timing-function: ease;
        animation-name: checkmark;
        transform: scaleX(-1) rotate(135deg);
    }
    
    .checkmark-circle .checkmark:after {
        opacity: 1;
        height: 50px;
        width: 25px;
        transform-origin: left top;
        border-right: 5px solid #fff;
        border-top: 5px solid #fff;
        content: '';
        left: 25px;
        top: 50px;
        position: absolute;
    }
    
    @keyframes checkmark {
        0% {
            height: 0;
            width: 0;
            opacity: 1;
        }
        20% {
            height: 0;
            width: 25px;
            opacity: 1;
        }
        40% {
            height: 50px;
            width: 25px;
            opacity: 1;
        }
        100% {
            height: 50px;
            width: 25px;
            opacity: 1;
        }
    }
    
    .order-info-card {
        background: #f8f9fa;
        border-radius: 12px;
        border: 1px dashed #ced4da;
    }
    
    .order-number {
        font-size: 32px;
        font-weight: 700;
        letter-spacing: 1px;
    }
    
    .title {
        color: #042656;
        font-weight: 600;
    }
    
    .description {
        font-size: 16px;
        color: #555;
        max-width: 450px;
        margin: 0 auto;
    }
    
    .btn--base {
        background-color: #ff6f0f;
        color: #fff;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 500;
    }
    
    .btn--base:hover {
        background-color: #e65a00;
        color: #fff;
    }
    
    .btn-outline--base {
        border: 1px solid #ff6f0f;
        color: #ff6f0f;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 500;
    }
    
    .btn-outline--base:hover {
        background-color: #ff6f0f;
        color: #fff;
    }
    
    .text--base {
        color: #ff6f0f !important;
    }
</style>
@endsection
