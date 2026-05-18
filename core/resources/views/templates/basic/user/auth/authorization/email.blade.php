@extends($activeTemplate . 'layouts.frontend')
@section('content')
<section class="auth-page">
    <div class="auth-card">
        <!-- Icon -->
        <div class="flex justify-center mb-4" style="display: flex; justify-content: center; margin-bottom: 16px;">
            <div class="w-16 h-16 bg-[#FFF6F0] rounded-full flex items-center justify-center" style="width: 64px; height: 64px; background-color: #FFF6F0; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="las la-envelope text-3xl text-[#FF6F0F]" style="font-size: 28px; color: #FF6F0F;"></i>
            </div>
        </div>

        <div class="auth-header">
            <h2>@lang('Xác minh Email')</h2>
            <p>@lang('Mã xác minh 6 chữ số đã được gửi đến email'): <span style="color: var(--accent); font-weight: bold; word-break: break-all;">{{ showEmailAddress($email) }}</span></p>
        </div>

        <form action="{{ route('user.verify.account') }}" method="POST" class="auth-form submit-form">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            
            @include($activeTemplate . 'partials.verification_code')

            <button type="submit" class="btn-auth mt-4" style="width: 100%;">@lang('Xác nhận kích hoạt')</button>
        </form>

        <div class="auth-footer" style="margin-top: 24px; text-align: center;">
            <p class="text-[#6B7280] text-sm" style="color: #6B7280; font-size: 14px;">
                @lang('Nếu bạn không nhận được mã nào'), <span class="countdown-wrapper">@lang('thử lại sau') <span id="countdown" class="fw-bold text-[#FF6F0F]" style="color: #FF6F0F; font-weight: bold;">--</span> @lang('giây')</span> 
                <a href="{{ route('user.verify.account.resend') }}?email={{ $email }}" class="try-again-link d-none text-[#FF6F0F] hover:underline" style="color: #FF6F0F; font-weight: 600; text-decoration: none;">@lang('Thử lại')</a>
            </p>
            <div style="margin-top: 20px; border-top: 1px solid #f3f4f6; padding-top: 15px;">
                <a href="{{ route('user.login') }}" style="color: #6B7280; text-decoration: none; font-size: 14px; font-weight: 500;">
                    <i class="las la-arrow-left"></i> @lang('Quay lại Đăng nhập')
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('script')
<script>
    (function($) {
        'use strict';
        var sendAt = "{{ @$user->ver_code_send_at ? $user->ver_code_send_at->timestamp : time() }}";
        var distance = Number("{{ @$user->ver_code_send_at ? ($user->ver_code_send_at->addMinutes(2)->timestamp - time()) : 120 }}");
        if (distance < 0) distance = 0;
        
        var x = setInterval(function() {
            distance--;
            var countdownEl = document.getElementById("countdown");
            if (countdownEl) {
                countdownEl.innerHTML = distance;
            }
            if (distance <= 0) {
                clearInterval(x);
                $('.countdown-wrapper').addClass('d-none');
                $('.try-again-link').removeClass('d-none');
            }
        }, 1000);
    })(jQuery);
</script>
@endpush