@extends($activeTemplate . 'layouts.frontend')
@section('content')
@php
$forgot_content = getContent('forgot_password_page.content', true);
@endphp

<section class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <h2>@lang('Quên mật khẩu')</h2>
            <p>@lang('Chúng tôi sẽ giúp bạn khôi phục lại mật khẩu')</p>
        </div>

        <form method="POST" action="{{ route('user.password.email') }}" class="auth-form verify-gcaptcha">
            @csrf
            
            <div class="form-group">
                <label for="value">@lang('Email hoặc Số điện thoại')</label>
                <input type="text" name="value" id="value" value="{{ old('value') }}" placeholder="@lang('Nhập email hoặc SĐT')" required autofocus="off">
            </div>

            <div class="mb-4">
                <x-captcha path="Template::partials" />
            </div>

            <button type="submit" class="btn-auth" style="margin-bottom: 24px;">@lang('Khôi phục mật khẩu')</button>

            <div class="separator" style="margin-bottom: 24px;"></div>

            <div class="auth-footer" style="display: flex; flex-direction: column; align-items: center; gap: 12px;">
                <span>@lang('Đã nhớ lại mật khẩu?')</span>
                <a href="{{ route('user.login') }}" class="btn-auth" style="background: white; color: var(--accent); border: 1px solid var(--border-color); text-decoration: none;">
                    @lang('Quay lại Đăng nhập')
                </a>
            </div>
        </form>
    </div>
</section>
@endsection