@extends('admin.layouts.app')

@section('panel')
    <div class="row gy-4">
        <div class="col-xl-8">
            <div class="row gy-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                <div>
                                    <h5 class="card-title mb-0">@lang('Progress')</h5>
                                    <small class="text-muted">@lang('Current SubOrder Status')</small>
                                </div>
                                <div>
                                    <h6>@lang('Order No') #{{ $suborder->order_number }}</h6>
                                    <div class="text-end">
                                        @php echo $suborder->statusBadge @endphp
                                    </div>
                                </div>
                            </div>
                            <div class="status_box_wrapper">
                                <div class="status_box_container">
                                    <div class="status_box @if ($suborder->status != Status::SUBORDER_REJECTED) complete @endif">
                                        <div class="icon">
                                            <i class="las la-spinner"></i>
                                        </div>
                                        <span class="title">@lang('Pending')</span>
                                        <span class="bar @if (in_array($suborder->status, [Status::SUBORDER_PROCESSING, Status::SUBORDER_READY_TO_PICKUP, Status::SUBORDER_DISPATCHED, Status::SUBORDER_DELIVERED, Status::SUBORDER_COMPLETED])) complete @endif"></span>
                                    </div>

                                    <div class="status_box @if (in_array($suborder->status, [Status::SUBORDER_PROCESSING, Status::SUBORDER_READY_TO_PICKUP, Status::SUBORDER_DISPATCHED, Status::SUBORDER_DELIVERED, Status::SUBORDER_COMPLETED])) complete @endif">
                                        <div class="icon">
                                            <i class="las la-cog"></i>
                                        </div>
                                        <span class="title">@lang('Processing')</span>
                                        <span class="bar @if (in_array($suborder->status, [Status::SUBORDER_READY_TO_PICKUP, Status::SUBORDER_DISPATCHED, Status::SUBORDER_DELIVERED, Status::SUBORDER_COMPLETED])) complete @endif"></span>
                                    </div>

                                    <div class="status_box @if (in_array($suborder->status, [Status::SUBORDER_READY_TO_PICKUP, Status::SUBORDER_DISPATCHED, Status::SUBORDER_DELIVERED, Status::SUBORDER_COMPLETED])) complete @endif">
                                        <div class="icon">
                                            <i class="las la-truck-loading"></i>
                                        </div>
                                        <span class="title">Đang chuẩn bị gửi</span>
                                        <span class="bar @if (in_array($suborder->status, [Status::SUBORDER_DISPATCHED, Status::SUBORDER_DELIVERED, Status::SUBORDER_COMPLETED])) complete @endif"></span>
                                    </div>

                                    <div class="status_box @if (in_array($suborder->status, [Status::SUBORDER_DELIVERED, Status::SUBORDER_COMPLETED])) complete @endif">
                                        <div class="icon">
                                            <i class="las la-hand-holding"></i>
                                        </div>
                                        <span class="title">Đã giao</span>
                                        <span class="bar @if (in_array($suborder->status, [Status::SUBORDER_DELIVERED, Status::SUBORDER_COMPLETED])) complete @endif"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <h5 class="card-title mb-0">@lang('Products')</h5>
                                <small class="text-muted">@lang('Ordered Items Information')</small>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered order-table">
                                    <thead>
                                        <tr>
                                            <th>@lang('Product')</th>
                                            <th>@lang('Quantity')</th>
                                            <th>@lang('Price')</th>
                                            <th>@lang('Total Price')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($suborder->orderDetail as $item)
                                            <tr>
                                                <td>
                                                    <div class="product-item">
                                                        <div class="thumb">
                                                            <img src="{{ getImage(getFilePath('product') . '/thumb_' . $item->product->main_image) }}" alt="product-image">
                                                        </div>

                                                        <div class="content">
                                                            <span class="name">
                                                                {{ strLimit(__($item->product->name), 60) }}
                                                            </span>

                                                            @if ($item->details)
                                                                @php
                                                                    $details = json_decode($item->details);
                                                                @endphp
                                                                @if ($details->variants)
                                                                    <ul class="variants">
                                                                        @foreach ($details->variants as $variant)
                                                                            <li>{{ __($variant->name) }} : <b>{{ __($variant->value) }}</b></li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ showAmount($item->base_price) }}</td>
                                                <td>{{ showAmount($item->total_price) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="3" class="text-end fw-bold">@lang('Total Amount')</td>
                                            <td class="fw-bold">{{ showAmount($suborder->total_amount) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            @if ($suborder->order->status != Status::ORDER_CANCELED && !in_array($suborder->status, [Status::SUBORDER_DELIVERED, Status::SUBORDER_REJECTED]))
                                <div class="text-end mt-3">
                                    @if ($suborder->status == Status::SUBORDER_PENDING)
                                        <button type="button" class="btn btn-outline--success confirmationBtn" data-question="@lang('Are you sure to mark the order as processing?')" data-action="{{ route('admin.suborder.mark.as.processing', $suborder->id) }}"><i class="las la-check-double"></i>@lang('Mark As Processing')</button>
                                        <button type="button" class="btn btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to reject the order?')" data-action="{{ route('admin.suborder.reject', $suborder->id) }}"><i class="las la-times-circle"></i>@lang('Reject')</button>
                                    @elseif($suborder->status == Status::SUBORDER_PROCESSING)
                                        <button type="button" class="btn btn-outline--success confirmationBtn" data-question="@lang('Are you sure to mark the order as picked up?')" data-action="{{ route('admin.suborder.mark.as.picked.up', $suborder->id) }}"><i class="las la-check-double"></i>@lang('Mark As Picked Up')</button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="row gy-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between flex-wrap gap-2 align-items-center mb-3">
                                <div>
                                    <h5 class="card-title mb-0">@lang('Payment') @php echo $suborder->order->paymentBadge() @endphp</h5>
                                    <small class="text-muted">@lang('Order Payment Summary')</small>
                                </div>
                                <div>
                                    @if($suborder->order->payment_status == Status::PAYMENT_SUCCESS)
                                        <span class="badge badge--success">@lang('Paid via') {{ __(@$suborder->order->deposit->gateway->name ?? 'Automatic Gateway') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="summary_wrapper text-center p-3 border rounded bg--light mb-3">
                                <h4 class="text--primary">{{ showAmount($suborder->total_amount) }}</h4>
                                <p class="mb-0">@lang('Total for this SubOrder')</p>
                            </div>
                            <div class="summary_wrapper">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
                                    <span class="title">@lang('Main Order Total')</span>
                                    <span class="value">{{ showAmount($suborder->order->total_amount) }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
                                    <span class="title">@lang('Order Number')</span>
                                    <span class="value">#{{ $suborder->order->order_number }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
                                    <span class="title">@lang('Ordered At')</span>
                                    <span class="value">{{ showDateTime($suborder->created_at) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
              @if($suborder->seller_id != 0)
                <div class="col-md-12 mb-30 mt-3">
                    <div class="card border--primary">
                        <div class="card-header bg--primary">
                            <h5 class="text-white mb-0">@lang('Seller Payout')</h5>
                        </div>
                        <div class="card-body">
                            @if($suborder->is_payout == Status::YES)
                                <div class="alert alert-success d-flex align-items-center mb-0" role="alert">
                                    <i class="la la-check-circle la-2x me-2"></i>
                                    <div>
                                        <strong>@lang('Paid to Seller')</strong><br>
                                        <small>@lang('Completed at'): {{ showDateTime($suborder->payout_at) }}</small>
                                    </div>
                                </div>
                            @elseif($suborder->status == Status::SUBORDER_COMPLETED)
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div>
                                        <p class="mb-0">@lang('This suborder is settled and ready for payout.')</p>
                                        <h4 class="text--primary">{{ showAmount($suborder->total_amount) }}</h4>
                                    </div>
                                    <button class="btn btn--primary confirmationBtn" 
                                        data-question="@lang('Are you sure to payout this amount to the seller?')"
                                        data-action="{{ route('admin.order.payout', $suborder->id) }}">
                                        <i class="la la-money-bill"></i> @lang('Payout to Seller')
                                    </button>
                                </div>
                            @else
                                <div class="alert alert-warning mb-0" role="alert">
                                    <i class="la la-info-circle"></i>
                                    @lang('Payment can only be processed after the suborder is marked as Settled (Settled).')
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-md-12 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <h5 class="card-title mb-0">@lang('Customer')</h5>
                                <small class="text-muted">@lang('Buyer and Shipping Information')</small>
                            </div>

                            <div class="summary_wrapper">
                                <div class="inner_wrapper border-bottom pb-2 mb-2">
                                    <h6 class="title mb-2">
                                        <i class="lar la-user-circle"></i> @lang('Customer Information')
                                    </h6>
                                    <div class="details list-unstyled">
                                        <li>
                                            @lang('Name'): <a href="{{ route('admin.users.detail', $suborder->order->user_id) }}" class="user_link">{{ $suborder->order->user->fullname }}</a>
                                        </li>
                                        <li>
                                            @lang('Email'): {{ $suborder->order->user->email }}
                                        </li>
                                        <li>
                                            @lang('Mobile'): +{{ $suborder->order->user->mobileNumber }}
                                        </li>
                                    </div>
                                </div>

                                @php
                                    $shippingAddress = $suborder->order->shipping_address ? json_decode($suborder->order->shipping_address) : null;
                                @endphp

                                @if ($shippingAddress)
                                    <div class="inner_wrapper">
                                        <h6 class="title mb-2">
                                            <i class="las la-home"></i> @lang('Shipping Address')
                                        </h6>
                                        <div class="details list-unstyled">
                                            <li>
                                                @lang('Contact Name'): <b>{{ $shippingAddress->firstname }} {{ $shippingAddress->lastname }}</b>
                                            </li>
                                            <li>
                                                @lang('Mobile'): {{ $shippingAddress->mobile }}
                                            </li>
                                            <li>
                                                @lang('Address'): {{ $shippingAddress->address }}, {{ $shippingAddress->ward }}, {{ $shippingAddress->province }}
                                            </li>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ url()->previous() }}" />
@endpush

@push('style')
<style>
    .status_box_wrapper {
        border: 1px solid #e5e5e5;
        border-radius: 5px;
        padding: 30px 10px;
    }

    .status_box_container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        gap: 30px;
    }

    .status_box {
        text-align: center;
        flex: 1;
        position: relative;
    }

    .status_box .icon {
        width: 50px;
        height: 50px;
        line-height: 50px;
        background-color: #f7f7f7;
        color: #acacac;
        font-size: 24px;
        border-radius: 50%;
        margin: 0 auto 10px;
        position: relative;
        z-index: 1;
    }

    .status_box .title {
        display: block;
        font-weight: 500;
        font-size: 14px;
        color: #5b6e88;
    }

    .status_box .bar {
        position: absolute;
        width: 100%;
        height: 4px;
        background-color: #f1f1f1;
        top: 23px;
        left: 50%;
        z-index: 0;
    }

    .status_box:last-child .bar {
        display: none;
    }

    .status_box .bar.complete {
        background-color: #28c76f;
    }

    .status_box.complete .icon {
        background-color: #28c76f;
        color: #fff;
    }

    /* Reuse table styles from order details */
    .order-table thead th {
        background-color: #f8f9fa;
        color: #5b6e88;
        font-weight: 600;
    }
    
    .product-item {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .product-item .thumb {
        width: 50px;
        height: 50px;
        border-radius: 5px;
        overflow: hidden;
    }
    
    .product-item .thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .product-item .name {
        font-weight: 500;
        color: #5b6e88;
        display: block;
    }
    
    .variants {
        list-style: none;
        padding: 0;
        margin: 0;
        font-size: 12px;
        color: #777;
    }
</style>
@endpush
