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
                    <a href="{{ route('home') }}" class="shrink-0 flex items-center justify-center w-10 h-10 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 text-[#272343]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <h1 class="font-semibold text-[#272343] text-xl md:text-2xl leading-normal">@lang('Tài khoản bị khóa')</h1>
                </div>

                <!-- Content Card -->
                <div class="flex flex-col items-center gap-6 p-4 md:p-6 w-full bg-white rounded-lg">
                    <div class="w-full max-w-md mx-auto text-center">
                        <!-- Icon -->
                        <div class="flex justify-center mb-6">
                            <div class="w-24 h-24 bg-red-50 rounded-full flex items-center justify-center">
                                <i class="las la-ban text-5xl text-red-500"></i>
                            </div>
                        </div>

                        <h2 class="w-full font-bold text-[#FF383C] text-2xl leading-[30px] mb-4">@lang('Tài khoản của bạn đã bị khóa')</h2>

                        <div class="bg-[#FFF6F0] rounded-lg p-6 mb-6 text-left">
                            <p class="text-[#6B7280] text-sm mb-2">@lang('Lý do'):</p>
                            <p class="text-[#272343] text-base leading-6">{{ $user->ban_reason }}</p>
                        </div>

                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 bg-[#FF6F0F] text-white px-6 py-3 rounded-lg font-semibold text-sm hover:bg-orange-600 transition-colors">
                            <i class="las la-home"></i>
                            @lang(' Quay về trang chủ')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection