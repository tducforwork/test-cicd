@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.promotions.update', $promotion->id) }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">@lang('Edit Promotion'): {{ $promotion->name }}</h5>
                        <small class="text--info">@lang('Trong trường hợp sản phẩm có giá ưu đãi thì sẽ ưu tiên hiển thị giá ưu đãi của sản phẩm')</small>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Promotion Name')</label>
                                    <input type="text" class="form-control" name="name" value="{{ $promotion->name }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Discount Type')</label>
                                    <select class="form-control" id="discount_type" name="discount_type" required>
                                        <option value="1" {{ $promotion->discount_type == 1 ? 'selected' : '' }}>@lang('Fixed Amount')</option>
                                        <option value="2" {{ $promotion->discount_type == 2 ? 'selected' : '' }}>@lang('Percentage')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Discount Value')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="discount_value" value="{{ getAmount($promotion->discount_value) }}" required>
                                        <span class="input-group-text discount-label">{{ $promotion->discount_type == 1 ? gs('cur_text') : '%' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('Start Date')</label>
                                    <input type="text" name="start_date" id="start_date" class="form-control" value="{{ $promotion->start_date }}" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('End Date')</label>
                                    <input type="text" name="end_date" id="end_date" class="form-control" value="{{ $promotion->end_date }}" autocomplete="off" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Search & Add Products')</label>
                                    <select class="form-control" id="product-search-ajax">
                                        <option value="">@lang('Search by name...')</option>
                                    </select>
                                    <small class="text-muted"><i class="las la-info-circle"></i> @lang('Search and click on a product to add it to the promotion list below.')</small>
                                </div>
                                
                                <div class="table-responsive mt-4">
                                    <table class="table table--light style--two custom-data-table">
                                        <thead>
                                            <tr>
                                                <th>@lang('Product')</th>
                                                <th>@lang('Base Price')</th>
                                                <th>@lang('Action')</th>
                                            </tr>
                                        </thead>
                                        <tbody id="selected-products-list">
                                            @forelse($promotion->products as $product)
                                                <tr class="product-row">
                                                    <td>
                                                        <div class="customer-details d-block">
                                                            <span class="thumb"><img src="{{ $product->productImage() }}" alt="image" style="width:40px; height:40px; border-radius:4px;"></span>
                                                            <span class="name">{{ __($product->name) }}</span>
                                                            <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                                                        </div>
                                                    </td>
                                                    <td>{{ showAmount($product->base_price) }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-outline--danger remove-product-btn"><i class="la la-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr class="empty-row">
                                                    <td colspan="100%" class="text-center text-muted">@lang('No products selected yet.')</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Update')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.promotions.index') }}" class="btn btn-sm btn-outline--primary"><i class="las la-undo"></i>@lang('Back')</a>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            function dateRangePicker(element, minDate = moment().format('YYYY-MM-DD')) {
                $(element).daterangepicker({
                    autoUpdateInput: false,
                    singleDatePicker: true,
                    showDropdowns: true,
                    // minDate: minDate,
                    locale: {
                        format: 'YYYY-MM-DD'
                    }
                });
            }

            function applyDatePicker(element) {
                $(element).on('apply.daterangepicker', (event, picker) => {
                    $(event.target).val(picker.startDate.format('YYYY-MM-DD HH:mm:ss'));
                });
            }

            dateRangePicker('#start_date');
            dateRangePicker('#end_date');
            applyDatePicker('#start_date');
            applyDatePicker('#end_date');

            $('#discount_type').on('change', function() {
                if ($(this).val() == 1) {
                    $('.discount-label').text('{{ gs('cur_text') }}');
                } else {
                    $('.discount-label').text('%');
                }
            });

            // Rich Product Search
            $('#product-search-ajax').select2({
                ajax: {
                    url: '{{ route('admin.promotions.products') }}',
                    type: "get",
                    dataType: 'json',
                    delay: 500,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(response, params) {
                        params.page = params.page || 1;
                        return {
                            results: response.products,
                            pagination: { more: response.has_more }
                        };
                    },
                    cache: true
                },
                placeholder: "@lang('Search products...')",
                minimumInputLength: 1,
                templateResult: formatProduct,
                templateSelection: formatProductSelection
            });

            function formatProduct(product) {
                if (product.loading) return product.text;
                var $container = $(
                    "<div class='select2-result-product d-flex align-items-center'>" +
                        "<div class='select2-result-product__avatar mr-2'><img src='" + product.image + "' style='width:40px; height:40px; object-fit:cover; border-radius:4px;' /></div>" +
                        "<div class='select2-result-product__meta'>" +
                            "<div class='select2-result-product__title' style='font-weight:600;'>" + product.name + "</div>" +
                            "<div class='select2-result-product__price text-muted' style='font-size:12px;'>" + product.price + "</div>" +
                        "</div>" +
                    "</div>"
                );
                return $container;
            }

            function formatProductSelection(product) {
                return product.name || product.text;
            }

            // Handle Product Selection
            $('#product-search-ajax').on('select2:select', function (e) {
                var data = e.params.data;
                addProductToList(data);
                $(this).val(null).trigger('change');
            });

            function addProductToList(product) {
                // Check if already added
                if ($('#selected-products-list').find(`input[value="${product.id}"]`).length > 0) {
                    notify('error', 'Product already added');
                    return;
                }

                $('.empty-row').hide();

                var html = `
                    <tr class="product-row">
                        <td>
                            <div class="customer-details d-block">
                                <span class="thumb"><img src="${product.image}" alt="image" style="width:40px; height:40px; border-radius:4px;"></span>
                                <span class="name">${product.name}</span>
                                <input type="hidden" name="product_ids[]" value="${product.id}">
                            </div>
                        </td>
                        <td>${product.price}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline--danger remove-product-btn"><i class="la la-trash"></i></button>
                        </td>
                    </tr>
                `;
                $('#selected-products-list').append(html);
            }

            $(document).on('click', '.remove-product-btn', function() {
                $(this).closest('tr').remove();
                if ($('#selected-products-list').find('.product-row').length == 0) {
                    $('.empty-row').show();
                }
            });

        })(jQuery);
    </script>
@endpush
