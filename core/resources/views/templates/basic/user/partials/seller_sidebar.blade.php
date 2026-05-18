@php
    $user = auth()->user();
@endphp
<aside class="profile-sidebar">
    <div class="sidebar-user-info">
        <img src="{{ getAvatar(getFilePath('userProfile') . '/' . $user->image, $user->fullname ?? $user->username) }}"
            alt="Avatar" class="sidebar-avatar">
        <div style="text-align: center; margin-top: 10px;">
            <h3 style="margin-bottom: 5px;">{{ __($user->fullname) }}</h3>
            <span style="font-size: 13px; color: var(--accent); font-weight: 700; display: block;">{{ __($user->shop->name ?? 'Official Store') }}</span>
            <span class="user-status-active">@lang('Đang hoạt động')</span>
        </div>
    </div>
    <nav class="sidebar-menu">
        <a href="{{ route('seller.home') }}" class="sidebar-link {{ request()->routeIs('seller.home') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i> @lang('Dashboard thống kê')
        </a>
        <a href="{{ route('seller.shop') }}" class="sidebar-link {{ request()->routeIs('seller.shop') ? 'active' : '' }}">
            <i class="fa-solid fa-gear"></i> @lang('Thông tin người bán')
        </a>
        <a href="{{ route('seller.products.all') }}" class="sidebar-link {{ request()->routeIs('seller.products.*') ? 'active' : '' }}">
            <i class="fa-solid fa-boxes-stacked"></i> @lang('Quản lý sản phẩm')
        </a>
        <a href="{{ route('seller.order.all') }}" class="sidebar-link {{ request()->routeIs('seller.order.*') ? 'active' : '' }}">
            <i class="fa-solid fa-clipboard-list"></i> @lang('Quản lý đơn hàng')
        </a>
        <a href="{{ route('seller.transactions') }}" class="sidebar-link {{ request()->routeIs('seller.transactions') ? 'active' : '' }}">
            <i class="fa-solid fa-wallet"></i> @lang('Lịch sử thanh toán')
        </a>
    </nav>
    <div class="sidebar-footer" style="padding: 20px; border-top: 1px solid #eee;">
        <a href="{{ route('user.profile.setting') }}" class="btn btn-light w-100" style="border: 1px solid var(--border); font-weight: 700; font-size: 12px; display: flex; align-items: center; justify-content: center; gap: 8px;">
            <i class="fa-solid fa-arrow-left"></i> @lang('QUAY LẠI CÁ NHÂN')
        </a>
    </div>
</aside>
