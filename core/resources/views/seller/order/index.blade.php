@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="breadcrumb-section"
    style="background-color: #f8f9fa; padding: 20px 0; border-bottom: 1px solid #eaeaea;">
    <div class="container">
        <a href="{{ route('home') }}" style="color: #666; font-size: 14px">@lang('Trang chủ')</a>
        <span style="margin: 0 10px; color: #ccc">/</span>
        <a href="{{ route('seller.home') }}" style="color: #666; font-size: 14px">@lang('Kênh người bán')</a>
        <span style="margin: 0 10px; color: #ccc">/</span>
        <span style="color: var(--primary); font-weight: 600; font-size: 14px">@lang('Quản lý đơn hàng')</span>
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
                <div class="content-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h2>@lang('Đơn hàng đã nhận')</h2>
                        <p style="color: var(--text-muted); font-size: 14px;">@lang('Quản lý và xử lý các đơn hàng từ khách hàng')</p>
                    </div>
                </div>

                <!-- Stats Grid (Premium style) -->
                <div class="row g-3 mt-2 mb-4">
                    <div class="col-lg-2-5 col-md-4 col-6">
                        <div class="premium-card p-3 d-flex align-items-center gap-3" style="background: #f8fafc; border: 1px solid var(--border);">
                            <div class="flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; border-radius: 12px; background: #fef3c7; color: #d97706;">
                                <i class="fa-solid fa-clock-rotate-left" style="font-size: 20px;"></i>
                            </div>
                            <div>
                                <span style="font-size: 11px; font-weight: 600; color: #64748b; display: block; text-transform: uppercase;">@lang('Chờ xử lý')</span>
                                <strong style="font-size: 20px; color: var(--primary);">{{ $stats['pending'] }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2-5 col-md-4 col-6">
                        <div class="premium-card p-3 d-flex align-items-center gap-3" style="background: #f8fafc; border: 1px solid var(--border);">
                            <div class="flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; border-radius: 12px; background: #dbeafe; color: #2563eb;">
                                <i class="fa-solid fa-truck" style="font-size: 20px;"></i>
                            </div>
                            <div>
                                <span style="font-size: 11px; font-weight: 600; color: #64748b; display: block; text-transform: uppercase;">@lang('Đang giao')</span>
                                <strong style="font-size: 20px; color: var(--primary);">{{ $stats['processing'] }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2-5 col-md-4 col-6">
                        <div class="premium-card p-3 d-flex align-items-center gap-3" style="background: #f8fafc; border: 1px solid var(--border);">
                            <div class="flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; border-radius: 12px; background: #e0f2fe; color: #0284c7;">
                                <i class="fa-solid fa-boxes-packing" style="font-size: 20px;"></i>
                            </div>
                            <div>
                                <span style="font-size: 11px; font-weight: 600; color: #64748b; display: block; text-transform: uppercase;">@lang('Chờ lấy hàng')</span>
                                <strong style="font-size: 20px; color: var(--primary);">{{ $stats['readyToPickup'] }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2-5 col-md-4 col-6">
                        <div class="premium-card p-3 d-flex align-items-center gap-3" style="background: #f8fafc; border: 1px solid var(--border);">
                            <div class="flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; border-radius: 12px; background: #dcfce7; color: #16a34a;">
                                <i class="fa-solid fa-circle-check" style="font-size: 20px;"></i>
                            </div>
                            <div>
                                <span style="font-size: 11px; font-weight: 600; color: #64748b; display: block; text-transform: uppercase;">@lang('Hoàn thành')</span>
                                <strong style="font-size: 20px; color: var(--primary);">{{ $stats['delivered'] }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2-5 col-md-4 col-6">
                        <div class="premium-card p-3 d-flex align-items-center gap-3" style="background: #f8fafc; border: 1px solid var(--border);">
                            <div class="flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; border-radius: 12px; background: #fee2e2; color: #dc2626;">
                                <i class="fa-solid fa-circle-xmark" style="font-size: 20px;"></i>
                            </div>
                            <div>
                                <span style="font-size: 11px; font-weight: 600; color: #64748b; display: block; text-transform: uppercase;">@lang('Đã hủy')</span>
                                <strong style="font-size: 20px; color: var(--primary);">{{ $stats['rejected'] }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="order-filter-card premium-card mb-4" style="padding: 20px;">
                    <form action="" method="GET">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label mb-1" style="font-size: 12px; font-weight: 600; color: #475569;">@lang('Tìm kiếm đơn hàng')</label>
                                <div class="search-box-custom" style="max-width: 100%;">
                                    <i class="fa-solid fa-magnifying-glass" style="color: #94a3b8;"></i>
                                    <input type="text" name="search" value="{{ request()->search }}" placeholder="@lang('Tìm mã đơn, tên khách hàng...')">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label mb-1" style="font-size: 12px; font-weight: 600; color: #475569;">@lang('Trạng thái đơn hàng')</label>
                                <select class="form-select" name="status" style="border-radius: 10px; height: 45px; font-size: 14px;">
                                    <option value="" selected>@lang('Tất cả trạng thái')</option>
                                    <option value="pending" @selected(request()->status == 'pending')>@lang('Chờ xử lý')</option>
                                    <option value="processing" @selected(request()->status == 'processing')>@lang('Đang giao hàng')</option>
                                    <option value="readyToPickup" @selected(request()->status == 'readyToPickup')>@lang('Đang chuẩn bị hàng')</option>
                                    <option value="delivered" @selected(request()->status == 'delivered')>@lang('Đã hoàn thành')</option>
                                    <option value="rejected" @selected(request()->status == 'rejected')>@lang('Đã hủy đơn')</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100" style="height: 45px; background: var(--primary); border: none; font-weight: 700;">@lang('Lọc dữ liệu')</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Order Table -->
                <div class="product-list-card premium-card">
                    <div class="order-table-wrapper">
                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th>@lang('Mã đơn')</th>
                                    <th>@lang('Khách hàng')</th>
                                    <th>@lang('Ngày đặt')</th>
                                    <th>@lang('Tổng tiền')</th>
                                    <th>@lang('Trạng thái')</th>
                                    <th style="text-align: right; width: 100px;">@lang('Thao tác')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $item)
                                    <tr>
                                        <td><strong>#{{ $item->order_number }}</strong></td>
                                        <td>
                                            <div class="customer-info" style="display: flex; align-items: center; gap: 10px;">
                                                <div style="width: 35px; height: 35px; border-radius: 50%; background: #eee; display: flex; align-items: center; justify-content: center; overflow: hidden; font-weight: bold; color: #64748b; font-size: 13px;">
                                                    {{ strtoupper(substr($item->order?->user?->fullname ?? 'K', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="customer-name" style="font-weight: 600; color: var(--primary);">{{ @$item->order?->user?->fullname }}</div>
                                                    <div class="customer-phone" style="font-size: 11px; color: var(--text-muted);">{{ @$item->order?->user?->mobile }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ showDateTime($item->created_at, 'd/m/Y') }}</td>
                                        <td style="font-weight: 700; color: var(--accent);">{{ showAmount($item->total_amount) }}</td>
                                        <td>@php echo $item->statusBadge @endphp</td>
                                        <td style="text-align: right;">
                                            <a href="{{ route('seller.order.details', $item->id) }}" class="btn btn-sm btn-white" style="border: 1px solid var(--border); font-weight: 600; font-size: 12px; padding: 5px 12px;">
                                                <i class="fa-solid fa-eye" style="margin-right: 4px;"></i> @lang('Chi tiết')
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="fa-regular fa-clipboard" style="font-size: 40px; color: #cbd5e1; display: block; margin-bottom: 10px;"></i>
                                            @lang('Không tìm thấy đơn hàng nào.')
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($orders->hasPages())
                        <div class="pagination-container d-flex justify-content-center align-items-center px-4 py-3">
                            {{ paginateLinks($orders) }}
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
</section>
@endsection

@push('style')
<style>
    .col-lg-2-5 {
        flex: 0 0 20%;
        max-width: 20%;
    }
    @media (max-width: 991px) {
        .col-lg-2-5 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
    }
    @media (max-width: 575px) {
        .col-lg-2-5 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }
</style>
@endpush
