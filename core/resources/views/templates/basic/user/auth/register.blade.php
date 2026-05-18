@extends($activeTemplate . 'layouts.frontend')
@if (gs('registration'))
    @section('content')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@300;400;700&display=swap"
            rel="stylesheet">

        <section class="auth-page">
            <div class="auth-card auth-card-register">
                <div class="auth-header">
                    <h2>@lang('Tạo tài khoản mới')</h2>
                    <p>@lang('Khám phá hệ sinh thái Quảng Phát ngay hôm nay')</p>
                </div>

                <form class="auth-form verify-gcaptcha disableSubmission" method="POST" action="{{ route('user.register') }}">
                    @csrf

                    <div class="form-group">
                        <label for="fullName">@lang('Họ và tên')</label>
                        <input type="text" id="fullName" name="fullName" value="{{ old('fullName') }}"
                            placeholder="@lang('Nhập họ và tên của bạn')" required />
                        @error('fullName')
                            <span class="text-red-500 text-sm mt-1 block" style="color: red; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px">
                        <div class="form-group">
                            <label for="mobile">@lang('Số điện thoại')</label>
                            <input type="tel" id="mobile" name="mobile" value="{{ old('mobile') }}" class="checkUser"
                                placeholder="@lang('Số điện thoại')" required />
                            @error('mobile')
                                <span class="text-red-500 text-sm mt-1 block"
                                    style="color: red; font-size: 12px;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">@lang('Email')</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="checkUser"
                                placeholder="@lang('Địa chỉ email')" required />
                            @error('email')
                                <span class="text-red-500 text-sm mt-1 block"
                                    style="color: red; font-size: 12px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">@lang('Mật khẩu')</label>
                        <input type="password" id="password" name="password" placeholder="@lang('Tối thiểu 8 ký tự')"
                            class="@if (gs('secure_password')) secure-password @endif" required />
                        @error('password')
                            <span class="text-red-500 text-sm mt-1 block" style="color: red; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-check">
                        <input type="checkbox" id="isSeller" name="is_seller" value="1" />
                        <label for="isSeller">@lang('Tôi muốn đăng ký làm Người Bán (Seller)')</label>
                    </div>

                    <!-- Seller Extra Fields -->
                    <div class="seller-fields" id="sellerFields">
                        <div class="form-group" style="grid-column: span 2">
                            <label for="address_seller">@lang('Địa chỉ kinh doanh')</label>
                            <input type="text" id="address_seller" name="address_seller" value="{{ old('address_seller') }}"
                                placeholder="@lang('Nhập địa chỉ chi tiết')" />
                        </div>
                        <div class="form-group">
                            <label for="id_card">@lang('Số CCCD / Định danh')</label>
                            <input type="text" id="id_card" name="id_card" value="{{ old('id_card') }}"
                                placeholder="@lang('Số căn cước công dân')" />
                        </div>
                        <div class="form-group">
                            <label for="bank_name">@lang('Tên người thụ hưởng')</label>
                            <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name') }}"
                                placeholder="@lang('Ví dụ: PHAM VAN A')" />
                        </div>
                        <div class="form-group">
                            <label for="bank_account_number">@lang('Số tài khoản ngân hàng')</label>
                            <input type="text" id="bank_account_number" name="bank_account_number"
                                value="{{ old('bank_account_number') }}" placeholder="@lang('Nhập số tài khoản')" />
                        </div>
                        <div class="form-group">
                            <label for="bank_branch">@lang('Tên ngân hàng')</label>
                            <input type="text" id="bank_branch" name="bank_branch" value="{{ old('bank_branch') }}"
                                placeholder="@lang('Ví dụ: Vietcombank')" />
                        </div>
                    </div>

                    <div class="mb-4">
                        <x-captcha path="Template::partials" />
                    </div>

                    <!-- Agree Terms -->
                    @if (gs('agree'))
                        @php
                            $pages = getContent('policy_pages.element', false);
                        @endphp
                        <div class="form-check" style="margin-bottom: 20px;">
                            <input type="checkbox" name="agree" id="agree" value="1" required>
                            <label for="agree" style="font-size: 14px;">
                                @lang('By creating account, you agree to our')
                                @foreach ($pages as $item)
                                    <a href="{{ route('policy.pages', $item->slug) }}"
                                        style="color: #1e5eff; text-decoration: none;">{{ __($item->data_values->title) }}</a>
                                    @if (!$loop->last), @endif
                                @endforeach
                            </label>
                        </div>
                    @endif

                    <button type="submit" id="recaptcha" class="btn-auth">@lang('Đăng ký ngay')</button>

                    @if (@gs('socialite_credentials')->linkedin->status || @gs('socialite_credentials')->facebook->status == Status::ENABLE || @gs('socialite_credentials')->google->status == Status::ENABLE)
                        <div class="separator">@lang('Hoặc đăng ký bằng')</div>

                        <div class="social-login" style="display: flex; flex-direction: column; gap: 10px;">
                            @if (@gs('socialite_credentials')->google->status == Status::ENABLE)
                                <a href="{{ route('user.social.login', 'google') }}" class="btn-google"
                                    style="text-decoration: none; display: flex; align-items: center; justify-content: center; width: 100%;">
                                    <img src="https://cdn1.iconfinder.com/data/icons/google-s-logo/150/Google_Icons-09-512.png"
                                        alt="Google">
                                    @lang('Tiếp tục với Google')
                                </a>
                            @endif
                            @if (@gs('socialite_credentials')->facebook->status == Status::ENABLE)
                                <a href="{{ route('user.social.login', 'facebook') }}" class="btn-google"
                                    style="text-decoration: none; display: flex; align-items: center; justify-content: center; width: 100%;">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/05/Facebook_Logo_%282019%29.png/768px-Facebook_Logo_%282019%29.png"
                                        style="width: 20px; height: 20px; margin-right: 8px;" alt="Facebook">
                                    @lang('Tiếp tục với Facebook')
                                </a>
                            @endif
                        </div>
                    @endif

                    <div class="auth-footer">
                        @lang('Bạn đã có tài khoản?') <a href="{{ route('user.login') }}">@lang('Đăng nhập')</a>
                    </div>
                </form>
            </div>
        </section>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const sellerCheckbox = document.getElementById("isSeller");
                const sellerFields = document.getElementById("sellerFields");

                if (sellerCheckbox && sellerFields) {
                    sellerCheckbox.addEventListener("change", function () {
                        if (this.checked) {
                            sellerFields.classList.add("active");
                        } else {
                            sellerFields.classList.remove("active");
                        }
                    });
                    // trigger on load if checked by old input
                    if (sellerCheckbox.checked) {
                        sellerFields.classList.add("active");
                    }
                }
            });
        </script>

        <!-- User Exists Modal -->
        <div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h6 class="text-center">@lang('You already have an account please Sign in')</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark h-auto text-white"
                            data-bs-dismiss="modal">@lang('Close')</button>
                        <a href="{{ route('user.login') }}" class="btn btn--base h-auto">@lang('Login')</a>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @if (gs('secure_password'))
        @push('script-lib')
            <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
        @endpush
    @endif

    @push('script')
        <script>
            "use strict";
            (function ($) {
                $('.checkUser').on('focusout', function (e) {
                    var url = '{{ route('user.checkUser') }}';
                    var value = $(this).val();
                    var token = '{{ csrf_token() }}';

                    var data = {
                        email: value,
                        _token: token
                    }

                    $.post(url, data, function (response) {
                        if (response.data != false) {
                            $('#existModalCenter').modal('show');
                        }
                    });
                });
            })(jQuery);
        </script>
    @endpush
@else
    @section('content')
        @include($activeTemplate . 'partials.registration_disabled')
    @endsection
@endif