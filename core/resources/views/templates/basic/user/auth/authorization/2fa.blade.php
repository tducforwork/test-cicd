@extends($activeTemplate . 'layouts.frontend')
@section('content')
<div class="bg-[#F7F7F7]">
    <main class="container mx-auto pb-32 pt-10">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar -->
            <aside class="w-full lg:w-[312px] shrink-0">
                @include($activeTemplate . 'user.partials.sidebar')
            </aside>

            <!-- Main Panel -->
            <div class="flex-1 min-w-0">
                <!-- Page Heading -->
                <div class="flex items-center gap-4 mb-6">
                    <a href="{{ url()->previous() }}" class="shrink-0 flex items-center justify-center w-10 h-10 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 text-[#272343]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <h1 class="font-semibold text-[#272343] text-xl md:text-2xl leading-normal">@lang('Xác minh 2FA')</h1>
                </div>

                <!-- Content Card -->
                <div class="flex flex-col items-center gap-6 p-4 md:p-6 w-full bg-white rounded-lg">
                    <div class="w-full max-w-md mx-auto">
                        <!-- Icon -->
                        <div class="flex justify-center mb-6">
                            <div class="w-20 h-20 bg-[#FFF6F0] rounded-full flex items-center justify-center">
                                <i class="las la-shield-alt text-4xl text-[#FF6F0F]"></i>
                            </div>
                        </div>

                        <h2 class="w-full font-bold text-[#272343] text-xl leading-[30px] text-center mb-2">@lang('Xác minh hai yếu tố')</h2>
                        <p class="text-[#6B7280] text-base leading-6 text-center mb-6">
                            @lang('Nhập mã xác minh 6 chữ số được gửi đến thiết bị của bạn')
                        </p>

                        <form action="{{ route('user.2fa.verify') }}" method="POST" class="submit-form ajax-form">
                            @csrf
                            @include($activeTemplate . 'partials.verification_code')

                            <button type="submit" class="bg-[#FF6F0F] text-white px-8 py-3 rounded-lg font-semibold text-base leading-[17.6px] hover:bg-orange-600 transition-colors flex items-center justify-center gap-2 w-full mt-6 shadow-sm">
                                @lang('Xác nhận')
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection