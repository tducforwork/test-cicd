@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="bg-[#F7F7F7]">
        <main class="container mx-auto pb-32 pt-10">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Sidebar -->
                <aside class="w-full lg:w-[312px] shrink-0">
                    @include($activeTemplate . 'user.partials.sidebar')
                </aside>

                <!-- Main Content Area -->
                <div class="flex-1 min-w-0">
                    <!-- Product Section -->
                    <div class="bg-white rounded-lg mb-6">
                        <div class="p-4 md:p-6">
                            <h2 class="text-xl text-[#272343] md:text-[20px] font-bold leading-[150%] mb-4">
                                @lang('Products')
                            </h2>
                            @if ($wishlistProducts->count() > 0)
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-[17px]">
                                    @foreach ($wishlistProducts as $item)
                                        <div class="product-card group js-wishlist-item" data-id="{{ $item->id }}">
                                            <div
                                                class="relative overflow-hidden rounded-lg bg-[#f0f0f0] flex items-center justify-center aspect-square">
                                                <a href="javascript:void(0)"
                                                    class="absolute top-3 right-3 w-8 h-8 bg-black/25 backdrop-blur-[12px] rounded-[4px] !flex items-center justify-center text-white z-10 transition-all hover:bg-red-500 btn-remove-wishlist"
                                                    data-id="{{ $item->id }}" title="{{ __('Remove from wishlist') }}">
                                                    <i class="las la-trash-alt"></i>
                                                </a>

                                                <a href="{{ route('product.detail', $item->product->slug) }}"
                                                    class="w-full h-full block">
                                                    <img src="{{ getImage(getFilePath('product') . '/thumb_' . @$item->product->main_image, getFileSize('product')) }}"
                                                        class="w-full h-full object-cover" alt="{{ __($item->product->name) }}" />
                                                </a>

                                                <div
                                                    class="absolute bottom-0 left-0 right-0 py-3 bg-black/24 backdrop-blur-[12px] rounded-b-[6px] text-white flex items-center justify-center gap-2 pointer-events-auto">
                                                    <div class="flex items-center gap-2 text-[#FFF] text-[14px] md:text-[16px] font-medium leading-[110%] no-underline cursor-pointer btn-add-to-cart"
                                                        data-id="{{ $item->product->id }}">
                                                        <svg class="hidden md:block" xmlns="http://www.w3.org/2000/svg" width="22"
                                                            height="22" viewBox="0 0 22 22" fill="none">
                                                            <path
                                                                d="M2.52051 2.98047L4.42717 3.31047L5.30992 13.8274C5.34383 14.2412 5.53253 14.6271 5.83837 14.9079C6.14421 15.1888 6.54469 15.344 6.95992 15.3426H16.9608C17.3583 15.3431 17.7427 15.1999 18.0432 14.9396C18.3436 14.6792 18.54 14.3191 18.5961 13.9255L19.4669 7.91397C19.4901 7.75409 19.4816 7.59121 19.4418 7.43463C19.402 7.27806 19.3318 7.13086 19.2351 7.00145C19.1384 6.87205 19.0171 6.76297 18.8782 6.68047C18.7393 6.59797 18.5855 6.54366 18.4256 6.52064C18.3669 6.51422 4.73334 6.50964 4.73334 6.50964"
                                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                            <path d="M12.9473 9.89648H15.4892" stroke="white" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M6.55737 18.52C6.62459 18.5171 6.6917 18.5279 6.75466 18.5516C6.81762 18.5753 6.87513 18.6116 6.92371 18.6581C6.9723 18.7047 7.01097 18.7606 7.03738 18.8224C7.06379 18.8843 7.07741 18.9509 7.07741 19.0182C7.07741 19.0855 7.06379 19.1521 7.03738 19.214C7.01097 19.2758 6.9723 19.3317 6.92371 19.3783C6.87513 19.4248 6.81762 19.4611 6.75466 19.4848C6.6917 19.5085 6.62459 19.5193 6.55737 19.5164C6.42893 19.5109 6.30759 19.456 6.21866 19.3632C6.12972 19.2703 6.08008 19.1468 6.08008 19.0182C6.08008 18.8896 6.12972 18.7661 6.21866 18.6732C6.30759 18.5804 6.42893 18.5255 6.55737 18.52Z"
                                                                fill="white" stroke="white" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M16.898 18.5215C17.0305 18.5215 17.1576 18.5741 17.2513 18.6678C17.345 18.7615 17.3976 18.8886 17.3976 19.0211C17.3976 19.1536 17.345 19.2806 17.2513 19.3743C17.1576 19.468 17.0305 19.5206 16.898 19.5206C16.7655 19.5206 16.6385 19.468 16.5448 19.3743C16.4511 19.2806 16.3984 19.1536 16.3984 19.0211C16.3984 18.8886 16.4511 18.7615 16.5448 18.6678C16.6385 18.5741 16.7655 18.5215 16.898 18.5215Z"
                                                                fill="white" stroke="white" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                        <span>{{ __('Add to Cart') }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="product-card__body p-3">
                                                <a href="{{ route('product.detail', $item->product->slug) }}">
                                                    <p
                                                        class="product-card__title text-[#272343] font-medium leading-normal overflow-hidden h-fit min-h-fit line-clamp-2">
                                                        {{ __($item->product->name) }}
                                                    </p>
                                                </a>
                                                <p class="text-[#CC0001] text-[18px] font-semibold leading-[110%] capitalize">
                                                    {{ $item->product->base_price ? showAmount($item->product->base_price) : 'Contact' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="py-12 text-center">
                                    <i class="las la-heart text-5xl text-gray-300 mb-3"></i>
                                    <p class="text-[#7A7A7A]">{{ __('No products in your wishlist.') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="h-px w-full bg-[#F2F2F2] my-3"></div>

                    <!-- Real Estate Section -->
                    <div class="bg-white rounded-lg">
                        <div class="p-4 md:p-6">
                            <h2 class="text-xl text-[#272343] md:text-[20px] font-bold leading-[150%] mb-4">
                                @lang('Real estate')
                            </h2>
                            @if ($wishlistRealEstate->count() > 0)
                                <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach ($wishlistRealEstate as $item)
                                        <div class="real-estate-card group js-wishlist-item rounded-[6px] overflow-hidden"
                                            data-id="{{ $item->id }}">
                                            <div
                                                class="relative overflow-hidden bg-[#f0f0f0] flex items-center rounded-[6px] justify-center aspect-square">
                                                <a href="javascript:void(0)"
                                                    class="absolute top-3 right-3 w-8 h-8 bg-black/25 backdrop-blur-[12px] rounded-[4px] !flex items-center justify-center text-white z-10 transition-all hover:bg-red-500 btn-remove-wishlist"
                                                    data-id="{{ $item->id }}" title="{{ __('Remove from wishlist') }}">
                                                    <i class="las la-trash-alt"></i>
                                                </a>

                                                <a href="{{ route('real_estate.detail', $item->product->slug) }}"
                                                    class="w-full h-full block">
                                                    <img src="{{ getImage(getFilePath('product') . '/thumb_' . @$item->product->main_image, getFileSize('product')) }}"
                                                        class="w-full h-full object-cover" alt="{{ __($item->product->name) }}" />
                                                </a>
                                            </div>

                                            <div class="real-estate-card__body">
                                                <a href="{{ route('real_estate.detail', $item->product->slug) }}">
                                                    <p
                                                        class="overflow-hidden text-[#272343] overflow-ellipsis whitespace-nowrap text-[14px] md:text-[16px] capitalize font-medium leading-normal line-clamp-2">
                                                        {{ __($item->product->name) }}
                                                    </p>
                                                </a>
                                                <p
                                                    class="text-[#CC0001] text-[14px] md:text-[18px] not-italic font-semibold leading-[110%] capitalize">
                                                    {{ getRePrice($item->product) }}
                                                </p>

                                                <div
                                                    class="flex flex-col md:flex-row items-start md:items-center gap-2 md:gap-4 text-sm text-[#6B7280]">
                                                    <span class="flex items-center gap-1">
                                                        <i class="las la-bed text-[#FF6F0F]"></i>
                                                        {{ __('Beds') }}: {{ $item->product->re_bedrooms }}
                                                    </span>
                                                    <span class="flex items-center gap-1">
                                                        <i class="las la-bath text-[#FF6F0F]"></i>
                                                        {{ __('Baths') }}: {{ $item->product->re_bathrooms }}
                                                    </span>
                                                    <span class="flex items-center gap-1">
                                                        <i class="las la-ruler-combined text-[#FF6F0F]"></i>
                                                        {{ getAmount($item->product->re_area) }}m²
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="py-12 text-center">
                                    <i class="las la-building text-5xl text-gray-300 mb-3"></i>
                                    <p class="text-[#7A7A7A]">{{ __('No real estate listings in your wishlist.') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection

@push('script')
    <script>
        (function ($) {
            "use strict";

            // Remove from wishlist
            $('.btn-remove-wishlist').on('click', function () {
                const id = $(this).data('id');
                const $item = $(this).closest('.js-wishlist-item');

                $.ajax({
                    url: "{{ route('wishlist.remove', '') }}/" + id,
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.status) {
                            $item.fadeOut(300, function () {
                                $(this).remove();
                            });
                            notify('success', response.message);
                        } else {
                            notify('error', response.message);
                        }
                    }
                });
            });

        })(jQuery);
    </script>
@endpush