@extends('Template::layouts.frontend')
@section('content')
    @push('style-lib')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    @endpush
    @push('script-lib')
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    @endpush
    @php
        $gs = gs();
        $seller = $product->seller;
        $shop = @$seller->shop;

        if (!$seller) {
            $contactPhone = $gs->support_phone ?: $gs->hotline;
            $shopName = $gs->site_name;
            $shopLogo = getImage(getFilePath('logoIcon') . '/logo.png');
        } else {
            $contactPhone = $seller->mobile;
            $shopName = __(@$shop->name);
            $shopLogo = getImage(getFilePath('sellerShopLogo') . '/' . @$shop->logo, getFileSize('sellerShopLogo'));
        }

        $breadcrumbItems = [];
        $category = $product->categories->first();
        if ($category) {
            $breadcrumbItems[] = [
                'name' => __($category->name),
                'url' => route('pages', $category->slug),
            ];
        }
        $breadcrumbItems[] = ['name' => __($product->name)];

        // Prepare gallery images
        $galleryImages = [];
        $galleryImages[] = getImage(getFilePath('product') . '/' . $product->main_image);
        foreach ($images as $img) {
            if ($img->image != $product->main_image) {
                $galleryImages[] = getImage(getFilePath('product') . '/' . $img->image);
            }
        }
    @endphp

    <x-breadcrumb :items="$breadcrumbItems" />

    <!-- PRODUCT DETAIL SECTION -->
    <section class="p-detail-section">
        <div class="container">
            <div class="p-detail-layout row g-0" style="gap: 40px;">
                <!-- Left: Images & Shop Info -->
                <div class="p-gallery-column col-12 col-lg-auto" style="width: 450px;">
                    <div class="p-gallery">
                        <div class="main-img-box swiper" id="productGallerySwiper">
                            <div class="swiper-wrapper">
                                @foreach ($galleryImages as $index => $imageUrl)
                                    <div class="swiper-slide">
                                        <img src="{{ $imageUrl }}" class="gallery-slide" alt="Slide {{ $index + 1 }}"
                                            data-fancybox="gallery" data-src="{{ $imageUrl }}" />
                                    </div>
                                @endforeach
                            </div>
                            <!-- Counter & View All -->
                            <div class="gallery-counter">
                                <span class="count-text" id="galleryCountText">1/{{ count($galleryImages) }}</span>
                                <span class="view-all-text cursor-pointer" id="viewAllBtn">@lang('Xem tất cả')</span>
                            </div>
                        </div>
                    </div>

                    <!-- Shop Info Card (Under Image) -->
                    <div class="shop-info-card">
                        <div class="shop-main">
                            <img src="{{ $shopLogo }}" alt="{{ $shopName }}" class="shop-logo" />
                            <div class="shop-details">
                                <h3 class="shop-name">{{ $shopName }}</h3>
                                <div class="shop-rating">
                                    <i class="fa-solid fa-star" style="color: #f59e0b;"></i>
                                    {{ number_format($product->reviews->avg('rating'), 1) ?: '5.0' }}
                                    <span style="color: #64748b; font-weight: normal; margin-left: 5px;">
                                        @lang('Đánh giá') {{ $product->reviews_count ?? 0 }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="shop-actions-right">
                            @if ($contactPhone)
                                <a href="https://zalo.me/{{ preg_replace('/[^0-9]/', '', $contactPhone) }}" target="_blank"
                                    class="shop-icon-btn zalo-btn" title="Chat Zalo">
                                    <img src="{{ asset('assets/images/icon_zalo.svg') }}" alt="Zalo" style="width: 20px;">
                                </a>
                                <a href="tel:{{ preg_replace('/[^0-9]/', '', $contactPhone) }}" class="shop-icon-btn phone-btn"
                                    title="Gọi điện">
                                    <i class="fa-solid fa-phone" style="color: #64748b;"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right: Info -->
                <div class="p-detail-info col">
                    <div class="p-badges-wrapper">
                        @foreach($product->tags as $tag)
                            <div class="p-badge-new {{ $tag->type }}">@lang($tag->name)</div>
                        @endforeach
                    </div>
                    <h1 class="p-detail-name">{{ __($product->name) }}</h1>
                    <div class="p-meta-subtitle">{{ __(@$product->brand->name ?? 'Quảng Phát') }} •
                        {{ $product->created_at->diffForHumans() }}
                    </div>

                    <div class="p-detail-rating-row">
                        <div class="stat-group">
                            <strong>{{ $product->sold ?? 0 }}</strong> @lang('Đã bán')
                        </div>
                        <div class="divider-v"></div>
                        <div class="stat-group rating-stars">
                            <i class="fa-solid fa-star"></i>
                            <strong>{{ number_format($product->reviews->avg('rating'), 1) ?: '5.0' }}</strong>
                        </div>
                        <div class="divider-v"></div>
                        <div class="stat-group">
                            <strong>{{ $product->reviews_count ?? 0 }}</strong> @lang('Đánh giá')
                        </div>
                        @if ($product->track_inventory && $product->total_quantity <= 0)
                            <div class="status-badge stock-out">@lang('Hết hàng')</div>
                        @else
                            <div class="status-badge stock-in">@lang('Còn hàng')</div>
                        @endif
                    </div>

                    <div class="p-detail-price-box">
                        @if ($product->discount_price > 0)
                            <div class="p-detail-price">{{ showAmount($product->discount_price) }}</div>
                            <div class="p-detail-old-price" style="margin-top: 5px;">
                                <span class="old"
                                    style="text-decoration: line-through; color: #94a3b8; font-size: 16px;">{{ showAmount($product->base_price) }}</span>
                                <span class="discount"
                                    style="background: #ef4444; color: white; padding: 2px 6px; border-radius: 4px; font-size: 12px; font-weight: 700;">
                                    -{{ round((($product->base_price - $product->discount_price) / $product->base_price) * 100) }}%
                                    @lang('GIẢM')
                                </span>
                            </div>
                        @else
                            <div class="p-detail-price">{{ showAmount($product->base_price) }}</div>
                        @endif
                    </div>

                    <table class="p-meta-table">
                        <tr>
                            <td class="meta-label">@lang('Mã SKU')</td>
                            <td class="meta-value">: {{ $product->sku ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">@lang('Danh mục')</td>
                            <td class="meta-value">: {{ __(@$category->name ?? 'N/A') }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">@lang('Thương hiệu')</td>
                            <td class="meta-value">: {{ __(@$product->brand->name ?? 'Quảng Phát') }}</td>
                        </tr>
                        @if ($product->meta_keywords && count($product->meta_keywords) > 0)
                            <tr>
                                <td class="meta-label">@lang('Thẻ')</td>
                                <td class="meta-value">:
                                    @foreach ($product->meta_keywords as $keyword)
                                        {{ __($keyword) }}{{ !$loop->last ? ',' : '' }}
                                    @endforeach
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td class="meta-label">@lang('Chia sẻ')</td>
                            <td class="meta-value social-shares">
                                :
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank"><i class="fa-brands fa-linkedin"></i></a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($product->name) }}" target="_blank"><i class="fa-brands fa-x-twitter"></i></a>
                            </td>
                        </tr>
                    </table>

                    <div class="p-condition-text">{{ __($product->condition ?: 'Mới 100%') }}</div>

                    <div class="p-qty-row">
                        <div class="qty-input">
                            <button class="qty-btn btn-minus">-</button>
                            <input type="text" class="qty-val" id="quantity-input" value="1" readonly />
                            <button class="qty-btn btn-plus">+</button>
                        </div>
                    </div>

                    <div class="p-actions-row">
                        <button class="btn-p-action btn-add-cart-outline btn-add-to-cart" data-id="{{ $product->id }}">
                            @lang('Thêm vào giỏ hàng')
                        </button>
                        <button class="btn-p-action btn-buy-now-full btn-buy-now" data-id="{{ $product->id }}">
                            @lang('Mua ngay')
                        </button>
                    </div>

                </div>
            </div>


            <!-- DESCRIPTION BLOCK -->
            <div class="product-description-block mt-5">
                <h2 class="section-title-sm mb-4">@lang('Mô tả sản phẩm')</h2>
                <div class="description-wrapper" id="descWrapper">
                    <div class="prose max-w-none text-[#444] leading-relaxed">
                        {!! $product->description !!}
                    </div>
                    <div class="description-overlay"></div>
                </div>
                <div class="text-center mt-3">
                    <button id="toggleDescBtn" class="btn-show-more">
                        @lang('Xem thêm') <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>
            </div>

            <!-- REVIEWS BLOCK -->
            <div class="product-reviews-block" style="margin-top: 60px;">
                @include('Template::partials.product_reviews', ['product' => $product])
            </div>

            <!-- SIMILAR PRODUCTS -->
            @if ($relatedProducts->count() > 0)
                <div style="margin-top: 80px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                        <h2 class="section-title">@lang('Sản phẩm tương tự')</h2>
                        <a href="{{ route('quang_phat_mall') }}"
                            style="color: var(--primary); font-weight: 600; text-decoration: none; font-size: 15px;">
                            @lang('Xem thêm') <i class="fa-solid fa-arrow-right" style="margin-left: 5px;"></i>
                        </a>
                    </div>
                    <div class="product-grid-5">
                        @foreach ($relatedProducts as $related)
                            @include('Template::partials.product_card', ['product' => $related])
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('script')
    @include('Template::partials.product_detail_script')
@endpush

@push('style')
    <style>
        .cursor-pointer {
            cursor: pointer;
        }

        .hidden {
            display: none !important;
        }

        /* Description Block */
        .section-title-sm {
            font-size: 20px;
            font-weight: 700;
            color: #272343;
            border-left: 4px solid var(--primary);
            padding-left: 15px;
        }
        .description-wrapper {
            position: relative;
            max-height: 400px;
            overflow: hidden;
            transition: max-height 0.5s ease;
        }
        .description-wrapper.expanded {
            max-height: 5000px; /* Large enough to show all content */
        }
        .description-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background: linear-gradient(transparent, #fff);
            pointer-events: none;
            transition: opacity 0.3s;
        }
        .description-wrapper.expanded .description-overlay {
            opacity: 0;
        }
        .btn-show-more {
            background: none;
            border: 1px solid #e2e8f0;
            padding: 8px 30px;
            border-radius: 20px;
            font-weight: 600;
            color: var(--primary);
            transition: all 0.3s;
            cursor: pointer;
        }
        .btn-show-more:hover {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }

        .p-actions-row {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn-p-action {
            flex: 1;
            height: 50px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 15px;
            transition: all 0.3s;
        }

        .btn-add-cart-outline {
            background: #fff;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-add-cart-outline:hover {
            background: var(--primary);
            color: #fff;
        }

        .btn-buy-now-full {
            background: var(--primary);
            border: none;
            color: #fff;
        }

        .btn-buy-now-full:hover {
            background: #e0441a;
            box-shadow: 0 4px 12px rgba(251, 77, 27, 0.3);
        }

        @media (max-width: 768px) {
            .p-actions-row {
                flex-direction: column;
            }
        }
    </style>
@endpush