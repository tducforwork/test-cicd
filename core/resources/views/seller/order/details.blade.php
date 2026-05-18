@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="breadcrumb-section"
    style="background-color: #f8f9fa; padding: 20px 0; border-bottom: 1px solid #eaeaea;">
    <div class="container">
        <a href="{{ route('home') }}" style="color: #666; font-size: 14px">@lang('Trang chủ')</a>
        <span style="margin: 0 10px; color: #ccc">/</span>
        <a href="{{ route('seller.home') }}" style="color: #666; font-size: 14px">@lang('Kênh người bán')</a>
        <span style="margin: 0 10px; color: #ccc">/</span>
        <a href="{{ route('seller.order.all') }}" style="color: #666; font-size: 14px">@lang('Quản lý đơn hàng')</a>
        <span style="margin: 0 10px; color: #ccc">/</span>
        <span style="color: var(--primary); font-weight: 600; font-size: 14px">@lang('Chi tiết đơn hàng') #{{ $suborder->order_number }}</span>
    </div>
</div>

<!-- MAIN SECTION -->
<section class="profile-section py-lg-5 py-4">
    <div class="container">
        <div class="profile-container">
            <!-- Sidebar -->
            <aside class="profile-sidebar">
                @include('seller.partials.sidebar')
            </aside>

            <!-- Main Content -->
            <main class="profile-main-content">
                <!-- Order Header -->
                <div class="content-header d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <div>
                        <h2 class="mb-1">@lang('Đơn hàng') #{{ $suborder->order_number }}</h2>
                        <p style="color: var(--text-muted); font-size: 14px; margin: 0;">
                            @lang('Ngày đặt'): {{ showDateTime($suborder->created_at, 'd/m/Y H:i') }}
                        </p>
                    </div>
                    <a href="{{ route('seller.order.all') }}" class="btn btn-light" style="border: 1px solid var(--border); font-weight: 600;">
                        <i class="fa-solid fa-arrow-left" style="margin-right: 6px;"></i> @lang('Quay lại danh sách')
                    </a>
                </div>

                <!-- Order Status Banner -->
                <div class="premium-card mb-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: white; border: none; padding: 24px;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-4">
                            <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </div>
                            <div>
                                <h4 class="mb-1 text-white" style="font-weight: 700;">@lang('Trạng thái đơn hàng')</h4>
                                <p class="mb-0" style="opacity: 0.8; font-size: 13px;">@lang('Vui lòng cập nhật tiến độ xử lý và giao nhận cho khách hàng.')</p>
                            </div>
                        </div>
                        <div>
                            @php echo $suborder->statusBadge @endphp
                        </div>
                    </div>
                </div>

                <!-- Status Update Section -->
                <div class="premium-card mb-4" style="padding: 24px;">
                    <h5 style="font-size: 15px; font-weight: 700; color: var(--primary); margin-bottom: 15px;"><i class="fa-solid fa-arrows-rotate" style="margin-right: 8px;"></i> @lang('Cập nhật tiến trình đơn hàng')</h5>
                    <form id="statusForm" action="{{ route('seller.order.change.status') }}" method="POST">
                        @csrf
                        <input type="hidden" name="suborder_id" value="{{ $suborder->id }}">
                        <div class="d-flex gap-3 align-items-center flex-wrap">
                            <div style="flex: 1; min-width: 250px; position: relative;">
                                <select name="status" id="statusSelect" onchange="handleStatusChange(this)"
                                    class="form-select" style="height: 48px; font-size: 14px; border-radius: 10px; cursor: pointer; @if(in_array($suborder->status, [\App\Constants\Status::SUBORDER_DELIVERED, \App\Constants\Status::SUBORDER_REJECTED, \App\Constants\Status::SUBORDER_COMPLETED, \App\Constants\Status::SUBORDER_DISPUTED])) background: #f1f5f9; color: #94a3b8; pointer-events: none; @endif">
                                    <option value="{{ \App\Constants\Status::SUBORDER_PENDING }}" @selected($suborder->status == \App\Constants\Status::SUBORDER_PENDING)>@lang('Chờ xử lý')</option>
                                    <option value="{{ \App\Constants\Status::SUBORDER_PROCESSING }}" @selected($suborder->status == \App\Constants\Status::SUBORDER_PROCESSING)>@lang('Đang chuẩn bị hàng / Đóng gói')</option>
                                    <option value="{{ \App\Constants\Status::SUBORDER_READY_TO_PICKUP }}" @selected($suborder->status == \App\Constants\Status::SUBORDER_READY_TO_PICKUP)>@lang('Chờ lấy hàng / Bàn giao shipper')</option>
                                    <option value="{{ \App\Constants\Status::SUBORDER_DISPATCHED }}" @selected($suborder->status == \App\Constants\Status::SUBORDER_DISPATCHED)>@lang('Đang giao hàng')</option>
                                    <option value="{{ \App\Constants\Status::SUBORDER_DELIVERED }}" @selected($suborder->status == \App\Constants\Status::SUBORDER_DELIVERED)>@lang('Đã giao thành công')</option>
                                </select>
                            </div>
                            <button type="submit" id="statusSubmitBtn" class="btn btn-primary hidden" style="height: 48px; background: var(--primary); border: none; font-weight: 700; padding: 0 30px;">
                                <i class="fa-solid fa-circle-check" style="margin-right: 6px;"></i> @lang('Cập nhật trạng thái')
                            </button>
                        </div>
                    </form>
                </div>

                <div class="row g-4">
                    <!-- Left Column: Products -->
                    <div class="col-lg-8">
                        <div class="premium-card mb-4" style="padding: 24px;">
                            <h4 style="font-size: 16px; font-weight: 700; color: var(--primary); margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">
                                <i class="fa-solid fa-box-open" style="margin-right: 8px;"></i> @lang('Danh sách sản phẩm')
                            </h4>
                            <div class="table-responsive">
                                <table class="table align-middle" style="margin-bottom: 0;">
                                    <thead>
                                        <tr style="border-bottom: 1px solid #f1f5f9; font-size: 12px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">
                                            <th>@lang('Sản phẩm')</th>
                                            <th style="text-align: center;">@lang('Đơn giá')</th>
                                            <th style="text-align: center;">@lang('Số lượng')</th>
                                            <th style="text-align: right;">@lang('Thành tiền')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($suborder->orderDetail as $item)
                                            @php
                                                $details = json_decode($item->details);
                                                $offer_price = $details->offer_amount ?? 0;
                                                $extra_price = 0;
                                                if (@$details->variants) {
                                                    foreach ($details->variants as $variant) {
                                                        $extra_price += $variant->price;
                                                    }
                                                }
                                                $base_price = $item->base_price + $extra_price;
                                                $item_total = ($base_price - $offer_price) * $item->quantity;
                                            @endphp
                                            <tr style="border-bottom: 1px solid #f8fafc;">
                                                <td style="padding: 15px 0;">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <img src="{{ getImage(getFilePath('product') . '/' . @$item->product->main_image, getFileSize('product')) }}"
                                                            style="width: 50px; height: 50px; border-radius: 8px; object-fit: cover; border: 1px solid var(--border);">
                                                        <div>
                                                            <div style="font-weight: 600; font-size: 14px; color: var(--primary);">
                                                                {{ __($item->product->name) }}
                                                            </div>
                                                            @if ($item->details && @$details->variants)
                                                                <div class="d-flex gap-2 flex-wrap mt-1">
                                                                    @foreach ($details->variants as $variant)
                                                                        <span style="font-size: 11px; padding: 2px 8px; background: #f1f5f9; border-radius: 4px; color: #64748b;">
                                                                            {{ __($variant->name) }}: <b>{{ __($variant->value) }}</b>
                                                                        </span>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="text-align: center; font-weight: 600; font-size: 14px;">
                                                    {{ showAmount($item->base_price - $offer_price) }}
                                                </td>
                                                <td style="text-align: center; font-weight: 600; font-size: 14px; color: #64748b;">
                                                    x{{ $item->quantity }}
                                                </td>
                                                <td style="text-align: right; font-weight: 700; font-size: 14px; color: var(--accent);">
                                                    {{ showAmount($item->total_price) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div style="border-top: 2px dashed var(--border); margin-top: 20px; padding-top: 20px;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span style="font-weight: 700; font-size: 15px; color: var(--primary);">@lang('TỔNG GIÁ TRỊ ĐƠN HÀNG')</span>
                                    <span style="font-weight: 800; font-size: 22px; color: var(--accent);">{{ showAmount($suborder->total_amount) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Shipping & Customer Details -->
                    <div class="col-lg-4">
                        <!-- Customer Card -->
                        <div class="premium-card mb-4" style="padding: 20px;">
                            <h5 style="font-size: 15px; font-weight: 700; color: var(--primary); margin-bottom: 15px; border-bottom: 1px solid var(--border); padding-bottom: 8px;">
                                <i class="fa-solid fa-user" style="margin-right: 6px;"></i> @lang('Khách hàng')
                            </h5>
                            @php
                                $shippingAddr = json_decode($suborder->order->shipping_address);
                            @endphp
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: #eee; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #64748b;">
                                    {{ strtoupper(substr($shippingAddr->firstname ?? 'K', 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 700; font-size: 15px; color: var(--primary);">
                                        {{ @$shippingAddr->firstname }} {{ @$shippingAddr->lastname }}
                                    </div>
                                    <div style="font-size: 12px; color: var(--text-muted);">
                                        @lang('Điện thoại'): {{ @$shippingAddr->mobile }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Card -->
                        <div class="premium-card mb-4" style="padding: 20px;">
                            <h5 style="font-size: 15px; font-weight: 700; color: var(--primary); margin-bottom: 15px; border-bottom: 1px solid var(--border); padding-bottom: 8px;">
                                <i class="fa-solid fa-truck" style="margin-right: 6px;"></i> @lang('Địa chỉ giao hàng')
                            </h5>
                            <p style="font-size: 13.5px; color: #475569; line-height: 1.6; margin: 0;">
                                {{ @$shippingAddr->address }}, {{ @$shippingAddr->ward }}, {{ @$shippingAddr->province }}
                            </p>
                        </div>

                        <!-- Payment Card -->
                        @if (isset($suborder->order->deposit))
                            <div class="premium-card" style="padding: 20px;">
                                <h5 style="font-size: 15px; font-weight: 700; color: var(--primary); margin-bottom: 15px; border-bottom: 1px solid var(--border); padding-bottom: 8px;">
                                    <i class="fa-solid fa-credit-card" style="margin-right: 6px;"></i> @lang('Thông tin thanh toán')
                                </h5>
                                <div style="font-size: 13.5px; color: #475569; line-height: 1.8;">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">@lang('Phương thức'):</span>
                                        <strong>
                                            @if ($suborder->order->deposit->method_code == 0)
                                                COD (Thanh toán khi nhận hàng)
                                            @else
                                                {{ __($suborder->order->deposit->gateway->name) }}
                                            @endif
                                        </strong>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">@lang('Trạng thái'):</span>
                                        <span>
                                            @php echo $suborder->order->paymentBadge() @endphp
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>
</section>
@endsection

@push('script')
<script>
    'use strict';
    function handleStatusChange(select) {
        var submitBtn = document.getElementById('statusSubmitBtn');
        if (select.value != select.options[0].value) {
            submitBtn.classList.remove('hidden');
            submitBtn.classList.add('inline-flex', 'items-center');
        } else {
            submitBtn.classList.add('hidden');
            submitBtn.classList.remove('inline-flex', 'items-center');
        }
    }
</script>
@endpush

@push('style')
<style>
    .badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        border-width: 1px;
        border-style: solid;
        text-transform: uppercase;
    }

    .badge--warning {
        background: #fef3c7;
        color: #92400e;
        border-color: #fcd34d;
    }

    .badge--dark {
        background: #f3f4f6;
        color: #374151;
        border-color: #9ca3af;
    }

    .badge--success {
        background: #d1fae5;
        color: #047857;
        border-color: #6ee7b7;
    }

    .badge--danger {
        background: #fce7f3;
        color: #9d174d;
        border-color: #f9a8d4;
    }

    .badge-paid {
        background: #d1fae5;
        color: #047857;
        border-color: #6ee7b7;
    }

    .badge-unpaid {
        background: #fee2e2;
        color: #b91c1c;
        border-color: #fca5a5;
    }
</style>
@endpush
