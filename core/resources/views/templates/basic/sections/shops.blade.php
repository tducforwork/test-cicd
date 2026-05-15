<!-- SHOP NỔI BẬT -->
<section class="shop-section py-lg-5 py-4">
  <div class="container">
    <h2 class="section-title">Shop nổi bật</h2>
    <div class="shopee-cat-wrap">
      <button class="shopee-cat-arrow shop-prev" aria-label="Trước">
        <i class="fa-solid fa-chevron-left"></i>
      </button>
      <button class="shopee-cat-arrow shop-next" aria-label="Sau">
        <i class="fa-solid fa-chevron-right"></i>
      </button>
      <div class="brand-slider-wrapper shop-wrapper swiper shop-featured-slider">
        <div class="swiper-wrapper">
          @foreach($featuredSeller as $seller)
            @php
              $shop = $seller->shop;
              $logo = $shop->logo;
              if (filter_var($logo, FILTER_VALIDATE_URL)) {
                $logoUrl = $logo;
              } else {
                $logoUrl = getImage(getFilePath('sellerShopLogo') . '/' . $logo, getFileSize('sellerShopLogo'));
              }
            @endphp
            <div class="swiper-slide">
              <a href="#" class="brand-item">
                <div class="brand-logo-box" style="overflow: hidden; padding: 0">
                  <img src="{{ $logoUrl }}" alt="{{ $shop->name }}" style="
                                            width: 100%;
                                            height: 100%;
                                            object-fit: cover;
                                            border-radius: var(--radius-circle);
                                            " />
                </div>
                <p class="brand-name">{{ $shop->name }}</p>
              </a>
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
      new Swiper(".shop-featured-slider", {
        slidesPerView: 2.5,
        spaceBetween: 10,
        loop: false,
        watchOverflow: true,
        navigation: {
          nextEl: ".shop-next",
          prevEl: ".shop-prev",
        },
        on: {
          lock: function () {
            $('.shop-prev, .shop-next').addClass('d-none');
          },
          unlock: function () {
            $('.shop-prev, .shop-next').removeClass('d-none');
          }
        },
        breakpoints: {
          480: { slidesPerView: 3.5, spaceBetween: 10 },
          576: { slidesPerView: 4.5, spaceBetween: 15 },
          768: { slidesPerView: 6.5, spaceBetween: 15 },
          992: { slidesPerView: 8.5, spaceBetween: 15 },
          1200: { slidesPerView: 9.5, spaceBetween: 25 },
        },
      });
    })(jQuery);
  </script>
@endpush