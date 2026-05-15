@php
  $topDealContent = getContent('top_deal.content', true);
  $topDealProducts = \App\Models\Product::publishable()->where('is_topdeal', 1)->with(['brands', 'reviews', 'orderDetails', 'tags'])->limit(15)->get();
@endphp

@if($topDealProducts->count() > 0)
<!-- TOP DEAL CHO BẠN -->
<section class="top-deal-section py-lg-4 py-4" style="background-color: #fffaf0;">
  <div class="container">
    <div class="flash-sale-header">
      <div class="d-flex align-items-center gap-3">
        <h2 class="section-title mb-0">{{ __(@$topDealContent->data_values->title ?? 'Top deal cho bạn') }}</h2>
        <div class="p-tag orange p-tag-large">
          <i class="fa-solid fa-fire-flame-curved me-1"></i> GIÁ TỐT NHẤT
        </div>
      </div>
      <a href="{{ route('quang_phat_mall') }}" class="view-more-link">XEM TẤT CẢ</a>
    </div>

    <div class="shopee-cat-wrap">
      <button class="shopee-cat-arrow prev top-deal-prev" aria-label="Trước">
        <i class="fa-solid fa-chevron-left"></i>
      </button>
      <button class="shopee-cat-arrow next top-deal-next" aria-label="Sau">
        <i class="fa-solid fa-chevron-right"></i>
      </button>
      <div class="product-slider-wrapper swiper top-deal-slider">
        <div class="swiper-wrapper">
          @foreach($topDealProducts as $product)
            <div class="swiper-slide">
              @include($activeTemplate . 'partials.product_card', ['product' => $product])
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

@push('script')
  <script>
    (function ($) {
      "use strict";
      new Swiper(".top-deal-slider", {
        slidesPerView: 2,
        spaceBetween: 10,
        loop: true,
        navigation: {
          nextEl: ".top-deal-next",
          prevEl: ".top-deal-prev",
        },
        breakpoints: {
          480: { slidesPerView: 2, spaceBetween: 10 },
          576: { slidesPerView: 3, spaceBetween: 15 },
          768: { slidesPerView: 4, spaceBetween: 15 },
          992: { slidesPerView: 5, spaceBetween: 15 },
          1200: { slidesPerView: 5, spaceBetween: 15 },
        },
      });
    })(jQuery);
  </script>
@endpush
@endif