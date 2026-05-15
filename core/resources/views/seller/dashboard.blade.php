@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <div class="bg-[#F7F7F7]">
        <main class="container mx-auto pb-32 pt-10">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Sidebar -->
                <aside class="w-full lg:w-[312px] shrink-0">
                    @include('seller.partials.sidebar')
                </aside>

                <!-- Main Content Area -->
                <div class="flex-1 min-w-0">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <h1 class="text-2xl font-semibold text-[#272343]">{{ __($pageTitle) }}</h1>
                    </div>

                    <!-- Profile Card -->
                    <div class="bg-white rounded-[8px] p-6 mb-6">
                        <h2 class="text-xl md:text-[20px] font-bold text-[#272343] mb-6">@lang('Hello'),
                            {{ seller()->fullname }}</h2>

                        <div class="flex flex-col lg:flex-row gap-6 md:gap-[36px]">
                            <img src="{{ getImage(getFilePath('userProfile') . '/' . seller()->image, getFileSize('userProfile')) }}"
                                alt="Avatar"
                                class="w-16 h-16 lg:w-[70px] lg:h-[70px] rounded-full object-cover border-[2px] border-[#ccc]">

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 flex-1">
                                <div>
                                    <p class="text-sm md:text-[16px] font-semibold text-[#272343] mb-1">@lang('Full name')
                                    </p>
                                    <p class="text-[#272343]/70 text-[16px]">{{ seller()->fullname }}</p>
                                </div>
                                <div>
                                    <p class="text-sm md:text-[16px] font-semibold text-[#272343] mb-1">@lang('Phone')</p>
                                    <p class="text-[#272343]/70 text-[16px]">{{ seller()->mobile }}</p>
                                </div>
                                <div>
                                    <p class="text-sm md:text-[16px] font-semibold text-[#272343] mb-1">@lang('Email')</p>
                                    <p class="text-[#272343]/70 text-[16px]">{{ seller()->email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Sales Log Cards -->
                <div class="mb-6 hidden">
                    <h2 class="text-xl font-bold text-[#272343] mb-4">@lang('Sales Log')</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="bg-white rounded-[8px] p-6 border border-[#E6E6E6]">
                            <h3 class="text-2xl font-bold text-[#272343]">{{ showAmount($sale['last_seven_days']) }}</h3>
                            <p class="text-sm text-gray-600 mt-2">@lang('Sale Amount In Last 7 Days')</p>
                        </div>

                        <div class="bg-white rounded-[8px] p-6 border border-[#E6E6E6]">
                            <h3 class="text-2xl font-bold text-[#272343]">{{ showAmount($sale['last_fifteen_days']) }}</h3>
                            <p class="text-sm text-gray-600 mt-2">@lang('Sale Amount In Last 15 Days')</p>
                        </div>

                        <div class="bg-white rounded-[8px] p-6 border border-[#E6E6E6]">
                            <h3 class="text-2xl font-bold text-[#272343]">{{ showAmount($sale['last_thirty_days']) }}</h3>
                            <p class="text-sm text-gray-600 mt-2">@lang('Sale Amount In Last 30 Days')</p>
                        </div>
                    </div>
                </div>

                <!-- Latest Orders -->
                <div class="bg-white rounded-[8px] p-6">
                    <h2 class="text-xl md:text-[20px] leading-relaxed font-bold text-[#272343] mb-6">@lang('Latest Orders')</h2>

                    <!-- Desktop Table -->
                    <div class="hidden md:block">
                        <div class="flex flex-col gap-4">
                            @forelse($latestOrders->take(10) as $item)
                            <div class="flex items-center justify-between p-5 border rounded-[12px] border-[#E6E6E6] hover:bg-gray-50 transition-colors bg-white">
                                <div class="w-[20%]">
                                    <div class="flex flex-col gap-[8px]">
                                        <span class="overflow-hidden text-[#333] overflow-ellipsis text-[14px] font-bold leading-[20px]">#{{ $item->order_number }}</span>
                                        <span class="overflow-hidden text-[#666] overflow-ellipsis text-[14px] not-italic font-bold leading-[20px]">@lang('Order')</span>
                                    </div>
                                </div>
                                <div class="w-[20%] text-center">
                                    <div class="flex flex-col gap-[8px] justify-center">
                                        <span class="overflow-hidden text-[#333] overflow-ellipsis text-[14px] font-bold leading-[20px]">{{ showAmount($item->total_amount) }}</span>
                                        <span class="overflow-hidden text-[#666] overflow-ellipsis text-[14px] not-italic font-bold leading-[20px]">@lang('Amount')</span>
                                    </div>
                                </div>
                                <div class="w-[20%] text-center">
                                    <div class="flex flex-col gap-[8px] justify-center">
                                        <span class="overflow-hidden text-[#333] overflow-ellipsis text-[14px] font-bold leading-[20px]">{{ showDateTime($item->created_at, 'd M, Y') }}</span>
                                        <span class="overflow-hidden text-[#666] overflow-ellipsis text-[14px] not-italic font-bold leading-[20px]">@lang('Date')</span>
                                    </div>
                                </div>
                                <div class="w-[20%] text-center">
                                    <div class="flex flex-col gap-[8px] justify-center">
                                        {!! $item->statusBadge !!}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <a href="{{ route('seller.order.details', $item->id) }}"
                                        class="overflow-hidden text-[#2563EB] align-middle overflow-ellipsis text-[14px] font-bold leading-[20px]">@lang('View Order')</a>
                                </div>
                            </div>
                            @empty
                            <div class="py-12 text-center text-gray-400 border border-[#E6E6E6] rounded-[12px]">
                                @lang('No orders found')
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="md:hidden space-y-4">
                        @forelse($latestOrders->take(10) as $item)
                        <div class="border border-gray-200 rounded-xl p-4 space-y-3">
                            <div>
                                <span class="font-bold text-gray-800">#{{ $item->order_number }}</span>
                                <span class="overflow-hidden text-[#666] overflow-ellipsis text-[14px] not-italic font-bold leading-[20px]">@lang('Order')</span>
                                <div class="mt-1">{!! $item->statusBadge !!}</div>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div><span class="text-gray-500">@lang('Amount'):</span> <span class="font-semibold">{{ showAmount($item->total_amount) }}</span></div>
                                <div><span class="text-gray-500">@lang('Date'):</span> <span class="font-semibold">{{ showDateTime($item->created_at, 'd M, Y') }}</span></div>
                            </div>
                            <a href="{{ route('seller.order.details', $item->id) }}"
                                class="block text-center bg-blue-50 text-[#2563EB] font-bold text-sm py-2 rounded-lg hover:bg-blue-100 transition-colors">
                                @lang('View Order')
                            </a>
                        </div>
                        @empty
                            <div class="py-12 text-center text-gray-400 border border-[#E6E6E6] rounded-[12px]">
                                @lang('No orders found')
                            </div>
                        @endforelse
                    </div>
                </div>

                </div>{{-- end flex-1 --}}
            </div>{{-- end flex flex-col --}}
        </main>
    </div>{{-- end bg-[#F7F7F7] --}}
@endsection

    @push('style')
        <style>
            /* Alert styles */
            .alert {
                display: block;
                padding: 20px;
                border-radius: 5px;
            }

            .alert-warning {
                border: 1px solid hsla(29, 100%, 53%, 0.50);
            }

            .alert-danger {
                border: 1px solid hsla(0, 83%, 53%, 0.50);
            }

            .alert-info {
                border: 1px solid hsla(203, 89%, 53%, 0.50);
            }

            /* Stats card icon circle */
            .bg-orange-100 {
                background-color: #fff4e6;
            }

            .text-orange-600 {
                color: #ff6f0f;
            }

            .bg-green-100 {
                background-color: #f0fdf4;
            }

            .text-green-600 {
                color: #16a34a;
            }

            .bg-yellow-100 {
                background-color: #fefce8;
            }

            .text-yellow-600 {
                color: #ca8a04;
            }

            .bg-blue-100 {
                background-color: #eff6ff;
            }

            .text-blue-600 {
                color: #2563eb;
            }

            .bg-indigo-100 {
                background-color: #eef2ff;
            }

            .text-indigo-600 {
                color: #4f46e5;
            }

            .bg-red-100 {
                background-color: #fef2f2;
            }

            .text-red-600 {
                color: #dc2626;
            }
        </style>
    @endpush
