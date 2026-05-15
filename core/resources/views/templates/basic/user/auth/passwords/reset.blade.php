@extends($activeTemplate . 'layouts.app')
@section('panel')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@300;400;700&display=swap');

    .font-inter {
        font-family: 'Inter', sans-serif;
    }

    .font-poppins {
        font-family: 'Poppins', sans-serif;
    }

    .text-kviet-orange {
        color: #ff6f0f;
    }

    .bg-kviet-orange {
        background-color: #ff6f0f;
    }

    .text-kviet-dark {
        color: #272343;
    }

    .bg-kviet-dark {
        background-color: #272343;
    }

    .text-kviet-gray {
        color: #5a607f;
    }

    .border-kviet-input {
        border-color: #d9e1ec;
    }

    .bg-kviet-sidebar {
        background-color: #ebebeb;
    }

    .bg-kviet-bg {
        background-color: #f7f7f7;
    }

    /* Hide global header/footer if they interfere */
    header,
    footer {
        display: none !important;
    }
</style>

<div class="min-h-screen flex font-inter bg-kviet-bg">
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

    <!-- Right Side - Confirmation Success -->
    <div class="flex-1 flex items-center justify-center md:ml-[400px] p-6">
        <div class="w-full max-w-[540px] bg-white rounded-md shadow-[0px_1px_4px_0px_rgba(21,34,50,0.08)] p-8 md:p-[60px]">
            <!-- Illustration -->
            <div class="flex justify-center mb-6 md:mb-[40px]">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="140" height="140" viewBox="0 0 140 140" fill="none">
                        <rect opacity="0.01" width="140" height="140" fill="white" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M19 60L70 20L121 60V112C121 116.418 117.418 120 113 120H27C22.5817 120 19 116.418 19 112V60Z" fill="#272343" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M29 32C29 29.7909 30.7909 28 33 28H99L111 40V98H29V32Z" fill="#FF6F0F" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M29 28H99V40H111V108H29V28Z" fill="#FF6F0F" />
                        <rect x="50" y="69" width="40" height="4" rx="2" fill="white" />
                        <rect x="60" y="77" width="20" height="4" rx="2" fill="white" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M79.1129 41.5596L81.8874 44.4409L66.9737 58.8022L59.5859 51.4144L62.4144 48.586L67.0262 53.1972L79.1129 41.5596Z" fill="white" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M19 60L67.5314 98.0639C68.9808 99.2007 71.0192 99.2007 72.4686 98.0639L121 60V116C121 118.209 119.209 120 117 120H23C20.7909 120 19 118.209 19 116V60Z" fill="#D7DBEC" />
                    </svg>
                </div>
            </div>

            <div class="flex flex-col gap-[24px]">
                <!-- Title -->
                <div class="text-center">
                    <h2 class="text-[32px] font-bold text-kviet-dark text-center">@lang('Almost There!')</h2>
                </div>

                <!-- Description -->
                <p class="text-[16px] text-[#5A607F] text-center">Check your email inbox and confirm your account</p>

                <!-- Divider -->
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-[#E6E9F4]"></div>
                    </div>
                </div>

                <!-- Sign In Button -->
                <a href="{{ route('user.login') }}" class="flex items-center justify-center w-full h-12 bg-white border border-[#D7DBEC] text-[#1E5EFF] text-[16px] font-normal rounded-[4px] hover:bg-gray-50 transition-colors">
                    Resend Confirmation
                </a>
            </div>
        </div>
    </div>

    <!-- Top Right - Already a member -->
    <div class="absolute top-6 right-6 md:right-20">
        <a href="{{ route('user.login') }}" class="text-[13px] text-[#8a8a8a] hover:text-kviet-dark transition-colors">@lang('Already a member?')</a>
        <a href="{{ route('user.login') }}" class="text-[13px] text-kviet-dark font-medium ml-2 hover:underline">@lang('Sign in')</a>
    </div>
</div>
@endsection