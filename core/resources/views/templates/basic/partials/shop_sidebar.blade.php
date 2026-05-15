<aside class="listing-sidebar" id="listingSidebar" data-aos="fade-right">
    <div class="sidebar-mobile-header">
        <span>{{ __('Bộ lọc tìm kiếm') }}</span>
        <i class="fa-solid fa-xmark" id="closeFilters"></i>
    </div>

    {{-- Header cho Desktop --}}
    <div class="sidebar-header d-none d-lg-flex" style="justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #f1f5f9;">
        <h3 style="font-size: 20px; font-weight: 700; margin: 0; color: #0f172a;">{{ __('Bộ lọc') }}</h3>
        <a href="javascript:void(0)" class="btn-clear-filters" style="font-size: 13px; color: #ff4d4f; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 5px;">
            <i class="fa-solid fa-rotate-left"></i> {{ __('Xóa tất cả') }}
        </a>
    </div>

    <div class="listing-sidebar-scrollable">
        {{-- Danh mục --}}
        <div class="filter-widget">
            <div class="widget-header" >
                <h4 class="widget-title" >{{ __('Danh mục') }}</h4>
                <i class="fa-solid fa-chevron-up toggle-widget" style="position: absolute; right: 0; top: 2px;"></i>
            </div>
            <div class="widget-content">
                <ul class="filter-list category-tree">
                    @foreach($categories as $cat)
                        @include('Template::partials.category_item', ['category' => $cat, 'activeCategoryId' => @$activeCategoryId])
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Lọc theo giá --}}
        <div class="filter-widget">
            <div class="widget-header" >
                <h4 class="widget-title" >{{ __('Lọc theo giá') }}</h4>
            </div>
            <div class="price-range-wrapper">
                <input type="range" min="{{ $min_price ?? 0 }}" max="{{ $max_price ?? 100000000 }}" step="1000" 
                       value="{{ request()->max ?? $max_price ?? 100000000 }}" id="priceRange" class="trigger-filter" />
                <div class="price-labels">
                    <span id="priceMin">{{ showAmount($min_price ?? 0) }}</span>
                    <span id="priceMax">{{ showAmount(request()->max ?? $max_price ?? 100000000) }}</span>
                </div>
            </div>
        </div>

        {{-- Thương hiệu --}}
        <div class="filter-widget">
            <div class="widget-header" >
                <h4 class="widget-title" >{{ __('Thương hiệu') }}</h4>
                <i class="fa-solid fa-chevron-down toggle-widget" style="position: absolute; right: 0; top: 2px;"></i>
            </div>
            <div class="widget-content" style="display: none;">
                <ul class="filter-list">
                    @foreach($brands as $brand)
                        <li>
                            <label>
                                <input type="checkbox" class="filter-brand trigger-filter" value="{{ $brand->id }}" 
                                       {{ (isset($activeBrandId) && $activeBrandId == $brand->id) || (is_array(request()->brand) && in_array($brand->id, request()->brand)) ? 'checked' : '' }} /> 
                                {{ __($brand->name) }} <span>({{ $brand->products_count ?? 0 }})</span>
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Loại sản phẩm --}}
        <div class="filter-widget">
            <div class="widget-header" >
                <h4 class="widget-title" >{{ __('Loại sản phẩm') }}</h4>
                <i class="fa-solid fa-chevron-down toggle-widget" style="position: absolute; right: 0; top: 2px;"></i>
            </div>
            <div class="widget-content" style="display: none;">
                <ul class="filter-list">
                    @foreach($productTypes as $type)
                        <li>
                            <label>
                                <input type="checkbox" class="filter-type trigger-filter" value="{{ $type->id }}" 
                                       {{ (isset($activeTypeId) && $activeTypeId == $type->id) || (is_array(request()->product_type) && in_array($type->id, request()->product_type)) ? 'checked' : '' }} /> 
                                {{ __($type->name) }} <span>({{ $type->products_count ?? 0 }})</span>
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="sidebar-mobile-footer">
        <button class="btn-reset-filter btn-clear-filters">{{ __('Thiết lập lại') }}</button>
        <button class="btn-apply-filter" id="applyFilters">{{ __('Áp dụng') }}</button>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Khởi tạo thanh trượt giá
        function updatePriceRange() {
            const range = $('#priceRange');
            const val = range.val();
            const min = range.attr('min');
            const max = range.attr('max');
            const percent = ((val - min) / (max - min)) * 100;
            range.css('--range-progress', percent + '%');
        }
        updatePriceRange();

        // Thu gọn/mở rộng sub-category
        $(document).on('click', '.toggle-sub', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const parent = $(this).closest('.category-item');
            const subList = parent.find('> .sub-category-list');
            
            subList.slideToggle(300);
            $(this).toggleClass('fa-chevron-down fa-chevron-up');
        });

        // Thu gọn/mở rộng widget
        $(document).on('click', '.widget-header', function() {
            const content = $(this).next('.widget-content');
            const icon = $(this).find('.toggle-widget');
            
            if (content.length) {
                content.slideToggle(300);
                icon.toggleClass('fa-chevron-up fa-chevron-down');
            }
        });

        // Kích hoạt bộ lọc khi thay đổi checkbox
        $(document).on('change', '.trigger-filter', function() {
            if (window.innerWidth > 991) {
                filterProducts();
            }
        });

        // Lọc theo giá với debounce
        let priceTimer;
        $(document).on('input', '#priceRange', function() {
            const val = $(this).val();
            updatePriceRange();
            
            $('#priceMax').text(new Intl.NumberFormat('vi-VN').format(val) + 'đ');
            
            if (window.innerWidth > 991) {
                clearTimeout(priceTimer);
                priceTimer = setTimeout(function() {
                    filterProducts();
                }, 500);
            }
        });

        // Xóa bộ lọc
        $(document).on('click', '.btn-clear-filters', function() {
            $('.trigger-filter').prop('checked', false);
            $('#priceRange').val($('#priceRange').attr('max'));
            updatePriceRange();
            $('#priceMax').text(new Intl.NumberFormat('vi-VN').format($('#priceRange').attr('max')) + 'đ');
            filterProducts();
        });

        // Đóng sidebar trên mobile
        $('#closeFilters, #applyFilters').on('click', function() {
            $('#listingSidebar').removeClass('active');
            if ($(this).attr('id') === 'applyFilters') {
                filterProducts();
            }
        });
    });
</script>

<style>
    .price-range-wrapper {
        padding: 10px 0;
    }
    #priceRange {
        cursor: pointer;
        height: 6px;
    }
</style>
