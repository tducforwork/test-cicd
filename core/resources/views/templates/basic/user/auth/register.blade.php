@extends($activeTemplate . 'layouts.app')
@if (gs('registration'))
@section('panel')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">

<div class="min-h-screen flex font-inter bg-kviet-bg overflow-x-hidden">
    <!-- Left Sidebar -->
    <div class="w-[400px] bg-kviet-sidebar hidden md:flex flex-col items-center justify-center absolute inset-y-0 left-0">
        <div class="text-center flex flex-col gap-[24px]">
            <div class="relative w-[202px] h-[174px] mx-auto">
                <img src="{{ asset('assets/images/tree.png') }}" alt="@lang('Site Logo')" class="w-full h-full object-contain">
            </div>
            <!-- Logo -->
            <div class="flex justify-center mx-auto">
                <img src="{{ asset('assets/images/Kviet.png') }}" alt="@lang('Site Logo')" class="w-[157px] h-[96px] object-contain">
            </div>

            <!-- Plan Includes -->
            <h2 class="text-[32px] font-semibold text-kviet-dark tracking-tight">@lang('Plan includes')</h2>

            <!-- Features List -->
            <div class="space-y-6 text-left">
                <div class="flex items-center gap-4">
                    <div class="h-[24px] w-[24px] flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M18.9366 7.16399C19.2881 7.51547 19.2881 8.08531 18.9366 8.43679L11.1366 16.2368C10.7851 16.5883 10.2153 16.5883 9.8638 16.2368L5.9638 12.3368C5.61233 11.9853 5.61233 11.4155 5.9638 11.064C6.31527 10.7125 6.88512 10.7125 7.23659 11.064L10.5002 14.3276L17.6638 7.16399C18.0153 6.81252 18.5851 6.81252 18.9366 7.16399Z" fill="#32A06E" />
                        </svg>
                    </div>
                    <span class="text-[14px] font-semibold text-kviet-dark">@lang('Unlimited product uploads')</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="h-[24px] w-[24px] flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M18.9366 7.16399C19.2881 7.51547 19.2881 8.08531 18.9366 8.43679L11.1366 16.2368C10.7851 16.5883 10.2153 16.5883 9.8638 16.2368L5.9638 12.3368C5.61233 11.9853 5.61233 11.4155 5.9638 11.064C6.31527 10.7125 6.88512 10.7125 7.23659 11.064L10.5002 14.3276L17.6638 7.16399C18.0153 6.81252 18.5851 6.81252 18.9366 7.16399Z" fill="#32A06E" />
                        </svg>
                    </div>
                    <span class="text-[14px] font-semibold text-kviet-dark">@lang('Pro tips')</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="h-[24px] w-[24px] flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M18.9366 7.16399C19.2881 7.51547 19.2881 8.08531 18.9366 8.43679L11.1366 16.2368C10.7851 16.5883 10.2153 16.5883 9.8638 16.2368L5.9638 12.3368C5.61233 11.9853 5.61233 11.4155 5.9638 11.064C6.31527 10.7125 6.88512 10.7125 7.23659 11.064L10.5002 14.3276L17.6638 7.16399C18.0153 6.81252 18.5851 6.81252 18.9366 7.16399Z" fill="#32A06E" />
                        </svg>
                    </div>
                    <span class="text-[14px] font-semibold text-kviet-dark">@lang('Free forever')</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="h-[24px] w-[24px] flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M18.9366 7.16399C19.2881 7.51547 19.2881 8.08531 18.9366 8.43679L11.1366 16.2368C10.7851 16.5883 10.2153 16.5883 9.8638 16.2368L5.9638 12.3368C5.61233 11.9853 5.61233 11.4155 5.9638 11.064C6.31527 10.7125 6.88512 10.7125 7.23659 11.064L10.5002 14.3276L17.6638 7.16399C18.0153 6.81252 18.5851 6.81252 18.9366 7.16399Z" fill="#32A06E" />
                        </svg>
                    </div>
                    <span class="text-[14px] font-semibold text-kviet-dark">@lang('Full author options')</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side - Create Account Form -->
    <div class="flex-1 flex items-center justify-center md:ml-[400px] p-6">
        <div class="w-full max-w-[540px] bg-white rounded-md shadow-[0px_1px_4px_0px_rgba(21,34,50,0.08)] p-8 md:p-[60px]">
            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-[32px] font-bold text-kviet-dark leading-[44px]">@lang('Create an Account')</h2>
                <div class="mt-4 flex flex-col items-center justify-center gap-2 text-[16px]">
                    <span class="text-kviet-gray">@lang('Have an Account?')</span>
                    <a href="{{ route('user.login') }}" class="text-[#1e5eff] hover:underline">@lang('Sign In')</a>
                </div>
            </div>

            <form action="{{ route('user.register') }}" method="POST" class="verify-gcaptcha disableSubmission">
                @csrf

                <!-- Email Input -->
                <div class="mb-5">
                    <label for="email" class="block text-[14px] text-kviet-gray mb-2">@lang('Email')</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="@lang('Enter Email Address')"
                        class="w-full h-12 px-4 border bg-white rounded text-[16px] focus:outline-none focus:border-kviet-orange transition-colors checkUser @error('email') border-red-500 @enderror" required>
                    @error('email')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="mb-5">
                    <label for="password" class="block text-[14px] text-kviet-gray mb-2">@lang('Password')</label>
                    <input type="password" id="password" name="password" placeholder="@lang('Create Password')"
                        class="w-full h-12 px-4 border bg-white rounded text-[16px] focus:outline-none focus:border-kviet-orange transition-colors @error('password') border-red-500 @enderror @if (gs('secure_password')) secure-password @endif" required>
                    @error('password')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Captcha -->
                <div class="mb-6">
                    <x-captcha path="Template::partials" />
                </div>

                <!-- Agree Terms -->
                @if (gs('agree'))
                @php
                $pages = getContent('policy_pages.element', false);
                @endphp
                <div class="mb-6">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="agree" id="agree" value="1" class="w-5 h-5 rounded border border-kviet-light bg-white appearance-none cursor-pointer mt-0.5 checked:bg-kviet-orange checked:border-kviet-orange relative checked:after:content-[''] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:w-2 checked:after:h-3 checked:after:bg-white checked:after:translate-x-1/2 checked:after:-translate-y-1/2 checked:after:rotate-45 checked:after:border checked:after:border-r-2 checked:after:border-b-2 checked:after:border-l-0 checked:after:border-t-0 checked:after:border-white">
                        <span class="text-[14px] text-kviet-gray">
                            @lang('By creating account, you agree to our')
                            @foreach ($pages as $item)
                            <a href="{{ route('policy.pages', $item->slug) }}" class="text-[#1e5eff] hover:underline">{{ __($item->data_values->title) }}</a>
                            @if (!$loop->last), @endif
                            @endforeach
                        </span>
                    </label>
                </div>
                @endif

                <!-- Create Account Button -->
                <button type="submit" id="recaptcha" class="w-full h-12 bg-kviet-orange text-white text-[16px] font-medium rounded hover:bg-orange-600 transition-colors mb-6 disabled:opacity-50 disabled:cursor-not-allowed">
                    @lang('Create Account')
                </button>
            </form>

            @if (@gs('socialite_credentials')->linkedin->status || @gs('socialite_credentials')->facebook->status == Status::ENABLE || @gs('socialite_credentials')->google->status == Status::ENABLE)
            <!-- Divider -->
            <div class="relative mb-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-kviet-light"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="px-4 bg-white text-[14px] text-kviet-gray">@lang('Or create an account using:')</span>
                </div>
            </div>

            <!-- Social Login Buttons -->
            <div class="space-y-4">
                @if (@gs('socialite_credentials')->google->status == Status::ENABLE)
                <a href="{{ route('user.social.login', 'google') }}" class="w-full h-12 flex items-center justify-center gap-3 bg-white border border-kviet-light rounded text-kviet-dark hover:bg-gray-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M21 11H12V14H17C16.2579 16.0956 14.3503 17.4 12 17.4C9.01785 17.4 6.6 14.9822 6.6 12C6.6 9.01785 9.01785 6.6 12 6.6C13.3765 6.6 14.6289 7.1193 15.5824 7.96755L18.1281 5.4219C16.5207 3.92385 14.3706 3 12 3C7.02975 3 3 7.02975 3 12C3 16.9703 7.02975 21 12 21C16.9703 21 21 16.9703 21 12C21 11.3966 21 11 21 11Z" fill="#FFC107" />
                        <path d="M4 7.82508L6.93798 10C7.73295 8.01328 9.65821 6.61057 11.9112 6.61057C13.2789 6.61057 14.5232 7.1314 15.4707 7.98214L18 5.42901C16.4029 3.92656 14.2666 3 11.9112 3C8.47649 3 5.49783 4.95738 4 7.82508Z" fill="#FF3D00" />
                        <path d="M11.9858 21C14.3029 21 16.4082 20.1321 18 18.7208L15.2237 16.4214C14.3231 17.0891 13.2036 17.4881 11.9858 17.4881C9.65266 17.4881 7.67156 16.032 6.92523 14L4 16.2059C5.48459 19.0492 8.49952 21 11.9858 21Z" fill="#4CAF50" />
                        <path d="M21 11L12 11V14H17C16.6436 14.9505 15.8352 15.403 15 16L18 19C17.8029 19.1691 21 16.7489 21 12.5C21 11.9302 21 11 21 11Z" fill="#1976D2" />
                    </svg>
                    <span class="text-[16px] text-[#1E5EFF]">@lang('Continue with Google')</span>
                </a>
                @endif
                @if (@gs('socialite_credentials')->facebook->status == Status::ENABLE)
                <a href="{{ route('user.social.login', 'facebook') }}" class="w-full h-12 flex items-center justify-center gap-3 bg-white border border-kviet-light rounded text-kviet-dark hover:bg-gray-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M21 12.055C21 7.05406 16.9706 3 12 3C7.02943 3 3 7.05406 3 12.055C3 16.5746 6.29117 20.3207 10.5938 21V14.6725H8.30859V12.055H10.5938V10.0601C10.5938 7.79066 11.9374 6.53711 13.9932 6.53711C14.9779 6.53711 16.0078 6.71397 16.0078 6.71397V8.94235H14.8729C13.7549 8.94235 13.4062 9.64034 13.4062 10.3564V12.055H15.9023L15.5033 14.6725H13.4062V21C17.7088 20.3207 21 16.5746 21 12.055Z" fill="#1877F2"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.4758 14.3738L15.8904 11.6796H13.2968V9.93126C13.2968 9.19418 13.6591 8.47573 14.8208 8.47573H16V6.18204C16 6.18204 14.9298 6 13.9067 6C11.7706 6 10.3744 7.29029 10.3744 9.62621V11.6796H8V14.3738H10.3744V20.8868C10.8505 20.9612 11.3385 21 11.8356 21C12.3327 21 13.0239 21.0745 13.5 21V14.487L15.4758 14.3738Z" fill="white"/>
                    </svg>
                    <span class="text-[16px] text-[#1E5EFF]">@lang('Continue with Facebook')</span>
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Top Right - Already a member -->
    <div class="absolute top-6 right-6 md:right-20">
        <span class="text-[13px] text-[#8a8a8a]">@lang('Already a member?')</span>
        <a href="{{ route('user.login') }}" class="text-[13px] text-kviet-dark font-medium ml-2 hover:underline">@lang('Sign in')</a>
    </div>
</div>

<!-- User Exists Modal -->
<div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle" aria-hidden="true">
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
                <button type="button" class="btn btn--dark h-auto text-white" data-bs-dismiss="modal">@lang('Close')</button>
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
    (function($) {
        $('.checkUser').on('focusout', function(e) {
            var url = '{{ route('user.checkUser') }}';
            var value = $(this).val();
            var token = '{{ csrf_token() }}';

            var data = {
                email: value,
                _token: token
            }

            $.post(url, data, function(response) {
                if (response.data != false) {
                    $('#existModalCenter').modal('show');
                }
            });
        });
    })(jQuery);
</script>
@endpush
@else
@section('panel')
@include($activeTemplate . 'partials.registration_disabled')
@endsection
@endif

@push('style')
<style>
    body {
        padding: 0 !important;
        margin: 0 !important;
    }

    .footer-section,
    .header-section {
        display: none !important;
    }

    input[type="checkbox"]:checked {
        background-color: #ff6f0f;
        border-color: #ff6f0f;
    }
</style>
@endpush
