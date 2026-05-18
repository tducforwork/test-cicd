@extends($activeTemplate . 'layouts.frontend')
@section('content')
@php
$login_content = getContent('login_page.content', true);
@endphp
<section class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <h2>@lang('Chào mừng trở lại')</h2>
            <p>@lang('Đăng nhập để quản lý đơn hàng và tài khoản của bạn')</p>
        </div>

        <form class="auth-form" method="POST" action="{{ route('user.login') }}" class="verify-gcaptcha">
            @csrf
            <input type="hidden" name="cart_session_id" value="{{ session('session_id') }}">
            
            <div class="form-group">
                <label for="username">@lang('Email hoặc Số điện thoại')</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}" placeholder="@lang('Nhập email hoặc SĐT')" required>
            </div>

            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <label style="margin-bottom: 0;">@lang('Mật khẩu')</label>
                    @if (Route::has('user.password.request'))
                    <a href="{{ route('user.password.request') }}" style="font-size: 12px; color: var(--accent); font-weight: 600; text-decoration: none;">@lang('Quên mật khẩu?')</a>
                    @endif
                </div>
                <input type="password" name="password" id="password" placeholder="@lang('Nhập mật khẩu')" required>
            </div>

            <div class="mb-4">
                <x-captcha path="Template::partials" />
            </div>

            <div class="form-check">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">@lang('Ghi nhớ đăng nhập')</label>
            </div>

            <button type="submit" class="btn-auth">@lang('Đăng nhập')</button>

            @if (@gs('socialite_credentials')->linkedin->status || @gs('socialite_credentials')->facebook->status == Status::ENABLE || @gs('socialite_credentials')->google->status == Status::ENABLE)
            <div class="separator">@lang('Hoặc đăng nhập với')</div>

            <div class="social-login" style="display: flex; flex-direction: column; gap: 10px;">
                @if (@gs('socialite_credentials')->google->status == Status::ENABLE)
                <a href="{{ route('user.social.login', 'google') }}" class="btn-google" style="text-decoration: none; display: flex; align-items: center; justify-content: center; width: 100%;">
                    <img src="https://cdn1.iconfinder.com/data/icons/google-s-logo/150/Google_Icons-09-512.png" alt="Google">
                    @lang('Tiếp tục với Google')
                </a>
                @endif
                @if (@gs('socialite_credentials')->facebook->status == Status::ENABLE)
                <a href="{{ route('user.social.login', 'facebook') }}" class="btn-google" style="text-decoration: none; display: flex; align-items: center; justify-content: center; width: 100%;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/05/Facebook_Logo_%282019%29.png/768px-Facebook_Logo_%282019%29.png" style="width: 20px; height: 20px; margin-right: 8px;" alt="Facebook">
                    @lang('Tiếp tục với Facebook')
                </a>
                @endif
            </div>
            @endif

            @if (Route::has('user.register') && gs('registration'))
            <div class="auth-footer">
                @lang('Bạn chưa có tài khoản?') <a href="{{ route('user.register') }}">@lang('Đăng ký ngay')</a>
            </div>
            @endif
        </form>
    </div>
</section>
@endsection