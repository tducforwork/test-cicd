@php
    $heroSliderContent = getContent('hero_slider.content', true);
    $heroSliderElements = getContent('hero_slider.element', false, null, true)->sortBy(function ($item) {
        return @$item->data_values->sort_order;
    });
@endphp

<section class="hero-layout-section">
    <div class="container">
        <div class="hero-layout-wrapper row">
            <!-- Main Slider (Left) -->
            <div class="hero-slider-main swiper hero-main-swiper col-md-8 col-12">
                <div class="swiper-wrapper">
                    @foreach ($heroSliderElements as $item)
                        <div class="swiper-slide slide">
                            <a href="{{ @$item->data_values->link ?? '#' }}" class="w-100 h-100 d-block">
                                <img src="{{ getImage('assets/images/frontend/hero_slider/' . @$item->data_values->slider_image, '1920x800') }}"
                                    alt="Slider Image" class="w-100 h-100 object-fit-cover">
                            </a>
                        </div>
                    @endforeach
                </div>
                <!-- Navigation Arrows -->
                <div class="slider-arrow prev swiper-button-prev"><i class="fa-solid fa-chevron-left"></i></div>
                <div class="slider-arrow next swiper-button-next"><i class="fa-solid fa-chevron-right"></i></div>
                <!-- Pagination Dots -->
                <div class="slider-dots  swiper-pagination"></div>
            </div>

            <!-- Side Banners (Right) -->
            <div class="hero-side-banners col-md-4 col-12">

                <a href="{{ @$heroSliderContent->data_values->side_banner_1_link ?? '#' }}" class="hero-side-item">
                    <img src="{{ frontendImage('hero_slider', @$heroSliderContent->data_values->side_banner_1) }}"
                        alt="Banner 1">
                </a>


                <a href="{{ @$heroSliderContent->data_values->side_banner_2_link ?? '#' }}" class="hero-side-item">
                    <img src="{{ frontendImage('hero_slider', @$heroSliderContent->data_values->side_banner_2) }}"
                        alt="Banner 2">
                </a>
            </div>
        </div>
    </div>
</section>

@push('script')
    <script>
        (function($) {
            "use strict";
            new Swiper(".hero-main-swiper", {
                slidesPerView: 1,
                loop: true,
                // autoplay: {
                //     delay: 5000,
                //     disableOnInteraction: false,
                // },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
            });
        })(jQuery);
    </script>
@endpush
