<!-- MOBILE HEADER -->
<div class="mobile-header">
    <div class="mobile-logo">
        <a href="index.html">
            <img src="assets/logo-black.png" alt="QUẢNG PHÁT" />
        </a>
    </div>
    <div class="mobile-tools">
        <div class="mobile-tool-item search-trigger">
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>
        <div class="mobile-tool-item" onclick="window.location.href='profile.html'">
            <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?q=80&w=100&auto=format&fit=crop"
                alt="User" class="mobile-avatar" />
        </div>
        <div class="mobile-tool-item cart-btn">
            <i class="fa-solid fa-cart-shopping"></i>
            <span class="badge">2</span>
        </div>
        <div class="mobile-tool-item" id="openMobileMenu">
            <i class="fa-solid fa-bars-staggered"></i>
        </div>
    </div>
</div>

<!-- MOBILE SEARCH BAR (Toggled) -->
<div class="mobile-search-bar" id="mobileSearchBar">
    <div class="container">
        <div class="search-input-wrapper">
            <input type="text" placeholder="Bạn muốn tìm gì hôm nay?" id="mobileSearchInput" />
            <button type="button" class="mobile-search-btn">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>
        <!-- Hot Keywords -->
        <div class="mobile-search-hot">
            <span>Xu hướng:</span>
            <div class="hot-tags">
                <a href="#">iPhone 15</a>
                <a href="#">Giao hàng 24h</a>
                <a href="#">Máy khoan Bosch</a>
                <a href="#">Thời trang Mall</a>
            </div>
        </div>
    </div>
</div
