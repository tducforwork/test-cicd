@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="breadcrumb-section"
    style="background-color: #f8f9fa; padding: 20px 0; border-bottom: 1px solid #eaeaea;">
    <div class="container">
        <a href="{{ route('home') }}" style="color: #666; font-size: 14px">@lang('Trang chủ')</a>
        <span style="margin: 0 10px; color: #ccc">/</span>
        <span style="color: var(--primary); font-weight: 600; font-size: 14px">@lang('Dashboard Người bán')</span>
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
                <div class="content-header d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">@lang('Tổng quan kinh doanh')</h2>
                        <p style="color: var(--text-muted); font-size: 14px;">@lang('Theo dõi hiệu quả bán hàng của bạn')</p>
                    </div>
                     <button class="btn-excel">
                            <i class="fa-solid fa-file-export" style="margin-right: 8px;"></i> XUẤT BÁO CÁO EXCEL
                        </button>
                </div>

                <!-- Stats Grid -->
                <div class="row g-4 mt-2">
                    <div class="col-md-3">
                        <div class="stat-card premium-card">
                            <div class="stat-icon icon-blue">
                                <i class="fa-solid fa-sack-dollar"></i>
                            </div>
                            <div class="stat-value">{{ showAmount($sale['last_thirty_days']) }}</div>
                            <div class="stat-label">@lang('Doanh thu tháng này')</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card premium-card">
                            <div class="stat-icon icon-orange">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </div>
                            <div class="stat-value">{{ $order['pending'] }}</div>
                            <div class="stat-label">@lang('Đơn hàng mới')</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card premium-card">
                            <div class="stat-icon icon-green">
                                <i class="fa-solid fa-box-archive"></i>
                            </div>
                            <div class="stat-value">{{ $product['approved'] }}</div>
                            <div class="stat-label">@lang('Sản phẩm đang bán')</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card premium-card">
                            <div class="stat-icon icon-red">
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <div class="stat-value">{{ number_format(seller()->reviews()->avg('rating') ?? 5.0, 1) }} / 5</div>
                            <div class="stat-label">@lang('Đánh giá trung bình')</div>
                        </div>
                    </div>
                </div>

                <!-- Revenue Chart -->
                @php
                    $months = [];
                    $salesData = [];
                    for ($i = 5; $i >= 0; $i--) {
                        $monthDate = \Carbon\Carbon::today()->subMonths($i);
                        $months[] = $monthDate->translatedFormat('M Y');
                        
                        // Sum of sales in this month for this seller
                        $monthlySum = App\Models\SellLog::where('seller_id', seller()->id)
                            ->whereMonth('created_at', $monthDate->month)
                            ->whereYear('created_at', $monthDate->year)
                            ->sum('product_price');
                        $salesData[] = floatval($monthlySum);
                    }
                @endphp
                <div class="chart-container premium-card mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 style="font-size: 18px;">@lang('Biểu đồ doanh thu 6 tháng gần nhất')</h4>
                    </div>
                    <canvas id="revenueChart" height="100"></canvas>
                </div>

                <!-- Recent Orders -->
                <div class="recent-order-card premium-card mt-4">
                    <h4 style="font-size: 18px; margin-bottom: 20px;">@lang('Đơn hàng mới nhất')</h4>
                    <div class="order-table-wrapper">
                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th>@lang('Mã đơn')</th>
                                    <th>@lang('Khách hàng')</th>
                                    <th>@lang('Sản phẩm')</th>
                                    <th>@lang('Tổng tiền')</th>
                                    <th>@lang('Trạng thái')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestOrders->take(10) as $item)
                                    @php
                                        $firstDetail = $item->orderDetail->first();
                                    @endphp
                                    <tr>
                                        <td>
                                            <a href="{{ route('seller.order.details', $item->id) }}" style="font-weight: 700; color: var(--primary);">
                                                #{{ $item->order_number }}
                                            </a>
                                        </td>
                                        <td class="customer-name">{{ __($item->order->user->fullname ?? $item->order->user->username) }}</td>
                                        <td>
                                            {{ $firstDetail && $firstDetail->product ? __($firstDetail->product->name) : 'N/A' }}
                                            @if($item->orderDetail->count() > 1)
                                                <span class="text-muted" style="font-size: 12px;">+ {{ $item->orderDetail->count() - 1 }} @lang('sản phẩm khác')</span>
                                            @endif
                                        </td>
                                        <td style="font-weight: 700;">{{ showAmount($item->total_amount) }}</td>
                                        <td>{!! $item->statusBadge !!}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">@lang('Chưa có đơn hàng mới nào.')</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('seller.order.all') }}"
                            style="font-size: 13px; color: var(--accent); font-weight: 700;">
                            @lang('Xem tất cả đơn hàng') <i class="fa-solid fa-arrow-right" style="margin-left: 5px;"></i>
                        </a>
                    </div>
                </div>
            </main>
        </div>
    </div>
</section>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const salesData = @json($salesData);
        const monthsLabels = @json($months);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthsLabels,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: salesData,
                    borderColor: '#ff6f0f',
                    backgroundColor: 'rgba(255, 111, 15, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#ff6f0f',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            drawBorder: false,
                            color: '#f1f5f9'
                        },
                        ticks: {
                            callback: function (value) {
                                return value.toLocaleString() + 'đ';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
