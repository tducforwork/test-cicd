@php
$isSeller = auth()->user()->is_seller ?? false;
@endphp

<div id="user-dashboard-sidebar" class="w-full lg:w-[312px] shrink-0 bg-[#EBEBEB] rounded-lg border border-[#e6e6e6] pb-2">
    <!-- Navigation header -->
    <div class="flex items-start pl-5 pr-0 pt-6 pb-4">
        <span class="font-medium text-[#272343] text-xl leading-[30px]">
            Điều hướng
        </span>
    </div>

    <!-- Dashboard -->
    <a href="{{ route('user.home') }}" data-sidebar-item="dashboard"
        class="sidebar-plain flex items-center gap-[10px] px-5 py-1 min-h-[56px]">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M21 21H13V15H21V21ZM11 21H3V11H11V21ZM21 13H13V3H21V13ZM11 9H3V3H11V9Z" fill="#CCCCCC" />
        </svg>
        <span class="sidebar-plain-body flex flex-1 min-w-0 items-center justify-between gap-3 rounded-xl border border-transparent transition-all duration-200 p-[0.75rem]">
            <span class="sidebar-plain-text font-normal text-[#666] text-base leading-6">Bảng điều khiển</span>
            <svg class="sidebar-plain-chevron hidden w-6 h-6 shrink-0 text-[#666]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </span>
    </a>

    <!-- Settings -->
    <a href="{{ route('user.profile.setting') }}" data-sidebar-item="settings"
        class="sidebar-plain flex items-center gap-[10px] px-5 py-1 min-h-[56px]">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M13.8194 22H10.1794C9.95133 22 9.7301 21.9221 9.5524 21.7792C9.3747 21.6362 9.2512 21.4368 9.2024 21.214L8.7954 19.33C8.25245 19.0921 7.73763 18.7946 7.2604 18.443L5.4234 19.028C5.20596 19.0973 4.97135 19.0902 4.75852 19.0078C4.54569 18.9254 4.36745 18.7727 4.2534 18.575L2.4294 15.424C2.31654 15.2261 2.27418 14.9958 2.30924 14.7708C2.3443 14.5457 2.4547 14.3392 2.6224 14.185L4.0474 12.885C3.98259 12.2961 3.98259 11.7019 4.0474 11.113L2.6224 9.816C2.45447 9.66177 2.34391 9.45507 2.30885 9.22978C2.27378 9.00449 2.31629 8.77397 2.4294 8.576L4.2494 5.423C4.36345 5.22532 4.54169 5.07259 4.75452 4.99019C4.96735 4.90778 5.20196 4.90066 5.4194 4.97L7.2564 5.555C7.5004 5.375 7.7544 5.207 8.0174 5.055C8.2694 4.913 8.5294 4.784 8.7954 4.669L9.2034 2.787C9.25197 2.5642 9.37523 2.36469 9.55274 2.22155C9.73026 2.07841 9.95136 2.00024 10.1794 2H13.8194C14.0474 2.00024 14.2685 2.07841 14.446 2.22155C14.6236 2.36469 14.7468 2.5642 14.7954 2.787L15.2074 4.67C15.75 4.9079 16.2645 5.20539 16.7414 5.557L18.5794 4.972C18.7967 4.90292 19.0311 4.91017 19.2437 4.99256C19.4563 5.07495 19.6344 5.22753 19.7484 5.425L21.5684 8.578C21.8014 8.985 21.7204 9.5 21.3754 9.817L19.9504 11.117C20.0152 11.7059 20.0152 12.3001 19.9504 12.889L21.3754 14.189C21.7204 14.507 21.8004 15.021 21.5684 15.428L19.7484 18.581C19.6344 18.7785 19.4563 18.931 19.2437 19.0134C19.0311 19.0958 18.7967 19.1031 18.5794 19.034L16.7414 18.449C16.2646 18.8004 15.7501 19.0976 15.2074 19.335L14.7954 21.214C14.7466 21.4366 14.6233 21.6359 14.4458 21.7788C14.2683 21.9218 14.0473 21.9998 13.8194 22ZM7.6194 16.229L8.4394 16.829C8.6244 16.965 8.8174 17.09 9.0164 17.204C9.2044 17.313 9.3974 17.411 9.5954 17.5L10.5284 17.909L10.9854 20H13.0154L13.4724 17.908L14.4054 17.499C14.8124 17.319 15.1994 17.096 15.5584 16.833L16.3794 16.233L18.4204 16.883L19.4354 15.125L17.8524 13.682L17.9644 12.67C18.0144 12.227 18.0144 11.78 17.9644 11.338L17.8524 10.326L19.4364 8.88L18.4204 7.121L16.3794 7.771L15.5584 7.171C15.1992 6.90669 14.8126 6.68173 14.4054 6.5L13.4724 6.091L13.0154 4H10.9854L10.5264 6.092L9.5954 6.5C9.18758 6.67861 8.80087 6.90198 8.4424 7.166L7.6214 7.766L5.5814 7.116L4.5644 8.88L6.1474 10.321L6.0354 11.334C5.9854 11.777 5.9854 12.224 6.0354 12.666L6.1474 13.678L4.5644 15.121L5.5794 16.879L7.6194 16.229ZM11.9954 16C10.9345 16 9.91711 15.5786 9.16697 14.8284C8.41682 14.0783 7.9954 13.0609 7.9954 12C7.9954 10.9391 8.41682 9.92172 9.16697 9.17157C9.91711 8.42143 10.9345 8 11.9954 8C13.0563 8 14.0737 8.42143 14.8238 9.17157C15.574 9.92172 15.9954 10.9391 15.9954 12C15.9954 13.0609 15.574 14.0783 14.8238 14.8284C14.0737 15.5786 13.0563 16 11.9954 16ZM11.9954 10C11.6038 10.0004 11.2208 10.1158 10.8942 10.3318C10.5675 10.5479 10.3115 10.855 10.1578 11.2153C10.0041 11.5755 9.95962 11.9729 10.0298 12.3583C10.0999 12.7436 10.2817 13.0998 10.5524 13.3828C10.8232 13.6657 11.1711 13.863 11.553 13.95C11.9348 14.037 12.3338 14.01 12.7005 13.8724C13.0671 13.7347 13.3853 13.4924 13.6155 13.1756C13.8457 12.8587 13.9778 12.4812 13.9954 12.09V12.49V12C13.9954 11.4696 13.7847 10.9609 13.4096 10.5858C13.0345 10.2107 12.5258 10 11.9954 10Z" fill="#CCCCCC" />
        </svg>
        <span class="sidebar-plain-body flex flex-1 min-w-0 items-center justify-between gap-3 rounded-xl border border-transparent transition-all duration-200 p-[0.75rem]">
            <span class="sidebar-plain-text font-normal text-[#666] text-base leading-6">Cài đặt hồ sơ</span>
            <svg class="sidebar-plain-chevron hidden w-6 h-6 shrink-0 text-[#666]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </span>
    </a>

    @if($isSeller)
    <!-- Cửa hàng của tôi -->
    <a href="{{ route('seller.shop') }}" data-sidebar-item="my-shop"
        class="sidebar-plain flex items-center gap-[10px] px-5 py-1 min-h-[56px]">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M8 10H5L3 21H21L19 10H16M8 10V7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7V10M8 10H16M8 10V13M16 10V13" stroke="#CCCCCC" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <span class="sidebar-plain-body flex flex-1 min-w-0 items-center justify-between gap-3 rounded-xl border border-transparent transition-all duration-200 p-[0.75rem]">
            <span class="sidebar-plain-text font-normal text-[#666] text-base leading-6">Cửa hàng của tôi</span>
            <svg class="sidebar-plain-chevron hidden w-6 h-6 shrink-0 text-[#666]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </span>
    </a>

    <!-- Sản phẩm -->
    <div class="sidebar-dropdown mt-1" data-dropdown="san-pham">
        <div class="sidebar-group relative flex flex-col rounded-lg">
            <button type="button" class="submenu-toggle flex w-full min-w-0 items-center gap-[10px] px-5 py-1 min-h-[56px] text-left bg-transparent cursor-pointer"
                data-target="san-pham-submenu" aria-expanded="false">
                <span class="submenu-toggle-icon-slot relative inline-flex h-5 w-5 shrink-0 items-center justify-center" aria-hidden="true">
                    <svg class="submenu-toggle-icon submenu-toggle-icon-outline absolute left-0 top-0 h-5 w-5 text-[#CCCCCC]" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M5.83765 3.75H6.84741C7.18572 5.18268 8.47058 6.24776 10.0066 6.24902V6.25H10.0095V6.24902C11.5453 6.24749 12.8305 5.18256 13.1687 3.75H14.1785C14.4708 3.75009 14.7508 3.85082 14.9724 4.03223L15.0632 4.11426L18.7693 7.82422C18.867 7.92195 18.867 8.08098 18.7693 8.17871L17.1853 9.76367C17.0876 9.86131 16.9275 9.86137 16.8298 9.76367L15.5369 8.46973L14.2566 7.18945V16C14.2566 16.6888 13.6953 17.2498 13.0066 17.25H7.00659C6.31768 17.25 5.75659 16.6889 5.75659 16V7.18945L3.18237 9.76367C3.09701 9.84903 2.96423 9.85965 2.86694 9.7959L2.8269 9.76367L1.24683 8.17676L1.24585 8.17578C1.14828 8.07811 1.14835 7.91901 1.24585 7.82129L4.9519 4.11426C5.18502 3.8812 5.50362 3.75 5.83765 3.75Z" stroke="currentColor" stroke-width="1.5" />
                    </svg>
                    <svg class="submenu-toggle-icon submenu-toggle-icon-filled absolute left-0 top-0 h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                        <path d="M10.0062 5.5C11.3875 5.5 12.5062 4.38125 12.5062 3H14.1781C14.7093 3 15.2187 3.20937 15.5937 3.58437L19.2999 7.29375C19.6906 7.68437 19.6906 8.31875 19.2999 8.70938L17.7156 10.2937C17.325 10.6844 16.6906 10.6844 16.3 10.2937L15.0062 9V16C15.0062 17.1031 14.1093 18 13.0062 18H7.0062C5.90308 18 5.0062 17.1031 5.0062 16V9L3.71245 10.2937C3.32183 10.6844 2.68745 10.6844 2.29683 10.2937L0.715576 8.70625C0.324951 8.31563 0.324951 7.68125 0.715576 7.29063L4.42183 3.58437C4.79683 3.20937 5.3062 3 5.83745 3H7.50933C7.50933 4.38125 8.62808 5.5 10.0093 5.5H10.0062Z" fill="#000" />
                    </svg>
                </span>
                <span class="sidebar-plain-body flex flex-1 min-w-0 items-center justify-between gap-3 rounded-xl border border-transparent transition-all duration-200 p-[0.75rem]">
                    <span class="submenu-toggle-label flex-1 min-w-0 font-normal text-[#666] text-base leading-6 text-left">Sản phẩm</span>
                    <span class="submenu-arrow inline-flex shrink-0 transition-transform duration-300 ease-in-out text-[#666]">
                        <svg class="w-3 h-3" viewBox="0 0 8 5" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.24849 0.195C6.98849 -0.065 6.56849 -0.065 6.30849 0.195L3.72183 2.78167L1.13516 0.195C0.875161 -0.0650003 0.455161 -0.0650003 0.195161 0.195C-0.0648389 0.455 -0.0648389 0.875 0.195161 1.135L3.25516 4.195C3.51516 4.455 3.93516 4.455 4.19516 4.195L7.25516 1.135C7.50849 0.881667 7.50849 0.455 7.24849 0.195Z" fill="currentColor" />
                        </svg>
                    </span>
                </span>
            </button>
            <div id="san-pham-submenu" class="submenu-content overflow-hidden transition-all duration-300 ease-in-out max-h-0">
                <nav class="submenu-tree submenu-tree-panel flex flex-col pb-2" aria-label="Sản phẩm">
                    <a href="{{ route('seller.products.all') }}" data-sidebar-sub="seller-products-all"
                        class="sidebar-sub flex w-full items-center pl-3 min-h-[44px] text-[15px] leading-snug text-[#666] rounded-lg transition-colors">
                        <span class="shrink-0 w-[25px] flex justify-center self-stretch text-[#c4c4c4]" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="49" viewBox="0 0 25 49" fill="none">
                                <path d="M11 2C11 1.44771 11.4477 1 12 1C12.5523 1 13 1.44772 13 2V49H11V2Z" fill="#D4D4D4" />
                                <path d="M12 1V17C12 21.4183 15.5817 25 20 25H24" stroke="#D4D4D4" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </span>
                        <span class="flex-1 font-normal py-3 pl-3 sidebar-sub-item rounded-[16px_0_0_16px]">Tất cả sản phẩm</span>
                    </a>
                    <a href="{{ route('seller.products.create') }}" data-sidebar-sub="seller-products-create"
                        class="sidebar-sub flex w-full items-center pl-3 min-h-[44px] text-[15px] leading-snug text-[#666] rounded-lg transition-colors">
                        <span class="shrink-0 w-[25px] flex justify-center self-stretch text-[#c4c4c4]" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="49" viewBox="0 0 25 49" fill="none">
                                <path d="M11 2C11 1.44771 11.4477 1 12 1C12.5523 1 13 1.44772 13 2V49H11V2Z" fill="#D4D4D4" />
                                <path d="M12 1V17C12 21.4183 15.5817 25 20 25H24" stroke="#D4D4D4" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </span>
                        <span class="flex-1 font-normal py-3 pl-3 sidebar-sub-item rounded-[16px_0_0_16px]">Thêm sản phẩm mới</span>
                    </a>
                    <a href="{{ route('seller.products.trashed') }}" data-sidebar-sub="seller-products-drafts"
                        class="sidebar-sub flex w-full items-center pl-3 min-h-[44px] text-[15px] leading-snug text-[#666] rounded-lg transition-colors">
                        <span class="shrink-0 w-[25px] flex justify-center self-stretch text-[#c4c4c4]" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="49" viewBox="0 0 25 49" fill="none">
                                <path d="M12 1V17C12 21.4183 15.5817 25 20 25H24" stroke="#D4D4D4" stroke-width="2" stroke-linecap="round" />
                            </svg> </span>
                        <span class="flex-1 font-normal py-3 pl-3 sidebar-sub-item rounded-[16px_0_0_16px]">Bản nháp</span>
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <!-- Bất động sản -->
    {{-- <div class="sidebar-dropdown" data-dropdown="bat-dong-san">
        <div class="sidebar-group relative flex flex-col rounded-lg">
            <button type="button" class="submenu-toggle flex w-full min-w-0 items-center gap-[10px] px-5 py-1 min-h-[56px] text-left bg-transparent cursor-pointer"
                data-target="bat-dong-san-submenu" aria-expanded="false">
                <span class="submenu-toggle-icon-slot relative inline-flex h-5 w-5 shrink-0 items-center justify-center" aria-hidden="true">
                    <svg class="submenu-toggle-icon submenu-toggle-icon-outline absolute left-0 top-0 h-5 w-5 text-[#CCCCCC]" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M10.0005 3.41699C10.2685 3.41705 10.4944 3.49425 10.6968 3.64844L10.6997 3.65039L15.6997 7.40039L15.7046 7.40332C15.8475 7.50814 15.9598 7.64074 16.0435 7.80762C16.1268 7.97391 16.1669 8.14735 16.1665 8.33301V15.834C16.1664 16.1582 16.0571 16.4237 15.8237 16.6572C15.59 16.8909 15.3241 17.0004 15.0005 17H12.5005C12.3906 17 12.3222 16.9684 12.2612 16.9072C12.2148 16.8606 12.1853 16.81 12.1733 16.7412L12.1665 16.666V12.5C12.1664 12.1378 12.0385 11.8106 11.7798 11.5527C11.522 11.2959 11.1956 11.1678 10.8345 11.167H9.1665C8.80438 11.1671 8.47704 11.2951 8.21924 11.5537C7.96237 11.8115 7.83438 12.1379 7.8335 12.499V16.667C7.8335 16.7769 7.80147 16.8455 7.73975 16.9072C7.67805 16.9689 7.61002 17.0002 7.50146 17H5.00049C4.67569 17 4.41025 16.8902 4.17725 16.6572C3.97333 16.4533 3.86285 16.2245 3.83838 15.9521L3.8335 15.833V8.33398C3.8335 8.14799 3.87405 7.97421 3.95752 7.80762C4.0413 7.64045 4.15391 7.5081 4.29639 7.40332L9.30029 3.65039L9.30322 3.64844C9.5057 3.49417 9.73229 3.41699 10.0005 3.41699Z" stroke="currentColor" stroke-width="1.5" />
                    </svg>
                    <svg class="submenu-toggle-icon submenu-toggle-icon-filled absolute left-0 top-0 h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                        <path d="M3.3335 15.8337V8.33366C3.3335 8.06977 3.39266 7.81977 3.511 7.58366C3.62933 7.34755 3.79239 7.1531 4.00016 7.00033L9.00016 3.25033C9.29183 3.0281 9.62516 2.91699 10.0002 2.91699C10.3752 2.91699 10.7085 3.0281 11.0002 3.25033L16.0002 7.00033C16.2085 7.1531 16.3718 7.34755 16.4902 7.58366C16.6085 7.81977 16.6674 8.06977 16.6668 8.33366V15.8337C16.6668 16.292 16.5035 16.6845 16.1768 17.0112C15.8502 17.3378 15.4579 17.5009 15.0002 17.5003H12.5002C12.2641 17.5003 12.0663 17.4203 11.9068 17.2603C11.7474 17.1003 11.6674 16.9025 11.6668 16.667V12.5003C11.6668 12.2642 11.5868 12.0664 11.4268 11.907C11.2668 11.7475 11.0691 11.6675 10.8335 11.667H9.16683C8.93072 11.667 8.73294 11.747 8.5735 11.907C8.41405 12.067 8.33405 12.2648 8.3335 12.5003V16.667C8.3335 16.9031 8.2535 17.1012 8.0935 17.2612C7.9335 17.4212 7.73572 17.5009 7.50016 17.5003H5.00016C4.54183 17.5003 4.14961 17.3373 3.8235 17.0112C3.49738 16.685 3.33405 16.2925 3.3335 15.8337Z" fill="#000" />
                    </svg>
                </span>
                <span class="sidebar-plain-body flex flex-1 min-w-0 items-center justify-between gap-3 rounded-xl border border-transparent transition-all duration-200 p-[0.75rem]">
                    <span class="submenu-toggle-label flex-1 min-w-0 font-normal text-[#666] text-base leading-6 text-left">Bất động sản</span>
                    <span class="submenu-arrow inline-flex shrink-0 transition-transform duration-300 ease-in-out text-[#666]">
                        <svg class="w-3 h-3" viewBox="0 0 8 5" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.24849 0.195C6.98849 -0.065 6.56849 -0.065 6.30849 0.195L3.72183 2.78167L1.13516 0.195C0.875161 -0.0650003 0.455161 -0.0650003 0.195161 0.195C-0.0648389 0.455 -0.0648389 0.875 0.195161 1.135L3.25516 4.195C3.51516 4.455 3.93516 4.455 4.19516 4.195L7.25516 1.135C7.50849 0.881667 7.50849 0.455 7.24849 0.195Z" fill="currentColor" />
                        </svg>
                    </span>
                </span>
            </button>
            <div id="bat-dong-san-submenu" class="submenu-content overflow-hidden transition-all duration-300 ease-in-out max-h-0">
                <nav class="submenu-tree submenu-tree-panel flex flex-col pb-2" aria-label="Bất động sản">
                    <a href="{{ route('seller.products.real_estate.index') }}" data-sidebar-sub="seller-re-index"
                        class="sidebar-sub flex w-full items-center pl-3 min-h-[44px] text-[15px] leading-snug text-[#666] rounded-lg transition-colors">
                        <span class="shrink-0 w-[25px] flex justify-center self-stretch text-[#c4c4c4]" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="49" viewBox="0 0 25 49" fill="none">
                                <path d="M11 2C11 1.44771 11.4477 1 12 1C12.5523 1 13 1.44772 13 2V49H11V2Z" fill="#D4D4D4" />
                                <path d="M12 1V17C12 21.4183 15.5817 25 20 25H24" stroke="#D4D4D4" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </span>
                        <span class="flex-1 font-normal py-3 pl-3 sidebar-sub-item rounded-[16px_0_0_16px]">Bài đăng của tôi</span>
                    </a>
                    <a href="{{ route('seller.products.real_estate.create') }}" data-sidebar-sub="seller-re-create"
                        class="sidebar-sub flex w-full items-center pl-3 min-h-[44px] text-[15px] leading-snug text-[#666] rounded-lg transition-colors">
                        <span class="shrink-0 w-[25px] flex justify-center self-stretch text-[#c4c4c4]" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="49" viewBox="0 0 25 49" fill="none">
                                <path d="M11 2C11 1.44771 11.4477 1 12 1C12.5523 1 13 1.44772 13 2V49H11V2Z" fill="#D4D4D4" />
                                <path d="M12 1V17C12 21.4183 15.5817 25 20 25H24" stroke="#D4D4D4" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </span>
                        <span class="flex-1 font-normal py-3 pl-3 sidebar-sub-item rounded-[16px_0_0_16px]">Thêm bài đăng mới</span>
                    </a>
                    <a href="{{ route('seller.products.trashed') }}" data-sidebar-sub="seller-re-drafts"
                        class="sidebar-sub flex w-full items-center pl-3 min-h-[44px] text-[15px] leading-snug text-[#666] rounded-lg transition-colors">
                        <span class="shrink-0 w-[25px] flex justify-center self-stretch text-[#c4c4c4]" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="49" viewBox="0 0 25 49" fill="none">
                                <path d="M12 1V17C12 21.4183 15.5817 25 20 25H24" stroke="#D4D4D4" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </span>
                        <span class="flex-1 font-normal py-3 pl-3 sidebar-sub-item rounded-[16px_0_0_16px]">Bản nháp</span>
                    </a>
                </nav>
            </div>
        </div>
    </div> --}}

    <!-- Việc làm -->
    {{-- <div class="sidebar-dropdown" data-dropdown="job">
        <div class="sidebar-group relative flex flex-col rounded-lg">
            <button type="button" class="submenu-toggle flex w-full min-w-0 items-center gap-[10px] px-5 py-1 min-h-[56px] text-left bg-transparent cursor-pointer"
                data-target="job-submenu" aria-expanded="false">
                <span class="submenu-toggle-icon-slot relative inline-flex h-5 w-5 shrink-0 items-center justify-center" aria-hidden="true">
                    <svg class="submenu-toggle-icon submenu-toggle-icon-outline absolute left-0 top-0 h-5 w-5 text-[#CCCCCC]" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M3.33341 17.5003C2.87508 17.5003 2.48286 17.3373 2.15675 17.0112C1.83064 16.685 1.6673 16.2925 1.66675 15.8337V6.66699C1.66675 6.20866 1.83008 5.81644 2.15675 5.49033C2.48341 5.16421 2.87564 5.00088 3.33341 5.00033H6.66675V3.33366C6.66675 2.87533 6.83008 2.4831 7.15675 2.15699C7.48341 1.83088 7.87564 1.66755 8.33341 1.66699H11.6667C12.1251 1.66699 12.5176 1.83033 12.8442 2.15699C13.1709 2.48366 13.334 2.87588 13.3334 3.33366V5.00033H16.6667C17.1251 5.00033 17.5176 5.16366 17.8442 5.49033C18.1709 5.81699 18.334 6.20921 18.3334 6.66699V15.8337C18.3334 16.292 18.1704 16.6845 17.8442 17.0112C17.5181 17.3378 17.1256 17.5009 16.6667 17.5003H3.33341ZM3.33341 15.8337H16.6667V6.66699H3.33341V15.8337ZM8.33341 5.00033H11.6667V3.33366H8.33341V5.00033Z" fill="#CCCCCC" />
                    </svg>
                    <svg class="submenu-toggle-icon submenu-toggle-icon-filled absolute left-0 top-0" xmlns="http://www.w3.org/2000/svg" width="18" height="16" viewBox="0 0 18 16" fill="none">
                        <path d="M1.616 16C1.15533 16 0.771 15.846 0.463 15.538C0.155 15.23 0.000666667 14.8453 0 14.384V4.616C0 4.15534 0.154333 3.771 0.463 3.463C0.771667 3.155 1.15567 3.00067 1.615 3H6V1.615C6 1.155 6.15433 0.770669 6.463 0.462002C6.77167 0.153335 7.156 -0.000664511 7.616 2.15517e-06H10.385C10.845 2.15517e-06 11.2293 0.154002 11.538 0.462002C11.8467 0.770002 12.0007 1.15434 12 1.615V3H16.385C16.845 3 17.229 3.15434 17.537 3.463C17.845 3.77167 17.9993 4.156 18 4.616V14.385C18 14.845 17.8457 15.2293 17.537 15.538C17.2283 15.8467 16.8443 16.0007 16.385 16H1.616ZM7 3H11V1.615C11 1.46167 10.936 1.32067 10.808 1.192C10.68 1.06334 10.539 0.999335 10.385 1H7.615C7.46167 1 7.32067 1.064 7.192 1.192C7.06333 1.32 6.99933 1.461 7 1.615V3Z" fill="black" />
                    </svg>
                </span>
                <span class="sidebar-plain-body flex flex-1 min-w-0 items-center justify-between gap-3 rounded-xl border border-transparent transition-all duration-200 p-[0.75rem]">
                    <span class="submenu-toggle-label flex-1 min-w-0 font-normal text-[#666] text-base leading-6 text-left">Việc làm</span>
                    <span class="submenu-arrow inline-flex shrink-0 transition-transform duration-300 ease-in-out text-[#666]">
                        <svg class="w-3 h-3" viewBox="0 0 8 5" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.24849 0.195C6.98849 -0.065 6.56849 -0.065 6.30849 0.195L3.72183 2.78167L1.13516 0.195C0.875161 -0.0650003 0.455161 -0.0650003 0.195161 0.195C-0.0648389 0.455 -0.0648389 0.875 0.195161 1.135L3.25516 4.195C3.51516 4.455 3.93516 4.455 4.19516 4.195L7.25516 1.135C7.50849 0.881667 7.50849 0.455 7.24849 0.195Z" fill="currentColor" />
                        </svg>
                    </span>
                </span>
            </button>
            <div id="job-submenu" class="submenu-content overflow-hidden transition-all duration-300 ease-in-out max-h-0">
                <nav class="submenu-tree submenu-tree-panel flex flex-col pb-2" aria-label="Việc làm">
                    <a href="{{ route('seller.jobs.index') }}" data-sidebar-sub="seller-jobs-all"
                        class="sidebar-sub flex w-full items-center pl-3 min-h-[44px] text-[15px] leading-snug text-[#666] rounded-lg transition-colors">
                        <span class="shrink-0 w-[25px] flex justify-center self-stretch text-[#c4c4c4]" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="49" viewBox="0 0 25 49" fill="none">
                                <path d="M11 2C11 1.44771 11.4477 1 12 1C12.5523 1 13 1.44772 13 2V49H11V2Z" fill="#D4D4D4" />
                                <path d="M12 1V17C12 21.4183 15.5817 25 20 25H24" stroke="#D4D4D4" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </span>
                        <span class="flex-1 font-normal py-3 pl-3 sidebar-sub-item rounded-[16px_0_0_16px]">Tất cả tin tuyển dụng</span>
                    </a>
                    <a href="{{ route('seller.jobs.create') }}" data-sidebar-sub="seller-jobs-create"
                        class="sidebar-sub flex w-full items-center pl-3 min-h-[44px] text-[15px] leading-snug text-[#666] rounded-lg transition-colors">
                        <span class="shrink-0 w-[25px] flex justify-center self-stretch text-[#c4c4c4]" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="49" viewBox="0 0 25 49" fill="none">
                                <path d="M12 1V17C12 21.4183 15.5817 25 20 25H24" stroke="#D4D4D4" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </span>
                        <span class="flex-1 font-normal py-3 pl-3 sidebar-sub-item rounded-[16px_0_0_16px]">Thêm việc làm mới</span>
                    </a>
                </nav>
            </div>
        </div>
    </div> --}}

    <!-- Đơn hàng (seller) -->
    <a href="{{ route('seller.order.all') }}" data-sidebar-item="seller-orders"
        class="sidebar-plain flex items-center gap-[10px] px-5 py-1 min-h-[56px]">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M8.33301 15.75C8.57608 15.75 8.80955 15.8467 8.98145 16.0186C9.15335 16.1905 9.25 16.4239 9.25 16.667C9.25 16.9101 9.15335 17.1435 8.98145 17.3154C8.80955 17.4873 8.57608 17.584 8.33301 17.584C8.08996 17.5839 7.85643 17.4873 7.68457 17.3154C7.51272 17.1435 7.41699 16.9101 7.41699 16.667C7.41699 16.4239 7.51272 16.1905 7.68457 16.0186C7.85643 15.8467 8.08996 15.7501 8.33301 15.75ZM14.167 15.75C14.4098 15.7501 14.6427 15.8469 14.8145 16.0186C14.9864 16.1905 15.083 16.4239 15.083 16.667C15.083 16.9101 14.9864 17.1435 14.8145 17.3154C14.6427 17.4871 14.4098 17.5839 14.167 17.584C13.924 17.584 13.6904 17.4872 13.5186 17.3154C13.3466 17.1435 13.25 16.9101 13.25 16.667C13.25 16.4239 13.3466 16.1905 13.5186 16.0186C13.6904 15.8468 13.924 15.75 14.167 15.75ZM4.16016 2.41699C4.17762 2.41695 4.19478 2.42245 4.20898 2.43262C4.22313 2.44277 4.23368 2.45714 4.23926 2.47363L5.16309 5.23828L5.33496 5.75H17.498C17.511 5.75003 17.5237 5.75376 17.5352 5.75977C17.5467 5.76584 17.557 5.77444 17.5645 5.78516C17.5718 5.79588 17.5765 5.80837 17.5781 5.82129C17.5797 5.83416 17.5778 5.84723 17.5732 5.85938L17.5723 5.86133L15.2725 11.9863V11.9883C15.2072 12.1629 15.0896 12.3135 14.9365 12.4199C14.7835 12.5262 14.6014 12.5837 14.415 12.584H8.09082C7.8981 12.5842 7.71004 12.5229 7.55371 12.4102C7.39758 12.2975 7.28042 12.1388 7.21973 11.9561V11.9551L4.26953 3.09668L4.09863 2.58398H2.4082V2.41699H4.16016Z" stroke="#CCCCCC" stroke-width="1.5" />
        </svg>
        <span class="sidebar-plain-body flex flex-1 min-w-0 items-center justify-between gap-3 rounded-xl border border-transparent transition-all duration-200 p-[0.75rem]">
            <span class="sidebar-plain-text font-normal text-[#666] text-base leading-6">Quản lý đơn hàng</span>
            <svg class="sidebar-plain-chevron hidden w-6 h-6 shrink-0 text-[#666]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </span>
    </a>
    @endif

    <!-- Order History -->
    <a href="{{ route('user.orders', 'all') }}" data-sidebar-item="order-history"
        class="sidebar-plain flex items-center gap-[10px] px-5 py-1 min-h-[56px]">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M4 20V13H11L7.783 16.22C8.33247 16.7819 8.98837 17.2287 9.71241 17.5343C10.4364 17.8399 11.2141 17.9983 12 18C13.2393 17.9982 14.4475 17.6127 15.4589 16.8965C16.4702 16.1802 17.2349 15.1684 17.648 14H17.666C17.78 13.675 17.867 13.34 17.925 13H19.937C19.6934 14.9333 18.7527 16.7111 17.2913 18C15.83 19.2888 13.9485 20 12 20H11.99C10.9398 20.0032 9.89944 19.798 8.9291 19.3963C7.95876 18.9946 7.07772 18.4045 6.337 17.66L4 20ZM6.074 11H4.062C4.30548 9.06751 5.24564 7.29019 6.70616 6.00145C8.16667 4.7127 10.0472 4.00108 11.995 4.00004H12C13.0504 3.99671 14.0909 4.20183 15.0615 4.6035C16.032 5.00517 16.9132 5.59541 17.654 6.34004L20 4.00004V11H13L16.222 7.78004C15.672 7.21752 15.0153 6.77035 14.2903 6.46471C13.5654 6.15907 12.7867 6.0011 12 6.00004C10.7607 6.00187 9.55246 6.38738 8.54114 7.10361C7.52982 7.81985 6.76508 8.83166 6.352 10H6.334C6.219 10.325 6.132 10.66 6.075 11H6.074Z" fill="#CCCCCC" />
        </svg>
        <span class="sidebar-plain-body flex flex-1 min-w-0 items-center justify-between gap-3 rounded-xl border border-transparent transition-all duration-200 p-[0.75rem]">
            <span class="sidebar-plain-text font-normal text-[#666] text-base leading-6">Lịch sử đơn hàng</span>
            <svg class="sidebar-plain-chevron hidden w-6 h-6 shrink-0 text-[#666]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </span>
    </a>

    <!-- Wishlist -->
    <a href="{{ route('wishlist') }}" data-sidebar-item="wishlist"
        class="sidebar-plain flex items-center gap-[10px] px-5 py-1 min-h-[56px]">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" class="sidebar-plain-icon">
            <path d="M11.9987 21.0538C-8.00085 9.99967 5.99914 -2.00033 11.9987 5.58772C17.9991 -2.00034 31.9991 9.99967 11.9987 21.0538Z" stroke="#CCCCCC" stroke-width="1.5" />
        </svg>
        <span class="sidebar-plain-body flex flex-1 min-w-0 items-center justify-between gap-3 rounded-xl border border-transparent transition-all duration-200 p-[0.75rem]">
            <span class="sidebar-plain-text font-normal text-[#666] text-base leading-6">Danh sách yêu thích</span>
            <svg class="sidebar-plain-chevron hidden w-6 h-6 shrink-0 text-[#666]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </span>
    </a>

    <!-- Log out -->
    <a href="{{ route('user.logout') }}" data-sidebar-item="logout"
        class="sidebar-plain flex items-center gap-[10px] px-5 py-1 min-h-[56px]">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M19 21H10C9.46957 21 8.96086 20.7893 8.58579 20.4142C8.21071 20.0391 8 19.5304 8 19V15H10V19H19V5H10V9H8V5C8 4.46957 8.21071 3.96086 8.58579 3.58579C8.96086 3.21071 9.46957 3 10 3H19C19.5304 3 20.0391 3.21071 20.4142 3.58579C20.7893 3.96086 21 4.46957 21 5V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21ZM12 16V13H3V11H12V8L17 12L12 16Z" fill="#CCCCCC" />
        </svg>
        <span class="sidebar-plain-body flex flex-1 min-w-0 items-center justify-between gap-3 rounded-xl border border-transparent transition-all duration-200 p-[0.75rem]">
            <span class="sidebar-plain-text font-normal text-[#666] text-base leading-6">Đăng xuất</span>
            <svg class="sidebar-plain-chevron hidden w-6 h-6 shrink-0 text-[#666]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </span>
    </a>
</div>

<style>
    /* 下拉主行：hover / 展开 / 当前子路由 — 图标、文案、箭头改品牌橙 */
    #user-dashboard-sidebar .submenu-toggle:hover .submenu-toggle-icon,
    #user-dashboard-sidebar .submenu-toggle.is-open .submenu-toggle-icon,
    #user-dashboard-sidebar .submenu-toggle.is-section-active .submenu-toggle-icon {
        color: #ff6f0f;
    }

    /* Sản phẩm：点击展开或当前路由在子菜单时显示实心图标，否则线框图标 */
    #user-dashboard-sidebar .sidebar-dropdown[data-dropdown="san-pham"] .submenu-toggle-icon-filled {
        display: none;
    }

    #user-dashboard-sidebar .sidebar-dropdown[data-dropdown="san-pham"] .submenu-toggle.is-open .submenu-toggle-icon-outline,
    #user-dashboard-sidebar .sidebar-dropdown[data-dropdown="san-pham"] .submenu-toggle.is-section-active .submenu-toggle-icon-outline {
        display: none;
    }

    #user-dashboard-sidebar .sidebar-dropdown[data-dropdown="san-pham"] .submenu-toggle.is-open .submenu-toggle-icon-filled,
    #user-dashboard-sidebar .sidebar-dropdown[data-dropdown="san-pham"] .submenu-toggle.is-section-active .submenu-toggle-icon-filled {
        display: block;
    }

    /* Bất động sản：点击展开或当前路由在子菜单时显示实心图标，否则线框图标 */
    #user-dashboard-sidebar .sidebar-dropdown[data-dropdown="bat-dong-san"] .submenu-toggle-icon-filled {
        display: none;
    }

    #user-dashboard-sidebar .sidebar-dropdown[data-dropdown="bat-dong-san"] .submenu-toggle.is-open .submenu-toggle-icon-outline,
    #user-dashboard-sidebar .sidebar-dropdown[data-dropdown="bat-dong-san"] .submenu-toggle.is-section-active .submenu-toggle-icon-outline {
        display: none;
    }

    #user-dashboard-sidebar .sidebar-dropdown[data-dropdown="bat-dong-san"] .submenu-toggle.is-open .submenu-toggle-icon-filled,
    #user-dashboard-sidebar .sidebar-dropdown[data-dropdown="bat-dong-san"] .submenu-toggle.is-section-active .submenu-toggle-icon-filled {
        display: block;
    }

    /* Việc làm：点击展开或当前路由在子菜单时显示实心图标，否则线框图标 */
    #user-dashboard-sidebar .sidebar-dropdown[data-dropdown="job"] .submenu-toggle-icon-filled {
        display: none;
    }

    #user-dashboard-sidebar .sidebar-dropdown[data-dropdown="job"] .submenu-toggle.is-open .submenu-toggle-icon-outline,
    #user-dashboard-sidebar .sidebar-dropdown[data-dropdown="job"] .submenu-toggle.is-section-active .submenu-toggle-icon-outline {
        display: none;
    }

    #user-dashboard-sidebar .sidebar-dropdown[data-dropdown="job"] .submenu-toggle.is-open .submenu-toggle-icon-filled,
    #user-dashboard-sidebar .sidebar-dropdown[data-dropdown="job"] .submenu-toggle.is-section-active .submenu-toggle-icon-filled {
        display: block;
    }

    #user-dashboard-sidebar .submenu-toggle:hover .submenu-arrow,
    #user-dashboard-sidebar .submenu-toggle.is-open .submenu-arrow,
    #user-dashboard-sidebar .submenu-toggle.is-section-active .submenu-arrow {
        color: #ff6f0f;
    }

    #user-dashboard-sidebar .sidebar-plain.is-active .sidebar-plain-body {
        padding: 0.75rem;
        background-color: #fff;
        border-radius: 0.75rem;
    }

    #user-dashboard-sidebar .sidebar-plain.is-active .sidebar-plain-chevron {
        display: block !important;
    }

    #user-dashboard-sidebar .sidebar-plain.is-active .sidebar-plain-text {
        font-weight: 600 !important;
        font-size: 15px !important;
        color: #303030 !important;
    }

    #user-dashboard-sidebar .sidebar-plain.is-active .sidebar-plain-icon path {
        stroke: #ff6f0f;
    }

    #user-dashboard-sidebar .sidebar-plain.is-active svg[fill="none"] path[fill="#CCCCCC"] {
        fill: #ff6f0f;
    }

    /* hover 与当前页同款：白底 + shadow + gray-200/80 描边（对齐 Tailwind 卡片） */
    #user-dashboard-sidebar .sidebar-plain:not(.is-active):hover .sidebar-plain-body {
        padding: 0.75rem;
        background-color: #fff;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 0 0 1px rgb(229 231 235 / 0.8);
    }

    #user-dashboard-sidebar .sidebar-plain:not(.is-active):hover .sidebar-plain-chevron {
        display: block !important;
    }

    /* 左侧品牌竖线在 button.submenu-toggle 上（DevTools 可见），默认透明避免布局跳动 */
    #user-dashboard-sidebar .submenu-toggle {
        border: none;
        border-left: 3px solid transparent;
        transition: color 0.2s ease, border-color 0.2s ease;
    }

    #user-dashboard-sidebar .submenu-toggle:hover,
    #user-dashboard-sidebar .submenu-toggle.is-open,
    #user-dashboard-sidebar .submenu-toggle.is-section-active {
        border-left-color: #ff6f0f;
    }

    /* Figma：有子菜单的主行不要白底卡片，铺在侧栏灰底上，仅左侧橙线 + 橙字/图标 */
    #user-dashboard-sidebar .submenu-toggle .sidebar-plain-body {
        background-color: transparent !important;
        box-shadow: none !important;
        border-color: transparent !important;
    }

    #user-dashboard-sidebar .submenu-toggle:hover .submenu-toggle-label,
    #user-dashboard-sidebar .submenu-toggle.is-open .submenu-toggle-label,
    #user-dashboard-sidebar .submenu-toggle.is-section-active .submenu-toggle-label {
        font-weight: 600 !important;
        font-size: 16px !important;
        color: #ff6f0f !important;
    }

    /* 子菜单：Figma 为浅灰圆角底，不用主菜单白卡片 */
    #user-dashboard-sidebar .sidebar-sub {
        transition: background-color 0.15s ease, color 0.15s ease;
    }

    #user-dashboard-sidebar .sidebar-sub:not(.is-active-sub):hover .sidebar-sub-item {
        background-color: #D9D9D9;
    }

    #user-dashboard-sidebar .sidebar-sub.is-active-sub {
        background-color: #D9D9D9;
    }

    #user-dashboard-sidebar .sidebar-sub.is-active-sub,
    #user-dashboard-sidebar .sidebar-sub.is-active-sub .sidebar-sub-item {
        font-weight: 600;
        color: #ff6f0f;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var path = window.location.pathname.replace(/\/+$/, '') || '/';
        var search = window.location.search || '';

        function pathMatches(href, allowChildPath) {
            if (!href || href === '#' || href.indexOf('javascript:') === 0) return false;
            try {
                var u = new URL(href, window.location.origin);
                var hp = u.pathname.replace(/\/+$/, '') || '/';
                if (u.search && search && u.search !== search) return false;
                if (hp === path) return true;
                if (allowChildPath && path.length > hp.length && path.indexOf(hp + '/') === 0) return true;
                return false;
            } catch (e) {
                return false;
            }
        }

        // Mark plain links & card rows (exact path only)
        document.querySelectorAll('#user-dashboard-sidebar a[data-sidebar-item]').forEach(function(a) {
            if (pathMatches(a.getAttribute('href'), false)) {
                a.classList.add('is-active');
            }
        });

        // Sub links: also match deeper URLs (e.g. edit product)
        document.querySelectorAll('#user-dashboard-sidebar a[data-sidebar-sub]').forEach(function(a) {
            if (pathMatches(a.getAttribute('href'), true)) {
                a.classList.add('is-active-sub');
            }
        });

        function setGroupOpenState(dropdown, open) {
            if (!dropdown) return;
            var btn = dropdown.querySelector('.submenu-toggle');
            var arrow = btn && btn.querySelector('.submenu-arrow');
            var menu = dropdown.querySelector('.submenu-content');
            if (!btn || !menu) return;
            if (open) {
                btn.classList.add('is-open');
                btn.setAttribute('aria-expanded', 'true');
                if (arrow) arrow.style.transform = 'rotate(180deg)';
                menu.style.maxHeight = menu.scrollHeight + 'px';
                menu.classList.remove('max-h-0');
                menu.classList.add('max-h-96');
            } else {
                btn.classList.remove('is-open');
                btn.setAttribute('aria-expanded', 'false');
                if (arrow) arrow.style.transform = 'rotate(0deg)';
                menu.style.maxHeight = '0';
                menu.classList.add('max-h-0');
                menu.classList.remove('max-h-96');
            }
        }

        // Open dropdown that contains active route + 标记分组，使主行白底卡片与 Dashboard 一致
        document.querySelectorAll('#user-dashboard-sidebar .sidebar-dropdown').forEach(function(dd) {
            if (dd.querySelector('.is-active-sub')) {
                var btnToggle = dd.querySelector('.submenu-toggle');
                if (btnToggle) btnToggle.classList.add('is-section-active');
                setGroupOpenState(dd, true);
            }
        });

        document.querySelectorAll('#user-dashboard-sidebar .submenu-toggle').forEach(function(toggle) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var targetId = this.getAttribute('data-target');
                var dropdown = this.closest('.sidebar-dropdown');
                var targetMenu = document.getElementById(targetId);
                var arrow = this.querySelector('.submenu-arrow');
                var isOpen = this.classList.contains('is-open');

                if (isOpen) {
                    if (dropdown.querySelector('.is-active-sub')) {
                        return;
                    }
                    setGroupOpenState(dropdown, false);
                } else {
                    setGroupOpenState(dropdown, true);
                }
            });
        });
    });
</script>