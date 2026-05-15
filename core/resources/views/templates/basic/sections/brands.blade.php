@php
  $brandsContent = getContent('brands.content', true);
  $topBrands = \App\Models\Brand::top()->get();
@endphp
<!-- THƯƠNG HIỆU -->
<section class="brand-section py-lg-5 py-4">
  <div class="container">
    <h2 class="section-title">{{ __(@$brandsContent->data_values->title) }}</h2>
    <div class="shopee-cat-wrap">
      <button class="shopee-cat-arrow brand-prev" aria-label="Trước">
        <i class="fa-solid fa-chevron-left"></i>
      </button>
      <button class="shopee-cat-arrow brand-next" aria-label="Sau">
        <i class="fa-solid fa-chevron-right"></i>
      </button>
      <div class="brand-slider-wrapper swiper brand-slider">
        <div class="swiper-wrapper">
          @foreach($topBrands as $brand)
            <div class="swiper-slide">
              <a href="{{ route('products', ['brand' => $brand->slug]) }}" class="brand-item">
                <div class="brand-logo-box">
                  <img src="{{ $brand->logo() }}" alt="{{ __($brand->name) }}"
                    style="width: 100%; height: 100%; object-fit: contain; padding: 10px; border-radius: 50%;">
                </div>
                <p class="brand-name">{{ __($brand->name) }}</p>
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
      new Swiper(".brand-slider", {
        slidesPerView: 10,
        spaceBetween: 10,
        loop: false,
        navigation: {
          nextEl: ".brand-next",
          prevEl: ".brand-prev",
        },
        breakpoints: {
          480: { slidesPerView: 3, spaceBetween: 15 },
          768: { slidesPerView: 4, spaceBetween: 20 },
          992: { slidesPerView: 5, spaceBetween: 20 },
          1200: { slidesPerView: 10, spaceBetween: 20 },
        },
      });
    })(jQuery);
  </script>
@endpush