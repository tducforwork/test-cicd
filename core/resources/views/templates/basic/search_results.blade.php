@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="searchPage container pb-[100px] ">
        <div class="product-detail__breadcrumb flex items-center gap-[12px] py-[12px] text-[#606060] text-base">
            <a href="{{ route('home') }}" class="product-detail__breadcrumb-item cursor-pointer">@lang('Home')</a>
            <img src="{{ asset('assets/images/frontend/kviet/detail-product/img.png') }}" class="w-2" alt="arrow" />
            <p class="product-detail__breadcrumb-item cursor-pointer">@lang('Search Results')</p>
        </div>

        <div class="searchContent flex flex-col">
            {{-- Search Header --}}
            <div class="mb-8">
                <h1 class="text-[24px] md:text-[28px] font-bold text-[#272343]">
                    @lang('Search results for'): <span class="text-[#cc0001]">"{{ $searchKey }}"</span>
                </h1>
            </div>

            {{-- Product Section --}}
            <div class="searchProducts flex flex-col gap-6">
                <h2 class="text-[20px] md:text-[24px] font-bold text-[#272343] leading-normal">@lang('Products')</h2>
                @if ($products->count() > 0)
                    <div class="product__grid grid grid-cols-2 md:grid-cols-4 gap-6">
                        @foreach ($products as $item)
                            <div class="product-card group">
                                <div
                                    class="relative overflow-hidden rounded-lg bg-[#f0f0f0] flex items-center justify-center aspect-square">
                                    
                                    <a href="{{ route('product.detail', $item->slug) }}" class="w-full h-full block">
                                        <img src="{{ getImage(getFilePath('product') . '/thumb_' . @$item->main_image, getFileSize('product')) }}"
                                            class="w-full h-full object-cover" alt="{{ __($item->name) }}" />
                                    </a>

                                    {{-- Action Overlay (Always Visible) --}}
                                    <div class="absolute bottom-0 left-0 right-0 py-3 bg-black/24 backdrop-blur-[12px] rounded-b-[6px] text-white flex items-center justify-center gap-2 pointer-events-auto btn-add-to-cart"
                                        data-id="{{ $item->id }}" style="cursor: pointer;">
                                        <div class="flex items-center gap-2 text-white no-underline">
                                            @include($activeTemplate . 'partials.svg.cart')
                                            <span>@lang('Add to Cart')</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="product-card__body py-3">
                                    <a href="{{ route('product.detail', $item->slug) }}">
                                        <p
                                            class="product-card__title text-[#272343] font-medium leading-normal h-[48px] overflow-hidden line-clamp-2">
                                            {{ __($item->name) }}
                                        </p>
                                    </a>
                                    <p class="product-card__price text-[#cc0001] font-bold text-lg">
                                        {{ $item->base_price ? showAmount($item->base_price) : __('Contact') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                @else
                    <div class="py-12 text-center bg-white rounded-lg border border-dashed border-gray-300">
                        <i class="las la-search text-5xl text-gray-300 mb-3 block"></i>
                        <p class="text-[#7A7A7A]">@lang('No products found matching your search.')</p>
                    </div>
                @endif
            </div>

            <div class="searchProducts__divider my-12 h-px w-full bg-[#F2F2F2]"></div>

            {{-- Real Estate Section --}}
            <div class="searchRealEstate flex flex-col gap-6">
                <h2 class="text-[20px] md:text-[24px] font-bold text-[#272343] leading-normal">@lang('Real Estate')</h2>
                @if ($realEstates->count() > 0)
                    <div class="product__grid grid grid-cols-2 md:grid-cols-4 gap-6">
                        @foreach ($realEstates as $item)
                            <div class="real-estate-card group">
                                <div
                                    class="relative overflow-hidden rounded-lg bg-[#f0f0f0] flex items-center justify-center aspect-square">
                                    
                                    <a href="{{ route('real_estate.detail', $item->slug) }}" class="w-full h-full block">
                                        <img src="{{ getImage(getFilePath('product') . '/thumb_' . @$item->main_image, getFileSize('product')) }}"
                                            class="w-full h-full object-cover" alt="{{ __($item->name) }}" />
                                    </a>

                                    {{-- Action Overlay (Always Visible) --}}
                                    <a href="{{ route('real_estate.detail', $item->slug) }}"
                                        class="absolute bottom-0 left-0 right-0 py-3 bg-black/24 backdrop-blur-[12px] rounded-b-[6px] text-white flex items-center justify-center gap-2 pointer-events-auto">
                                        <div class="flex items-center gap-2 text-white no-underline cursor-pointer ">
                                            @include($activeTemplate . 'partials.svg.view')
                                            <span>@lang('View')</span>
                                        </div>
                                    </a>
                                </div>

                                <div class="real-estate-card__body py-3">
                                    <a href="{{ route('real_estate.detail', $item->slug) }}">
                                        <p
                                            class="real-estate-card__title text-[#272343] font-medium leading-normal h-[48px] overflow-hidden line-clamp-2">
                                            {{ __($item->name) }}
                                        </p>
                                    </a>
                                    <p class="real-estate-card__price text-[#cc0001] font-bold text-lg ">
                                        {{ getRePrice($item) }}</p>
                                    <div class="real-estate-card__features">
                                        <div class="real-estate-card__feature">
                                            <object data="{{ asset('assets/images/frontend/kviet/row/row-icon.svg') }}"
                                                class="real-estate-card__feature-icon" type="image/svg+xml"></object>
                                            <p class="real-estate-card__feature-text">{{ __('Beds:') }}</p>
                                            <p class="real-estate-card__feature-number">{{ $item->re_bedrooms }}</p>
                                        </div>

                                        <div class="real-estate-card__feature">
                                            <object data="{{ asset('assets/images/frontend/kviet/row/row-icon2.svg') }}"
                                                class="real-estate-card__feature-icon" type="image/svg+xml"></object>
                                            <p class="real-estate-card__feature-text">{{ __('Baths:') }}</p>
                                            <p class="real-estate-card__feature-number">{{ $item->re_bathrooms }}</p>
                                        </div>

                                        <div class="real-estate-card__feature">
                                            <object data="{{ asset('assets/images/frontend/kviet/row/row-icon3.svg') }}"
                                                class="real-estate-card__feature-icon" type="image/svg+xml"></object>
                                            <p class="real-estate-card__feature-text">{{ __('Area:') }}</p>
                                            <p class="real-estate-card__feature-number">{{ getAmount($item->re_area) }}m²
                                            </p>
                                        </div>
                                    </div>

                                    <div class="product-card__meta real-estate-card__meta">
                                        <object data="{{ asset('assets/images/frontend/kviet/prices/prices-icon.svg') }}"
                                            class="icon product-card__meta-icon" type="image/svg+xml"></object>
                                        <p class="product-card__meta-text">{{ formatLocationName(@$item->province->name) }}</p>
                                        <div class="product-card__meta-divider"></div>
                                        <p class="product-card__meta-text">{{ diffForHumans($item->created_at) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                @else
                    <div class="py-12 text-center bg-white rounded-lg border border-dashed border-gray-300">
                        <i class="las la-building text-5xl text-gray-300 mb-3 block"></i>
                        <p class="text-[#7A7A7A]">@lang('No real estate listings found matching your search.')</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
