@php
  $mostSearchedContent = getContent('most_searched.content', true);
  $categoryIds = (array) @$mostSearchedContent->data_values->category_ids;
  $mostSearchedCategories = \App\Models\Category::whereIn('id', $categoryIds)
    ->with([
      'products' => function ($q) {
        $q->publishable()->where('is_search', 1)->with(['brands', 'reviews', 'orderDetails', 'tags'])->limit(15);
      }
    ])->get();
  $firstCategory = $mostSearchedCategories->first();
@endphp
<!-- TÌM KIẾM NHIỀU NHẤT -->
<section class="new-products-section py-lg-5 py-4">
  <div class="container">
    <div class="product-section-header">
      <div style="display: flex; align-items: center; gap: 40px">
        <h2 class="section-title">{{ __(@$mostSearchedContent->data_values->title) }}</h2>
        <div class="sale-tabs">
          @foreach($mostSearchedCategories as $category)
            <div class="tab-btn @if($loop->first) active @endif" data-tab="search-cat-{{ $category->id }}">
              {{ __($category->name) }}
            </div>
          @endforeach
        </div>
      </div>
      <div style="display: flex; align-items: center; gap: 20px">
        <a href="{{ route('quang_phat_mall') }}" class="view-more-link">XEM THÊM</a>
      </div>
    </div>

    <div class="shopee-cat-wrap">
      @foreach($mostSearchedCategories as $category)
        <div class="tab-content @if($loop->first) active @endif" id="search-cat-{{ $category->id }}">
          <button class="shopee-cat-arrow prev new-prev" aria-label="Trước">
            <i class="fa-solid fa-chevron-left"></i>
          </button>
          <button class="shopee-cat-arrow next new-next" aria-label="Sau">
            <i class="fa-solid fa-chevron-right"></i>
          </button>
          <div class="product-slider-wrapper swiper new-products-slider">
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
    .tab-content {
      display: none;
    }

    .tab-content.active {
      display: block;
    }
  </style>
@endpush

@push('script')
  <script>
    (function ($) {
      "use strict";

      // Tab Switching
      $('.new-products-section .tab-btn').on('click', function () {
        const tabId = $(this).data('tab');
        $('.new-products-section .tab-btn').removeClass('active');
        $(this).addClass('active');
        $('.new-products-section .tab-content').removeClass('active');
        $('#' + tabId).addClass('active');

        // Re-update swiper if needed
        if (window.mostSearchedSwipers) {
          window.mostSearchedSwipers.forEach(s => s.update());
        }
      });

      // Initialize Swipers
      window.mostSearchedSwipers = [];
      $('.new-products-section .new-products-slider').each(function () {
        const parent = $(this).closest('.tab-content');
        const swiper = new Swiper(this, {
          slidesPerView: 2,
          spaceBetween: 10,
          loop: true,
          navigation: {
            nextEl: parent.find(".new-next")[0],
            prevEl: parent.find(".new-prev")[0],
          },
          breakpoints: {
            480: { slidesPerView: 2, spaceBetween: 10 },
            576: { slidesPerView: 3, spaceBetween: 15 },
            768: { slidesPerView: 4, spaceBetween: 15 },
            992: { slidesPerView: 5, spaceBetween: 15 },
            1200: { slidesPerView: 5, spaceBetween: 15 },
          },
        });
        window.mostSearchedSwipers.push(swiper);
      });

    })(jQuery);
  </script>
@endpush