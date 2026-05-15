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
                    <div class="bg-white rounded-[12px] p-6 border border-gray-100">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                            <h1 class="text-2xl font-bold text-[#272343]">@lang('Bất động sản')</h1>
                            <a href="{{ route('seller.products.real_estate.create') }}"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#FF6F0F] text-white font-bold text-sm rounded-[12px] shadow-[inset_0_1px_2px_0_rgba(255,255,255,0.40),inset_0_-1px_2px_0_rgba(0,0,0,0.24),0_1px_2px_0_rgba(0,0,0,0.08)] hover:bg-orange-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                @lang('Đăng tin mới')
                            </a>
                        </div>

                        <!-- Search -->
                        <form action="{{ route('seller.products.real_estate.index') }}" method="GET" class="mb-6">
                            <div class="flex flex-col sm:flex-row items-center gap-3">
                                <div class="flex-1 w-full relative">
                                    <input type="text" name="search" value="{{ request()->search }}"
                                        placeholder="@lang('Tìm kiếm tin đăng bất động sản...')"
                                        class="w-full h-[49px] pl-12 pr-4 rounded-[12px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-[#FF6F0F]">
                                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <button type="submit"
                                    class="w-full sm:w-auto h-[49px] px-6 bg-[#272343] text-white font-bold rounded-[12px] hover:opacity-90 transition-opacity">
                                    @lang('Tìm kiếm')
                                </button>
                            </div>
                        </form>

                        <!-- List Header -->
                        <div class="hidden lg:flex items-center px-4 py-3 bg-[#f1f1f1] rounded-[12px] mb-2">
                            <div class="flex-1">
                                <span class="text-sm font-semibold text-[#8a8a8a]">@lang('Thông tin bất động sản')</span>
                            </div>
                            <div class="w-36 ">
                                <span class="text-sm font-semibold text-[#8a8a8a]">@lang('Giá')</span>
                            </div>
                            <div class="w-28 ">
                                <span class="text-sm font-semibold text-[#8a8a8a]">@lang('Diện tích')</span>
                            </div>
                            <div class="w-32 ">
                                <span class="text-sm font-semibold text-[#8a8a8a]">@lang('Phòng / Tầng')</span>
                            </div>
                            <div class="w-24 ">
                                <span class="text-sm font-semibold text-[#8a8a8a]">@lang('Trạng thái')</span>
                            </div>
                            <div class="w-20"></div>
                        </div>

                        <!-- Items -->
                        @forelse($products as $product)
                            <div
                                class="flex flex-col lg:flex-row lg:items-center gap-4 px-4 py-4 lg:py-3 border-b lg:border-none last:border-none lg:hover:bg-gray-50 lg:rounded-[12px] mb-1 group relative">
                                <!-- Info -->
                                <div class="flex-1 flex items-center gap-3">
                                    <div class="flex items-center gap-3 min-w-0 w-full">
                                        <div
                                            class="w-12 h-12 shrink-0 rounded-lg bg-gray-100 flex items-center justify-center text-kviet-orange">
                                            <i class="las la-home text-2xl"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('seller.products.real_estate.edit', $product->id) }}"
                                                class="text-[15px] lg:text-sm font-bold lg:font-medium text-[#272343] hover:text-[#FF6F0F] transition-colors block lg:truncate">
                                                {{ $product->name }}
                                            </a>
                                            <span class="text-xs text-muted block mt-0.5">
                                                <i class="la la-map-marker"></i> {{ $product->province?->name ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-wrap lg:contents gap-y-3">
                                    <!-- Price -->
                                    <div class="w-1/2 lg:w-36 lg:text-center shrink-0">
                                        <div class="lg:hidden text-[10px] uppercase text-muted mb-1">@lang('Giá')</div>
                                        @if($product->re_price_type == 'negotiable')
                                            <span
                                                class="inline-flex items-center px-3 py-1 bg-[#eaf4ff] rounded-lg text-sm font-semibold text-[#303030]">
                                                @lang('Thỏa thuận')
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 bg-[#eaf4ff] rounded-lg text-sm font-semibold text-[#303030]">
                                                @if($product->re_price_to > 0)
                                                    {{ showAmount($product->re_price_from) }} -
                                                    {{ showAmount($product->re_price_to) }}{{ $product->re_type == 'rent' ? $product->rePricePeriodSuffix() : '' }}
                                                @else
                                                    {{ showAmount($product->re_price_from) }}{{ $product->re_type == 'rent' ? $product->rePricePeriodSuffix() : '' }}
                                                @endif
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Area -->
                                    <div class="w-1/2 lg:w-28 lg:text-center shrink-0">
                                        <div class="lg:hidden text-[10px] uppercase text-muted mb-1">@lang('Diện tích')</div>
                                        <span class="text-sm text-[#303030] font-semibold lg:font-normal">
                                            @if($product->re_area_to > 0)
                                                {{ getAmount($product->re_area) }} - {{ getAmount($product->re_area_to) }} m²
                                            @else
                                                {{ getAmount($product->re_area) }} m²
                                            @endif
                                        </span>
                                    </div>

                                    <!-- Rooms -->
                                    <div class="w-1/2 lg:w-32 lg:text-center shrink-0">
                                        <div class="lg:hidden text-[10px] uppercase text-muted mb-1">@lang('Phòng / Tầng')</div>
                                        <div class="flex items-center lg:justify-center gap-1 text-xs text-[#666]">
                                            @if($product->re_bedrooms > 0)
                                                <span class="flex items-center gap-0.5"><i class="la la-bed"></i>
                                                    {{ $product->re_bedrooms }}</span>
                                            @endif
                                            @if($product->re_bathrooms > 0)
                                                <span class="flex items-center gap-0.5"><i class="la la-bath"></i>
                                                    {{ $product->re_bathrooms }}</span>
                                            @endif
                                            @if($product->re_floor > 0)
                                                <span class="flex items-center gap-0.5"><i class="la la-building"></i>
                                                    {{ $product->re_floor }}</span>
                                            @endif
                                            @if(!$product->re_bedrooms && !$product->re_bathrooms && !$product->re_floor)
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="w-1/2 lg:w-24 lg:text-center shrink-0">
                                        <div class="lg:hidden text-[10px] uppercase text-muted mb-1">@lang('Trạng thái')</div>
                                        @if ($product->status == 1)
                                            <span
                                                class="inline-flex items-center justify-center px-2 py-1 bg-[#e3ffed] rounded-md text-xs font-semibold text-[#32a06e]">
                                                @lang('Hiển thị')
                                            </span>
                                        @elseif($product->status == 0)
                                            <span
                                                class="inline-flex items-center justify-center px-2 py-1 bg-[#fff3cd] rounded-md text-xs font-semibold text-[#856404]">
                                                @lang('Chờ duyệt')
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center justify-center px-2 py-1 bg-[#fed3d1] rounded-md text-xs font-semibold text-[#ef4d2f]">
                                                @lang('Bị từ chối')
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="w-full lg:w-20 mt-2 lg:mt-0 pt-3 lg:pt-0 border-t lg:border-none border-dashed">
                                    <div class="flex items-center lg:justify-center gap-2">
                                        <a href="{{ route('seller.products.real_estate.edit', $product->id) }}"
                                            class="flex-1 lg:flex-none w-9 h-9 flex items-center justify-center bg-gray-100 rounded-lg hover:bg-[#FF6F0F] hover:text-white transition-colors gap-2 lg:gap-0"
                                            title="@lang('Sửa')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span class="lg:hidden text-sm font-medium">@lang('Chỉnh sửa')</span>
                                        </a>
                                        <button type="button"
                                            class="flex-1 lg:flex-none w-9 h-9 flex items-center justify-center bg-gray-100 rounded-lg hover:bg-red-500 hover:text-white transition-colors confirmationBtn gap-2 lg:gap-0"
                                            data-question="@lang('Bạn có chắc chắn muốn xóa tin đăng này?')"
                                            data-action="{{ route('seller.products.delete', $product->id) }}"
                                            title="@lang('Xóa')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span class="lg:hidden text-sm font-medium">@lang('Xóa bỏ')</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                <p class="text-gray-500 text-lg">@lang('Chưa có tin đăng bất động sản nào')</p>
                                <a href="{{ route('seller.products.real_estate.create') }}"
                                    class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 bg-[#FF6F0F] text-white font-bold text-sm rounded-[12px] hover:bg-orange-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    @lang('Đăng tin BĐS đầu tiên')
                                </a>
                            </div>
                        @endforelse

                        <!-- Pagination -->
                        @if ($products->hasPages())
                            <div class="mt-6">
                                {{ paginateLinks($products) }}
                            </div>
                        @endif
                </section>
            </div>
        </main>
    </div>

    <x-confirmation-modal />

@endsection