@extends($activeTemplate . 'layouts.app')
@section('panel')
@php
$forgot_content = getContent('forgot_password_page.content', true);
@endphp

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
            <h2 class="text-[32px] font-semibold text-[#272343] tracking-tight">@lang('Plan includes')</h2>

            <!-- Features List -->
            <div class="space-y-6 text-left">
                <div class="flex items-center gap-4">
                    <div class="h-[24px] w-[24px] flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M18.9366 7.16399C19.2881 7.51547 19.2881 8.08531 18.9366 8.43679L11.1366 16.2368C10.7851 16.5883 10.2153 16.5883 9.8638 16.2368L5.9638 12.3368C5.61233 11.9853 5.61233 11.4155 5.9638 11.064C6.31527 10.7125 6.88512 10.7125 7.23659 11.064L10.5002 14.3276L17.6638 7.16399C18.0153 6.81252 18.5851 6.81252 18.9366 7.16399Z" fill="#32A06E" />
                        </svg>
                    </div>
                    <span class="text-[14px] font-semibold text-[#272343]">@lang('Unlimited product uploads')</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="h-[24px] w-[24px] flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M18.9366 7.16399C19.2881 7.51547 19.2881 8.08531 18.9366 8.43679L11.1366 16.2368C10.7851 16.5883 10.2153 16.5883 9.8638 16.2368L5.9638 12.3368C5.61233 11.9853 5.61233 11.4155 5.9638 11.064C6.31527 10.7125 6.88512 10.7125 7.23659 11.064L10.5002 14.3276L17.6638 7.16399C18.0153 6.81252 18.5851 6.81252 18.9366 7.16399Z" fill="#32A06E" />
                        </svg>
                    </div>
                    <span class="text-[14px] font-semibold text-[#272343]">@lang('Pro tips')</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="h-[24px] w-[24px] flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M18.9366 7.16399C19.2881 7.51547 19.2881 8.08531 18.9366 8.43679L11.1366 16.2368C10.7851 16.5883 10.2153 16.5883 9.8638 16.2368L5.9638 12.3368C5.61233 11.9853 5.61233 11.4155 5.9638 11.064C6.31527 10.7125 6.88512 10.7125 7.23659 11.064L10.5002 14.3276L17.6638 7.16399C18.0153 6.81252 18.5851 6.81252 18.9366 7.16399Z" fill="#32A06E" />
                        </svg>
                    </div>
                    <span class="text-[14px] font-semibold text-[#272343]">@lang('Free forever')</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="h-[24px] w-[24px] flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M18.9366 7.16399C19.2881 7.51547 19.2881 8.08531 18.9366 8.43679L11.1366 16.2368C10.7851 16.5883 10.2153 16.5883 9.8638 16.2368L5.9638 12.3368C5.61233 11.9853 5.61233 11.4155 5.9638 11.064C6.31527 10.7125 6.88512 10.7125 7.23659 11.064L10.5002 14.3276L17.6638 7.16399C18.0153 6.81252 18.5851 6.81252 18.9366 7.16399Z" fill="#32A06E" />
                        </svg>
                    </div>
                    <span class="text-[14px] font-semibold text-[#272343]">@lang('Full author options')</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side - Password Reset Form -->
    <div class="flex-1 flex items-center justify-center md:ml-[400px] p-6">
        <div class="w-full max-w-[540px] bg-white rounded-md shadow-[0px_1px_4px_0px_rgba(21,34,50,0.08)] p-8 md:p-[60px]">
            <!-- Header -->
            <div class="text-center mb-8 md:mb-[40px]">
                <h2 class="text-[32px] font-bold text-[#272343] leading-[44px]">@lang('Password Reset')</h2>
                <p class="text-[16px] text-[#5A607F] mt-[8px]">@lang('We Will Help You Reset your Password')</p>
            </div>

            <div class="flex flex-col gap-[24px]">
                <form method="POST" action="{{ route('user.password.email') }}" class="verify-gcaptcha">
                    @csrf
                    <!-- Email Input -->
                    <div class="mb-6">
                        <label for="value" class="block text-[14px] text-[#5A607F] mb-[4px]">@lang('Email or Username')</label>
                        <input type="text" id="value" name="value" value="{{ old('value') }}" placeholder="@lang('Enter Email or Username')"
                            class="w-full h-12 px-4 border bg-white border-[#D9E1EC] placeholder:text-[#A1A7C4] placeholder:font-[400] placeholder:text-[16px] rounded-[4px] text-[16px] focus:outline-none focus:border-kviet-orange transition-colors" required autofocus="off">
                    </div>

                    <!-- Captcha -->
                    <div class="mb-6">
                        <x-captcha path="Template::partials" />
                    </div>

                    <!-- Reset Password Button -->
                    <button type="submit" class="w-full h-12 bg-[#FF6F0F] mb-[24px] text-white text-[16px] font-normal rounded-[4px] hover:bg-orange-600 transition-colors">
                        Reset Password
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-kviet-light"></div>
                    </div>
                </div>

                <!-- Remembered Password -->
                <p class="text-[14px] text-[#5A607F] text-center">@lang('Remembered your Password?')</p>
                <a href="{{ route('user.login') }}" class="w-full h-12 flex items-center justify-center bg-white border border-[#D7DBEC] rounded-[4px] text-[#1E5EFF] text-[16px] hover:bg-gray-50 transition-colors">
                    @lang('Back to Sign In')
                </a>
            </div>
        </div>
    </div>

    <!-- Top Right - Already a member -->
    <div class="absolute top-6 right-6 md:right-20 hidden sm:block">
        <span class="text-[13px] text-[#8a8a8a]">@lang('Already a member?')</span>
        <a href="{{ route('user.login') }}" class="text-[13px] text-[#272343] font-medium ml-2 hover:underline">@lang('Sign In')</a>
    </div>
</div>
@endsection

@push('style')
<style>
    /* Override any layout styles that might interfere */
    body {
        padding: 0 !important;
        margin: 0 !important;
    }

    .footer-section,
    .header-section {
        display: none !important;
    }
</style>
@endpush