@extends('Template::layouts.frontend')
@section('content')
<div class="thank-you-section padding-top padding-bottom">
    <div class="container">
        <div class="thank-you-wrapper text-center">
            <div class="success-animation-wrapper">
                <div class="failed-circle">
                    <div class="background"></div>
                    <div class="cross h-full w-full flex items-center justify-center">
                        <i class="las la-times text-white" style="font-size: 60px;"></i>
                    </div>
                </div>
            </div>
            
            <h2 class="title mt-4">@lang('Thanh toán không thành công')</h2>
            <p class="description mt-3">
                @lang('Rất tiếc, giao dịch của bạn không thể hoàn tất hoặc đã bị hủy. Vui lòng kiểm tra lại hoặc thử phương thức thanh toán khác.')
            </p>
            
            <div class="order-info-card mt-5">
                <div class="card-body p-4">
                    <p class="order-number-label">@lang('Mã đơn hàng liên quan:')</p>
                    <h3 class="order-number text--danger">#{{ $order->order_number }}</h3>
                    <p class="mt-2 text-muted">@lang('Bạn có thể thử thanh toán lại trong phần lịch sử đơn hàng.')</p>
                </div>
            </div>
            
            <div class="action-buttons mt-5">
                <a href="{{ route('user.deposit.history') }}" class="btn btn-outline--danger me-3">
                    <i class="las la-history"></i> @lang('Lịch sử thanh toán')
                </a>
                <a href="{{ route('home') }}" class="btn btn--danger">
                    @lang('Quay về trang chủ')
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
    
    .failed-circle {
        width: 100px;
        height: 100px;
        position: relative;
        display: inline-block;
        vertical-align: top;
    }
    
    .failed-circle .background {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: #dc3545;
        position: absolute;
    }
    
    .failed-circle .cross {
        position: relative;
        z-index: 1;
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
    
    .btn--danger {
        background-color: #dc3545;
        color: #fff;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 500;
    }
    
    .btn--danger:hover {
        background-color: #bb2d3b;
        color: #fff;
    }
    
    .btn-outline--danger {
        border: 1px solid #dc3545;
        color: #dc3545;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 500;
    }
    
    .btn-outline--danger:hover {
        background-color: #dc3545;
        color: #fff;
    }
    
    .text--danger {
        color: #dc3545 !important;
    }
</style>
@endsection
