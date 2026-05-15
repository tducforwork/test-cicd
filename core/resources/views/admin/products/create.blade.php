@extends('admin.layouts.app')
@php
use App\Constants\Status;
@endphp

@section('panel')

<div class="row justify-content-center">

    <div class="loader-container text-center d-none">
        <span class="loader">
            <i class="fa fa-circle-notch fa-spin" aria-hidden="true"></i>
        </span>
    </div>

    <div class="col-lg-12">
        <form action="{{ route('admin.products.store', $product->id ?? 0) }}" id="addForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card p-2 has-select2">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Product Information')</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-2">
                            <label>@lang('Name') <span class="text--danger">*</span></label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="{{ old('name', @$product->name) }}" name="name" required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">
                            <label>@lang('Đơn vị tính')</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="{{ old('unit', @$product->unit) }}" name="unit" placeholder="@lang('Ví dụ: Cái, Bộ, Hộp...')" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">
                            <label>@lang('Tình trạng sản phẩm')</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="{{ old('condition', @$product->condition) }}" name="condition" placeholder="@lang('Ví dụ: Mới 100%, Đã qua sử dụng...')" />
                        </div>
                    </div>

                    <div class="form-group row sku-wrapper d-none">
                        <div class="col-md-2">
                            <label>@lang('Product SKU') <span class="text--danger">*</span></label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="{{ old('sku', @$product->sku) }}" name="sku" />
                        </div>
                    </div>

                    @if(!@$product->id)
                    <div class="form-group row quantity-wrapper">
                        <div class="col-md-2">
                            <label>@lang('Stock Quantity')</label>
                        </div>
                        <div class="col-md-10">
                            <input type="number" class="form-control" name="quantity" value="{{ old('quantity') }}" />
                        </div>
                    </div>
                    @endif




                    <div class="form-group row">
                        <div class="col-lg-2 col-md-3">
                            <label for="categories">@lang('Categories') <span class="text--danger">*</span></label>
                        </div>
                        <div class="col-lg-10 col-md-9 select2-parent">
                            <select class="category-select2 form-control" name="categories[]" id="categories">
                                <option value="">@lang('Select One')</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" data-title="{{ __($category->name) }}">@lang($category->name)</option>
                                @php
                                $prefix = '--';
                                @endphp
                                @foreach ($category->allSubcategories as $subcategory)
                                <option value="{{ $subcategory->id }}" data-title="{{ __($subcategory->name) }}">
                                    {{ $prefix }}@lang($subcategory->name)
                                </option>
                                @include('admin.partials.subcategories', ['subcategory' => $subcategory, 'prefix' => $prefix])
                                @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @php
                        $productConfig = \App\Models\ProductConfig::firstOrCreate([]);
                    @endphp
                    <div class="form-group row">
                        <div class="col-md-2">
                            <label>@lang('Currency')</label>
                        </div>
                        <div class="col-md-10">
                            <select name="currency_type" class="form-control" id="currency_type">
                                <option value="1" @selected(old('currency_type', @$product->currency_type) == 1)>VND</option>
                                <option value="2" @selected(old('currency_type', @$product->currency_type) == 2)>Tệ (CNY)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row cny-price-wrapper d-none">
                        <div class="col-md-2">
                            <label>@lang('Base Price') (CNY)</label>
                        </div>
                        <div class="col-md-10">
                            <div class="input-group">
                                <input type="text" class="form-control numeric-validation" name="cny_price" id="cny_price" value="{{ old('cny_price', @$product->cny_price ? getAmount($product->cny_price) : '') }}" />
                                <span class="input-group-text">CNY</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">
                            <label>@lang('Base Price') <span class="text--danger">*</span></label>
                        </div>
                        <div class="col-md-10">
                            <div class="input-group">
                                <input type="text" class="form-control numeric-validation" name="base_price" id="base_price" value="{{ old('base_price', @$product->base_price ? getAmount($product->base_price) : '') }}" required />
                                <span class="input-group-text">{{ __(gs('cur_text')) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row cny-discount-price-wrapper d-none">
                        <div class="col-md-2">
                            <label>@lang('Discount Price') (CNY)</label>
                        </div>
                        <div class="col-md-10">
                            <div class="input-group">
                                <input type="text" class="form-control numeric-validation" name="cny_discount_price" id="cny_discount_price" value="{{ old('cny_discount_price', @$product->cny_discount_price ? getAmount($product->cny_discount_price) : '') }}" />
                                <span class="input-group-text">CNY</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">
                            <label>@lang('Discount Price')</label>
                        </div>
                        <div class="col-md-10">
                            <div class="input-group">
                                <input type="text" class="form-control numeric-validation" name="discount_price" id="discount_price" value="{{ old('discount_price', @$product->discount_price ? getAmount($product->discount_price) : '') }}" />
                                <span class="input-group-text">{{ __(gs('cur_text')) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card p-2 my-3 border--primary shadow-sm">
                <div class="card-header bg--white border-bottom-0 pb-0">
                    <h5 class="card-title mb-0 text-primary"><i class="la la-certificate"></i> @lang('Brands & Product Types')</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="fw-bold mb-3 d-block text-dark opacity-75 border-left border-primary ps-2 border-3">@lang('Cấu hình hiển thị (Figma UI)')</label>
                        <div class="row g-3">
                            <div class="col-xl-4 col-lg-4 col-sm-6">
                                <label class="filter-item-label d-flex align-items-center py-2 px-3 rounded border transition-all cursor-pointer mb-0">
                                    <input type="checkbox" name="is_search" value="1" id="is_search" class="cursor-pointer m-0" @checked(old('is_search', @$product->is_search)) style="width: 18px; height: 18px; min-width: 18px;">
                                    <span class="ms-2 cursor-pointer text-muted" style="font-size: 14px; line-height: 1.2;">
                                        @lang('Tìm kiếm nhiều nhất')
                                    </span>
                                </label>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-sm-6">
                                <label class="filter-item-label d-flex align-items-center py-2 px-3 rounded border transition-all cursor-pointer mb-0">
                                    <input type="checkbox" name="is_topdeal" value="1" id="is_topdeal" class="cursor-pointer m-0" @checked(old('is_topdeal', @$product->is_topdeal)) style="width: 18px; height: 18px; min-width: 18px;">
                                    <span class="ms-2 cursor-pointer text-muted" style="font-size: 14px; line-height: 1.2;">
                                        @lang('Top Deal')
                                    </span>
                                </label>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-sm-6">
                                <label class="filter-item-label d-flex align-items-center py-2 px-3 rounded border transition-all cursor-pointer mb-0">
                                    <input type="checkbox" name="is_suggestion" value="1" id="is_suggestion" class="cursor-pointer m-0" @checked(old('is_suggestion', @$product->is_suggestion)) style="width: 18px; height: 18px; min-width: 18px;">
                                    <span class="ms-2 cursor-pointer text-muted" style="font-size: 14px; line-height: 1.2;">
                                        @lang('Gợi ý cho bạn')
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 opacity-25">

                    <div class="mb-4">
                        <label class="fw-bold mb-3 d-block text-dark opacity-75 border-left border-primary ps-2 border-3">@lang('Thương hiệu')</label>
                        <select name="brands[]" class="form-control select2-basic">
                            <option value="">@lang('Select One')</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" @selected(in_array($brand->id, old('brands', @$product->brand_id ? [@$product->brand_id] : [])))>
                                    {{ __($brand->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <hr class="my-4 opacity-25">

                    <div class="mb-4">
                        <label class="fw-bold mb-3 d-block text-dark opacity-75 border-left border-primary ps-2 border-3">@lang('Loại sản phẩm')</label>
                        <div class="row g-3">
                            @foreach($productTypes as $type)
                                <div class="col-xl-3 col-lg-4 col-sm-6">
                                    <label class="filter-item-label d-flex align-items-center py-2 px-3 rounded border transition-all cursor-pointer mb-0">
                                        <input type="checkbox" name="product_types[]" value="{{ $type->id }}" id="type_{{ $type->id }}" class="cursor-pointer m-0" @checked(in_array($type->id, old('product_types', @$product->productTypes ? $product->productTypes->pluck('id')->toArray() : []))) style="width: 18px; height: 18px; min-width: 18px;">
                                        <span class="ms-2 cursor-pointer text-muted" style="font-size: 14px; line-height: 1.2;">
                                            {{ __($type->name) }}
                                        </span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <hr class="my-4 opacity-25">

                    <div class="mb-4">
                        <label class="fw-bold mb-3 d-block text-dark opacity-75 border-left border-info ps-2 border-3">@lang('Tag sản phẩm & Flash Sale')</label>
                        <div class="row">
                            <div class="col-md-6 border-end">
                                <label class="text-muted small fw-bold mb-2">@lang('Chọn Tag sản phẩm')</label>
                                <small class="text-info d-block mb-2"><i class="las la-info-circle"></i> @lang('Có thể chọn nhiều tag, nhưng chỉ nên chọn 1 tag để hiển thị đẹp nhất.')</small>
                                <select name="tags[]" class="form-control select2-basic" multiple="multiple" id="tag-select">
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}" data-type="{{ $tag->type }}" @selected(in_array($tag->id, old('tags', @$product->tags ? $product->tags->pluck('id')->toArray() : [])))>
                                            {{ __($tag->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="tag-previews" class="mt-3 d-flex flex-wrap gap-2 p-3 bg-light rounded min-height-50">
                                    {{-- Tag previews will appear here --}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small fw-bold mb-2">@lang('Cấu hình Thanh tiến độ (Flash Sale)')</label>
                                <div class="form-group mb-2">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">% Độ dài</span>
                                        <input type="number" name="flash_percentage" class="form-control" value="{{ old('flash_percentage', @$product->flash_percentage) }}" min="0" max="100" id="flash-percent">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Nội dung</span>
                                        <input type="text" name="flash_text" class="form-control" value="{{ old('flash_text', @$product->flash_text) }}" placeholder="Ví dụ: Đã bán 58%" id="flash-text-input">
                                    </div>
                                </div>
                                <div class="flash-progress-preview p-3 bg-light rounded">
                                    <p class="small text-muted mb-2">@lang('Preview Thanh tiến độ'):</p>
                                    <div class="flash-progress">
                                        <div class="flash-progress-bar" style="width: {{ @$product->flash_percentage ?? 0 }}%"></div>
                                        <div class="flash-progress-text">{{ @$product->flash_text ?? 'Đã bán 0%' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <style>
                .filter-item-label {
                    background-color: #fff;
                    border-color: #ebebeb !important;
                }
                .filter-item-label:hover {
                    background-color: #f9f9f9;
                    border-color: #7c7c7c !important;
                }
                .filter-item-label input:checked + span {
                    color: #007bff !important;
                    font-weight: 600;
                }
                .transition-all {
                    transition: all 0.2s ease-in-out;
                }
            </style>





            <div class="card p-2 my-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Product Description')</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-2">
                            <label>@lang('Description') <span class="text--danger">*</span></label>
                        </div>
                        <div class="col-md-10">
                            <textarea rows="5" class="form-control ckeditor" name="description">{{ old('description', @$product->description) }}</textarea>
                        </div>
                    </div>

                </div>
            </div>



            <div class="card p-2 my-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('SEO Contents')</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-2">
                            <label>@lang('Meta Title')</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title', @$product->meta_title) }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">
                            <label>@lang('Meta Description')</label>
                        </div>
                        <div class="col-md-10">
                            <textarea name="meta_description" rows="5" class="form-control">{{ old('meta_description', @$product->meta_description) }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">
                            <label>@lang('Meta Keywords')</label>
                        </div>
                        <div class="col-md-10">
                            <select name="meta_keywords[]" class="form-control select2-auto-tokenize" multiple="multiple">
                                @php 
                                    $keywords = old('meta_keywords', @$product->meta_keywords); 
                                @endphp
                                @if ($keywords)
                                @foreach ($keywords as $option)
                                <option value="{{ $option }}" selected>{{ __($option) }}</option>
                                @endforeach
                                @endif
                            </select>
                            <small class="form-text text-muted">
                                <i class="las la-info-circle"></i>
                                @lang('Type , as separator or hit enter among keywords')
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card p-2 my-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Media Contents')</h5>
                </div>
                <div class="card-body">

                    <div class="form-group row">
                        <div class="col-md-2">
                            <label>@lang('Main Image')</label>
                        </div>
                        <div class="col-md-10">
                            <x-image-uploader class="w-50" type="product" :image="@$product->main_image" :name="'main_image'" :required="request()->routeIs('admin.products.create')" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">
                            <label>@lang('Additional Images')</label>
                        </div>
                        <div class="col-md-10">
                            <div class="input-field">
                                <div class="input-images"></div>
                                <small class="form-text text-muted">
                                    <i class="las la-info-circle"></i> @lang('You can only upload a maximum of 6 images')</label>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn w-100 h-45 btn--primary">@lang('Submit')</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button type="button" class="close ml-auto m-3" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body text-center">
                <i class="las la-times-circle f-size--100 text--danger mb-15"></i>
                <h3 class="text--danger mb-15">@lang('Error: Cannot process your entry!')</h3>
                <p class="mb-15">@lang('You can\'t add more than 6 image')</p>
                <button type="button" class="btn btn--danger" data-bs-dismiss="modal">@lang('Continue')</button>
            </div>
        </div>
    </div>
</div>

<x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
@if (@$product->seller_id)
@if ($product->status == Status::ENABLE)
<button type="button" class="confirmationBtn btn btn-sm btn-outline--danger" data-action="{{ route('admin.products.action', $product->id) }}" data-question="@lang('Are you sure to mark as pending?')"><i class="la la-ban"></i> @lang('Mark as Pending')</button>
@else
<button type="button" class="confirmationBtn btn btn-sm btn-outline--success" data-action="{{ route('admin.products.action', $product->id) }}" data-question="@lang('Are you sure to mark as pending?')"><i class="las la-check-double"></i> @lang('Approve')</button>
@endif
@endif

<x-back route="{{ route('admin.products.all') }}" />
@endpush

@push('script-lib')
<script src="{{ asset('assets/global/js/image-uploader.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
@endpush

@push('style-lib')
<link rel="stylesheet" href="{{ asset('assets/global/css/image-uploader.min.css') }}">
@endpush

@push('script')
<script>
    'use strict';
    (function($) {
        // jQuery Validation
        $("#addForm").validate({
            ignore: [], 
            rules: {
                name: {
                    required: true
                },
                sku: {
                    required: function(element) {
                        return $(element).is(':visible');
                    }
                },
                "categories[]": {
                    required: true
                },
                base_price: {
                    required: true,
                    number: true
                },
                description: {
                    required: true
                },
                main_image: {
                    required: {{ request()->routeIs('admin.products.create') ? 'true' : 'false' }} 
                }
            },
            messages: {
                name: {
                    required: "@lang('Vui lòng nhập tên sản phẩm')"
                },
                sku: {
                    required: "@lang('Vui lòng nhập mã sản phẩm (SKU)')"
                },
                "categories[]": {
                    required: "@lang('Vui lòng chọn ít nhất một danh mục')"
                },
                base_price: {
                    required: "@lang('Vui lòng nhập giá cơ bản')",
                    number: "@lang('Vui lòng nhập số hợp lệ')"
                },
                description: {
                    required: "@lang('Vui lòng nhập mô tả sản phẩm')"
                },
                main_image: {
                    required: "@lang('Vui lòng chọn hình ảnh chính')"
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2-container'));
                } else if (element.attr("name") == "main_image") {
                    error.insertAfter(element.closest('.image-upload-wrapper'));
                } else {
                    element.closest('.col-md-10').append(error);
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
            submitHandler: function(form) {
                // Sync ckeditor content to textarea before submission
                if (window.editors) {
                    for (const [key, editor] of Object.entries(window.editors)) {
                        editor.updateSourceElement();
                        $(editor.sourceElement).val(editor.getData());
                    }
                }
                
                // Final check for description after sync
                if($('textarea[name="description"]').val().trim() == "") {
                    // Manual trigger error for description if empty after sync
                    var validator = $("#addForm").validate();
                    validator.showErrors({
                        "description": "@lang('Vui lòng nhập mô tả sản phẩm')"
                    });
                    return false;
                }

                $(form).find('button[type="submit"]').prop('disabled', true);
                form.submit();
            }
        });

        // Trigger validation on Select2 change
        $('.category-select2').on('change', function() {
            $(this).valid();
        });

        var dropdownParent = $('.has-select2');

        let preloaded = @json($images ?? []);

        $('.input-images').imageUploader({
            preloaded: preloaded,
            imagesInputName: 'photos',
            preloadedInputName: 'old',
            maxFiles: 6
        });

        $(document).on('input', 'input[name="images[]"]', function() {
            var fileUpload = $("input[type='file']");
            if (parseInt(fileUpload.get(0).files.length) > 6) {
                $('#errorModal').modal('show');
            }
        });

        var categories = @json(old('categories') ?? (isset($product) && $product->categories ? $product->categories->pluck('id') : []));

        let categoriesSelect = $('.category-select2');
        categoriesSelect.val(categories).select2({
            dropdownParent: categoriesSelect.parent('.select2-parent')
        });

        $('.select2-basic').select2({
            dropdownParent: dropdownParent
        });

        $('.add-specification').on('click', function() {
            var specifications = $(document).find('.specifications');
            var length = specifications.length;
            $('.specification-info').addClass('d-none');
            var content = `<div class="specifications">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>${length+1}</label>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="specification[${length}][name]" placeholder="@lang('Product Information')">
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group abs-form-group d-flex justify-content-between">
                                                    <input type="text" class="form-control" name="specification[${length}][value]" placeholder="@lang('Name')">
                                                    <button type="button" class="btn btn-outline--danger remove-specification abs-button ms-2"><i class="la la-minus me-0"></i></button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>`;

            $(content).appendTo('.specifications-wrapper').hide().slideDown('slow');

            specifications = $(document).find('.specifications');
            length = specifications.length;

            if (length > 0) {
                $('.remove-specification').removeClass('d-none');
            } else {
                $('.remove-specification').addClass('d-none');
            }
        });

        $(document).on('click', '.remove-specification', function() {

            var parent = $(this).parents('.specifications');

            parent.slideUp('slow', function(e) {
                this.remove();
            });

        });

        $('.add-extra').on('click', function() {
            var extras = $(document).find('.extra');
            var length = extras.length;

            $('.extra-info').addClass('d-none');

            var content = `<div class="extra">
                                    <div class="d-flex justify-content-end mb-3">
                                        <button type="button" class="btn btn-outline--danger float-right  remove-extra"><i class="la la-minus"></i></button>
                                    </div>
                                <div class="form-group row">
                                    <div class="col-md-2">
                                        <label>@lang('Slug')</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="extra[${length + 1}][key]" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-2">
                                        <label>@lang('Generate')</label>
                                    </div>
                                    <div class="col-md-10">
                                        <textarea class="form-control" name="extra[${length + 1}][value]" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>`;


            var elm = $(content).appendTo('.extras').hide().slideDown('slow').find(`textarea[name="extra[${length +1}][value]"]`);
            ClassicEditor.create(elm[0], {
                extraPlugins: [ typeof MyCustomUploadAdapterPlugin !== 'undefined' ? MyCustomUploadAdapterPlugin : function(){} ]
            }).then(editor => {
                window.editors = window.editors || {};
                window.editors['extra_' + (length + 1)] = editor;
                editor.model.document.on('change:data', () => {
                    $(elm[0]).val(editor.getData());
                });
            }).catch(error => {
                console.error(error);
            });
            extras = $(document).find('.extra');
            length = extras.length;

            if (length != 0) {
                $('.remove-extra').removeClass('d-none');
            } else {
                $('.remove-extra').addClass('d-none');
            }
        });

        $(document).on('click', '.remove-extra', function() {
            var parent = $(this).parents('.extra');
            parent.slideUp('slow', function() {
                this.remove();
            });
        });

        $("input[name='base_price']").on('click', function() {
            if ($(this).val() == 0) {
                $(this).val('');
            }
        });

        if ($(document).find('input[name="has_variants"]').prop("checked") == true) {
            $(document).find('.sku-wrapper').hide();
            $(document).find('.quantity-wrapper').hide();
        }

        var cnyExchangeRate = {{ $productConfig->cny_exchange_rate }};
        
        $('#currency_type').on('change', function() {
            if($(this).val() == '2') {
                $('.cny-price-wrapper').removeClass('d-none');
                $('.cny-discount-price-wrapper').removeClass('d-none');
                $('#base_price, #discount_price').attr('readonly', true);
            } else {
                $('.cny-price-wrapper').addClass('d-none');
                $('.cny-discount-price-wrapper').addClass('d-none');
                $('#base_price, #discount_price').attr('readonly', false);
            }
        }).change();

        $('#cny_price').on('input', function() {
            var cny = parseFloat($(this).val()) || 0;
            var vnd = Math.round(cny * cnyExchangeRate);
            $('#base_price').val(vnd);
        });

        $('#cny_discount_price').on('input', function() {
            var cny = parseFloat($(this).val()) || 0;
            if(cny > 0) {
                var vnd = Math.round(cny * cnyExchangeRate);
                $('#discount_price').val(vnd);
            } else {
                $('#discount_price').val('');
            }
        });
        
        // Tag Preview Logic
        function updateTagPreviews() {
            let container = $('#tag-previews');
            container.empty();
            $('#tag-select option:selected').each(function() {
                let name = $(this).text().trim();
                let type = $(this).data('type');
                container.append(`<div class="p-tag ${type}">${name}</div>`);
            });
            if (container.children().length == 0) {
                container.append('<span class="text-muted small italic">Chưa chọn tag nào</span>');
            }
        }

        $('#tag-select').on('change', updateTagPreviews);
        updateTagPreviews();

        // Flash Progress Preview Logic
        function updateFlashPreview() {
            let percent = $('#flash-percent').val() || 0;
            let text = $('#flash-text-input').val() || `Đã bán ${percent}%`;
            $('.flash-progress-bar').css('width', `${percent}%`);
            $('.flash-progress-text').text(text);
        }

        $('#flash-percent, #flash-text-input').on('input', updateFlashPreview);



    })(jQuery)
</script>
@endpush

@push('style')
<style>
    .generate-slug {
        cursor: pointer;
    }

    /* Premium style for Multi-Image Uploader */
    .input-images {
        border: 2px dashed #fb4d1b !important;
        border-radius: 12px !important;
        background: #fff8f6 !important;
        transition: all 0.3s ease;
        min-height: 250px !important;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .input-images:hover {
        background: #fff0ed !important;
    }

    .image-uploader .upload-text {
        color: #fb4d1b !important;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .image-uploader .upload-text i {
        font-size: 3.5rem !important;
        color: #fb4d1b !important;
        margin-bottom: 15px !important;
    }

    .image-uploader .upload-text span {
        font-weight: 600 !important;
        color: #fb4d1b !important;
        font-size: 1rem !important;
    }

    .image-uploader .uploaded .uploaded-image {
        border-radius: 10px !important;
        border: 1px solid #ddd;
    }

    /* Flash Sale & Tag Styles for Admin Preview */
    .min-height-50 { min-height: 50px; }
    .p-tag {
        font-size: 9.5px;
        font-weight: 800;
        color: white;
        padding: 5px 10px;
        text-transform: uppercase;
        width: fit-content;
        border-radius: 6px 0px 6px 0px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        background-size: 300% 300% !important;
        position: relative;
        overflow: hidden;
        animation: bgMove 4s ease infinite, tagGlow 3s ease-in-out infinite;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        letter-spacing: 0.5px;
        line-height: 1;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.4);
    }
    @keyframes tagGlow { 0%, 100% { box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); } 50% { box-shadow: 0 4px 20px rgba(255, 255, 255, 0.4), 0 0 10px rgba(255, 255, 255, 0.2); } }
    .p-tag::after { content: ""; position: absolute; top: -50%; left: -100%; width: 50%; height: 200%; background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.6), transparent); transform: rotate(25deg); animation: tagShine 3s cubic-bezier(0.4, 0, 0.2, 1) infinite; }
    @keyframes bgMove { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
    @keyframes tagShine { 0% { left: -100%; } 20% { left: 150%; } 100% { left: 150%; } }
    .p-tag.orange { background: linear-gradient(-45deg, #f59e0b, #fbbf24, #f97316, #fbbf24); }
    .p-tag.green { background: linear-gradient(-45deg, #00bfa5, #26e2c6, #10b981, #26e2c6); }
    .p-tag.red { background: linear-gradient(-45deg, #ef4444, #f87171, #dc2626, #f87171); }
    .p-tag.purple { background: linear-gradient(-45deg, #8b5cf6, #a78bfa, #7c3aed, #a78bfa); }

    .flash-progress {
        height: 14px;
        background: #ffbd95;
        border-radius: 10px;
        position: relative;
        overflow: hidden;
        margin-top: 5px;
    }
    .flash-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #ff7b28 0%, #ff4d00 100%);
        border-radius: 10px;
        transition: width 0.3s ease;
    }
    .flash-progress-text {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
        color: #fff;
        font-weight: 500;
        text-transform: uppercase;
    }
</style>
@endpush