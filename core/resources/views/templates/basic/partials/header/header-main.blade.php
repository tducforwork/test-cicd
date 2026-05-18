@php
    $headerContent = getContent('header.content', true);
@endphp
<header>
    <div class="container header-inner">
        <!-- Logo Section: Two Separate Logos Side-by-Side -->
        <div class="dual-logo-container">
            <a href="{{ @$headerContent->data_values->logo_mall_link ?? route('home') }}" class="logo-link">
                <img src="{{ frontendImage('header', @$headerContent->data_values->logo_mall) }}"
                    alt="{{ gs('site_name') }}" style="height: 60px;" />
            </a>
            <div class="logo-divider-v"></div>
            <a href="{{ @$headerContent->data_values->logo_logistics_link ?? route('home') }}" class="logo-link">
                <img src="{{ frontendImage('header', @$headerContent->data_values->logo_logistics) }}"
                    alt="QP LOGISTICS" style="height: 60px;" />
            </a>
        </div>

        <!-- Centered Search Area -->
        <div class="search-area-wrapper">
            <div class="shopee-search-bar">
                <form action="#" method="GET" class="w-100 d-flex">
                    <input type="text" name="search"
                        placeholder="@lang('Tìm kiếm sản phẩm, thương hiệu hoặc dịch vụ Logistics')..."
                        value="{{ request()->search }}" autocomplete="off" />
                    <button type="submit" class="shopee-search-btn">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>

            <!-- Suggestions Box (Synced & Enhanced) -->
            <div class="search-suggestions-box">
                <!-- Recent Searches Section -->
                <div class="suggestion-section">
                    <div class="suggestion-section-title">
                        <span>@lang('SẢN PHẨM VỪA TÌM KIẾM')</span>
                        <a href="javascript:void(0)">@lang('Xóa lịch sử')</a>
                    </div>
                    <div class="recent-products-grid">
                        @php
                            $recentProducts = \App\Models\Product::orderBy('id', 'desc')->limit(4)->get();
                        @endphp
                        @foreach ($recentProducts as $p)
                            <a href="{{ route('product.detail', ['id' => $p->id, 'slug' => slug($p->name)]) }}"
                                class="recent-p-item">
                                <img src="{{ getImage(getFilePath('product') . '/' . $p->main_image) }}"
                                    class="recent-p-img" alt="{{ $p->name }}">
                                <div class="recent-p-info">
                                    <p class="recent-p-name">{{ __($p->name) }}</p>
                                    <p class="recent-p-price">{{ showAmount($p->price) }} {{ gs('cur_text') }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <hr class="my-3 opacity-10">

                <!-- Hot Keywords Section -->
                <div class="suggestion-section">
                    <div class="suggestion-section-title">
                        <span>@lang('XU HƯỚNG TÌM KIẾM')</span>
                        <a href="javascript:void(0)">@lang('Xóa tất cả')</a>
                    </div>
                    <div class="suggestion-tags">
                        @php
                            $hotKeywords = ['iPhone 15 Pro Max', 'Vận chuyển Trung Việt', 'Phụ kiện Mall', 'Ốp lưng iPhone', 'Sạc dự phòng 20W'];
                        @endphp
                        @foreach ($hotKeywords as $kw)
                            <a href="{{ route('product.search', ['search' => $kw]) }}" class="suggestion-tag">{{ $kw }}</a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Hot Hints Below -->
            <div class="search-hints">
                @foreach ($hotKeywords as $kw)
                    <a href="{{ route('product.search', ['search' => $kw]) }}">{{ $kw }}</a>
                @endforeach
            </div>
        </div>

        <!-- Tools Section -->
        <div class="header-right-tools">
            @guest
                <!-- Guest: Avatar mặc định + Đăng nhập / Đăng ký -->
                <div class="header-auth" id="headerAuth">
                    <div class="user-profile guest-profile">
                        <img src="https://ui-avatars.com/api/?name=Guest&background=e2e8f0&color=64748b&size=80&bold=true"
                            alt="Guest" class="avatar" />
                        <span class="user-name-mall" style="color:#64748b;">@lang('Tài khoản')</span>
                        <div class="profile-menu">
                            <a href="{{ route('user.login') }}" class="menu-item"><i
                                    class="fa-solid fa-right-to-bracket"></i>
                                @lang('Đăng nhập')</a>
                            <a href="{{ route('user.register') }}" class="menu-item"><i class="fa-solid fa-user-plus"></i>
                                @lang('Đăng ký')</a>
                        </div>
                    </div>
                </div>
            @endguest

            @auth
                <!-- Logged-in profile (hiển thị khi đã đăng nhập) -->
                <div class="user-profile is-logged-in" id="userProfile">
                    <img src="{{ getAvatar(getFilePath('userProfile') . '/' . auth()->user()->image, auth()->user()->fullname ?? auth()->user()->username) }}"
                        alt="User" class="avatar" />
                    <span class="user-name-mall">{{ auth()->user()->fullname }}</span>
                    <div class="profile-menu">
                        {{-- <a href="{{ route('user.home') }}" class="menu-item"><i class="fa-regular fa-user"></i>
                            @lang('Dashboard')</a> --}}
                        <a href="{{ route('user.profile.setting') }}" class="menu-item"><i class="fa-solid fa-gear"></i>
                            @lang('Hồ sơ')</a>
                        @if (auth()->user()->is_seller)
                            <hr class="dropdown-divider">
                            <a href="{{ route('seller.home') }}" class="menu-item"><i class="fa-solid fa-store"></i>
                                @lang('Kênh người bán')</a>
                        @endif
                        <hr class="dropdown-divider">
                        <form action="{{ route('user.logout') }}" method="GET" id="logoutForm" class="d-none"></form>
                        <a href="{{ route('user.logout') }}" class="menu-item logout-item" id="logoutBtn">
                            <i class="fa-solid fa-right-from-bracket"></i> @lang('Thoát')
                        </a>
                    </div>
                </div>
            @endauth

            <div class="header-cart-shopee">
                <a href="#" class="cart-btn">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="cart-count">{{ session('cart') ? count(session('cart')) : 0 }}</span>
                </a>
            </div>
        </div>
    </div>
</header>