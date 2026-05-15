@php
  $featuredProductsContent = getContent('featured_products.content', true);
  $categoryIds = (array) @$featuredProductsContent->data_values->category_ids;
  $featuredProductsCategories = \App\Models\Category::whereIn('id', $categoryIds)
    ->with([
      'products' => function ($q) {
        $q->publishable()->whereHas('productType', function ($type) {
          $type->where('slug', 'san-pham-noi-bat');
        })->with(['brands', 'reviews', 'orderDetails'])->limit(15);
      }
    ])->get();
  $firstCategory = $featuredProductsCategories->first();
@endphp
<!-- SẢN PHẨM NỔI BẬT -->
<section class="featured-products-section py-lg-5 py-4">
  <div class="container">
    <div class="product-section-header">
      <div style="display: flex; align-items: center; gap: 40px">
        <h2 class="section-title">{{ __(@$featuredProductsContent->data_values->title) }}</h2>
        <div class="sale-tabs">
          @foreach($featuredProductsCategories as $category)
            <div class="tab-btn @if($loop->first) active @endif" data-tab="feat-cat-{{ $category->id }}">
              {{ __($category->name) }}
            </div>
          @endforeach
        </div>
      </div>
      <div style="display: flex; align-items: center; gap: 20px">
        <a href="{{ route('products') }}" style="
                font-size: 13px;
                font-weight: 700;
                color: var(--primary);
                text-decoration: underline;
              ">XEM THÊM</a>
      </div>
    </div>

    <div class="shopee-cat-wrap">
      @foreach($featuredProductsCategories as $category)
        <div class="tab-content @if($loop->first) active @endif" id="feat-cat-{{ $category->id }}">
          <button class="shopee-cat-arrow featured-prev" aria-label="Trước">
            <i class="fa-solid fa-chevron-left"></i>
          </button>
          <button class="shopee-cat-arrow featured-next" aria-label="Sau">
            <i class="fa-solid fa-chevron-right"></i>
          </button>
          <div class="product-slider-wrapper swiper featured-products-slider">
            <div class="swiper-wrapper">
              @forelse($category->products as $product)
                <div class="swiper-slide">
                  @include($activeTemplate . 'partials.product_card', ['product' => $product])
                </div>
              @empty
                <div class="text-center w-100 py-5">
                  <p class="text-muted">@lang('Chưa có sản phẩm nào trong danh mục này.')</p>
                </div>
              @endforelse
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

@push('style')
  <style>
    .featured-products-section .tab-content {
      display: none;
    }

    .featured-products-section .tab-content.active {
      display: block;
    }
  </style>
@endpush

@push('script')
  <script>
    (function ($) {
      "use strict";

      // Tab Switching
      $('.featured-products-section .tab-btn').on('click', function () {
        const tabId = $(this).data('tab');
        $('.featured-products-section .tab-btn').removeClass('active');
        $(this).addClass('active');
        $('.featured-products-section .tab-content').removeClass('active');
        $('#' + tabId).addClass('active');

        // Re-update swiper if needed
        if (window.featuredProductsSwipers) {
          window.featuredProductsSwipers.forEach(s => s.update());
        }
      });

      // Initialize Swipers
      window.featuredProductsSwipers = [];
      $('.featured-products-slider').each(function () {
        const parent = $(this).closest('.tab-content');
        const swiper = new Swiper(this, {
          slidesPerView: 2,
          spaceBetween: 10,
          loop: false,
          navigation: {
            nextEl: parent.find(".featured-next")[0],
            prevEl: parent.find(".featured-prev")[0],
          },
          breakpoints: {
            480: { slidesPerView: 2, spaceBetween: 10 },
            576: { slidesPerView: 3, spaceBetween: 15 },
            768: { slidesPerView: 4, spaceBetween: 15 },
            992: { slidesPerView: 5, spaceBetween: 15 },
            1200: { slidesPerView: 5, spaceBetween: 15 },
          },
        });
        window.featuredProductsSwipers.push(swiper);
      });

    })(jQuery);
  </script>
@endpush
