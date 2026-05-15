@extends('Template::layouts.frontend')
@section('content')
    @php
        $breadcrumbItems = [];
        if (isset($category)) {
            $breadcrumbItems[] = ['name' => $category->name];
        } elseif (isset($brand)) {
            $breadcrumbItems[] = ['name' => $brand->name];
        } elseif (isset($activeTypeId)) {
            $breadcrumbItems[] = ['name' => $pageTitle];
        } else {
            $breadcrumbItems[] = ['name' => $pageTitle ?? 'Quảng Phát Mall'];
        }
    @endphp

    <x-breadcrumb :items="$breadcrumbItems" />

    <section class="listing-section" style="padding: 40px 0">
        <div class="container listing-grid-container">
            <!-- SIDEBAR FILTERS -->
            @include('Template::partials.shop_sidebar')

            <!-- PRODUCT CONTENT -->
            <main class="listing-main-content" data-aos="fade-up">
                <div class="loading-overlay">
                    <div class="loading-spinner"></div>
                </div>
                <div class="listing-header">
                    <h2 class="listing-title">{{ __($pageTitle) }}</h2>
                    <div class="listing-sort">
                        <span style="font-size: 14px; color: #666">{{ __('Sắp xếp theo') }}: </span>
                        <select class="sort-select" onchange="filterProducts()">
                            <option value="newest">{{ __('Mới nhất') }}</option>
                            <option value="price_low_to_high">{{ __('Giá thấp đến cao') }}</option>
                            <option value="price_high_to_low">{{ __('Giá cao đến thấp') }}</option>
                        </select>
                    </div>
                </div>

                <!-- PRODUCT GRID -->
                <div id="product-grid" class="product-grid-4">
                    @include('Template::partials.ajax_product_items', ['products' => $products])
                </div>

                <!-- PAGINATION -->
                <div id="pagination-container">
                    @include('Template::components.pagination', ['products' => $products])
                </div>
            </main>
        </div>
    </section>
    @push('style')
        <style>
            .listing-main-content {
                position: relative;
                min-height: 400px;
            }

            .loading-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.6);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 1000;
                visibility: hidden;
                opacity: 0;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                backdrop-filter: blur(4px);
            }

            .loading-overlay.active {
                visibility: visible;
                opacity: 1;
            }

            .loading-spinner {
                position: relative;
                width: 60px;
                height: 60px;
            }

            .loading-spinner::before,
            .loading-spinner::after {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                border-radius: 50%;
                border: 3px solid transparent;
                border-top-color: var(--accent);
                animation: spin 1s cubic-bezier(0.5, 0, 0.5, 1) infinite;
            }

            .loading-spinner::after {
                border-top-color: var(--primary);
                animation-duration: 1.5s;
                opacity: 0.3;
            }

            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }
        </style>
    @endpush
@endsection

@push('script')
    <script>
        let currentCategoryId = "{{ @$activeCategoryId }}";
        let isFirstLoad = true;

        function filterProducts(page = 1) {
            $('.loading-overlay').addClass('active');
            let brands = [];
            $('.filter-brand:checked').each(function () {
                brands.push($(this).val());
            });

            let types = [];
            $('.filter-type:checked').each(function () {
                types.push($(this).val());
            });

            let max_price = $('#priceRange').val();
            let sort = $('.sort-select').val();

            let data = {
                page: page,
                category_id: currentCategoryId,
                brand: brands,
                product_type: types,
                max: max_price,
                sort: sort
            };

            $.ajax({
                url: "{{ url()->current() }}",
                method: "GET",
                data: data,
                success: function (response) {
                    $('#product-grid').html(response.html);
                    $('#pagination-container').html(response.pagination);

                    // Sync URL
                    let query = $.param(data);
                    window.history.pushState(data, "", "{{ url()->current() }}?" + query);
                },
                complete: function () {
                    $('.loading-overlay').removeClass('active');
                }
            });
        }

        $(document).on('click', '.ajax-category', function (e) {
            e.preventDefault();
            currentCategoryId = $(this).data('id');

            // Update active state
            $('.ajax-category').removeClass('text-primary font-weight-bold active');
            $(this).addClass('text-primary font-weight-bold active');

            filterProducts();
        });

        $(document).on('click', '.btn-clear-filters', function () {
            // Uncheck all
            $('.trigger-filter').prop('checked', false);

            // Reset price range
            const maxPrice = $('#priceRange').attr('max');
            $('#priceRange').val(maxPrice);
            $('#priceMax').text(parseInt(maxPrice).toLocaleString('vi-VN') + 'đ');

            // Reset category (if desired, back to Mall)
            currentCategoryId = "";
            $('.ajax-category').removeClass('text-primary font-weight-bold active');

            filterProducts();
        });

        $(document).on('click', '.btn-apply-filters', function () {
            filterProducts();
        });

        $('#priceRange').on('input', function () {
            let val = parseInt($(this).val()).toLocaleString('vi-VN');
            $('#priceMax').text(val + 'đ');
        });
    </script>
@endpush