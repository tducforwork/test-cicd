@php
    $user = auth()->user();
@endphp
<aside class="profile-sidebar">
    <div class="sidebar-user-info">
        <img src="{{ getAvatar(getFilePath('userProfile') . '/' . $user->image, $user->fullname ?? $user->username) }}"
            alt="Avatar" class="sidebar-avatar">
        <div style="text-align: center; margin-top: 10px;">
            <h3 style="margin-bottom: 5px;">{{ __($user->fullname) }}</h3>
            @if($user->is_seller && $user->seller_active)
                <span style="font-size: 13px; color: var(--accent); font-weight: 700; display: block;">{{ __($user->shop->name ?? 'Official Store') }}</span>
            @endif
            <span class="user-status-active">@lang('Đang hoạt động')</span>
        </div>
    </div>
    <nav class="sidebar-menu">
        <a href="{{ route('user.profile.setting') }}" class="sidebar-link {{ request()->routeIs('user.profile.setting') ? 'active' : '' }}">
            <i class="fa-regular fa-user"></i> @lang('Thông tin cá nhân')
        </a>
        <a href="{{ route('user.orders', 'all') }}" class="sidebar-link {{ request()->routeIs('user.orders') || request()->routeIs('user.order.details') ? 'active' : '' }}">
            <i class="fa-solid fa-box"></i> @lang('Lịch sử đơn hàng')
        </a>
        <a href="{{ route('user.change.password') }}" class="sidebar-link {{ request()->routeIs('user.change.password') ? 'active' : '' }}">
            <i class="fa-solid fa-lock"></i> @lang('Đổi mật khẩu')
        </a>
    </nav>
    <div class="sidebar-footer"
        style="padding: 20px; border-top: 1px solid #eee; display: flex; flex-direction: column; gap: 10px;">
        @if(!$user->is_seller)
            <a href="javascript:void(0)" onclick="showSellerModal()" class="btn btn-reg-seller w-100"
                style="font-weight: 700; font-size: 12px;">
                <i class="fa-solid fa-store" style="margin-right: 8px;"></i> @lang('TRỞ THÀNH NGƯỜI BÁN')
            </a>
        @elseif($user->is_seller && !$user->seller_active)
            <button class="btn btn-warning w-100"
                style="padding: 10px; font-weight: 700; font-size: 12px; display: flex; align-items: center; justify-content: center; gap: 8px; background: #FF9F43 !important; color: white !important; border: none; cursor: not-allowed;" disabled>
                <i class="fa-solid fa-hourglass-half"></i> @lang('ĐANG CHỜ DUYỆT')
            </button>
        @else
            <a href="{{ route('seller.home') }}" class="btn btn-dark w-100"
                style="padding: 10px; font-weight: 700; font-size: 12px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <i class="fa-solid fa-chart-line"></i> @lang('QUẢN LÝ SHOP')
            </a>
        @endif
    </div>
</aside>

@if(!$user->is_seller)
<!-- CUSTOM MODAL: SELLER REGISTRATION -->
<div class="qp-modal-overlay" id="sellerModal">
    <div class="qp-modal-container">
        <div class="qp-modal-header">
            <h3 class="qp-modal-title">@lang('Đăng Ký Trở Thành Người Bán')</h3>
            <div class="qp-modal-close" onclick="closeSellerModal()">
                <i class="fa-solid fa-xmark"></i>
            </div>
        </div>
        <form id="sellerRegistrationForm" action="{{ route('user.become.seller') }}" method="POST" class="ajax-seller-form">
            @csrf
            <div class="qp-modal-body" style="padding: 25px 30px; max-height: 70vh; overflow-y: auto;">
                <div class="mb-4">
                    <label class="form-label" style="font-weight: 700; font-size: 12px; text-transform: uppercase; color: #64748b; margin-bottom: 8px; display: block;">@lang('Địa chỉ kinh doanh') <span class="text-red-500">*</span></label>
                    <input type="text" name="address_seller" class="form-control" style="padding: 12px 15px;" placeholder="@lang('Nhập địa chỉ kinh doanh')" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label" style="font-weight: 700; font-size: 12px; text-transform: uppercase; color: #64748b; margin-bottom: 8px; display: block;">@lang('Số CCCD / Định danh') <span class="text-red-500">*</span></label>
                        <input type="text" name="id_card" class="form-control" style="padding: 12px 15px;" placeholder="@lang('Số căn cước công dân')" required>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label" style="font-weight: 700; font-size: 12px; text-transform: uppercase; color: #64748b; margin-bottom: 8px; display: block;">@lang('Tên ngân hàng') <span class="text-red-500">*</span></label>
                        <input type="text" name="bank_name" class="form-control" style="padding: 12px 15px;" placeholder="@lang('Ví dụ: Vietcombank')" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label" style="font-weight: 700; font-size: 12px; text-transform: uppercase; color: #64748b; margin-bottom: 8px; display: block;">@lang('Số tài khoản ngân hàng') <span class="text-red-500">*</span></label>
                        <input type="text" name="bank_account_number" class="form-control" style="padding: 12px 15px;" placeholder="@lang('Số tài khoản')" required>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label" style="font-weight: 700; font-size: 12px; text-transform: uppercase; color: #64748b; margin-bottom: 8px; display: block;">@lang('Chi nhánh ngân hàng') <span class="text-red-500">*</span></label>
                        <input type="text" name="bank_branch" class="form-control" style="padding: 12px 15px;" placeholder="@lang('Chi nhánh ngân hàng')" required>
                    </div>
                </div>
            </div>
            <div class="qp-modal-footer">
                <button type="button" class="btn btn-light" style="font-weight: 600; color: #64748b; border: 1px solid #e2e8f0;" onclick="closeSellerModal()">@lang('Hủy bỏ')</button>
                <button type="submit" class="btn btn-primary" style="background: var(--primary); border: none; padding: 10px 25px; font-weight: 700;">@lang('ĐĂNG KÝ NGAY')</button>
            </div>
        </form>
    </div>
</div>
@endif

@push('script')
<script>
    function showSellerModal() {
        $('#sellerModal').addClass('active');
    }

    function closeSellerModal() {
        $('#sellerModal').removeClass('active');
        $('#sellerRegistrationForm').trigger('reset');
    }

    (function($) {
        'use strict';

        // Validating modal form
        $('#sellerRegistrationForm').validate({
            rules: {
                address_seller: "required",
                id_card: "required",
                bank_name: "required",
                bank_account_number: "required",
                bank_branch: "required",
            },
            messages: {
                address_seller: "Vui lòng nhập địa chỉ kinh doanh",
                id_card: "Vui lòng nhập số CCCD",
                bank_name: "Vui lòng nhập tên ngân hàng",
                bank_account_number: "Vui lòng nhập số tài khoản",
                bank_branch: "Vui lòng nhập chi nhánh ngân hàng",
            },
            errorElement: 'label',
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            }
        });

        // Submit AJAX Become Seller form
        $('#sellerRegistrationForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);

            if (!form.valid()) return false;

            var btn = form.find('button[type=submit]');
            var btnText = btn.html();
            var formData = new FormData(this);

            btn.prop('disabled', true).html('<i class="las la-spinner la-spin"></i> Đang đăng ký...');

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status == 'success') {
                        notify('success', response.message);
                        closeSellerModal();
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    } else {
                        notify('error', response.message);
                    }
                },
                error: function(xhr) {
                    var response = xhr.responseJSON;
                    if (xhr.status == 422 && response && response.errors) {
                        $.each(response.errors, function(key, value) {
                            notify('error', value[0]);
                        });
                    } else if (response && response.message) {
                        notify('error', response.message);
                    } else {
                        notify('error', 'Có lỗi xảy ra. Vui lòng thử lại sau.');
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).html(btnText);
                }
            });
        });
    })(jQuery)
</script>
@endpush
