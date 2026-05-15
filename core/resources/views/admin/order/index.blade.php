@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Order ID') | @lang('Time')</th>
                                    <th class="text-center">@lang('Customer')</th>
                                    <th class="text-center">@lang('Seller')</th>
                                    <th class="text-center">@lang('Products')</th>
                                    <th class="text-center">@lang('Amount')</th>
                                    <th class="text-center">@lang('Status')</th>
                                    <th class="text-center">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $item)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">#{{ @$item->order_number }}</span> <br>
                                            <small class="text--muted">Khớp với: #{{ @$item->order->order_number }}</small><br>
                                            <small>{{ showDateTime($item->created_at) }}</small>
                                        </td>

                                        <td class="text-center">
                                            <a href="{{ route('admin.users.detail', $item->order->user_id) }}">
                                                {{ $item->order->user->username }}
                                            </a>
                                        </td>

                                        <td class="text-center">
                                            @if ($item->seller_id == 0)
                                                <span class="badge badge--dark">@lang('Quản trị viên')</span>
                                            @else
                                                <a href="{{ route('admin.sellers.shop.details', $item->seller_id) }}" class="fw-bold text--primary">
                                                    {{ __(@$item->seller->shop->name ?? $item->seller->fullname) }}
                                                </a>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <span class="badge badge--info">{{ $item->total_products }}</span>
                                        </td>

                                        <td class="text-center">
                                            <b>{{ showAmount($item->total_amount) }}</b><br>
                                            @php echo $item->order->paymentBadge() @endphp
                                        </td>

                                        <td class="text-center">
                                            @php echo $item->statusBadge @endphp
                                        </td>

                                        <td class="text-center">
                                            <div class="button--group">
                                                <a href="{{ route('admin.suborder.detail', $item->id) }}"
                                                    class="btn btn-sm btn-outline--primary">
                                                    <i class="la la-desktop"></i>@lang('Detail')
                                                </a>

                                                @if ($item->status == Status::SUBORDER_PENDING)
                                                    <button type="button" class="btn btn-sm btn-outline--success approveBtn"
                                                        data-action="{{ Status::SUBORDER_PROCESSING }}" data-id='{{ $item->id }}'
                                                        title="@lang('Mark as Processing')">
                                                        <i class="la la-check"></i>@lang('Confirm')
                                                    </button>
                                                @elseif ($item->status == Status::SUBORDER_PROCESSING)
                                                    <button type="button" class="btn btn-sm btn-outline--info approveBtn"
                                                        data-action="{{ Status::SUBORDER_READY_TO_PICKUP }}" data-id='{{ $item->id }}'
                                                        title="@lang('Mark as Ready')">
                                                        <i class="la la-archive"></i>@lang('Packed')
                                                    </button>
                                                @elseif ($item->status == Status::SUBORDER_READY_TO_PICKUP)
                                                    <button type="button" class="btn btn-sm btn-outline--warning approveBtn"
                                                        data-action="{{ Status::SUBORDER_DISPATCHED }}" data-id='{{ $item->id }}'
                                                        title="@lang('Mark as Shipped')">
                                                        <i class="la la-truck"></i>@lang('Dispatch')
                                                    </button>
                                                @elseif ($item->status == Status::SUBORDER_DISPATCHED)
                                                    <button type="button" class="btn btn-sm btn-outline--success approveBtn"
                                                        data-action="{{ Status::SUBORDER_DELIVERED }}" data-id='{{ $item->id }}'
                                                        title="@lang('Mark as Delivered')">
                                                        <i class="la la-check-circle"></i>@lang('Deliver')
                                                    </button>
                                                @elseif ($item->status == Status::SUBORDER_DELIVERED)
                                                    <button type="button" class="btn btn-sm btn--success approveBtn"
                                                        data-action="{{ Status::SUBORDER_COMPLETED }}" data-id='{{ $item->id }}'
                                                        title="@lang('Xác nhận thành công & Quyết toán')">
                                                        <i class="la la-check-double"></i>@lang('Quyết toán')
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline--danger approveBtn"
                                                        data-action="{{ Status::SUBORDER_DISPUTED }}" data-id='{{ $item->id }}'
                                                        title="@lang('Ghi nhận khiếu nại')">
                                                        <i class="la la-info-circle"></i>@lang('Khiếu nại')
                                                    </button>
                                                @endif
                                                
                                                @if (in_array($item->status, [Status::SUBORDER_PENDING, Status::SUBORDER_DISPUTED]))
                                                    <button type="button" class="btn btn-sm btn-outline--danger approveBtn"
                                                        data-action="{{ Status::SUBORDER_REJECTED }}" data-id="{{ $item->id }}"
                                                        title="@lang('Từ chối đơn hàng')">
                                                        <i class="la la-ban"></i>@if($item->status == Status::SUBORDER_DISPUTED) @lang('Hủy đơn') @else @lang('Reject') @endif
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($orders->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($orders) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- STATUS MODAL --}}
    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.order.status') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="oid">
                    <input type="hidden" name="action" id="action">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Confirmation Alert')</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="question"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <div class="d-flex flex-wrap justify-content-end gap-2 align-items-center">
        <form action="" method="GET" class="d-flex flex-wrap gap-2" id="filterForm">
            <div class="input-group w-auto flex-fill">
                <select name="status" class="form-control bg--white" onchange="this.form.submit()">
                    <option value="">@lang('Trạng thái')</option>
                    <option value="pending" @selected(request()->status == 'pending')>@lang('Pending')</option>
                    <option value="processing" @selected(request()->status == 'processing')>@lang('Processing')</option>
                    <option value="ready_to_pickup" @selected(request()->status == 'ready_to_pickup')>@lang('Ready to Pickup')</option>
                    <option value="dispatched" @selected(request()->status == 'dispatched')>@lang('Dispatched')</option>
                    <option value="delivered" @selected(request()->status == 'delivered')>@lang('Delivered')</option>
                    <option value="completed" @selected(request()->status == 'completed')>@lang('Settled')</option>
                    <option value="disputed" @selected(request()->status == 'disputed')>@lang('Disputed')</option>
                    <option value="canceled" @selected(request()->status == 'canceled')>@lang('Canceled')</option>
                </select>
            </div>
            
            <x-search-date-field />
            
            <div class="input-group w-auto flex-fill">
                <input type="text" name="search" class="form-control bg--white" placeholder="@lang('Order ID / Customer')" value="{{ request()->search }}">
                <button class="btn btn--primary" type="submit"><i class="la la-search"></i></button>
                @if(request()->anyFilled(['status', 'date', 'search']))
                    <a href="{{ url()->current() }}" class="btn btn--danger"><i class="las la-sync-alt"></i></a>
                @endif
            </div>
        </form>
        
        <a href="{{ route($exportRoute ?? 'admin.order.export', request()->all()) }}" class="btn btn--success"><i class="las la-file-excel"></i> @lang('Export')</a>
    </div>
@endpush

@push('script')
    <script>
        'use strict';
        (function ($) {
            $('.approveBtn').on('click', function () {
                var modal = $('#approveModal');
                modal.find('#oid').val($(this).data('id'));
                var action = $(this).data('action');
                modal.find('#action').val(action);

                var message = '';
                if (action == @json(Status::SUBORDER_PROCESSING)) {
                    message = "@lang('Bạn có chắc chắn muốn xác nhận và bắt đầu đóng gói đơn hàng này?') text-primary";
                } else if (action == @json(Status::SUBORDER_READY_TO_PICKUP)) {
                    message = "@lang('Đơn hàng này đã được đóng gói xong và sẵn sàng để gửi đi?') text-info";
                } else if (action == @json(Status::SUBORDER_DISPATCHED)) {
                    message = "@lang('Bạn có chắc chắn muốn đánh dấu đơn hàng này đã bắt đầu gửi cho khách?') text-warning";
                } else if (action == @json(Status::SUBORDER_DELIVERED)) {
                    message = "@lang('Bạn có chắc chắn đơn hàng này đã được giao đến tận tay khách hàng?') text-success";
                } else if (action == @json(Status::SUBORDER_COMPLETED)) {
                    message = "@lang('Bạn xác nhận khách hàng đã hài lòng và muốn quyết toán thanh toán cho đơn hàng này?') text-success";
                } else if (action == @json(Status::SUBORDER_DISPUTED)) {
                    message = "@lang('Bạn muốn đánh dấu đơn hàng này có khiếu nại để tạm dừng quy trình quyết toán?') text-danger";
                } else if (action == @json(Status::SUBORDER_REJECTED)) {
                    message = "@lang('Bạn có chắc chắn muốn từ chối đơn hàng này?') text-danger";
                }
                
                modal.find('.question').html(message);
                modal.modal('show');
            });
        })(jQuery)
    </script>
@endpush