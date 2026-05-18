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

    <!-- MOBILE DRAWER & OVERLAY -->
    <div class="mobile-drawer-overlay" id="mobileOverlay"></div>
    <div class="mobile-drawer" id="mobileDrawer">
        <div class="drawer-user-header">
            <div class="user-info">
                <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?q=80&w=100&auto=format&fit=crop"
                    alt="User" />
                <div class="text">
                    <p class="name">Nam Nguyễn</p>
                    <p class="level"><i class="fa-solid fa-crown"></i> Thành viên Vàng</p>
                </div>
            </div>
            <div class="close-drawer" id="closeMobileMenu">
                <i class="fa-solid fa-xmark"></i>
            </div>
        </div>

        <div class="drawer-content">
            <div class="drawer-menu">
                <a href="index.html" class="menu-item"><i class="fa-solid fa-house" style="color: #64748b;"></i> Quảng
                    Phát
                    Mall</a>
                <a href="about.html" class="menu-item active"><i class="fa-solid fa-truck-fast"
                        style="color: #64748b;"></i>
                    Quảng Phát Logistic</a>
                <a href="news.html" class="menu-item"><i class="fa-solid fa-newspaper" style="color: #64748b;"></i> Tin
                    tức mới
                    nhất</a>
                <a href="lien-he.html" class="menu-item"><i class="fa-solid fa-envelope" style="color: #f59e0b;"></i>
                    Liên hệ hỗ
                    trợ</a>
            </div>

            <div class="drawer-divider"></div>

            <div class="drawer-menu">
                <div class="menu-label">Tiện ích của tôi</div>
                <a href="cart.html" class="menu-item"><i class="fa-solid fa-cart-shopping" style="color: #f97316;"></i>
                    Giỏ
                    hàng</a>
                <a href="orders.html" class="menu-item"><i class="fa-solid fa-clipboard-list"
                        style="color: #f97316;"></i> Đơn
                    hàng của tôi</a>
                <a href="#" class="menu-item"><i class="fa-solid fa-ticket" style="color: #f59e0b;"></i> Kho
                    Voucher</a>
                <a href="#" class="menu-item"><i class="fa-solid fa-coins" style="color: #f59e0b;"></i> QP Xu
                    <span class="badge-mini">1.2k</span></a>
                <a href="#" class="menu-item"><i class="fa-solid fa-heart" style="color: #f97316;"></i> Đã
                    thích</a>
            </div>

            <div class="drawer-divider"></div>

            <!-- Seller Only Section -->
            <div class="seller-menu-section">
                <div class="menu-label">Kênh người bán</div>
                <a href="seller-dashboard.html" class="menu-item"><i class="fa-solid fa-chart-line"></i> Dashboard
                    thống kê</a>
                <a href="seller-products.html" class="menu-item"><i class="fa-solid fa-boxes-stacked"></i> Quản lý
                    sản phẩm</a>
                <a href="seller-orders.html" class="menu-item"><i class="fa-solid fa-clipboard-list"></i> Đơn hàng đã
                    nhận</a>
                <a href="seller-payments.html" class="menu-item"><i class="fa-solid fa-wallet"></i> Lịch sử thanh
                    toán</a>
                <a href="shop-config.html" class="menu-item"><i class="fa-solid fa-gear"></i> Cài đặt Shop</a>
            </div>

            <div class="drawer-divider"></div>

            <div class="drawer-menu">
                <a href="#" class="menu-item logout-item"><i class="fa-solid fa-right-from-bracket"
                        style="color: #f97316;"></i>
                    Đăng xuất</a>
            </div>
        </div>
    </div>

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

        $('.policy').on('click', function() {
            $.get('{{ route('cookie.accept') }}',
                function(response) {
                    $('.cookie__wrapper').removeClass('show');
                });
        });

        setTimeout(() => {
            $('.cookie__wrapper').addClass('show');
        }, 2000);

        let disableSubmission = false;
        $('.disableSubmission').on('submit', function(e) {
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
