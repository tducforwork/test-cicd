@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="breadcrumb-section"
    style="background-color: #f8f9fa; padding: 20px 0; border-bottom: 1px solid #eaeaea;">
    <div class="container">
        <a href="{{ route('home') }}" style="color: #666; font-size: 14px">@lang('Trang chủ')</a>
        <span style="margin: 0 10px; color: #ccc">/</span>
        <a href="{{ route('seller.home') }}" style="color: #666; font-size: 14px">@lang('Kênh người bán')</a>
        <span style="margin: 0 10px; color: #ccc">/</span>
        <span style="color: var(--primary); font-weight: 600; font-size: 14px">@lang('Lịch sử thanh toán')</span>
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
                <div class="content-header d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <div>
                        <h2>@lang('Lịch sử thanh toán')</h2>
                        <p style="color: var(--text-muted); font-size: 14px; margin: 0;">@lang('Quản lý các giao dịch rút tiền và đối soát từ hệ thống')</p>
                    </div>
                </div>

                <!-- Filters -->
                <div class="order-filter-card mb-4 premium-card" style="padding: 20px;">
                    <form action="" method="GET">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label mb-1" style="font-size: 12px; font-weight: 600; color: #475569;">@lang('Mã giao dịch')</label>
                                <div class="search-box-custom" style="max-width: 100%;">
                                    <i class="fa-solid fa-magnifying-glass" style="color: #94a3b8;"></i>
                                    <input type="text" name="search" value="{{ request()->search }}" placeholder="@lang('Tìm kiếm mã giao dịch hoặc phương thức...')">
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100" style="height: 45px; background: var(--primary); border: none; font-weight: 700;">@lang('LỌC DỮ LIỆU')</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- History Table -->
                <div class="product-list-card premium-card">
                    <h4 style="font-size: 16px; font-weight: 700; color: var(--primary); margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">
                        <i class="fa-solid fa-clock-rotate-left" style="margin-right: 8px;"></i> @lang('Các giao dịch gần đây')
                    </h4>
                    <div class="order-table-wrapper">
                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th>@lang('Cổng thanh toán | Mã GD')</th>
                                    <th>@lang('Thời gian khởi tạo')</th>
                                    <th>@lang('Số tiền yêu cầu')</th>
                                    <th>@lang('Quy đổi')</th>
                                    <th>@lang('Trạng thái')</th>
                                    <th style="text-align: right; width: 100px;">@lang('Chi tiết')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($withdraws as $withdraw)
                                    @php
                                        $details = [];
                                        if ($withdraw->withdraw_information) {
                                            foreach ($withdraw->withdraw_information as $key => $info) {
                                                $details[] = $info;
                                                if ($info->type == 'file') {
                                                    $details[$key]->value = route('user.download.attachment', encrypt(getFilePath('verify') . '/' . $info->value));
                                                }
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ __(@$withdraw->method->name) }}</strong>
                                            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">{{ $withdraw->trx }}</div>
                                        </td>
                                        <td>
                                            {{ showDateTime($withdraw->created_at, 'd/m/Y H:i') }}
                                            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">{{ diffForHumans($withdraw->created_at) }}</div>
                                        </td>
                                        <td>
                                            <span style="font-weight: 700; color: var(--primary);">{{ showAmount($withdraw->amount) }}</span>
                                            <div style="font-size: 11px; color: #dc2626; margin-top: 2px;">- {{ showAmount($withdraw->charge) }} @lang('phí')</div>
                                        </td>
                                        <td>
                                            1 = {{ showAmount($withdraw->rate, currencyFormat: false) }} {{ __($withdraw->currency) }}
                                            <div style="font-size: 12px; font-weight: 700; color: var(--accent); margin-top: 2px;">
                                                {{ showAmount($withdraw->final_amount, currencyFormat: false) }} {{ __($withdraw->currency) }}
                                            </div>
                                        </td>
                                        <td>
                                            @php echo $withdraw->statusBadge @endphp
                                        </td>
                                        <td style="text-align: right;">
                                            <button type="button" class="btn btn-sm btn-white detailBtn" style="border: 1px solid var(--border); font-weight: 600; font-size: 12px;"
                                                data-user_data="{{ json_encode($details) }}" 
                                                @if ($withdraw->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $withdraw->admin_feedback }}" @endif>
                                                <i class="fa-solid fa-circle-info" style="margin-right: 4px;"></i> @lang('Xem')
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="fa-solid fa-wallet" style="font-size: 40px; color: #cbd5e1; display: block; margin-bottom: 10px;"></i>
                                            @lang('Không tìm thấy lịch sử thanh toán nào.')
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($withdraws->hasPages())
                        <div class="pagination-container d-flex justify-content-center align-items-center px-4 py-3">
                            {{ paginateLinks($withdraws) }}
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
</section>

{{-- DETAIL MODAL --}}
<div id="detailModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background: var(--primary); color: white; border: none; padding: 18px 24px;">
                <h5 class="modal-title text-white" style="font-weight: 700;">@lang('Thông tin chi tiết giao dịch')</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 24px;">
                <ul class="list-group list-group-flush userData" style="margin-bottom: 0;"></ul>
                <div class="feedback" style="border-top: 1px dashed var(--border); margin-top: 15px; padding-top: 15px;"></div>
            </div>
            <div class="modal-footer border-0 pb-4 justify-content-center">
                <button type="button" class="btn btn-primary" style="background: var(--primary); border: none; padding: 10px 30px; font-weight: 700; border-radius: 8px;" data-bs-dismiss="modal">@lang('Đóng')</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    (function($) {
        "use strict";
        $('.detailBtn').on('click', function() {
            var modal = $('#detailModal');
            var userData = $(this).data('user_data');
            var html = ``;
            if (userData && userData.length > 0) {
                userData.forEach(element => {
                    if (element.type != 'file') {
                        html += `
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0" style="border-bottom: 1px solid #f1f5f9; font-size: 14px; padding: 10px 0;">
                                <span style="font-weight: 600; color: #64748b;">${element.name}</span>
                                <span style="font-weight: 700; color: var(--primary);">${element.value}</span>
                            </li>`;
                    } else {
                        html += `
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0" style="border-bottom: 1px solid #f1f5f9; font-size: 14px; padding: 10px 0;">
                                <span style="font-weight: 600; color: #64748b;">${element.name}</span>
                                <span><a href="${element.value}" class="text-primary font-bold" target="_blank"><i class="fa-regular fa-file-image"></i> @lang('Xem tài liệu đính kèm')</a></span>
                            </li>`;
                    }
                });
            } else {
                html = `<div class="text-center text-muted">@lang('Không có dữ liệu bổ sung')</div>`;
            }
            modal.find('.userData').html(html);

            if ($(this).data('admin_feedback') != undefined) {
                var adminFeedback = `
                    <div style="background: #fef2f2; border: 1px solid #fca5a5; padding: 12px; border-radius: 8px;">
                        <strong style="color: #991b1b; font-size: 13px; display: block; margin-bottom: 4px;"><i class="fa-solid fa-circle-exclamation"></i> Phản hồi từ Admin:</strong>
                        <p style="margin: 0; font-size: 13px; color: #7f1d1d;">${$(this).data('admin_feedback')}</p>
                    </div>
                `;
                modal.find('.feedback').html(adminFeedback).show();
            } else {
                modal.find('.feedback').hide();
            }

            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush

@push('style')
<style>
    .badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        border: 1px solid transparent;
    }
    .badge--success {
        background: #d1fae5;
        color: #065f46;
        border-color: #a7f3d0;
    }
    .badge--warning {
        background: #fef3c7;
        color: #92400e;
        border-color: #fcd34d;
    }
    .badge--danger {
        background: #fee2e2;
        color: #991b1b;
        border-color: #fca5a5;
    }
</style>
@endpush