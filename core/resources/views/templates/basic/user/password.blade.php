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
                    <h2>@lang('Đổi mật khẩu')</h2>
                    <p style="color: var(--text-muted); font-size: 14px;">@lang('Để bảo mật tài khoản, vui lòng không chia sẻ mật khẩu cho người khác')</p>
                </div>

                <form action="{{ route('user.change.password') }}" method="post" class="profile-form ajax-form" id="passwordForm" style="max-width: 450px;">
                    @csrf
                    <div class="form-group mb-4">
                        <label class="form-label font-semibold text-[#272343]">@lang('Mật khẩu hiện tại') <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" name="current_password" class="form-control" placeholder="@lang('Nhập mật khẩu cũ')" required>
                            <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 toggle-password h-unset" style="height: unset; background: none; border: none; padding: 0;">
                                <i class="las la-eye text-xl"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label font-semibold text-[#272343]">@lang('Mật khẩu mới') <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" name="password" id="password" class="form-control" placeholder="@lang('Nhập mật khẩu mới')" required>
                            <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 toggle-password h-unset" style="height: unset; background: none; border: none; padding: 0;">
                                <i class="las la-eye text-xl"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label font-semibold text-[#272343]">@lang('Xác nhận mật khẩu mới') <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="@lang('Nhập lại mật khẩu mới')" required>
                            <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 toggle-password h-unset" style="height: unset; background: none; border: none; padding: 0;">
                                <i class="las la-eye text-xl"></i>
                            </button>
                        </div>
                    </div>
                    <div style="margin-top: 35px;">
                        <button type="submit" class="btn btn-primary w-100" style="padding: 14px; font-size: 14px; letter-spacing: 0.5px;">
                            <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> @lang('Cập nhật mật khẩu mới')
                        </button>
                    </div>
                </form>
            </main>
        </div>
    </main>
</div>
@endsection

@push('style')
<style>
    label.error {
        color: #ef4444;
        font-size: 14px;
        margin-top: 4px;
        display: block;
    }

    input.error {
        border-color: #ef4444 !important;
    }
</style>
@endpush

@push('script-lib')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
@endpush

@push('script')
<script>
    (function($) {
        'use strict';

        // Validation Rules
        $('#passwordForm').validate({
            rules: {
                current_password: "required",
                password: {
                    required: true,
                    minlength: 6
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password"
                }
            },
            messages: {
                current_password: "Vui lòng nhập mật khẩu hiện tại",
                password: {
                    required: "Vui lòng nhập mật khẩu mới",
                    minlength: "Mật khẩu phải có ít nhất 6 ký tự"
                },
                password_confirmation: {
                    required: "Vui lòng xác nhận mật khẩu mới",
                    equalTo: "Mật khẩu xác nhận không khớp"
                }
            },
            errorElement: 'label',
            errorPlacement: function(error, element) {
                if (element.parent('.relative').length) {
                    error.insertAfter(element.parent('.relative'));
                } else {
                    error.insertAfter(element);
                }
            }
        });

        // AJAX Form Submission
        $('.ajax-form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);

            if (!form.valid()) return false;

            var btn = form.find('button[type=submit]');
            var btnText = btn.html();
            var formData = new FormData(this);

            btn.prop('disabled', true).html('<i class="las la-spinner la-spin"></i> Đang lưu...');

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status == 'success') {
                        notify('success', response.message);
                        form.trigger('reset');
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
                        notify('error', 'Mật khẩu cũ không chính xác hoặc có lỗi xảy ra.');
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).html(btnText);
                }
            });
        });

        // Password visibility toggle
        $(document).on('click', '.toggle-password', function() {
            var input = $(this).siblings('input');
            var type = input.attr('type') === 'password' ? 'text' : 'password';
            input.attr('type', type);
            var icon = $(this).find('i');
            if (icon.length) {
                if (type === 'text') {
                    icon.removeClass('la-eye').addClass('la-eye-slash');
                } else {
                    icon.removeClass('la-eye-slash').addClass('la-eye');
                }
            }
        });
    })(jQuery)
</script>
@endpush
