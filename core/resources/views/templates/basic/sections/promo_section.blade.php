@php
  $promoElements = getContent('promo_section.element');
@endphp
<!-- BANNER KHUYẾN MÃI -->
<section class="promo-section py-lg-5 py-4">
  <div class="container">
    <div class="promo-slider-outer">
      <div class="promo-slider-wrapper swiper promo-swiper">
        <div class="swiper-wrapper">
          @foreach($promoElements as $item)
            @php
              $bgColor = @$item->data_values->background_color;
              $bgImage = @$item->data_values->background_image;

              $style = "";
              if ($bgColor) {
                $style = "background-color: #$bgColor;";
              } elseif ($bgImage) {
                $style = "background-image: url(" . frontendImage('promo_section', $bgImage, '1920x800') . "); background-size: cover; background-position: center;";
              } else {
                $style = "background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);";
              }
            @endphp
            <div class="swiper-slide">
              <div class="promo-banner-item" style="{{ $style }}">
                <div class="promo-banner-content">
                  @if(@$item->data_values->badge)
                    <span class="promo-badge">{{ __($item->data_values->badge) }}</span>
                  @endif
                  <h3 class="promo-title">{{ __(@$item->data_values->title) }}</h3>
                  <p class="promo-desc">
                    @php echo __(@$item->data_values->description); @endphp
                  </p>
                  @if(@$item->data_values->button_text)
                    <a href="{{ @$item->data_values->button_url }}"
                      class="promo-cta">{{ __($item->data_values->button_text) }} &rarr;</a>
                  @endif
                </div>
                @if(@$item->data_values->banner_badge)
                  <div class="promo-banner-badge">{{ __($item->data_values->banner_badge) }}</div>
                @endif
              </div>
            </div>
          @endforeach
        </div>
        <!-- Dots -->
        <div class="swiper-pagination promo-dots-wrapper"></div>
      </div>
    </div>
  </div>
</section>

@push('style')
  <style>
    .promo-dots-wrapper {
      position: relative !important;
      margin-top: 16px;
      display: flex;
      justify-content: center;
      gap: 8px;
      bottom: 0 !important;
    }

    .promo-dots-wrapper .swiper-pagination-bullet {
      width: 8px;
      height: 8px;
      border-radius: var(--radius-circle);
      background: var(--border);
      cursor: pointer;
      opacity: 1;
      margin: 0 !important;
      transition: all 0.3s ease;
    }

    .promo-dots-wrapper .swiper-pagination-bullet-active {
      width: 24px;
      border-radius: var(--radius-sm);
      background: var(--primary);
    }
  </style>
@endpush

@push('script')
  <script>
    (function ($) {
      "use strict";
      new Swiper(".promo-swiper", {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        // autoplay: {
        //     delay: 5000,
        //     disableOnInteraction: false,
        // },
        pagination: {
          el: ".promo-dots-wrapper",
          clickable: true,
        },
      });
    })(jQuery);
  </script>
@endpush