@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <div class="breadcrumb-section" style="background-color: #f8f9fa; padding: 20px 0; border-bottom: 1px solid #eaeaea;">
        <div class="container">
            <a href="{{ route('home') }}" style="color: #666; font-size: 14px">@lang('Trang chủ')</a>
            <span style="margin: 0 10px; color: #ccc">/</span>
            <a href="{{ route('seller.home') }}" style="color: #666; font-size: 14px">@lang('Kênh người bán')</a>
            <span style="margin: 0 10px; color: #ccc">/</span>
            <span style="color: var(--primary); font-weight: 600; font-size: 14px">@lang('Cấu hình Gian hàng')</span>
        </div>
    </div>

    <!-- PROFILE SECTION -->
    <section class="profile-section py-lg-5 py-4">
        <div class="container">
            <div class="profile-container">
                <!-- Sidebar -->
                <aside class="profile-sidebar">
                    @include('seller.partials.sidebar')
                </aside>

                <!-- Main Content -->
                <main class="profile-main-content">
                    <div class="content-header">
                        <h2>@lang('Cấu hình Gian hàng')</h2>
                        <p style="color: var(--text-muted); font-size: 14px;">
                            @lang('Quản lý thông tin hiển thị của shop trên sàn Quảng Phát Mall')</p>
                    </div>

                    <form action="{{ route('seller.shop') }}" method="POST" enctype="multipart/form-data" id="shopForm">
                        @csrf
                        <div class="row">
                            <!-- Left Side: Images -->
                            <div class="col-lg-4 col-md-12">
                                <div class="form-group mb-4">
                                    <label class="form-label" style="font-weight: 700;">@lang('Ảnh đại diện Shop')</label>
                                    <div
                                        style="display: flex; flex-direction: column; align-items: center; gap: 15px; border: 1px dashed var(--border); padding: 20px; border-radius: 16px; background: #f8fafc;">
                                        <div id="logoPreviewWrapper"
                                            style="width: 150px; height: 150px; border-radius: 16px; overflow: hidden; border: 4px solid white; box-shadow: 0 8px 20px rgba(0,0,0,0.1); background: #eee; display: flex; align-items: center; justify-content: center;">
                                            @if(@$shop->logo)
                                                <img id="logoPreview"
                                                    src="{{ getImage(getFilePath('sellerShopLogo') . '/' . $shop->logo, true) }}"
                                                    style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <i class="fa-solid fa-store" style="font-size: 50px; color: #cbd5e1;"></i>
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-sm btn-white"
                                            style="border: 1px solid var(--border); font-weight: 600;"
                                            onclick="document.getElementById('logoInput').click()">
                                            <i class="fa-solid fa-camera" style="margin-right: 5px;"></i>
                                            @lang('Thay đổi ảnh')
                                        </button>
                                        <input type="file" name="image" id="logoInput" style="display: none;"
                                            accept="image/*">
                                        <p style="font-size: 11px; color: var(--text-muted); text-align: center;">Tỷ lệ
                                            khuyên dùng 1:1. Tối đa 2MB.</p>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label" style="font-weight: 700;">@lang('Ảnh bìa Shop')</label>
                                    <div
                                        style="display: flex; flex-direction: column; align-items: center; gap: 15px; border: 1px dashed var(--border); padding: 20px; border-radius: 16px; background: #f8fafc;">
                                        <div id="coverPreviewWrapper"
                                            style="width: 100%; height: 120px; border-radius: 12px; overflow: hidden; border: 3px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.05); background: #eee; display: flex; align-items: center; justify-content: center;">
                                            @if(@$shop->cover)
                                                <img id="coverPreview"
                                                    src="{{ getImage(getFilePath('sellerShopCover') . '/' . $shop->cover, true) }}"
                                                    style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <i class="fa-solid fa-image" style="font-size: 40px; color: #cbd5e1;"></i>
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-sm btn-white"
                                            style="border: 1px solid var(--border); font-weight: 600;"
                                            onclick="document.getElementById('coverInput').click()">
                                            <i class="fa-solid fa-camera" style="margin-right: 5px;"></i>
                                            @lang('Thay đổi ảnh bìa')
                                        </button>
                                        <input type="file" name="cover_image" id="coverInput" style="display: none;"
                                            accept="image/*">
                                        <p style="font-size: 11px; color: var(--text-muted); text-align: center;">Tối đa
                                            2MB.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Side: Fields -->
                            <div class="col-lg-8 col-md-12">
                                <div class="form-group mb-4">
                                    <label class="form-label" style="font-weight: 700;">@lang('Tên gian hàng') <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                        placeholder="@lang('Nhập tên shop của bạn')" value="{{ old('name', @$shop->name) }}"
                                        style="font-size: 16px; font-weight: 600;" required>
                                    <p style="font-size: 12px; color: #94a3b8; margin-top: 5px;">
                                        @lang('Tên shop sẽ hiển thị trên tất cả sản phẩm của bạn.')</p>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label" style="font-weight: 700;">@lang('Mô tả gian hàng')</label>
                                    <textarea name="meta_description" class="form-control" rows="4"
                                        placeholder="@lang('Giới thiệu ngắn về shop của bạn')">{{ old('meta_description', @$shop->meta_description) }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label class="form-label"
                                                style="font-weight: 700;">@lang('Hotline chăm sóc khách hàng') <span
                                                    class="text-red-500">*</span></label>
                                            <input type="tel" name="phone" class="form-control"
                                                value="{{ old('phone', @$shop->phone) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label class="form-label"
                                                style="font-weight: 700;">@lang('Email liên hệ')</label>
                                            <input type="email" class="form-control" value="{{ $seller->email }}"  style="
          b                                     ackground: #f1f5f9;">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label class="form-label" style="font-weight: 700;">@lang('Giờ mở cửa')</label>
                                            <input type="time" name="opening_time" class="form-control"
                                                value="{{ old('opening_time', showDateTime(@$shop->opens_at, 'H:i')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label class="form-label"
                                                style="font-weight: 700;">@lang('Giờ đóng cửa')</label>
                                            <input type="time" name="closing_time" class="form-control"
                                                value="{{ old('closing_time', showDateTime(@$shop->closed_at, 'H:i')) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label" style="font-weight: 700;">@lang('Địa chỉ gian hàng') <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="address" class="form-control"
                                        placeholder="@lang('Nhập địa chỉ của shop')"
                                        value="{{ old('address', @$shop->address) }}" required>
                                </div>

                                <!-- Verification info (Readonly) -->
                                <div class="row"
                                    style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid var(--border); margin: 20px 0;">
                                    <div class="col-12 mb-3">
                                        <h5
                                            style="margin: 0; font-size: 14px; font-weight: 700; color: #475569; text-transform: uppercase;">
                                            <i class="fa-solid fa-shield-halved"
                                                style="margin-right: 6px; color: var(--accent);"></i>
                                            @lang('Thông tin định danh và thanh toán')
                                        </h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label"
                                                style="font-weight: 600; font-size: 12px; color: #64748b;">@lang('Số CCCD / MST')</label>
                                            <input type="text" class="form-control"
                                                value="{{ $seller->id_card ?? 'Chưa cung cấp' }}" readonly
                                                style="background: #e2e8f0; font-size: 13px;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label"
                                                style="font-weight: 600; font-size: 12px; color: #64748b;">@lang('Tên ngân hàng')</label>
                                            <input type="text" class="form-control"
                                                value="{{ $seller->bank_name ?? 'Chưa cung cấp' }}" readonly
                                                style="background: #e2e8f0; font-size: 13px;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label"
                                                style="font-weight: 600; font-size: 12px; color: #64748b;">@lang('Số tài khoản')</label>
                                            <input type="text" class="form-control"
                                                value="{{ $seller->bank_account_number ?? 'Chưa cung cấp' }}" readonly
                                                style="background: #e2e8f0; font-size: 13px;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label"
                                                style="font-weight: 600; font-size: 12px; color: #64748b;">@lang('Chi nhánh ngân hàng')</label>
                                            <input type="text" class="form-control"
                                                value="{{ $seller->bank_branch ?? 'Chưa cung cấp' }}" readonly
                                                style="background: #e2e8f0; font-size: 13px;">
                                        </div>
                                    </div>
                                </div>

                                <div style="margin-top: 30px; display: flex; gap: 15px;">
                                    <button type="submit" class="btn btn-primary"
                                        style="padding: 12px 40px; background: var(--primary); border: none; font-weight: 700;">@lang('LƯU CẤU HÌNH')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </main>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script>
        (function ($) {
            'use strict';

            // Logo Upload Preview
            document.getElementById('logoInput').addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        document.getElementById('logoPreviewWrapper').innerHTML = '<img id="logoPreview" src="' + event.target.result + '" style="width: 100%; height: 100%; object-fit: cover;">';
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Cover Upload Preview
            document.getElementById('coverInput').addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        document.getElementById('coverPreviewWrapper').innerHTML = '<img id="coverPreview" src="' + event.target.result + '" style="width: 100%; height: 100%; object-fit: cover;">';
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Initialize Validation
            $('#shopForm').validate({
                rules: {
                    name: "required",
                    phone: "required",
                    address: "required"
                },
                messages: {
                    name: "@lang('Vui lòng nhập tên gian hàng')",
                    phone: "@lang('Vui lòng nhập số hotline chăm sóc khách hàng')",
                    address: "@lang('Vui lòng nhập địa chỉ của shop')"
                },
                errorElement: 'label',
                errorPlacement: function (error, element) {
                    error.insertAfter(element);
                }
            });

            // AJAX Form Submission
            $('#shopForm').on('submit', function (e) {
                e.preventDefault();
                var form = $(this);

                if (!form.valid()) return false;

                var btn = form.find('button[type=submit]');
                var btnText = btn.text();
                var formData = new FormData(this);

                btn.prop('disabled', true).html('<i class="las la-spinner la-spin"></i> @lang('Đang lưu')...');

                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.status == 'success') {
                            notify('success', response.message);
                        } else if (response.status == 'error') {
                            notify('error', response.message);
                        } else {
                            notify('success', 'Cập nhật cấu hình gian hàng thành công');
                        }
                    },
                    error: function (xhr) {
                        var response = xhr.responseJSON;
                        if (xhr.status == 422 && response && response.errors) {
                            $.each(response.errors, function (key, value) {
                                notify('error', value[0]);
                            });
                        } else if (response && response.message) {
                            notify('error', response.message);
                        } else {
                            notify('error', '@lang('Có lỗi xảy ra. Vui lòng thử lại sau.')');
                        }
                    },
                    complete: function () {
                        btn.prop('disabled', false).text(btnText.trim());
                    }
                });
            });
        })(jQuery);
    </script>
@endpush