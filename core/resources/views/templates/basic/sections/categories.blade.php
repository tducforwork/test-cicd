@php
    $categoriesContent = getContent('categories.content', true);
    // Lấy danh mục có show_on_home = 1 và là danh mục cha (parent_id = null)
    $categories = $allCategoriesHome->where('show_on_home', 1);
@endphp

<!-- CATEGORY GRID (LAZADA STYLE) -->
<section class="category-grid-section py-lg-4 py-4">
    <div class="container">
        <div class="product-section-header">
            <h2 class="section-title">{{ __(@$categoriesContent->data_values->title) }}</h2>
        </div>
        <div class="category-slider-container">
            <div class="swiper category-swiper">
                <div class="swiper-wrapper">
                    @foreach($categories->chunk(20) as $chunk)
                        <div class="swiper-slide">
                            <div class="lazada-cat-grid">
                                @foreach($chunk as $category)
                                    <a href="{{ route('pages', ['slug'=>$category->slug]) }}" class="lazada-cat-item">
                                        <div class="lazada-cat-img"
                                            style="background-color: {{ $category->bg_color ?? '#f5f5f5' }};">
                                            @if ($category->image)
                                                <img src="{{ getImage(getFilePath('category') . '/' . $category->image) }}"
                                                    alt="{{ $category->name }}">
                                            @else
                                                <i class="{{ $category->icon ?? 'las la-tag' }}"
                                                    style="color: {{ $category->icon_color ?? '#fb4d1b' }};"></i>
                                            @endif
                                        </div>
                                        <span class="lazada-cat-name">{{ __($category->name) }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- Navigation Buttons -->
            <button class="cat-slider-arrow prev swiper-cat-prev"><i class="fa-solid fa-chevron-left"></i></button>
            <button class="cat-slider-arrow next swiper-cat-next"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </div>
</section>

@push('script')
    <script>
        (function ($) {
            "use strict";
            new Swiper(".category-swiper", {
                slidesPerView: 1,
                spaceBetween: 0,
                loop: false,
                navigation: {
                    nextEl: ".swiper-cat-next",
                    prevEl: ".swiper-cat-prev",
                },
                // Nếu chỉ có 1 slide thì ẩn nút điều hướng
                on: {
                    init: function () {
                        if (this.slides.length <= 1) {
                            $('.swiper-cat-next, .swiper-cat-prev').hide();
                        }
                    }
                }
            });
        })(jQuery);
    </script>
@endpush