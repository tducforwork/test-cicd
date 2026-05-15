@extends('Template::layouts.frontend')
@section('content')
    @if ($sections != null)
        @foreach (json_decode($sections) as $sec)
            @include('Template::sections.' . $sec)
        @endforeach
    @endif
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        (function ($) {
            "use strict";

            // HERO SWIPER
            new Swiper('.hero-swiper', {
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.hero-swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.hero-swiper-next',
                    prevEl: '.hero-swiper-prev',
                },
            });

            // MOST SEARCHED SWIPER
            new Swiper('.most-searched-slider', {
                slidesPerView: 2,
                spaceBetween: 10,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    640: { slidesPerView: 3, spaceBetween: 20 },
                    768: { slidesPerView: 4, spaceBetween: 20 },
                    1024: { slidesPerView: 6, spaceBetween: 20 },
                }
            });

        })(jQuery);
    </script>
@endpush