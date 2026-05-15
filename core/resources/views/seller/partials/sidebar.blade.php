<div id="seller-dashboard-sidebar"
    class="w-full lg:w-[312px] shrink-0 bg-[#EBEBEB] rounded-lg border border-[#e6e6e6] pb-2">
    <!-- Navigation header -->
    <div class="flex items-start pl-5 pr-0 pt-6 pb-4">
        <span class="font-medium text-[#272343] text-xl leading-[30px]">
            @lang('Navigation')
        </span>
    </div>

    <!-- Dashboard -->
    <a href="{{ route('seller.home') }}" data-sidebar-item="seller-home"
        class="sidebar-plain flex items-center gap-[10px] px-5 py-1 min-h-[56px]">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M21 21H13V15H21V21ZM11 21H3V11H11V21ZM21 13H13V3H21V13ZM11 9H3V3H11V9Z" fill="#CCCCCC" />
        </svg>
        <span
            class="sidebar-plain-body flex flex-1 min-w-0 items-center justify-between gap-3 rounded-xl border border-transparent transition-all duration-200 p-[0.75rem]">
            <span class="sidebar-plain-text font-normal text-[#666] text-base leading-6">@lang('Dashboard')</span>
            <svg class="sidebar-plain-chevron hidden w-6 h-6 shrink-0 text-[#666]" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </span>
    </a>

    <!-- Profile -->
    <a href="{{ route('seller.profile') }}" data-sidebar-item="seller-profile"
        class="sidebar-plain flex items-center gap-[10px] px-5 py-1 min-h-[56px]">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path
                d="M12 4C14.21 4 16 5.79 16 8C16 10.21 14.21 12 12 12C9.79 12 8 10.21 8 8C8 5.79 9.79 4 12 4ZM12 14C16.42 14 20 15.79 20 18V20H4V18C4 15.79 7.58 14 12 14Z"
                fill="#CCCCCC" />
        </svg>
        <span
            class="sidebar-plain-body flex flex-1 min-w-0 items-center justify-between gap-3 rounded-xl border border-transparent transition-all duration-200 p-[0.75rem]">
            <span class="sidebar-plain-text font-normal text-[#666] text-base leading-6">@lang('Settings')</span>
            <svg class="sidebar-plain-chevron hidden w-6 h-6 shrink-0 text-[#666]" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </span>
    </a>

    <!-- My Shop -->
    <a href="{{ route('seller.shop') }}" data-sidebar-item="seller-shop"
        class="sidebar-plain flex items-center gap-[10px] px-5 py-1 min-h-[56px]">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path
                d="M8 10H5L3 21H21L19 10H16M8 10V7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7V10M8 10H16M8 10V13M16 10V13"
                stroke="#CCCCCC" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <span
            class="sidebar-plain-body flex flex-1 min-w-0 items-center justify-between gap-3 rounded-xl border border-transparent transition-all duration-200 p-[0.75rem]">
            <span class="sidebar-plain-text font-normal text-[#666] text-base leading-6">@lang('My Shop')</span>
            <svg class="sidebar-plain-chevron hidden w-6 h-6 shrink-0 text-[#666]" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </span>
    </a>

    <!-- Products Dropdown -->
    <div class="sidebar-dropdown mt-1" data-dropdown="san-pham">
        <div class="sidebar-group relative flex flex-col rounded-lg">
            <button type="button"
                class="submenu-toggle flex w-full min-w-0 items-center gap-[10px] px-5 py-1 min-h-[56px] text-left bg-transparent cursor-pointer"
                data-target="seller-products-submenu" aria-expanded="false">
                <span class="submenu-toggle-icon-slot relative inline-flex h-5 w-5 shrink-0 items-center justify-center"
                    aria-hidden="true">
                    <svg class="submenu-toggle-icon submenu-toggle-icon-outline absolute left-0 top-0 h-5 w-5 text-[#CCCCCC]"
                        xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                        fill="none">
                        <path
                            d="M5.83765 3.75H6.84741C7.18572 5.18268 8.47058 6.24776 10.0066 6.24902V6.25H10.0095V6.24902C11.5453 6.24749 12.8305 5.18256 13.1687 3.75H14.1785C14.4708 3.75009 14.7508 3.85082 14.9724 4.03223L15.0632 4.11426L18.7693 7.82422C18.867 7.92195 18.867 8.08098 18.7693 8.17871L17.1853 9.76367C17.0876 9.86131 16.9275 9.86137 16.8298 9.76367L15.5369 8.46973L14.2566 7.18945V16C14.2566 16.6888 13.6953 17.2498 13.0066 17.25H7.00659C6.31768 17.25 5.75659 16.6889 5.75659 16V7.18945L3.18237 9.76367C3.09701 9.84903 2.96423 9.85965 2.86694 9.7959L2.8269 9.76367L1.24683 8.17676L1.24585 8.17578C1.14828 8.07811 1.14835 7.91901 1.24585 7.82129L4.9519 4.11426C5.18502 3.8812 5.50362 3.75 5.83765 3.75Z"
                            stroke="currentColor" stroke-width="1.5" />
                    </svg>
                    <svg class="submenu-toggle-icon submenu-toggle-icon-filled absolute left-0 top-0 h-5 w-5"
                        xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                        <path
                            d="M10.0062 5.5C11.3875 5.5 12.5062 4.38125 12.5062 3H14.1781C14.7093 3 15.2187 3.20937 15.5937 3.58437L19.2999 7.29375C19.6906 7.68437 19.6906 8.31875 19.2999 8.70938L17.7156 10.2937C17.325 10.6844 16.6906 10.6844 16.3 10.2937L15.0062 9V16C15.0062 17.1031 14.1093 18 13.0062 18H7.0062C5.90308 18 5.0062 17.1031 5.0062 16V9L3.71245 10.2937C3.32183 10.6844 2.68745 10.6844 2.29683 10.2937L0.715576 8.70625C0.324951 8.31563 0.324951 7.68125 0.715576 7.29063L4.42183 3.58437C4.79683 3.20937 5.3062 3 5.83745 3H7.50933C7.50933 4.38125 8.62808 5.5 10.0093 5.5H10.0062Z"
                            fill="#000" />
                    </svg>
                </span>
                <span
                    class="sidebar-plain-body flex flex-1 min-w-0 items-center justify-between gap-3 rounded-xl border border-transparent transition-all duration-200 p-[0.75rem]">
                    <span
                        class="submenu-toggle-label flex-1 min-w-0 font-normal text-[#666] text-base leading-6 text-left">@lang('Products')</span>
                    <span
                        class="submenu-arrow inline-flex shrink-0 transition-transform duration-300 ease-in-out text-[#666]">
                        <svg class="w-3 h-3" viewBox="0 0 8 5" fill="none" xmlns="http://www.w3.org/2000/svg"
                            aria-hidden="true">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M7.24849 0.195C6.98849 -0.065 6.56849 -0.065 6.30849 0.195L3.72183 2.78167L1.13516 0.195C0.875161 -0.0650003 0.455161 -0.0650003 0.195161 0.195C-0.0648389 0.455 -0.0648389 0.875 0.195161 1.135L3.25516 4.195C3.51516 4.455 3.93516 4.455 4.19516 4.195L7.25516 1.135C7.50849 0.881667 7.50849 0.455 7.24849 0.195Z"
                                fill="currentColor" />
                        </svg>
                    </span>
                </span>
            </button>
            <div id="seller-products-submenu"
                class="submenu-content overflow-hidden transition-all duration-300 ease-in-out max-h-0">
                <nav class="submenu-tree submenu-tree-panel flex flex-col pb-2" aria-label="@lang('Products')">
                    <a href="{{ route('seller.products.all') }}" data-sidebar-sub="seller-products-all"
                        class="sidebar-sub flex w-full items-center pl-3 min-h-[44px] text-[15px] leading-snug text-[#666] rounded-lg transition-colors">
                        <span class="shrink-0 w-[25px] flex justify-center self-stretch text-[#c4c4c4]"
                            aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="49"
                                viewBox="0 0 25 49" fill="none">
                                <path d="M11 2C11 1.44771 11.4477 1 12 1C12.5523 1 13 1.44772 13 2V49H11V2Z"
                                    fill="#D4D4D4" />
                                <path d="M12 1V17C12 21.4183 15.5817 25 20 25H24" stroke="#D4D4D4" stroke-width="2"
                                    stroke-linecap="round" />
                            </svg>
                        </span>
                        <span
                            class="flex-1 font-normal py-3 pl-3 sidebar-sub-item rounded-[16px_0_0_16px]">@lang('All Products')</span>
                    </a>
                    <a href="{{ route('seller.products.create') }}" data-sidebar-sub="seller-products-create"
                        class="sidebar-sub flex w-full items-center pl-3 min-h-[44px] text-[15px] leading-snug text-[#666] rounded-lg transition-colors">
                        <span class="shrink-0 w-[25px] flex justify-center self-stretch text-[#c4c4c4]"
                            aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="49"
                                viewBox="0 0 25 49" fill="none">
                                <path d="M12 1V17C12 21.4183 15.5817 25 20 25H24" stroke="#D4D4D4" stroke-width="2"
                                    stroke-linecap="round" />
                            </svg>
                        </span>
                        <span
                            class="flex-1 font-normal py-3 pl-3 sidebar-sub-item rounded-[16px_0_0_16px]">@lang('Add Product')</span>
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <!-- Real Estate Dropdown -->
    <div class="sidebar-dropdown" data-dropdown="bat-dong-san">
        <div class="sidebar-group relative flex flex-col rounded-lg">
            <button type="button"
                class="submenu-toggle flex w-full min-w-0 items-center gap-[10px] px-5 py-1 min-h-[56px] text-left bg-transparent cursor-pointer"
                data-target="seller-realestate-submenu" aria-expanded="false">
                <span
                    class="submenu-toggle-icon-slot relative inline-flex h-5 w-5 shrink-0 items-center justify-center"
                    aria-hidden="true">
                    <svg class="submenu-toggle-icon submenu-toggle-icon-outline absolute left-0 top-0 h-5 w-5 text-[#CCCCCC]"
                        xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                        fill="none">
                        <path
                            d="M10.0005 3.41699C10.2685 3.41705 10.4944 3.49425 10.6968 3.64844L10.6997 3.65039L15.6997 7.40039L15.7046 7.40332C15.8475 7.50814 15.9598 7.64074 16.0435 7.80762C16.1268 7.97391 16.1669 8.14735 16.1665 8.33301V15.834C16.1664 16.1582 16.0571 16.4237 15.8237 16.6572C15.59 16.8909 15.3241 17.0004 15.0005 17H12.5005C12.3906 17 12.3222 16.9684 12.2612 16.9072C12.2148 16.8606 12.1853 16.81 12.1733 16.7412L12.1665 16.666V12.5C12.1664 12.1378 12.0385 11.8106 11.7798 11.5527C11.522 11.2959 11.1956 11.1678 10.8345 11.167H9.1665C8.80438 11.1671 8.47704 11.2951 8.21924 11.5537C7.96237 11.8115 7.83438 12.1379 7.8335 12.499V16.667C7.8335 16.7769 7.80147 16.8455 7.73975 16.9072C7.67805 16.9689 7.61002 17.0002 7.50146 17H5.00049C4.67569 17 4.41025 16.8902 4.17725 16.6572C3.97333 16.4533 3.86285 16.2245 3.83838 15.9521L3.8335 15.833V8.33398C3.8335 8.14799 3.87405 7.97421 3.95752 7.80762C4.0413 7.64045 4.15391 7.5081 4.29639 7.40332L9.30029 3.65039L9.30322 3.64844C9.5057 3.49417 9.73229 3.41699 10.0005 3.41699Z"
                            stroke="currentColor" stroke-width="1.5" />
                    </svg>
                    <svg class="submenu-toggle-icon submenu-toggle-icon-filled absolute left-0 top-0 h-5 w-5"
                        xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                        <path
                            d="M3.3335 15.8337V8.33366C3.3335 8.06977 3.39266 7.81977 3.511 7.58366C3.62933 7.34755 3.79239 7.1531 4.00016 7.00033L9.00016 3.25033C9.29183 3.0281 9.62516 2.91699 10.0002 2.91699C10.3752 2.91699 10.7085 3.0281 11.0002 3.25033L16.0002 7.00033C16.2085 7.1531 16.3718 7.34755 16.4902 7.58366C16.6085 7.81977 16.6674 8.06977 16.6668 8.33366V15.8337C16.6668 16.292 16.5035 16.6845 16.1768 17.0112C15.8502 17.3378 15.4579 17.5009 15.0002 17.5003H12.5002C12.2641 17.5003 12.0663 17.4203 11.9068 17.2603C11.7474 17.1003 11.6674 16.9025 11.6668 16.667V12.5003C11.6668 12.2642 11.5868 12.0664 11.4268 11.907C11.2668 11.7475 11.0691 11.6675 10.8335 11.667H9.16683C8.93072 11.667 8.73294 11.747 8.5735 11.907C8.41405 12.067 8.33405 12.2648 8.3335 12.5003V16.667C8.3335 16.9031 8.2535 17.1012 8.0935 17.2612C7.9335 17.4212 7.73572 17.5009 7.50016 17.5003H5.00016C4.54183 17.5003 4.14961 17.3373 3.8235 17.0112C3.49738 16.685 3.33405 16.2925 3.3335 15.8337Z"
                            fill="#000" />
                    </svg>
                </span>
                <span
                    class="sidebar-plain-body flex flex-1 min-w-0 items-center justify-between gap-3 rounded-xl border border-transparent transition-all duration-200 p-[0.75rem]">
                    <span
                        class="submenu-toggle-label flex-1 min-w-0 font-normal text-[#666] text-base leading-6 text-left">@lang('Real Estate')</span>
                    <span
                        class="submenu-arrow inline-flex shrink-0 transition-transform duration-300 ease-in-out text-[#666]">
                        <svg class="w-3 h-3" viewBox="0 0 8 5" fill="none" xmlns="http://www.w3.org/2000/svg"
                            aria-hidden="true">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M7.24849 0.195C6.98849 -0.065 6.56849 -0.065 6.30849 0.195L3.72183 2.78167L1.13516 0.195C0.875161 -0.0650003 0.455161 -0.0650003 0.195161 0.195C-0.0648389 0.455 -0.0648389 0.875 0.195161 1.135L3.25516 4.195C3.51516 4.455 3.93516 4.455 4.19516 4.195L7.25516 1.135C7.50849 0.881667 7.50849 0.455 7.24849 0.195Z"
                                fill="currentColor" />
                        </svg>
                    </span>
                </span>
            </button>
            <div id="seller-realestate-submenu"
                class="submenu-content overflow-hidden transition-all duration-300 ease-in-out max-h-0">
                <nav class="submenu-tree submenu-tree-panel flex flex-col pb-2" aria-label="@lang('Real Estate')">
                    <a href="{{ route('seller.products.real_estate.index') }}"
                        data-sidebar-sub="seller-re-index" data-section-slug="bat-dong-san"
                        class="sidebar-sub flex w-full items-center pl-3 min-h-[44px] text-[15px] leading-snug text-[#666] rounded-lg transition-colors">
                        <span class="shrink-0 w-[25px] flex justify-center self-stretch text-[#c4c4c4]"
                            aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="49"
                                viewBox="0 0 25 49" fill="none">
                                <path d="M11 2C11 1.44771 11.4477 1 12 1C12.5523 1 13 1.44772 13 2V49H11V2Z"
                                    fill="#D4D4D4" />
                                <path d="M12 1V17C12 21.4183 15.5817 25 20 25H24" stroke="#D4D4D4" stroke-width="2"
                                    stroke-linecap="round" />
                            </svg>
                        </span>
                        <span
                            class="flex-1 font-normal py-3 pl-3 sidebar-sub-item rounded-[16px_0_0_16px]">@lang('Manage Listings')</span>
                    </a>
                    <a href="{{ route('seller.products.real_estate.create') }}"
                        data-sidebar-sub="seller-re-create" data-section-slug="bat-dong-san"
                        class="sidebar-sub flex w-full items-center pl-3 min-h-[44px] text-[15px] leading-snug text-[#666] rounded-lg transition-colors">
                        <span class="shrink-0 w-[25px] flex justify-center self-stretch text-[#c4c4c4]"
                            aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="49"
                                viewBox="0 0 25 49" fill="none">
                                <path d="M12 1V17C12 21.4183 15.5817 25 20 25H24" stroke="#D4D4D4" stroke-width="2"
                                    stroke-linecap="round" />
                            </svg>
                        </span>
                        <span
                            class="flex-1 font-normal py-3 pl-3 sidebar-sub-item rounded-[16px_0_0_16px]">@lang('Add New Listing')</span>
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <!-- Jobs Recruitment Dropdown -->
    <div class="sidebar-dropdown" data-dropdown="job-recruitment">
        <div class="sidebar-group relative flex flex-col rounded-lg">
            <button type="button"
                class="submenu-toggle flex w-full min-w-0 items-center gap-[10px] px-5 py-1 min-h-[56px] text-left bg-transparent cursor-pointer"
                data-target="seller-job-recruitment-submenu" aria-expanded="false">
                <span
                    class="submenu-toggle-icon-slot relative inline-flex h-5 w-5 shrink-0 items-center justify-center"
                    aria-hidden="true">
                    <svg class="submenu-toggle-icon submenu-toggle-icon-outline absolute left-0 top-0 h-5 w-5 text-[#CCCCCC]"
                        xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                        fill="none">
                        <path d="M16 7V5C16 3.89543 15.1046 3 14 3H6C4.89543 3 4 3.89543 4 5V7M16 7C17.1046 7 18 7.89543 18 9V15C18 16.1046 17.1046 17 16 17H4C2.89543 17 2 16.1046 2 15V9C2 7.89543 2.89543 7 4 7M16 7H4M10 10V10.01M7 10V10.01M13 10V10.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <svg class="submenu-toggle-icon submenu-toggle-icon-filled absolute left-0 top-0 h-5 w-5"
                        xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M16 7V5C16 3.89543 15.1046 3 14 3H6C4.89543 3 4 3.89543 4 5V7M16 7C17.1046 7 18 7.89543 18 9V15C18 16.1046 17.1046 17 16 17H4C2.89543 17 2 16.1046 2 15V9C2 7.89543 2.89543 7 4 7M16 7H4M10 10V10.01M7 10V10.01M13 10V10.01" fill="black" stroke="black" stroke-width="0.5"/>
                    </svg>
                </span>
                <span
                    class="sidebar-plain-body flex flex-1 min-w-0 items-center justify-between gap-3 rounded-xl border border-transparent transition-all duration-200 p-[0.75rem]">
                    <span
                        class="submenu-toggle-label flex-1 min-w-0 font-normal text-[#666] text-base leading-6 text-left">@lang('Recruitment')</span>
                    <span
                        class="submenu-arrow inline-flex shrink-0 transition-transform duration-300 ease-in-out text-[#666]">
                        <svg class="w-3 h-3" viewBox="0 0 8 5" fill="none" xmlns="http://www.w3.org/2000/svg"
                            aria-hidden="true">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M7.24849 0.195C6.98849 -0.065 6.56849 -0.065 6.30849 0.195L3.72183 2.78167L1.13516 0.195C0.875161 -0.0650003 0.455161 -0.0650003 0.195161 0.195C-0.0648389 0.455 -0.0648389 0.875 0.195161 1.135L3.25516 4.195C3.51516 4.455 3.93516 4.455 4.19516 4.195L7.25516 1.135C7.50849 0.881667 7.50849 0.455 7.24849 0.195Z"
                                fill="currentColor" />
                        </svg>
                    </span>
                </span>
            </button>
            <div id="seller-job-recruitment-submenu"
                class="submenu-content overflow-hidden transition-all duration-300 ease-in-out max-h-0">
                <nav class="submenu-tree submenu-tree-panel flex flex-col pb-2" aria-label="@lang('Recruitment')">
                    <a href="{{ route('seller.jobs.index', ['type' => 1]) }}" data-sidebar-sub="seller-jobs-all"
                        class="sidebar-sub flex w-full items-center pl-3 min-h-[44px] text-[15px] leading-snug text-[#666] rounded-lg transition-colors">
                        <span class="shrink-0 w-[25px] flex justify-center self-stretch text-[#c4c4c4]"
                            aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="49"
                                viewBox="0 0 25 49" fill="none">
                                <path d="M11 2C11 1.44771 11.4477 1 12 1C12.5523 1 13 1.44772 13 2V49H11V2Z"
                                    fill="#D4D4D4" />
                                <path d="M12 1V17C12 21.4183 15.5817 25 20 25H24" stroke="#D4D4D4" stroke-width="2"
                                    stroke-linecap="round" />
                            </svg>
                        </span>
                        <span
                            class="flex-1 font-normal py-3 pl-3 sidebar-sub-item rounded-[16px_0_0_16px]">@lang('Manage Recruitment Ads')</span>
                    </a>
                    <a href="{{ route('seller.jobs.create', ['type' => 1]) }}" data-sidebar-sub="seller-jobs-create"
                        class="sidebar-sub flex w-full items-center pl-3 min-h-[44px] text-[15px] leading-snug text-[#666] rounded-lg transition-colors">
                        <span class="shrink-0 w-[25px] flex justify-center self-stretch text-[#c4c4c4]"
                            aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="49"
                                viewBox="0 0 25 49" fill="none">
                                <path d="M12 1V17C12 21.4183 15.5817 25 20 25H24" stroke="#D4D4D4" stroke-width="2"
                                    stroke-linecap="round" />
                            </svg>
                        </span>
                        <span
                            class="flex-1 font-normal py-3 pl-3 sidebar-sub-item rounded-[16px_0_0_16px]">@lang('Post Recruitment Ad')</span>
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <!-- Jobs Seeking Dropdown -->
    <div class="sidebar-dropdown" data-dropdown="job-seeking">
        <div class="sidebar-group relative flex flex-col rounded-lg">
            <button type="button"
                class="submenu-toggle flex w-full min-w-0 items-center gap-[10px] px-5 py-1 min-h-[56px] text-left bg-transparent cursor-pointer"
                data-target="seller-job-seeking-submenu" aria-expanded="false">
                <span
                    class="submenu-toggle-icon-slot relative inline-flex h-5 w-5 shrink-0 items-center justify-center"
                    aria-hidden="true">
                    <svg class="submenu-toggle-icon submenu-toggle-icon-outline absolute left-0 top-0 h-5 w-5 text-[#CCCCCC]"
                        xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                        fill="none">
                        <path d="M10 10C12.21 10 14 8.21 14 6C14 3.79 12.21 2 10 2C7.79 2 6 3.79 6 6C6 8.21 7.79 10 10 10ZM10 12C7.33 12 2 13.34 2 16V18H18V16C18 13.34 12.67 12 10 12Z" fill="#CCCCCC"/>
                    </svg>
                    <svg class="submenu-toggle-icon submenu-toggle-icon-filled absolute left-0 top-0 h-5 w-5"
                        xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M10 10C12.21 10 14 8.21 14 6C14 3.79 12.21 2 10 2C7.79 2 6 3.79 6 6C6 8.21 7.79 10 10 10ZM10 12C7.33 12 2 13.34 2 16V18H18V16C18 13.34 12.67 12 10 12Z" fill="black"/>
                    </svg>
                </span>
                <span
                    class="sidebar-plain-body flex flex-1 min-w-0 items-center justify-between gap-3 rounded-xl border border-transparent transition-all duration-200 p-[0.75rem]">
                    <span
                        class="submenu-toggle-label flex-1 min-w-0 font-normal text-[#666] text-base leading-6 text-left">@lang('Job Seekers')</span>
                    <span
                        class="submenu-arrow inline-flex shrink-0 transition-transform duration-300 ease-in-out text-[#666]">
                        <svg class="w-3 h-3" viewBox="0 0 8 5" fill="none" xmlns="http://www.w3.org/2000/svg"
                            aria-hidden="true">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M7.24849 0.195C6.98849 -0.065 6.56849 -0.065 6.30849 0.195L3.72183 2.78167L1.13516 0.195C0.875161 -0.0650003 0.455161 -0.0650003 0.195161 0.195C-0.0648389 0.455 -0.0648389 0.875 0.195161 1.135L3.25516 4.195C3.51516 4.455 3.93516 4.455 4.19516 4.195L7.25516 1.135C7.50849 0.881667 7.50849 0.455 7.24849 0.195Z"
                                fill="currentColor" />
                        </svg>
                    </span>
                </span>
            </button>
            <div id="seller-job-seeking-submenu"
                class="submenu-content overflow-hidden transition-all duration-300 ease-in-out max-h-0">
                <nav class="submenu-tree submenu-tree-panel flex flex-col pb-2" aria-label="@lang('Job Seekers')">
                    <a href="{{ route('seller.jobs.index', ['type' => 2]) }}" data-sidebar-sub="seller-jobs-seeking-all"
                        class="sidebar-sub flex w-full items-center pl-3 min-h-[44px] text-[15px] leading-snug text-[#666] rounded-lg transition-colors">
                        <span class="shrink-0 w-[25px] flex justify-center self-stretch text-[#c4c4c4]"
                            aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="49"
                                viewBox="0 0 25 49" fill="none">
                                <path d="M11 2C11 1.44771 11.4477 1 12 1C12.5523 1 13 1.44772 13 2V49H11V2Z"
                                    fill="#D4D4D4" />
                                <path d="M12 1V17C12 21.4183 15.5817 25 20 25H24" stroke="#D4D4D4" stroke-width="2"
                                    stroke-linecap="round" />
                            </svg>
                        </span>
                        <span
                            class="flex-1 font-normal py-3 pl-3 sidebar-sub-item rounded-[16px_0_0_16px]">@lang('Manage Job Seekers')</span>
                    </a>
                    <a href="{{ route('seller.jobs.create', ['type' => 2]) }}" data-sidebar-sub="seller-jobs-seeking-create"
                        class="sidebar-sub flex w-full items-center pl-3 min-h-[44px] text-[15px] leading-snug text-[#666] rounded-lg transition-colors">
                        <span class="shrink-0 w-[25px] flex justify-center self-stretch text-[#c4c4c4]"
                            aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="49"
                                viewBox="0 0 25 49" fill="none">
                                <path d="M12 1V17C12 21.4183 15.5817 25 20 25H24" stroke="#D4D4D4" stroke-width="2"
                                    stroke-linecap="round" />
                            </svg>
                        </span>
                        <span
                            class="flex-1 font-normal py-3 pl-3 sidebar-sub-item rounded-[16px_0_0_16px]">@lang('Post Job Seeking Ad')</span>
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <!-- Orders -->
    <a href="{{ route('seller.order.all') }}" data-sidebar-item="seller-orders"
        class="sidebar-plain flex items-center gap-[10px] px-5 py-1 min-h-[56px]">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path
                d="M8.33301 15.75C8.57608 15.75 8.80955 15.8467 8.98145 16.0186C9.15335 16.1905 9.25 16.4239 9.25 16.667C9.25 16.9101 9.15335 17.1435 8.98145 17.3154C8.80955 17.4873 8.57608 17.584 8.33301 17.584C8.08996 17.5839 7.85643 17.4873 7.68457 17.3154C7.51272 17.1435 7.41699 16.9101 7.41699 16.667C7.41699 16.4239 7.51272 16.1905 7.68457 16.0186C7.85643 15.8467 8.08996 15.7501 8.33301 15.75ZM14.167 15.75C14.4098 15.7501 14.6427 15.8469 14.8145 16.0186C14.9864 16.1905 15.083 16.4239 15.083 16.667C15.083 16.9101 14.9864 17.1435 14.8145 17.3154C14.6427 17.4871 14.4098 17.5839 14.167 17.584C13.924 17.584 13.6904 17.4872 13.5186 17.3154C13.3466 17.1435 13.25 16.9101 13.25 16.667C13.25 16.4239 13.3466 16.1905 13.5186 16.0186C13.6904 15.8468 13.924 15.75 14.167 15.75ZM4.16016 2.41699C4.17762 2.41695 4.19478 2.42245 4.20898 2.43262C4.22313 2.44277 4.23368 2.45714 4.23926 2.47363L5.16309 5.23828L5.33496 5.75H17.498C17.511 5.75003 17.5237 5.75376 17.5352 5.75977C17.5467 5.76584 17.557 5.77444 17.5645 5.78516C17.5718 5.79588 17.5765 5.80837 17.5781 5.82129C17.5797 5.83416 17.5778 5.84723 17.5732 5.85938L17.5723 5.86133L15.2725 11.9863V11.9883C15.2072 12.1629 15.0896 12.3135 14.9365 12.4199C14.7835 12.5262 14.6014 12.5837 14.415 12.584H8.09082C7.8981 12.5842 7.71004 12.5229 7.55371 12.4102C7.39758 12.2975 7.28042 12.1388 7.21973 11.9561V11.9551L4.26953 3.09668L4.09863 2.58398H2.4082V2.41699H4.16016Z"
                stroke="#CCCCCC" stroke-width="1.5" />
        </svg>
        <span
            class="sidebar-plain-body flex flex-1 min-w-0 items-center justify-between gap-3 rounded-xl border border-transparent transition-all duration-200 p-[0.75rem]">
            <span class="sidebar-plain-text font-normal text-[#666] text-base leading-6">@lang('Orders')</span>
            <svg class="sidebar-plain-chevron hidden w-6 h-6 shrink-0 text-[#666]" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </span>
    </a>

    <!-- Order History -->
    <a href="{{ route('seller.purchases.index') }}" data-sidebar-item="seller-purchases"
        class="sidebar-plain flex items-center gap-[10px] px-5 py-1 min-h-[56px]">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path
                d="M6 2L3 6V17C3 17.5304 3.21071 18.0391 3.58579 18.4142C3.96086 18.7893 4.46957 19 5 19H16C16.5304 19 17.0391 18.7893 17.4142 18.4142C17.7893 18.0391 18 17.5304 18 17V6L15 2H6ZM3 6H14M16 6H17.5M5 2V8H14V2M5 12H6M6 12V16H8M6 12H4M6 12V16H8M8 12H10M8 12V16H10M10 12H12M12 12V16H14"
                stroke="#CCCCCC" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <span
            class="sidebar-plain-body flex flex-1 min-w-0 items-center justify-between gap-3 rounded-xl border border-transparent transition-all duration-200 p-[0.75rem]">
            <span class="sidebar-plain-text font-normal text-[#666] text-base leading-6">@lang('Order History')</span>
            <svg class="sidebar-plain-chevron hidden w-6 h-6 shrink-0 text-[#666]" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </span>
    </a>

    <!-- Log out -->
    <a href="{{ route('seller.logout') }}" data-sidebar-item="seller-logout"
        class="sidebar-plain flex items-center gap-[10px] px-5 py-1 min-h-[56px]">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path
                d="M19 21H10C9.46957 21 8.96086 20.7893 8.58579 20.4142C8.21071 20.0391 8 19.5304 8 19V15H10V19H19V5H10V9H8V5C8 4.46957 8.21071 3.96086 8.58579 3.58579C8.96086 3.21071 9.46957 3 10 3H19C19.5304 3 20.0391 3.21071 20.4142 3.58579C20.7893 3.96086 21 4.46957 21 5V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21ZM12 16V13H3V11H12V8L17 12L12 16Z"
                fill="#CCCCCC" />
        </svg>
        <span
            class="sidebar-plain-body flex flex-1 min-w-0 items-center justify-between gap-3 rounded-xl border border-transparent transition-all duration-200 p-[0.75rem]">
            <span class="sidebar-plain-text font-normal text-[#666] text-base leading-6">@lang('Logout')</span>
            <svg class="sidebar-plain-chevron hidden w-6 h-6 shrink-0 text-[#666]" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </span>
    </a>
</div>

<style>
    /* 下拉主行：hover / 展开 / 当前子路由 — 图标、文案、箭头改品牌橙 */
    #seller-dashboard-sidebar .submenu-toggle:hover .submenu-toggle-icon,
    #seller-dashboard-sidebar .submenu-toggle.is-open .submenu-toggle-icon,
    #seller-dashboard-sidebar .submenu-toggle.is-section-active .submenu-toggle-icon {
        color: #ff6f0f;
    }

    /* Sản phẩm：点击展开或当前路由在子菜单时显示实心图标，否则线框图标 */
    #seller-dashboard-sidebar .sidebar-dropdown[data-dropdown="san-pham"] .submenu-toggle-icon-filled {
        display: none;
    }

    #seller-dashboard-sidebar .sidebar-dropdown[data-dropdown="san-pham"] .submenu-toggle.is-open .submenu-toggle-icon-outline,
    #seller-dashboard-sidebar .sidebar-dropdown[data-dropdown="san-pham"] .submenu-toggle.is-section-active .submenu-toggle-icon-outline {
        display: none;
    }

    #seller-dashboard-sidebar .sidebar-dropdown[data-dropdown="san-pham"] .submenu-toggle.is-open .submenu-toggle-icon-filled,
    #seller-dashboard-sidebar .sidebar-dropdown[data-dropdown="san-pham"] .submenu-toggle.is-section-active .submenu-toggle-icon-filled {
        display: block;
    }

    /* Bất động sản：点击展开或当前路由在子菜单时显示实心图标，否则线框图标 */
    #seller-dashboard-sidebar .sidebar-dropdown[data-dropdown="bat-dong-san"] .submenu-toggle-icon-filled {
        display: none;
    }

    #seller-dashboard-sidebar .sidebar-dropdown[data-dropdown="bat-dong-san"] .submenu-toggle.is-open .submenu-toggle-icon-outline,
    #seller-dashboard-sidebar .sidebar-dropdown[data-dropdown="bat-dong-san"] .submenu-toggle.is-section-active .submenu-toggle-icon-outline {
        display: none;
    }

    #seller-dashboard-sidebar .sidebar-dropdown[data-dropdown="bat-dong-san"] .submenu-toggle.is-open .submenu-toggle-icon-filled,
    #seller-dashboard-sidebar .sidebar-dropdown[data-dropdown="bat-dong-san"] .submenu-toggle.is-section-active .submenu-toggle-icon-filled {
        display: block;
    }

    /* Job：点击展开或当前路由在子菜单时显示实心图标，否则线框图标 */
    #seller-dashboard-sidebar .sidebar-dropdown[data-dropdown="job"] .submenu-toggle-icon-filled {
        display: none;
    }

    #seller-dashboard-sidebar .sidebar-dropdown[data-dropdown="job"] .submenu-toggle.is-open .submenu-toggle-icon-outline,
    #seller-dashboard-sidebar .sidebar-dropdown[data-dropdown="job"] .submenu-toggle.is-section-active .submenu-toggle-icon-outline {
        display: none;
    }

    #seller-dashboard-sidebar .sidebar-dropdown[data-dropdown="job"] .submenu-toggle.is-open .submenu-toggle-icon-filled,
    #seller-dashboard-sidebar .sidebar-dropdown[data-dropdown="job"] .submenu-toggle.is-section-active .submenu-toggle-icon-filled {
        display: block;
    }


    /* Ticket dropdown */
    #seller-dashboard-sidebar .sidebar-dropdown[data-dropdown="ticket"] .submenu-toggle-icon-filled {
        display: none;
    }

    #seller-dashboard-sidebar .sidebar-dropdown[data-dropdown="ticket"] .submenu-toggle.is-open .submenu-toggle-icon-outline,
    #seller-dashboard-sidebar .sidebar-dropdown[data-dropdown="ticket"] .submenu-toggle.is-section-active .submenu-toggle-icon-outline {
        display: none;
    }

    #seller-dashboard-sidebar .sidebar-dropdown[data-dropdown="ticket"] .submenu-toggle.is-open .submenu-toggle-icon-filled,
    #seller-dashboard-sidebar .sidebar-dropdown[data-dropdown="ticket"] .submenu-toggle.is-section-active .submenu-toggle-icon-filled {
        display: block;
    }

    #seller-dashboard-sidebar .submenu-toggle:hover .submenu-arrow,
    #seller-dashboard-sidebar .submenu-toggle.is-open .submenu-arrow,
    #seller-dashboard-sidebar .submenu-toggle.is-section-active .submenu-arrow {
        color: #ff6f0f;
    }

    #seller-dashboard-sidebar .sidebar-plain.is-active .sidebar-plain-body {
        padding: 0.75rem;
        background-color: #fff;
        border-radius: 0.75rem;
    }

    #seller-dashboard-sidebar .sidebar-plain.is-active .sidebar-plain-chevron {
        display: block !important;
    }

    #seller-dashboard-sidebar .sidebar-plain.is-active .sidebar-plain-text {
        font-weight: 600 !important;
        font-size: 15px !important;
        color: #303030 !important;
    }

    #seller-dashboard-sidebar .sidebar-plain.is-active .sidebar-plain-icon path {
        stroke: #ff6f0f;
    }

    #seller-dashboard-sidebar .sidebar-plain.is-active svg[fill="none"] path[fill="#CCCCCC"] {
        fill: #ff6f0f;
    }

    /* hover 与当前页同款：白底 + shadow + gray-200/80 描边（对齐 Tailwind 卡片） */
    #seller-dashboard-sidebar .sidebar-plain:not(.is-active):hover .sidebar-plain-body {
        padding: 0.75rem;
        background-color: #fff;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 0 0 1px rgb(229 231 235 / 0.8);
    }

    #seller-dashboard-sidebar .sidebar-plain:not(.is-active):hover .sidebar-plain-chevron {
        display: block !important;
    }

    /* 左侧品牌竖线在 button.submenu-toggle 上（DevTools 可见），默认透明避免布局跳动 */
    #seller-dashboard-sidebar .submenu-toggle {
        border: none;
        border-left: 3px solid transparent;
        transition: color 0.2s ease, border-color 0.2s ease;
    }

    #seller-dashboard-sidebar .submenu-toggle:hover,
    #seller-dashboard-sidebar .submenu-toggle.is-open,
    #seller-dashboard-sidebar .submenu-toggle.is-section-active {
        border-left-color: #ff6f0f;
    }

    /* Figma：有子菜单的主行不要白底卡片，铺在侧栏灰底上，仅左侧橙线 + 橙字/图标 */
    #seller-dashboard-sidebar .submenu-toggle .sidebar-plain-body {
        background-color: transparent !important;
        box-shadow: none !important;
        border-color: transparent !important;
    }

    #seller-dashboard-sidebar .submenu-toggle:hover .submenu-toggle-label,
    #seller-dashboard-sidebar .submenu-toggle.is-open .submenu-toggle-label,
    #seller-dashboard-sidebar .submenu-toggle.is-section-active .submenu-toggle-label {
        font-weight: 600 !important;
        font-size: 16px !important;
        color: #ff6f0f !important;
    }

    /* 子菜单：Figma 为浅灰圆角底，不用主菜单白卡片 */
    #seller-dashboard-sidebar .sidebar-sub {
        transition: background-color 0.15s ease, color 0.15s ease;
    }

    #seller-dashboard-sidebar .sidebar-sub:not(.is-active-sub):hover .sidebar-sub-item {
        background-color: #D9D9D9;
    }

    #seller-dashboard-sidebar .sidebar-sub.is-active-sub .sidebar-sub-item {
        background-color: #D9D9D9;
    }

    #seller-dashboard-sidebar .sidebar-sub.is-active-sub,
    #seller-dashboard-sidebar .sidebar-sub.is-active-sub .sidebar-sub-item {
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
                var params = new URLSearchParams(window.location.search);
                var linkParams = u.searchParams;

                // For job routes, we must match the 'type' parameter
                if (hp.indexOf('/seller/jobs') !== -1) {
                    var linkType = linkParams.get('type') || '1';
                    var currentType = params.get('type') || '1';
                    if (linkType !== currentType) return false;
                } else if (u.search && search && u.search !== search) {
                    // Original behavior for non-job routes
                    return false;
                }

                if (hp === path) return true;
                if (allowChildPath && path.length > hp.length && path.indexOf(hp + '/') === 0) return true;
                return false;
            } catch (e) {
                return false;
            }
        }

        // Mark plain links & card rows (exact path only)
        document.querySelectorAll('#seller-dashboard-sidebar a[data-sidebar-item]').forEach(function(a) {
            if (pathMatches(a.getAttribute('href'), false)) {
                a.classList.add('is-active');
            }
        });

        // Sub links: exact match only
        document.querySelectorAll('#seller-dashboard-sidebar a[data-sidebar-sub]').forEach(function(a) {
            if (pathMatches(a.getAttribute('href'), false)) {
                a.classList.add('is-active-sub');
            }
        });

        // Open dropdown that contains active route + mark section active
        document.querySelectorAll('#seller-dashboard-sidebar .sidebar-dropdown').forEach(function(dd) {
            var activeSub = dd.querySelector('.is-active-sub');
            if (activeSub) {
                var btnToggle = dd.querySelector('.submenu-toggle');
                if (btnToggle) btnToggle.classList.add('is-section-active');
                setGroupOpenState(dd, true);
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

        document.querySelectorAll('#seller-dashboard-sidebar .submenu-toggle').forEach(function(toggle) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var targetId = this.getAttribute('data-target');
                var dropdown = this.closest('.sidebar-dropdown');
                var arrow = this.querySelector('.submenu-arrow');
                var isOpen = this.classList.contains('is-open');

                if (isOpen) {
                    if (dropdown && dropdown.querySelector('.is-active-sub')) {
                        return;
                    }
                    if (dropdown) setGroupOpenState(dropdown, false);
                } else {
                    if (dropdown) setGroupOpenState(dropdown, true);
                }
            });
        });
    });
</script>