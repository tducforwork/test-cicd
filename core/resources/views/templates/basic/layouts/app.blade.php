<!doctype html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ gs()->sitename(__($pageTitle ?? '')) }}</title>
    @include('partials.seo')
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/owl.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/odometer.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/global.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                        'poppins': ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        'kviet-orange': '#ff6f0f',
                        'kviet-dark': '#272343',
                        'kviet-gray': '#5a607f',
                        'kviet-light': '#d7dbec',
                        'kviet-input': '#d9e1ec',
                        'kviet-placeholder': '#a1a7c4',
                        'kviet-bg': '#f7f7f7',
                        'kviet-sidebar': '#ebebeb',
                        'brand': '#ff6f0f',
                        'dark-navy': '#272343',
                        'gray-scalegray-600': '#5a607f',
                        'gray-scalegray-100': '#f7f7f7',
                        'gray-scalegray-200': '#ebebeb',
                        'gray-scalegray-400': '#d9d9d9',
                    },
                    container: {
                        center: true,
                        padding: '1rem',
                        screens: {
                            '2xl': '1320px',
                        },
                    },
                },
            },
        }
    </script>
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/responsive.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    {{--
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css') }}"> --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=DM Sans:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">

    {{--
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/color.php?color=' . gs('base_color')) }}"> --}}
    <link rel="shortcut icon" href="{{ siteFavicon() }}" type="image/x-icon">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="{{ gs('base_color') }}">
    @stack('style-lib')

    @stack('style')
</head>
@php echo loadExtension('google-analytics') @endphp

<body>

    @php
        if (!session()->has('session_id')) {
            session()->put('session_id', uniqid());
        }
    @endphp

    @stack('fbComment')
    {{-- @include('Template::partials.preloader') --}}

    @yield('panel')
    @stack('modal')

    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/modernizr-3.6.0.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/jquery-ui.min.js') }}"></script>

    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset($activeTemplateTrue . 'js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/magnific-popup.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/owl.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/wow.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/odometer.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/viewport.jquery.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/nice-select.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/zoomsl.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>

    @stack('script-lib')

    @php echo loadExtension('tawk-chat') @endphp

    @include('partials.notify')

    @if (gs('pn'))
        @include('partials.push_script')
    @endif

    @include('Template::script.main')

    @stack('script')

    <script>
        'use strict';

        $('.policy').on('click', function () {
            $.get('{{ route('cookie.accept') }}',
                function (response) {
                    $('.cookie__wrapper').removeClass('show');
                });
        });

        setTimeout(() => {
            $('.cookie__wrapper').addClass('show');
        }, 2000);

        let disableSubmission = false;
        $('.disableSubmission').on('submit', function (e) {
            if (disableSubmission) {
                e.preventDefault()
            } else {
                disableSubmission = true;
            }
        });

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register("{{ asset('service-worker.js') }}")
                    .then(registration => {
                        console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    })
                    .catch(err => {
                        console.log('ServiceWorker registration failed: ', err);
                    });
            });
        }
    </script>
    @include($activeTemplate . 'partials.modal.cart')
</body>

</html>