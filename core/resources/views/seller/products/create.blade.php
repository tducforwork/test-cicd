@extends($activeTemplate . 'layouts.frontend')

@section('content')
    @php
        $productConfig = \App\Models\ProductConfig::firstOrCreate([]);
        $selectedCategories = old('categories', @$product->categories ? $product->categories->pluck('id')->toArray() : []);
        $selectedBrands = old('brands', @$product->brand_id ? [$product->brand_id] : []);
        $selectedTags = old('tags', @$product->tags ? $product->tags->pluck('id')->toArray() : []);
        $selectedProductTypes = old('product_types', @$product->productTypes ? $product->productTypes->pluck('id')->toArray() : []);
    @endphp
    <div class="breadcrumb-section" style="background-color: #f8f9fa; padding: 20px 0; border-bottom: 1px solid #eaeaea;">
        <div class="container">
            <a href="{{ route('home') }}" style="color: #666; font-size: 14px">@lang('Trang chủ')</a>
            <span style="margin: 0 10px; color: #ccc">/</span>
            <a href="{{ route('seller.home') }}" style="color: #666; font-size: 14px">@lang('Kênh người bán')</a>
            <span style="margin: 0 10px; color: #ccc">/</span>
            <span style="color: var(--primary); font-weight: 600; font-size: 14px">
                @if(isset($product))
                    @lang('Chỉnh sửa sản phẩm')
                @else
                    @lang('Thêm sản phẩm mới')
                @endif
            </span>
        </div>
    </div>

    <!-- MAIN SECTION -->
    <section class="profile-section py-lg-5 py-4">
        <div class="container">
            <div class="profile-container">
                <!-- Sidebar -->
                <aside class="profile-sidebar">
                    @include('seller.partials.sidebar')
                </aside>

                <!-- Main Content -->
                <main class="profile-main-content">
                    <div class="content-header d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2>
                                @if(isset($product))
                                    @lang('Chỉnh sửa sản phẩm')
                                @else
                                    @lang('Thêm sản phẩm mới')
                                @endif
                            </h2>
                            <p style="color: var(--text-muted); font-size: 14px;">
                                @lang('Đăng tải sản phẩm mới lên gian hàng của bạn')
                            </p>
                        </div>
                        <a href="{{ route('seller.products.all') }}" class="btn btn-light"
                            style="border: 1px solid var(--border); font-weight: 600;">
                            <i class="fa-solid fa-arrow-left" style="margin-right: 6px;"></i> @lang('Quay lại')
                        </a>
                    </div>

                    <div class="row">
                        <!-- Form Column -->
                        <div class="col-lg-12 col-md-12">
                            <form action="{{ route('seller.products.product.store', $product->id ?? 0) }}" id="addForm"
                                method="POST" enctype="multipart/form-data">
                                @csrf

                                <!-- SECTION 1: BASIC INFO -->
                                <div class="form-section-card premium-card mb-5" style="padding: 24px;">
                                    <h3 class="form-section-title"><i class="fa-solid fa-circle-info"></i>
                                        @lang('Thông tin cơ bản')</h3>
                                    <div class="row">
                                        <div class="col-md-12 mb-4">
                                            <label class="form-label">@lang('Tên sản phẩm') <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="name" id="productName" class="form-control"
                                                placeholder="@lang('Nhập tên sản phẩm (Ví dụ: Giày thể thao nam cao cấp)')"
                                                value="{{ old('name', @$product->name) }}" required>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label">@lang('Đơn vị tính')</label>
                                            <input type="text" name="unit" class="form-control"
                                                placeholder="@lang('Ví dụ: Cái, Chiếc, Bộ, Hộp, kg...')"
                                                value="{{ old('unit', @$product->unit) }}">
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label">@lang('Tình trạng sản phẩm')</label>
                                            <input type="text" name="condition" class="form-control"
                                                placeholder="@lang('Ví dụ: Mới 100%, Likenew 99%, Đã qua sử dụng...')"
                                                value="{{ old('condition', @$product->condition) }}">
                                        </div>
                                        <div class="col-md-12 mb-4 select2-parent">
                                            <label class="form-label">@lang('Danh mục sản phẩm') <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select category-select2" name="categories[]" id="categories"
                                                required>
                                                <option value="">@lang('Chọn một danh mục')</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" data-title="{{ __($category->name) }}"
                                                        @selected(in_array($category->id, $selectedCategories))>
                                                        @lang($category->name)
                                                    </option>
                                                    @php $prefix = '--'; @endphp
                                                    @foreach ($category->allSubcategories as $subcategory)
                                                        <option value="{{ $subcategory->id }}"
                                                            data-title="{{ __($subcategory->name) }}"
                                                            @selected(in_array($subcategory->id, $selectedCategories))>
                                                            {{ $prefix }}@lang($subcategory->name)
                                                        </option>
                                                        @include('admin.partials.subcategories', ['subcategory' => $subcategory, 'prefix' => $prefix])
                                                    @endforeach
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">@lang('Mô tả sản phẩm') <span
                                                    class="text-danger">*</span></label>
                                            <textarea id="description" name="description" class="form-control" rows="8"
                                                placeholder="@lang('Mô tả chi tiết về sản phẩm của bạn...')"
                                                required>{{ old('description', @$product->description) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- SECTION 2: PRICE & STOCK -->
                                <div class="form-section-card premium-card mb-5" style="padding: 24px;">
                                    <h3 class="form-section-title"><i class="fa-solid fa-tags"></i>
                                        @lang('Giá bán & Kho hàng')</h3>
                                    <div class="row">
                                        <div class="col-md-12 mb-4">
                                            <label class="form-label">@lang('Tiền tệ') <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" id="currencySelector" name="currency_type" required>
                                                <option value="1" @selected(old('currency_type', @$product->currency_type) == 1)>@lang('VNĐ (Việt Nam Đồng)')</option>
                                                <option value="2" @selected(old('currency_type', @$product->currency_type) == 2)>@lang('Tệ (CNY - Nhân dân tệ)')</option>
                                            </select>
                                        </div>

                                        <!-- VND Price Inputs -->
                                        <div class="col-md-6 mb-4 vnd-only">
                                            <label class="form-label">@lang('Giá gốc') <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" name="base_price" id="basePriceInput"
                                                    class="form-control" placeholder="0"
                                                    value="{{ old('base_price', @$product->base_price ? getAmount($product->base_price) : '') }}"
                                                    required>
                                                <span class="input-group-text">đ</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4 vnd-only">
                                            <label class="form-label">@lang('Giá khuyến mãi')</label>
                                            <div class="input-group">
                                                <input type="number" name="discount_price" id="discountPriceInput"
                                                    class="form-control" placeholder="0"
                                                    value="{{ old('discount_price', @$product->discount_price ? getAmount($product->discount_price) : '') }}">
                                                <span class="input-group-text">đ</span>
                                            </div>
                                        </div>

                                        <!-- CNY Price Inputs -->
                                        <div class="col-md-6 mb-4 cny-only" style="display: none;">
                                            <label class="form-label">@lang('Giá gốc (CNY)') <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" name="cny_price" id="cny_price" class="form-control"
                                                    placeholder="0"
                                                    value="{{ old('cny_price', @$product->cny_price ? getAmount($product->cny_price) : '') }}">
                                                <span class="input-group-text">CNY</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4 cny-only" style="display: none;">
                                            <label class="form-label">@lang('Giá gốc (VNĐ quy đổi)')</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control bg-light" id="cny_price_converted"
                                                    placeholder="0" readonly>
                                                <span class="input-group-text">đ</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4 cny-only" style="display: none;">
                                            <label class="form-label">@lang('Giá ưu đãi (CNY)')</label>
                                            <div class="input-group">
                                                <input type="number" name="cny_discount_price" id="cny_discount_price"
                                                    class="form-control" placeholder="0"
                                                    value="{{ old('cny_discount_price', @$product->cny_discount_price ? getAmount($product->cny_discount_price) : '') }}">
                                                <span class="input-group-text">CNY</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4 cny-only" style="display: none;">
                                            <label class="form-label">@lang('Giá ưu đãi (VNĐ quy đổi)')</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control bg-light" id="cny_discount_converted"
                                                    placeholder="0" readonly>
                                                <span class="input-group-text">đ</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label class="form-label">@lang('Số lượng tồn kho') <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="quantity" class="form-control" placeholder="0"
                                                value="{{ old('quantity', isset($product) && !$product->has_variants ? @$product->stock->quantity : '') }}"
                                                required>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label">@lang('Mã SKU (Quản lý kho)')</label>
                                            <input type="text" name="sku" class="form-control bg-light"
                                                placeholder="Ví dụ: SP-001-RED" value="{{ old('sku', @$product->sku) }}">
                                        </div>

                                        <div class="col-md-12 mt-3 d-none">
                                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3"
                                                style="border-radius: 12px !important;">
                                                <div>
                                                    <label class="form-label mb-0"
                                                        style="font-weight: 700;">@lang('Giá có thể thương lượng')</label>
                                                    <p class="mb-0 text-muted" style="font-size: 12px;">
                                                        @lang('Bật nếu bạn muốn khách hàng chủ động đàm phán giá cả')
                                                    </p>
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="negotiable"
                                                        value="1" id="negotiableSwitch" @checked(old('negotiable', @$product->negotiable))
                                                        style="width: 40px; height: 20px; cursor: pointer;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hidden fields to preserve original system specs -->
                                    @php
                                        $trackInventory = old('track_inventory', @$product->track_inventory ?? 1);
                                        $showInFrontend = old('show_in_frontend', @$product->show_in_frontend ?? 0);
                                        $hasVariants = old('has_variants', @$product->has_variants ?? 0);
                                    @endphp
                                    <input type="hidden" name="track_inventory" value="{{ $trackInventory ? 1 : 0 }}">
                                    <input type="hidden" name="show_in_frontend" value="{{ $showInFrontend ? 1 : 0 }}">
                                    <input type="hidden" name="has_variants" value="{{ $hasVariants ? 1 : 0 }}">
                                </div>

                                <!-- SECTION: BRAND, PRODUCT TYPES, DISPLAY CONFIGS, TAGS & FLASH SALE (CUSTOM & DIFFERENT DESIGN!) -->
                                <div class="form-section-card premium-card mb-5"
                                    style="border: 2px solid var(--accent); background: #fffcf8; box-shadow: 0 12px 30px rgba(245, 158, 11, 0.05); padding: 24px;">
                                    <h3 class="form-section-title" style="color: var(--accent);"><i
                                            class="fa-solid fa-wand-magic-sparkles"></i>
                                        @lang('Cấu hình Phân loại & Hiển thị')</h3>
                                    <p style="font-size: 13px; color: #64748b; margin-top: -15px; margin-bottom: 25px;">
                                        <i class="fa-solid fa-sparkles"></i>
                                        @lang('Thiết lập cấu hình hiển thị đặc biệt của sản phẩm trên Quảng Phát Mall.')
                                    </p>

                                    <div class="row">
                                        <!-- Left Column: Brand & Product Types -->
                                        <div class="col-lg-12 mb-4">
                                            <div class="p-3 bg-white rounded-3 border mb-4"
                                                style="border-radius: 16px !important;">
                                                <label class="form-label" style="font-weight: 700; color: var(--primary);">
                                                    @lang('Thương hiệu sản phẩm')</label>
                                                <div class="select2-parent">
                                                    <select name="brands[]" class="form-select select2-basic"
                                                        style="font-size: 13px;">
                                                        <option value="">@lang('Chọn một thương hiệu')</option>
                                                        @foreach($brands as $brand)
                                                            <option value="{{ $brand->id }}" @selected(in_array($brand->id, $selectedBrands))>
                                                                {{ __($brand->name) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="p-3 bg-white rounded-3 border"
                                                style="border-radius: 16px !important;">
                                                <label class="form-label" style="font-weight: 700; color: var(--primary);">
                                                    @lang('Loại sản phẩm (Chọn nhiều)')</label>
                                                <div class="row g-2">
                                                    @foreach($productTypes as $type)
                                                        <div class="col-sm-6">
                                                            <label class="checkbox-card">
                                                                <input type="checkbox" name="product_types[]"
                                                                    value="{{ $type->id }}" id="type_{{ $type->id }}"
                                                                    @checked(in_array($type->id, $selectedProductTypes))>
                                                                <span>{{ __($type->name) }}</span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Right Column: Display Config & Flash Sale -->
                                        <div class="col-lg-12 mb-4">
                                            <div class="p-3 bg-white rounded-3 border mb-4"
                                                style="border-radius: 16px !important;">
                                                <label class="form-label" style="font-weight: 700; color: var(--primary);">
                                                    @lang('Cấu hình hiển thị (Trang chủ)')</label>
                                                <div class="d-flex flex-column gap-3">
                                                        <label class="checkbox-card">
                                                            <input type="checkbox" name="is_search" value="1" id="is_search"
                                                                @checked(old('is_search', @$product->is_search))>
                                                            <span> @lang('Tìm kiếm nhiều nhất')</span>
                                                        </label>
                                                        <label class="checkbox-card">
                                                            <input type="checkbox" name="is_topdeal" value="1" id="is_topdeal"
                                                                @checked(old('is_topdeal', @$product->is_topdeal))>
                                                            <span> @lang('Top Deal đắt khách')</span>
                                                        </label>
                                                        <label class="checkbox-card">
                                                            <input type="checkbox" name="is_suggestion" value="1"
                                                                id="is_suggestion" @checked(old('is_suggestion', @$product->is_suggestion))>
                                                            <span> @lang('Gợi ý cho bạn')</span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="p-3 bg-white rounded-3 border"
                                                        style="border-radius: 16px !important;">
                                                        <label class="form-label" style="font-weight: 700; color: var(--primary);">
                                                            @lang('Tiến trình Flash Sale')</label>
                                                        <div class="row g-2 mb-2">
                                                            <div class="col-6">
                                                                <div class="input-group input-group-sm">
                                                                    <span class="input-group-text">% Độ dài</span>
                                                                    <input type="number" name="flash_percentage"
                                                                        class="form-control"
                                                                        value="{{ old('flash_percentage', @$product->flash_percentage) }}"
                                                                        min="0" max="100" id="flash-percent"
                                                                        style="padding: 6px 12px !important; border-radius: 0 !important;">
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <input type="text" name="flash_text"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ old('flash_text', @$product->flash_text) }}"
                                                                    placeholder="Ví dụ: Đã bán 58%" id="flash-text-input"
                                                                    style="padding: 6px 12px !important; border-radius: 12px !important;">
                                                            </div>
                                                        </div>
                                                        <div class="flash-progress-preview p-2 rounded"
                                                            style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                                                            <p class="small text-muted mb-1"
                                                                style="font-size: 10px; font-weight: 600;">
                                                                @lang('Xem trước tiến trình'):
                                                            </p>
                                                            <div class="flash-progress" style="height: 20px">
                                                                <div class="flash-progress-bar"
                                                                    style="width: {{ @$product->flash_percentage ?? 0 }}%;">
                                                                </div>
                                                                <div class="flash-progress-text">
                                                                    {{ @$product->flash_text ?? 'Đã bán 0%' }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Below: Tags Selection -->
                                                <div class="col-md-12">
                                                    <div class="p-3 bg-white rounded-3 border"
                                                        style="border-radius: 16px !important;">
                                                        <label class="form-label"
                                                            style="font-weight: 700; color: var(--primary);"><i
                                                                class="fa-solid fa-tags text-muted me-2"></i>
                                                            @lang('Nhãn sản phẩm (Tags)')
                                                        </label>
                                                        <small class="text-info d-block mb-2" style="font-size: 11px;">
                                                            <i class="fa-solid fa-circle-info me-1"></i>
                                                            @lang('Có thể chọn nhiều nhãn, nhưng nên chọn 1 nhãn nổi bật để hiển thị đẹp nhất.')
                                                        </small>
                                                        <div class="select2-parent">
                                                            <select name="tags[]" class="form-control select2-basic"
                                                                multiple="multiple" id="tag-select" style="font-size: 13px;">
                                                                @foreach($tags as $tag)
                                                                    <option value="{{ $tag->id }}" data-type="{{ $tag->type }}"
                                                                        @selected(in_array($tag->id, $selectedTags))>
                                                                        {{ __($tag->name) }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div id="tag-previews"
                                                            class="mt-3 d-flex flex-wrap gap-2 p-2 bg-light rounded"
                                                            style="min-height: 40px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <!-- SECTION 3: IMAGES -->
                                        <div class="form-section-card premium-card mb-5" style="padding: 24px;">
                                            <h3 class="form-section-title"><i class="fa-solid fa-image"></i>
                                                @lang('Hình ảnh sản phẩm')</h3>

                                            <div class="row">
                                                <!-- Main Image -->
                                                <div class="col-md-4 mb-4">
                                                    <label class="form-label">@lang('Ảnh chính') <span
                                                            class="text-danger">*</span></label>
                                                    <div class="image-upload-wrapper"
                                                        onclick="document.getElementById('mainImageInput').click()"
                                                        style="height: 200px;">
                                                        <div class="upload-icon" style="font-size: 30px;"><i
                                                                class="fa-solid fa-camera"></i></div>
                                                        <h5 style="font-size: 14px; font-weight: 700;">@lang('Ảnh đại diện')</h5>
                                                        <input type="file" name="main_image" id="mainImageInput"
                                                            style="display: none;" accept="image/*">
                                                    </div>
                                                    <div class="image-preview-container mt-2" id="mainImagePreview">
                                                        @if(@$product->main_image)
                                                            <div class="preview-item">
                                                                <img id="mainImagePreviewTag"
                                                                    src="{{ getImage(getFilePath('product') . '/' . $product->main_image) }}">
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Additional Images -->
                                                <div class="col-md-8 mb-4">
                                                    <label class="form-label">@lang('Ảnh bổ sung (Tối đa 6 ảnh)')</label>
                                                    <div class="image-upload-wrapper"
                                                        onclick="document.getElementById('additionalImagesInput').click()"
                                                        style="height: 200px;">
                                                        <div class="upload-icon" style="font-size: 30px;"><i
                                                                class="fa-solid fa-images"></i></div>
                                                        <h5 style="font-size: 14px; font-weight: 700;">
                                                            @lang('Thêm các góc chụp khác')
                                                        </h5>
                                                        <input type="file" id="additionalImagesInput" multiple
                                                            style="display: none;" accept="image/*">
                                                    </div>
                                                    <div class="image-preview-container mt-2" id="additionalImagesPreview">
                                                        @if(isset($images) && count($images) > 0)
                                                            @foreach($images as $img)
                                                                <div class="preview-item existing-image-item">
                                                                    <img src="{{ $img['src'] }}">
                                                                    <input type="hidden" name="old[]" value="{{ $img['id'] }}">
                                                                    <button type="button" class="remove-preview remove-existing-btn">
                                                                        <i class="fa-solid fa-xmark"></i>
                                                                    </button>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- SECTION 4: SEO -->
                                        <div class="form-section-card premium-card" style="padding: 24px;">
                                            <h3 class="form-section-title"><i class="fa-solid fa-magnifying-glass-chart"></i>
                                                @lang('Tối ưu SEO (Tùy chọn)')</h3>
                                            <div class="row">
                                                <div class="col-md-12 mb-4">
                                                    <label class="form-label">@lang('Tiêu đề SEO')</label>
                                                    <input type="text" name="meta_title" class="form-control"
                                                        placeholder="@lang('Tiêu đề hiển thị trên Google')"
                                                        value="{{ old('meta_title', @$product->meta_title) }}">
                                                </div>
                                                <div class="col-md-12 mb-4">
                                                    <label class="form-label">@lang('Từ khóa SEO / Tags')</label>
                                                    <div class="select2-parent">
                                                        <select class="select2-auto-tokenize-seller form-control"
                                                            name="meta_keywords[]" multiple="multiple">
                                                            @php
                                                                $metaKeywords = old('meta_keywords', @$product->meta_keywords);
                                                            @endphp
                                                            @if ($metaKeywords)
                                                                @foreach ($metaKeywords as $option)
                                                                    <option value="{{ $option }}" selected>{{ __($option) }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <p style="font-size: 11px; color: var(--text-muted); margin-top: 5px;">
                                                        @lang('Ấn phím Enter hoặc phẩy (,) để thêm từ khóa mới.')
                                                    </p>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">@lang('Mô tả SEO')</label>
                                                    <textarea name="meta_description" class="form-control" rows="3"
                                                        placeholder="@lang('Mô tả ngắn hiển thị dưới link tìm kiếm')">{{ old('meta_description', @$product->meta_description) }}</textarea>
                                                </div>
                                            </div>

                                            <!-- STICKY FOOTER -->
                                            <div class="sticky-form-footer">
                                                <button type="button" class="btn btn-light" onclick="history.back()"
                                                    style="padding: 12px 30px; font-weight: 600;">@lang('HỦY BỎ')</button>
                                                <button type="submit" class="btn btn-primary"
                                                    style="background: var(--primary); border: none; padding: 12px 40px; font-weight: 700;">@lang('LƯU SẢN PHẨM')</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </main>
                    </div>
                </div>
            </section>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
    <script src="https://cdn.tiny.cloud/1/az09l5hhv4r2bolg5fnhgy1vju0dri2amq12cvtmovqeeb52/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('style')
    <style>
        .input-group-text {
            border-radius: 0.25rem !important;
        }

        /* Custom Select2 Styling Override - Ultra Premium Figma Look */
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single,
        .select2-container--default .select2-selection--multiple {
            padding: 8px 16px !important;
            border-radius: 12px !important;
            border: 1px solid #e2e8f0 !important;
            background-color: #f8fafc !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            min-height: 48px !important;
            display: flex !important;
            align-items: center !important;
            outline: none !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            background-color: #ffffff !important;
            border-color: var(--accent) !important;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1) !important;
        }

        /* Arrow style for single select */
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            top: 0 !important;
            right: 16px !important;
            width: 20px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #64748b transparent transparent transparent !important;
            border-width: 6px 5px 0 5px !important;
            margin-left: 0 !important;
            margin-top: 0 !important;
            position: relative !important;
            top: auto !important;
            left: auto !important;
        }

        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent var(--accent) transparent !important;
            border-width: 0 5px 6px 5px !important;
        }

        /* Value positioning */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #1e293b !important;
            padding-left: 0 !important;
            padding-right: 24px !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            line-height: normal !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #94a3b8 !important;
        }

        /* Multiple choice pills styling */
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 6px !important;
            padding: 0 !important;
            width: 100% !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: #fff5f2 !important;
            border: 1px solid #ffdcd3 !important;
            color: var(--accent) !important;
            border-radius: 8px !important;
            padding: 6px 12px !important;
            margin: 0 !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            box-shadow: 0 2px 4px rgba(245, 158, 11, 0.05) !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #f43f5e !important;
            border: none !important;
            background: none !important;
            font-weight: 900 !important;
            padding: 0 !important;
            margin: 0 !important;
            order: 2 !important;
            /* Put close icon on right */
            font-size: 14px !important;
            transition: transform 0.2s ease !important;
            position: relative !important;
            right: 0 !important;
            left: 0 !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            background: none !important;
            color: #be123c !important;
            transform: scale(1.2) !important;
        }

        /* Dropdown container customization */
        .select2-dropdown {
            border: 1px solid #e2e8f0 !important;
            border-radius: 16px !important;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08) !important;
            overflow: hidden !important;
            background: white !important;
            z-index: 9999 !important;
            margin-top: 4px !important;
        }

        .select2-container--default .select2-search--dropdown {
            padding: 12px !important;
            background: #f8fafc !important;
            border-bottom: 1px solid #e2e8f0 !important;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border-radius: 10px !important;
            border: 1px solid #e2e8f0 !important;
            padding: 10px 14px !important;
            font-size: 13.5px !important;
            outline: none !important;
            background: white !important;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: var(--accent) !important;
        }

        /* Dropdown options customization */
        .select2-container--default .select2-results__options {
            max-height: 240px !important;
            padding: 6px !important;
        }

        .select2-container--default .select2-results__option {
            padding: 10px 14px !important;
            border-radius: 8px !important;
            font-size: 13.5px !important;
            font-weight: 600 !important;
            color: #334155 !important;
            transition: all 0.15s ease !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected],
        .select2-container--default .select2-results__option[aria-selected="true"] {
            background-color: #fff5f2 !important;
            color: var(--accent) !important;
        }

        .select2-container--default .select2-results__option[aria-disabled="true"] {
            color: #cbd5e1 !important;
            background-color: transparent !important;
        }

        /* Style for standard HTML form-select dropdowns */
        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right 16px center !important;
            background-size: 12px 12px !important;
            padding-right: 40px !important;
        }
    </style>
@endpush

@push('script')
    <script>
        (function ($) {
            'use strict';

            // Select2
            $('.category-select2').each(function () {
                $(this).select2({
                    dropdownParent: $(this).parent()
                });
            });

            $('.select2-basic').each(function () {
                $(this).select2({
                    dropdownParent: $(this).parent()
                });
            });

            $('.select2-auto-tokenize-seller').each(function () {
                $(this).select2({
                    tags: true,
                    tokenSeparators: [',', ' '],
                    placeholder: '@lang("Nhập tag và ấn Enter...")',
                    dropdownParent: $(this).parent()
                });
            });

            // Currency Switching & Exchange conversion
            var cnyExchangeRate = {{ $productConfig->cny_exchange_rate }};

            $('#currencySelector').on('change', function () {
                if (this.value === '2') { // CNY
                    $('.vnd-only').hide();
                    $('.cny-only').show();
                    $('#basePriceInput, #discountPriceInput').attr('readonly', true);
                } else { // VND
                    $('.vnd-only').show();
                    $('.cny-only').hide();
                    $('#basePriceInput, #discountPriceInput').attr('readonly', false);
                }
            }).change();

            $('#cny_price').on('input', function () {
                var cny = parseFloat($(this).val()) || 0;
                var vnd = Math.round(cny * cnyExchangeRate);
                $('#basePriceInput').val(vnd);
                $('#cny_price_converted').val(vnd.toLocaleString('vi-VN'));
            }).trigger('input');

            $('#cny_discount_price').on('input', function () {
                var cny = parseFloat($(this).val()) || 0;
                if (cny > 0) {
                    var vnd = Math.round(cny * cnyExchangeRate);
                    $('#discountPriceInput').val(vnd);
                    $('#cny_discount_converted').val(vnd.toLocaleString('vi-VN'));
                } else {
                    $('#discountPriceInput').val('');
                    $('#cny_discount_converted').val('0');
                }
            }).trigger('input');

            // Main Image Upload Callback
            document.getElementById('mainImageInput').addEventListener('change', function (e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        document.getElementById('mainImagePreview').innerHTML = `
                                                                            <div class="preview-item">
                                                                                <img src="${event.target.result}">
                                                                            </div>
                                                                        `;
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });

            // Additional images upload
            let additionalFiles = [];
            const additionalImagesInput = document.getElementById('additionalImagesInput');

            additionalImagesInput.addEventListener('change', function (e) {
                let files = e.target.files;
                for (let i = 0; i < files.length; i++) {
                    if (additionalFiles.length + $('.existing-image-item').length >= 6) break;
                    if (!files[i].type.startsWith('image/')) continue;
                    additionalFiles.push(files[i]);
                }
                renderAdditionalPreviews();
                updateAdditionalHiddenInput();
                e.target.value = '';
            });

            function renderAdditionalPreviews() {
                $('#additionalImagesPreview .new-preview-item').remove();
                additionalFiles.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        const div = document.createElement('div');
                        div.className = 'preview-item new-preview-item';
                        div.innerHTML = `
                                                                            <img src="${event.target.result}">
                                                                            <button type="button" class="remove-preview remove-new-btn" data-index="${index}">
                                                                                <i class="fa-solid fa-xmark"></i>
                                                                            </button>
                                                                        `;
                        document.getElementById('additionalImagesPreview').appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }

            function updateAdditionalHiddenInput() {
                const form = document.getElementById('addForm');
                let photosInput = form.querySelector('input[name="photos[]"]');
                if (photosInput) photosInput.remove();

                const dataTransfer = new DataTransfer();
                additionalFiles.forEach(file => dataTransfer.items.add(file));

                const input = document.createElement('input');
                input.type = 'file';
                input.name = 'photos[]';
                input.multiple = true;
                input.className = 'd-none';
                input.files = dataTransfer.files;
                form.appendChild(input);
            }

            $(document).on('click', '.remove-new-btn', function () {
                const index = parseInt($(this).data('index'));
                additionalFiles.splice(index, 1);
                renderAdditionalPreviews();
                updateAdditionalHiddenInput();
            });

            $(document).on('click', '.remove-existing-btn', function () {
                $(this).closest('.existing-image-item').remove();
            });

            // Initialize tinymce editor
            tinymce.init({
                selector: '#description',
                plugins: 'link image lists table',
                toolbar: 'bold italic underline | link image | bullist numlist | alignleft aligncenter alignright',
                menubar: false,
                height: 300,
                statusbar: false
            });

            // Tag Preview Logic
            function updateTagPreviews() {
                let container = $('#tag-previews');
                container.empty();
                $('#tag-select option:selected').each(function () {
                    let name = $(this).text().trim();
                    let type = $(this).data('type');
                    container.append(`<span class="p-tag ${type}">${name}</span>`);
                });
                if (container.children().length == 0) {
                    container.append('<span class="text-muted small italic" style="font-size: 11px;">Chưa chọn tag nào</span>');
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

            // Add form validation
            $('#addForm').validate({
                rules: {
                    name: "required",
                    base_price: "required"
                },
                messages: {
                    name: "@lang('Vui lòng nhập tên sản phẩm')",
                    base_price: "@lang('Vui lòng nhập đơn giá sản phẩm')"
                },
                errorElement: 'label',
                errorPlacement: function (error, element) {
                    error.insertAfter(element);
                }
            });
        })(jQuery);
    </script>
@endpush