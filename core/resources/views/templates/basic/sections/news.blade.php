@php
  $newsContent = getContent('news.content', true);
  $newsList = \App\Models\News::where('is_show_home', 1)
    ->with('category')
    ->orderBy('published_at', 'desc')
    ->limit(10)
    ->get();
@endphp
<!-- TIN TỨC -->
<section class="news-section py-lg-5 py-4">
  <div class="container">
    <h2 class="section-title">{{ __(@$newsContent->data_values->title ?? 'Tin tức mới nhất') }}</h2>
    <div class="shopee-cat-wrap">
      <button class="shopee-cat-arrow news-prev" aria-label="Trước">
        <i class="fa-solid fa-chevron-left"></i>
      </button>
      <button class="shopee-cat-arrow news-next" aria-label="Sau">
        <i class="fa-solid fa-chevron-right"></i>
      </button>
      <div class="news-slider-wrapper swiper news-slider">
        <div class="swiper-wrapper">
          @foreach($newsList as $news)
            <div class="swiper-slide">
              <a href="{{ route('news.details', $news->slug) }}" class="news-card">
                <div class="news-img-box">
                  <img src="{{ getImage(getFilePath('news') . '/' . $news->featured_image) }}"
                    alt="{{ __($news->title) }}" />
                </div>
                <div class="p-info">
                  <span class="news-date">{{ __(@$news->category->name ?? 'TIN TỨC') }}</span>
                  <h4 class="news-title">
                    {{ __($news->title) }}
                  </h4>
                  <p class="news-excerpt">
                    {{ strLimit(strip_tags($news->excerpt ?? $news->content), 100) }}
                  </p>
                  <div class="news-read-more">
                    @lang('Đọc thêm') <i class="fa-solid fa-arrow-right"></i>
                  </div>
                </div>
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
      new Swiper(".news-slider", {
        slidesPerView: 1,
        spaceBetween: 15,
        loop: true,
        navigation: {
          nextEl: ".news-next",
          prevEl: ".news-prev",
        },
        breakpoints: {
          576: { slidesPerView: 2, spaceBetween: 15 },
          992: { slidesPerView: 3, spaceBetween: 20 },
          1200: { slidesPerView: 4, spaceBetween: 20 },
        },
      });
    })(jQuery);
  </script>
@endpush