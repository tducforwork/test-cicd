@extends($activeTemplate . 'layouts.frontend')
@section('content')
<div class="bg-[#F7F7F7]">
    <main class="container mx-auto pb-32 pt-10">
        <div class="flex flex-col lg:flex-row items-start gap-6">
            <!-- Sidebar -->
            <aside class="w-full lg:w-[312px] shrink-0">
                @include($activeTemplate . 'user.partials.user_sidebar')
            </aside>

            <!-- Main Content -->
            <main class="profile-main-content flex-1 min-w-0">
                <div class="content-header">
                    <h2>@lang('Lịch sử đơn hàng')</h2>
                    <p style="color: var(--text-muted); font-size: 14px;">@lang('Theo dõi và quản lý các đơn hàng bạn đã đặt')</p>
                </div>

                <div class="order-table-wrapper">
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>@lang('Mã đơn hàng')</th>
                                <th>@lang('Ngày đặt')</th>
                                <th>@lang('Tổng tiền')</th>
                                <th>@lang('Trạng thái')</th>
                                <th>@lang('Thao tác')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                            <tr>
                                <td><strong>#{{ $order->order_number }}</strong></td>
                                <td>{{ showDateTime($order->created_at, 'd/m/Y') }}</td>
                                <td><strong class="text-[#FF6F0F]">{{ showAmount($order->total_amount) }}</strong></td>
                                <td>
                                    @php
                                        $statusClass = 'badge-info';
                                        $statusName = 'Đang xử lý';

                                        if ($order->computed_status == \App\Constants\Status::ORDER_DELIVERED) {
                                            $statusClass = 'badge-success';
                                            $statusName = 'Đã giao hàng';
                                        } elseif ($order->computed_status == \App\Constants\Status::ORDER_CANCELLED) {
                                            $statusClass = 'badge-danger';
                                            $statusName = 'Đã hủy';
                                        } elseif ($order->computed_status == \App\Constants\Status::ORDER_DISPATCHED || $order->computed_status == \App\Constants\Status::ORDER_SHIPPED) {
                                            $statusClass = 'badge-warning';
                                            $statusName = 'Đang giao hàng';
                                        }
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ __($statusName) }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('user.order.details', $order->order_number) }}" class="btn-view-detail">@lang('Xem chi tiết')</a>
                                        @if($order->computed_status == \App\Constants\Status::ORDER_PEND_SHIPPING || $order->computed_status == \App\Constants\Status::ORDER_PENDING)
                                            @if($order->payment_status != \App\Constants\Status::PAYMENT_SUCCESS)
                                                <span style="margin: 0 5px; color: #ccc">|</span>
                                                <form action="{{ route('user.order.cancel', $order->order_number) }}" method="POST" class="d-inline" onsubmit="return confirm('@lang('Bạn có chắc chắn muốn hủy đơn hàng này không?')')">
                                                    @csrf
                                                    <button type="submit" class="btn-qp-outline-danger" style="padding: 4px 12px; font-size: 12px; border: none; background: none;">
                                                        @lang('Hủy đơn')
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-gray-400">@lang('Không tìm thấy đơn hàng nào')</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="mt-6">
                        {{ paginateLinks($orders) }}
                    </div>
                @endif
            </main>
        </div>
    </main>
</div>
@endsection
