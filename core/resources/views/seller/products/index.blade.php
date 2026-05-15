@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <div class="bg-[#F7F7F7]">
        <main class="container mx-auto pb-32 pt-10">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Sidebar -->
                <aside class="w-full lg:w-[312px] shrink-0">
                    @include('seller.partials.sidebar')
                </aside>

                <!-- Main Content -->
                <section class="flex-1 min-w-0">
                    <!-- Header -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                        <h1 class="text-2xl font-bold text-[#272343]">@lang('Products')</h1>
                        <a href="{{ route('seller.products.create') }}"
                            class="inline-flex items-center justify-center gap-2 px-[18px] py-2.5 bg-[#FF6F0F] text-white font-bold text-sm rounded-xl shadow-[0px_1px_2px_0_rgba(0,0,0,0.08)] hover:bg-orange-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            @lang('Add product')
                        </a>
                    </div>

                    <div class="bg-white rounded-[12px] p-4 md:p-6 border border-gray-100 min-w-full">
                        <!-- Heading & Search -->
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6 md:mb-8">
                            <div class="text-[#303030] text-[20px] font-semibold leading-[32px] tracking-[-0.4px] hidden md:block">
                                @lang('Products')
                            </div>
                            
                            <div class="w-full lg:w-[400px]">
                                <!-- Search -->
                                <form action="{{ route('seller.products.all') }}" method="GET">
                                    <div class="relative">
                                        <input type="text" name="search" value="{{ request()->search }}"
                                            placeholder="@lang('Search product')"
                                            class="w-full h-[45px] md:h-[49px] pl-12 pr-4 rounded-xl border border-neutral-300 bg-white text-[15px] md:text-[16px] focus:outline-none focus:ring-1 focus:ring-[#FF6F0F]">
                                        <svg class="absolute left-4 top-1/2 -translate-y-1/2"
                                            xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M14.7874 16.0602C13.6797 16.9011 12.2982 17.4002 10.8002 17.4002C7.15512 17.4002 4.2002 14.4453 4.2002 10.8002C4.2002 7.15512 7.15512 4.2002 10.8002 4.2002C14.4453 4.2002 17.4002 7.15512 17.4002 10.8002C17.4002 12.2982 16.9011 13.6797 16.0602 14.7874L19.5366 18.2638C19.8881 18.6153 19.8881 19.1851 19.5366 19.5366C19.1851 19.8881 18.6153 19.8881 18.2638 19.5366L14.7874 16.0602ZM15.6002 10.8002C15.6002 13.4512 13.4512 15.6002 10.8002 15.6002C8.14923 15.6002 6.0002 13.4512 6.0002 10.8002C6.0002 8.14923 8.14923 6.0002 10.8002 6.0002C13.4512 6.0002 15.6002 8.14923 15.6002 10.8002Z"
                                                fill="#8A8A8A" />
                                        </svg>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Table List (Desktop) -->
                        <div class="hidden md:block">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-[#8A8A8A] text-[13px] font-semibold leading-[16px] tracking-[-0.13px] ">
                                        <th class="pb-4 pl-1 font-semibold">
                                            <div class="flex items-center gap-4">
                                                <input type="checkbox" id="selectAll"
                                                    class="w-6 h-6 bg-white rounded-md border-2 border-neutral-300 cursor-pointer focus:ring-0 checked:bg-[#FF6F0F] transition-all">
                                                <span>@lang('Product')</span>
                                            </div>
                                        </th>
                                        <th class="pb-4 px-2 font-semibold text-center">@lang('Price')</th>
                                        <th class="pb-4 px-2 font-semibold text-center">@lang('Status')</th>
                                        <th class="pb-4 px-2 font-semibold text-center">@lang('Sales')</th>
                                        <th class="pb-4 px-2 font-semibold text-center">@lang('Views')</th>
                                        <th class="pb-4 pr-1 text-right">@lang('Actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($products as $product)
                                        <tr class="group hover:bg-gray-50 transition-colors border-t border-[#F1F1F1]">
                                            <!-- Product Info -->
                                            <td class="py-3 pl-1 rounded-l-xl bg-white">
                                                <div class="flex items-center gap-4">
                                                    <input type="checkbox"
                                                        class="product-checkbox w-6 h-6 bg-white rounded-md border-2 border-neutral-300 cursor-pointer focus:ring-0 checked:bg-[#FF6F0F] transition-all"
                                                        value="{{ $product->id }}">
                                                    <div class="flex items-center gap-3">
                                                        <img src="{{ getImage(getFilePath('product') . '/thumb_' . @$product->main_image, getFileSize('product')) }}"
                                                            alt="{{ __($product->name) }}"
                                                            class="w-20 h-20 rounded-lg object-cover bg-gray-200">
                                                        <div class="max-w-[280px] overflow-hidden">
                                                            <a href="{{ route('seller.products.edit', $product->id) }}"
                                                                class="overflow-hidden text-[#272343] overflow-ellipsis whitespace-nowrap text-[14px] font-medium leading-[24px] block">
                                                                {{ strLimit(__($product->name), 60) }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Price -->
                                            <td class="py-3 px-2 text-center bg-white">
                                                <div
                                                    class="rounded-[8px] bg-[#EAF4FF] inline-flex items-center justify-center px-2 py-1">
                                                    <span class="text-[#303030] text-[15px] font-semibold leading-[24px]">
                                                        {{ showAmount($product->base_price, 0, true, false, false) }}
                                                    </span>
                                                </div>
                                            </td>

                                            <!-- Status Label -->
                                            <td class="py-3 px-2 text-center bg-white">
                                                @if ($product->status == 1)
                                                    <div
                                                        class="rounded-[6px] bg-[#E3FFED] inline-flex items-center justify-center px-2 py-0.5">
                                                        <span class="text-[#32A06E] text-[12px] font-medium">@lang('Active')</span>
                                                    </div>
                                                @else
                                                    <div
                                                        class="rounded-[6px] bg-[#FED3D1] inline-flex items-center justify-center px-2 py-0.5">
                                                        <span class="text-[#EF4D2F] text-[12px] font-medium">@lang('Deactive')</span>
                                                    </div>
                                                @endif
                                            </td>

                                            <!-- Sales -->
                                            <td class="py-3 px-2 text-center bg-white">
                                                <div class="inline-flex items-center justify-center px-3 py-1 min-w-[60px]">
                                                    <span class="text-[#8A8A8A] text-[14px] font-semibold">
                                                        @if ($product->track_inventory)
                                                            {{ optional($product->stocks)->sum('quantity') ?? 0 }}
                                                        @else
                                                            ∞
                                                        @endif
                                                    </span>
                                                </div>
                                            </td>

                                            <!-- Views -->
                                            <td class="py-3 px-2 text-center bg-white">
                                                <div class="inline-flex items-center justify-center px-3 py-1 min-w-[60px]">
                                                    <span class="text-[#8A8A8A] text-[14px] font-semibold">
                                                        {{ $product->views ?? 0 }}
                                                    </span>
                                                </div>
                                            </td>

                                            <!-- Actions -->
                                            <td class="py-4 pr-1 text-right bg-white rounded-r-xl relative">
                                                <div class="inline-block text-left">
                                                    <button type="button"
                                                        class="action-menu-btn w-9 h-9 flex items-center justify-center hover:bg-gray-100 rounded-lg transition-colors">
                                                        <svg class="w-6 h-6 text-gray-400" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor">
                                                            <circle cx="12" cy="5" r="1.5" fill="currentColor" />
                                                            <circle cx="12" cy="12" r="1.5" fill="currentColor" />
                                                            <circle cx="12" cy="19" r="1.5" fill="currentColor" />
                                                        </svg>
                                                    </button>

                                                    <!-- Dropdown Menu -->
                                                    <div
                                                        class="action-dropdown absolute right-0 top-full mt-1 w-52 bg-white rounded-xl shadow-[0px_8px_32px_-8px_#00000029,0px_0px_16px_-4px_#0000000d] z-50 hidden overflow-hidden border border-gray-100">
                                                        <div class="flex flex-col items-start p-1.5 self-stretch w-full gap-0.5">
                                                            <!-- Edit -->
                                                            <a href="{{ route('seller.products.edit', $product->id) }}"
                                                                class="flex items-center gap-2 px-3 py-2 self-stretch w-full hover:bg-gray-50 rounded-lg transition-colors">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                                    viewBox="0 0 24 24" fill="none">
                                                                    <path
                                                                        d="M11 20H4C2.89543 20 2 19.1046 2 18V5C2 3.89543 2.89543 3 4 3H16C17.1046 3 18 3.89543 18 5V11"
                                                                        stroke="#303030" stroke-width="2" stroke-linecap="round"
                                                                        stroke-linejoin="round" />
                                                                    <path
                                                                        d="M17.5 14C19.433 14 21 15.567 21 17.5C21 19.433 19.433 21 17.5 21C15.567 21 14 19.433 14 17.5C14 15.567 15.567 14 17.5 14Z"
                                                                        stroke="#303030" stroke-width="2" stroke-linecap="round"
                                                                        stroke-linejoin="round" />
                                                                    <path d="M17.5 15.5V17.5H19.5" stroke="#303030" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                                </svg>
                                                                <span
                                                                    class="text-[#303030] text-[14px] font-semibold">@lang('Edit product')</span>
                                                            </a>

                                                            @if ($product->track_inventory)
                                                                <!-- Stock Management -->
                                                                <a href="{{ route('seller.products.stock.create', $product->id) }}"
                                                                    class="flex items-center gap-2 px-3 py-2 self-stretch w-full hover:bg-gray-50 rounded-lg transition-colors">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                                        viewBox="0 0 24 24" fill="none">
                                                                        <path
                                                                            d="M20 7L12 3L4 7M20 7L12 11M20 7V17L12 21M12 11L4 7M12 11V21M4 7V17L12 21"
                                                                            stroke="#303030" stroke-width="2" stroke-linecap="round"
                                                                            stroke-linejoin="round" />
                                                                    </svg>
                                                                    <span
                                                                        class="text-[#303030] text-[14px] font-semibold">@lang('Manage Stock')</span>
                                                                </a>
                                                            @endif

                                                            <!-- Status Toggle -->
                                                            <div
                                                                class="flex items-center justify-between gap-2 px-3 py-2 self-stretch w-full rounded-lg hover:bg-gray-50 transition-colors">
                                                                <div class="flex items-center gap-2">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                                        viewBox="0 0 24 24" fill="none">
                                                                        <path
                                                                            d="M17 7H7C5.89543 7 5 7.89543 5 9V15C5 16.1046 5.89543 17 7 17H17C18.1046 17 19 16.1046 19 15V9C19 7.89543 18.1046 7 17 7Z"
                                                                            stroke="#8A8A8A" stroke-width="2" />
                                                                        <path
                                                                            d="M16 9H13C12.4477 9 12 9.44772 12 10V14C12 14.5523 12.4477 15 13 15H16C16.5523 15 17 14.5523 17 14V10C17 9.44772 16.5523 9 16 9Z"
                                                                            fill="#8A8A8A" />
                                                                    </svg>
                                                                    <span
                                                                        class="text-[#8A8A8A] text-[13px] font-semibold">@lang('Status')</span>
                                                                </div>
                                                                <div class="flex w-10 items-center btn-status-toggle {{ $product->status == 1 ? 'justify-end bg-[#4188ff]' : 'justify-start bg-gray-200' }} p-0.5 rounded-full overflow-hidden transition-all duration-300 cursor-pointer"
                                                                    data-id="{{ $product->id }}">
                                                                    <div class="w-4 h-4 bg-white rounded-full shadow-sm"></div>
                                                                </div>
                                                            </div>

                                                            <!-- Delete -->
                                                            <a href="javascript:void(0)"
                                                                class="flex items-center gap-2 px-3 py-2 self-stretch w-full rounded-lg hover:bg-red-50 transition-colors confirmationBtn"
                                                                data-question="@lang('Are you sure you want to delete this product?')"
                                                                data-action="{{ route('seller.products.delete', $product->id) }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                                    viewBox="0 0 24 24" fill="none">
                                                                    <path d="M3 6H21" stroke="#EF4D2F" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                                    <path
                                                                        d="M19 6V20C19 21.1046 18.1046 22 17 22H7C5.89543 22 5 21.1046 5 20V6"
                                                                        stroke="#EF4D2F" stroke-width="2" stroke-linecap="round"
                                                                        stroke-linejoin="round" />
                                                                    <path
                                                                        d="M8 6V4C8 2.89543 8.89543 2 10 2H14C15.1046 2 16 2.89543 16 4V6"
                                                                        stroke="#EF4D2F" stroke-width="2" stroke-linecap="round"
                                                                        stroke-linejoin="round" />
                                                                </svg>
                                                                <span
                                                                    class="text-[#EF4D2F] text-[14px] font-semibold">@lang('Delete forever')</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6"
                                                class="text-center py-20 bg-white rounded-xl border border-dashed border-gray-300">
                                                <div class="flex flex-col items-center gap-4">
                                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                    </svg>
                                                    <p class="text-gray-500 text-lg">@lang($emptyMessage ?? 'No products yet')</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Card Layout -->
                        <div class="md:hidden space-y-4">
                            @forelse($products as $product)
                                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm relative">
                                    <div class="flex gap-4">
                                        <!-- Image -->
                                        <div class="w-24 h-24 shrink-0 rounded-xl overflow-hidden bg-gray-100">
                                            <img src="{{ getImage(getFilePath('product') . '/thumb_' . @$product->main_image, getFileSize('product')) }}"
                                                alt="{{ __($product->name) }}"
                                                class="w-full h-full object-cover">
                                        </div>

                                        <!-- Basic Info -->
                                        <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
                                            <div>
                                                <a href="{{ route('seller.products.edit', $product->id) }}" 
                                                   class="text-[14px] font-bold text-[#272343] line-clamp-2 leading-snug mb-1">
                                                    {{ __($product->name) }}
                                                </a>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[#FF6F0F] text-[15px] font-bold">
                                                        {{ showAmount($product->base_price, 0, true, false, false) }}
                                                    </span>
                                                    @if ($product->status == 1)
                                                        <span class="text-[10px] px-1.5 py-0.5 bg-green-50 text-green-600 font-bold rounded uppercase">@lang('Active')</span>
                                                    @else
                                                        <span class="text-[10px] px-1.5 py-0.5 bg-red-50 text-red-600 font-bold rounded uppercase">@lang('Deactive')</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-4 text-[#8A8A8A] text-[11px] font-semibold">
                                                <span class="flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                                    @if ($product->track_inventory)
                                                        {{ optional($product->stocks)->sum('quantity') ?? 0 }}
                                                    @else
                                                        ∞
                                                    @endif
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                                    {{ $product->views ?? 0 }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Action Trigger -->
                                        <div class="absolute top-2 right-2">
                                            <div class="relative inline-block text-left">
                                                <button type="button" class="action-menu-btn w-8 h-8 flex items-center justify-center hover:bg-gray-100 rounded-lg transition-colors">
                                                    <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                        <circle cx="12" cy="5" r="1.5" fill="currentColor" />
                                                        <circle cx="12" cy="12" r="1.5" fill="currentColor" />
                                                        <circle cx="12" cy="19" r="1.5" fill="currentColor" />
                                                    </svg>
                                                </button>
                                                
                                                <!-- Dropdown Menu Mobile -->
                                                <div class="action-dropdown absolute right-0 top-full mt-1 w-48 bg-white rounded-xl shadow-[0px_8px_32px_-8px_#00000029,0px_0px_16px_-4px_#0000000d] z-50 hidden overflow-hidden border border-gray-100">
                                                    <div class="flex flex-col items-start p-1.5 self-stretch w-full gap-0.5">
                                                        <a href="{{ route('seller.products.edit', $product->id) }}" class="flex items-center gap-2 px-3 py-2 self-stretch w-full hover:bg-gray-50 rounded-lg transition-colors">
                                                            <span class="text-[#303030] text-[13px] font-semibold">@lang('Edit product')</span>
                                                        </a>
                                                        @if ($product->track_inventory)
                                                            <a href="{{ route('seller.products.stock.create', $product->id) }}" class="flex items-center gap-2 px-3 py-2 self-stretch w-full hover:bg-gray-50 rounded-lg transition-colors">
                                                                <span class="text-[#303030] text-[13px] font-semibold">@lang('Manage Stock')</span>
                                                            </a>
                                                        @endif
                                                        <div class="flex items-center justify-between gap-2 px-3 py-2 self-stretch w-full rounded-lg hover:bg-gray-50 transition-colors">
                                                            <span class="text-[#8A8A8A] text-[12px] font-semibold">@lang('Status')</span>
                                                            <div class="flex w-9 items-center btn-status-toggle {{ $product->status == 1 ? 'justify-end bg-[#4188ff]' : 'justify-start bg-gray-200' }} p-0.5 rounded-full overflow-hidden transition-all duration-300 cursor-pointer" data-id="{{ $product->id }}">
                                                                <div class="w-3.5 h-3.5 bg-white rounded-full shadow-sm"></div>
                                                            </div>
                                                        </div>
                                                        <a href="javascript:void(0)" class="flex items-center gap-2 px-3 py-2 self-stretch w-full rounded-lg hover:bg-red-50 transition-colors confirmationBtn" 
                                                           data-question="@lang('Are you sure you want to delete this product?')" data-action="{{ route('seller.products.delete', $product->id) }}">
                                                            <span class="text-[#EF4D2F] text-[13px] font-semibold">@lang('Delete forever')</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-gray-300">
                                    <p class="text-gray-500 font-medium">@lang($emptyMessage ?? 'No products yet')</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Pagination -->
                        @if ($products->hasPages())
                            <div class="mt-8 flex justify-center">
                                {{ paginateLinks($products) }}
                            </div>
                        @endif
                    </div>
                </section>
            </div>
        </main>
    </div>

    <x-confirmation-modal />

@endsection

@push('script')
    <script>
        'use strict';
        (function ($) {
            // Action dropdown toggle
            $(document).on('click', '.action-menu-btn', function (e) {
                e.stopPropagation();
                let $dropdown = $(this).siblings('.action-dropdown');
                let $parentTd = $(this).closest('td');

                // Reset other rows
                $('.action-dropdown').not($dropdown).addClass('hidden');
                $('td').css('z-index', '');

                $dropdown.toggleClass('hidden');

                if (!$dropdown.hasClass('hidden')) {
                    $parentTd.css('z-index', '60');
                } else {
                    $parentTd.css('z-index', '');
                }
            });

            $(document).click(function () {
                $('.action-dropdown').addClass('hidden');
            });

            // Status Toggle AJAX
            $('.btn-status-toggle').on('click', function () {
                let $el = $(this);
                let id = $el.data('id');
                let $row = $el.closest('tr'); // Cập nhật tìm theo thẻ tr

                $el.css('opacity', '0.5').css('pointer-events', 'none');

                $.ajax({
                    url: "{{ route('seller.products.status', '') }}/" + id,
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.success) {
                            if (response.status == 1) {
                                $el.removeClass('justify-start bg-gray-200').addClass('justify-end bg-[#4188ff]');
                            } else {
                                $el.removeClass('justify-end bg-[#4188ff]').addClass('justify-start bg-gray-200');
                            }

                            // Optional: Update the status pill in the table row if you want immediate feedback there too
                            location.reload(); // Simplest way to ensure all status labels are updated
                        } else {
                            notify('error', response.message || 'Có lỗi xảy ra');
                        }
                    },
                    error: function () {
                        notify('error', 'Không thể kết nối máy chủ');
                    },
                    complete: function () {
                        $el.css('opacity', '1').css('pointer-events', 'auto');
                    }
                });
            });

            // Checkbox All Logic
            $('#selectAll').on('change', function () {
                $('.product-checkbox').prop('checked', $(this).prop('checked'));
            });

            $('.product-checkbox').on('change', function () {
                if ($('.product-checkbox:checked').length == $('.product-checkbox').length) {
                    $('#selectAll').prop('checked', true);
                } else {
                    $('#selectAll').prop('checked', false);
                }
            });

            // Keyboard shortcut (N for New)
            $(document).keypress(function (e) {
                var unicode = e.charCode ? e.charCode : e.keyCode;
                if (unicode == 78) {
                    window.location = "{{ route('seller.products.create') }}";
                }
            });
        })(jQuery);
    </script>
@endpush