@php
  $flashSaleContent = getContent('flash_sale.content', true);
  $promotion = \App\Models\Promotion::where('id', @$flashSaleContent->data_values->promotion_id)->active()->first();
  $flashSaleProducts = $promotion ? $promotion->products()->active()->with(['brands', 'productTypes', 'reviews', 'orderDetails', 'tags'])->limit(10)->get() : collect([]);
@endphp
<!-- FLASH SALE -->
<section class="flash-sale-section py-lg-4 py-4">
  <div class="container">
    <div class="flash-sale-header">
      <div class="d-flex align-items-center gap-3">
        <h2 class="section-title mb-0">{{ __(@$flashSaleContent->data_values->title) }}</h2>
        <div class="flash-countdown">
          <span class="countdown-item" id="timer-days">00</span>
          <span class="countdown-sep">:</span>
          <span class="countdown-item" id="timer-hours">00</span>
          <span class="countdown-sep">:</span>
          <span class="countdown-item" id="timer-minutes">00</span>
          <span class="countdown-sep">:</span>
          <span class="countdown-item" id="timer-seconds">00</span>
        </div>
      </div>
      <a href="{{ route('products') }}" class="view-more-link">@lang('XEM THÊM')</a>
    </div>

    <div class="shopee-cat-wrap">
      <button class="shopee-cat-arrow flash-prev" aria-label="Trước">
        <i class="fa-solid fa-chevron-left"></i>
      </button>
      <button class="shopee-cat-arrow flash-next" aria-label="Sau">
        <i class="fa-solid fa-chevron-right"></i>
      </button>
      <div class="product-slider-wrapper swiper flash-sale-slider">
        <div class="swiper-wrapper">
          @foreach($flashSaleProducts as $product)
            <div class="swiper-slide">
              @include($activeTemplate . 'partials.product_card', ['product' => $product, 'isFlashSale' => true])
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

@if($promotion)
  @push('script')
    <script>
      (function ($) {
        "use strict";
        const countDownDate = new Date("{{ $promotion->end_date }}").getTime();

        const x = setInterval(function () {
          const now = new Date().getTime();
          const distance = countDownDate - now;

          const days = Math.floor(distance / (1000 * 60 * 60 * 24));
          const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
          const seconds = Math.floor((distance % (1000 * 60)) / 1000);

          $("#timer-days").text(days < 10 ? "0" + days : days);
          $("#timer-hours").text(hours < 10 ? "0" + hours : hours);
          $("#timer-minutes").text(minutes < 10 ? "0" + minutes : minutes);
          $("#timer-seconds").text(seconds < 10 ? "0" + seconds : seconds);

          if (distance < 0) {
            clearInterval(x);
            $(".flash-countdown").html('<span class="text--danger fw-bold">@lang("ĐÃ KẾT THÚC")</span>');
          }
        }, 1000);

        new Swiper(".flash-sale-slider", {
          slidesPerView: 2,
          spaceBetween: 10,
          navigation: {
            nextEl: ".flash-next",
            prevEl: ".flash-prev",
          },
          breakpoints: {
            576: { slidesPerView: 3, spaceBetween: 15 },
            768: { slidesPerView: 4, spaceBetween: 15 },
            992: { slidesPerView: 5, spaceBetween: 20 },
            1200: { slidesPerView: 6, spaceBetween: 20 },
          },
        });
      })(jQuery);
    </script>
  @endpush
@endif