@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <div class="bg-[#F7F7F7]">
        <main class="container mx-auto pb-32 pt-10">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Sidebar -->
                <aside class="w-full lg:w-[312px] shrink-0">
                    @include('seller.partials.sidebar')
                </aside>

                <!-- Main Content -->
                <section class="flex-1 min-w-0">

                    <!-- Page Header -->
                    <div class="mb-2">
                        <h1 class="text-2xl font-semibold text-[#272343] mb-2">
                            {{ @$product ? __('Sửa tin đăng bất động sản') : __('Đăng tin bất động sản miễn phí') }}
                        </h1>
                        <p class="text-muted text-sm">
                            @lang('Lưu ý: Tính năng Đăng tin miễn phí chỉ dành cho khách hàng chưa từng có tài khoản trên Homedy. Nếu bạn đã có tài khoản, vui lòng đăng nhập và đăng tin trong trang Dịch vụ của Homedy tại ')
                            <a href="#" class="text-primary">@lang('ĐÂY')</a>
                        </p>
                    </div>

                    <form id="realEstateForm" action="{{ route('seller.products.product.store', $product->id ?? 0) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="is_real_estate" value="1">
                        <input type="hidden" name="re_type" value="{{ old('re_type', @$product->re_type ?? 'sale') }}">
                        <input type="hidden" name="categories[]" value="{{ @$reCategory->id }}">

                        <!-- I. Thông tin cơ bản -->
                        <div class="bg-white rounded-[12px] p-6 mb-6 border border-gray-100">
                            <h2 class="text-xl font-bold text-kviet-dark mb-6">@lang('I. Thông tin cơ bản')</h2>

                            @php
                                $selectedConditions = $product->re_listing_condition ?? [];
                                if (!is_array($selectedConditions)) {
                                    $selectedConditions = [];
                                }
                            @endphp

                            <div class="space-y-6">
                                <!-- Tiêu đề -->
                                <div>
                                    <label class="text-[16px] font-normal text-[#272343] leading-relaxed">
                                        @lang('Tên tiêu đề:') <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name"
                                        class="w-full h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange mt-2"
                                        placeholder="@lang('Nhập tiêu đề tin đăng')"
                                        value="{{ old('name', @$product->name) }}" required>
                                    <div class="text-end text-xs text-muted mt-1">0/99</div>
                                </div>

                                <!-- Tên dự án -->
                                <div>
                                    <label class="text-[16px] font-normal text-[#272343] leading-relaxed">
                                        @lang('Tên dự án:')
                                    </label>
                                    <input type="text" name="re_project_name"
                                        class="w-full h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange mt-2"
                                        placeholder="@lang('Nhập tên dự án')"
                                        value="{{ old('re_project_name', @$product->re_project_name) }}">
                                </div>

                                <!-- Loại hình & Loại hình chính -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="text-[16px] font-normal text-[#272343] leading-relaxed">
                                            @lang('Loại hình:') <span class="text-danger">*</span>
                                        </label>
                                        <div class="flex gap-2 mt-2">
                                            @php $selectedType = old('re_type', @$product->re_type ?? 'sale'); @endphp
                                            <button type="button"
                                                class="re-type-btn flex-1 {{ $selectedType == 'sale' ? 'active' : '' }}"
                                                data-value="sale">@lang('Mua bán')</button>
                                            <button type="button"
                                                class="re-type-btn flex-1 {{ $selectedType == 'rent' ? 'active' : '' }}"
                                                data-value="rent">@lang('Cho thuê')</button>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="text-[16px] font-normal text-[#272343] leading-relaxed">
                                                @lang('Loại hình chính:') <span class="text-danger">*</span>
                                            </label>
                                            <select id="parent_type"
                                                class="w-full h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange mt-2 select2-basic">
                                                <option value="">@lang('Chọn loại hình chính')</option>
                                                @foreach ($propertyTypes->where('parent_id', 0) as $typeItem)
                                                    @php
                                                        $isSelected =
                                                            isset($product) && $product->re_property_type == $typeItem->id;
                                                        if (!$isSelected && isset($product)) {
                                                            $isSelected = $propertyTypes
                                                                ->where('parent_id', $typeItem->id)
                                                                ->pluck('id')
                                                                ->contains($product->re_property_type);
                                                        }
                                                    @endphp
                                                    <option value="{{ $typeItem->id }}" data-name="{{ $typeItem->name }}"
                                                        @selected($isSelected)>
                                                        {{ __($typeItem->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div id="child_type_wrapper" style="display: none;">
                                            <label class="text-[16px] font-normal text-[#272343] leading-relaxed">
                                                @lang('Loại hình chi tiết:')
                                            </label>
                                            <select id="child_type"
                                                class="w-full h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange mt-2 select2-basic">
                                                <option value="">@lang('Chọn loại hình chi tiết')</option>
                                            </select>
                                        </div>
                                        <input type="hidden" name="re_filter_property_type" id="final_property_type"
                                            value="{{ old('re_filter_property_type', @$product->re_property_type ?? '') }}">
                                    </div>
                                </div>

                                <!-- Loại giao dịch & Hình thức giao dịch -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="text-[16px] font-normal text-[#272343] leading-relaxed">
                                            @lang('Loại giao dịch/ Giá:') <span class="text-danger">*</span>
                                        </label>
                                        <select name="re_transaction_type"
                                            class="w-full h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange mt-2 select2-basic"
                                            required>
                                            <option value="">@lang('Chọn loại giao dịch')</option>
                                            @foreach ($transactionTypes ?? [] as $typeItem)
                                                <option value="{{ $typeItem->id }}" @selected(old('re_transaction_type', @$product->re_transaction_type) == $typeItem->id)>
                                                    {{ __($typeItem->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[16px] font-normal text-[#272343] leading-relaxed">
                                            @lang('Hình thức giao dịch:') <span class="text-danger">*</span>
                                        </label>
                                        <select name="re_transaction_method"
                                            class="w-full h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange mt-2 select2-basic"
                                            required>
                                            <option value="">@lang('Chọn hình thức')</option>
                                            @foreach ($transactionMethods ?? [] as $method)
                                                <option value="{{ $method->id }}" @selected(old('re_transaction_method', @$product->re_transaction_method) == $method->id)>
                                                    {{ __($method->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Điều kiện tin đăng & Tỉnh/Thành phố -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="text-[16px] font-normal text-[#272343] leading-relaxed">
                                            @lang('Điều kiện tin đăng:') <span class="text-danger">*</span>
                                        </label>
                                        @php $conditions = old('re_listing_conditions', $selectedConditions); @endphp
                                        <select name="re_listing_conditions[]" class="select2-basic h-[49px] mt-2"
                                            multiple="multiple" required>
                                            @foreach ($listingConditions ?? [] as $condition)
                                                <option value="{{ $condition->id }}" @selected(in_array($condition->id, (array) $conditions))>{{ __($condition->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[16px] font-normal text-[#272343] leading-relaxed">
                                            @lang('Tỉnh/ Thành phố:') <span class="text-danger">*</span>
                                        </label>
                                        <select name="re_province_id"
                                            class="w-full h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange mt-2 select2-basic"
                                            required>
                                            <option value="">@lang('Chọn Tỉnh/ Thành phố')</option>
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->id }}" @selected(old('re_province_id', @$product->re_province_id) == $province->id)>
                                                    {{ __($province->full_name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Xã/Phường & Địa chỉ -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="text-[16px] font-normal text-[#272343] leading-relaxed">
                                            @lang('Xã/ Phường:') <span class="text-danger">*</span>
                                        </label>
                                        <select name="re_ward_id"
                                            class="w-full h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange mt-2 select2-basic"
                                            required>
                                            <option value="">@lang('Chọn Xã/ Phường')</option>
                                            @if (@$product->re_province_id)
                                                @foreach (\App\Models\Ward::where('province_id', $product->re_province_id)->active()->orderBy('name')->get() as $ward)
                                                    <option value="{{ $ward->id }}" @selected(old('re_ward_id', @$product->re_ward_id) == $ward->id)>
                                                        {{ __($ward->full_name) }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[16px] font-normal text-[#272343] leading-relaxed">
                                            @lang('Địa chỉ:')
                                        </label>
                                        <input type="text" name="re_address"
                                            class="w-full h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange mt-2"
                                            placeholder="@lang('Số nhà, ngõ, ngách...')"
                                            value="{{ old('re_address', @$product->re_address) }}">
                                    </div>
                                </div>

                                <!-- Bản đồ -->
                                <div>
                                    <label class="text-[16px] font-normal text-[#272343] leading-relaxed">
                                        @lang('Bản đồ (Google Maps Embed):')
                                    </label>
                                    <textarea name="re_map_embed"
                                        class="w-full px-4 py-3 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange mt-2"
                                        rows="4"
                                        placeholder="@lang('Dán iframe Google Maps vào đây...')">{{ old('re_map_embed', @$product->re_map_embed) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- II. Thông tin mô tả -->
                        <div class="bg-white rounded-[12px] p-6 mb-6 border border-gray-100">
                            <h2 class="text-xl font-bold text-kviet-dark mb-6">@lang('II. Thông tin mô tả')</h2>

                            <div class="space-y-6">
                                <!-- Giá tiền -->
                                <div>
                                    <label class="text-[16px] font-normal text-[#272343] leading-relaxed">
                                        @lang('Giá tiền (VND):') <span class="text-danger">*</span>
                                    </label>
                                    <div class="flex flex-wrap items-center gap-3 mt-2">
                                        <input type="number" name="re_price_from"
                                            class="h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange price-input"
                                            style="width: 200px;" placeholder="0"
                                            value="{{ old('re_price_from', getAmount(@$product->re_price_from)) }}">
                                        <span
                                            class="price-to-wrapper text-[#272343] @if (!old('re_price_to', getAmount(@$product->re_price_to))) hidden @endif">
                                            @lang('đến')
                                        </span>
                                        <input type="number" name="re_price_to"
                                            class="h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange price-input"
                                            style="width: 200px; @if (!old('re_price_to', getAmount(@$product->re_price_to))) display: none; @endif"
                                            placeholder="0"
                                            value="{{ old('re_price_to', getAmount(@$product->re_price_to)) }}">
                                        <button type="button" class="btn-range-toggle btn-price-toggle">
                                            @lang('Khoảng giá')
                                        </button>
                                    </div>
                                    <div class="mt-3">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="re_price_type" value="negotiable" id="negotiable"
                                                class="w-4 h-4 text-kviet-orange rounded focus:ring-kviet-orange"
                                                @checked(old('re_price_type', @$product->re_price_type) == 'negotiable')>
                                            <span class="ml-2 text-sm text-gray-600">@lang('Giá thỏa thuận')</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Diện tích -->
                                <div>
                                    <label class="text-[16px] font-normal text-[#272343] leading-relaxed">
                                        @lang('Diện tích (m2):') <span class="text-danger">*</span>
                                    </label>
                                    <div class="flex flex-wrap items-center gap-3 mt-2">
                                        <input type="number" name="re_area"
                                            class="h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange area-input"
                                            style="width: 200px;" placeholder="@lang('Nhập diện tích')" required
                                            value="{{ old('re_area', getAmount(@$product->re_area)) }}">
                                        <span
                                            class="area-to-wrapper text-[#272343] @if (!old('re_area_to', getAmount(@$product->re_area_to))) hidden @endif">
                                            @lang('đến')
                                        </span>
                                        <input type="number" name="re_area_to"
                                            class="h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange area-input"
                                            style="width: 200px; @if (!old('re_area_to', getAmount(@$product->re_area_to))) display: none; @endif"
                                            placeholder="0"
                                            value="{{ old('re_area_to', getAmount(@$product->re_area_to)) }}">
                                        <button type="button" class="btn-range-toggle btn-area-toggle">
                                            @lang('Khoảng diện tích')
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <label class="text-[16px] font-normal text-[#272343] leading-relaxed">
                                        @lang('Hướng nhà:')
                                    </label>
                                    <select name="re_direction_id"
                                        class="w-full h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange mt-2 select2-basic">
                                        <option value="">@lang('Chọn hướng')</option>
                                        @foreach ($directions as $direction)
                                            <option value="{{ $direction->id }}" @selected(old('re_direction_id', @$product->re_direction_id) == $direction->id)>
                                                {{ __($direction->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[16px] font-normal text-[#272343] leading-relaxed">
                                        @lang('Tình trạng pháp lý:')
                                    </label>
                                    <select name="re_legal_status_id"
                                        class="w-full h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange mt-2 select2-basic">
                                        <option value="">@lang('Chọn pháp lý')</option>
                                        @foreach ($legalStatuses as $status)
                                            <option value="{{ $status->id }}" @selected(old('re_legal_status_id', @$product->re_legal_status_id) == $status->id)>
                                                {{ __($status->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- III. Thông tin chi tiết -->
                        <div class="bg-white rounded-[12px] p-6 mb-6 border border-gray-100">
                            <h2 class="text-xl font-bold text-kviet-dark mb-6">@lang('III. Thông tin chi tiết')</h2>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="text-[16px] font-normal text-[#272343] leading-relaxed">
                                        @lang('Số phòng ngủ:')
                                    </label>
                                    <input type="number" name="re_bedrooms"
                                        class="w-full h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange mt-2"
                                        min="0" max="50" placeholder="@lang('VD: 3')"
                                        value="{{ old('re_bedrooms', @$product->re_bedrooms ?? 0) }}">
                                </div>
                                <div>
                                    <label class="text-[16px] font-normal text-[#272343] leading-relaxed">
                                        @lang('Số phòng tắm:')
                                    </label>
                                    <input type="number" name="re_bathrooms"
                                        class="w-full h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange mt-2"
                                        min="0" max="50" placeholder="@lang('VD: 2')"
                                        value="{{ old('re_bathrooms', @$product->re_bathrooms ?? 0) }}">
                                </div>
                                <div>
                                    <label class="text-[16px] font-normal text-[#272343] leading-relaxed">
                                        @lang('Số tầng:')
                                    </label>
                                    <input type="number" name="re_floor"
                                        class="w-full h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange mt-2"
                                        min="0" max="200" placeholder="@lang('VD: 5')"
                                        value="{{ old('re_floor', @$product->re_floor ?? 0) }}">
                                </div>
                            </div>

                            <!-- Chu kỳ giá thuê (chỉ hiển thị khi là cho thuê) -->
                            <div class="mt-6 re-rent-period-row"
                                style="{{ old('re_type', @$product->re_type ?? 'sale') == 'rent' ? '' : 'display: none;' }}">
                                <label class="text-[16px] font-normal text-[#272343] leading-relaxed">
                                    @lang('Chu kỳ giá thuê:') <span class="text-danger">*</span>
                                </label>
                                <select name="re_rent_period"
                                    class="w-full h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange mt-2 select2-basic"
                                    style="max-width: 320px;">
                                    @php $rp = old('re_rent_period', @$product->re_rent_period ?? 'month'); @endphp
                                    <option value="month" @selected($rp == 'month')>@lang('Theo tháng')</option>
                                    <option value="year" @selected($rp == 'year')>@lang('Theo năm')</option>
                                    <option value="day" @selected($rp == 'day')>@lang('Theo ngày')</option>
                                </select>
                                <small
                                    class="text-muted text-xs mt-1 block">@lang('Chỉ áp dụng khi loại hình là Cho thuê.')</small>
                            </div>
                        </div>

                        <!-- IV. Thông tin mô tả chi tiết -->
                        <div class="bg-white rounded-[12px] p-6 mb-6 border border-gray-100">
                            <h2 class="text-xl font-bold text-kviet-dark mb-6">@lang('IV. Mô tả chi tiết')</h2>

                            <div>
                                <label class="text-[16px] font-normal text-[#272343] leading-relaxed">
                                    @lang('Mô tả:') <span class="text-danger">*</span>
                                </label>
                                <textarea name="description" id="description"
                                    class="w-full mt-2 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange"
                                    rows="8"
                                    placeholder="@lang('Nhập nội dung mô tả chi tiết về bất động sản...')">{{ old('description', @$product->description) }}</textarea>
                            </div>
                        </div>

                        <!-- V. Thông tin hình ảnh -->
                        <div class="bg-white rounded-[12px] p-6 mb-6 border border-gray-100">
                            <h2 class="text-xl font-bold text-kviet-dark mb-6">@lang('V. Thông tin hình ảnh')</h2>

                            <p class="text-sm text-muted mb-4">*
                                @lang('Up ít nhất 3 ảnh cho bài đăng để đạt hiệu quả tốt nhất.')
                            </p>

                            <!-- Ảnh đại diện -->
                            <div class="mb-6">
                                <label
                                    class="text-[16px] font-medium text-[#272343] mb-2">@lang('Ảnh đại diện (Main Image)')
                                    <span class="text-danger">*</span></label>
                                <div id="mainImageBox"
                                    class="relative h-[200px] flex items-center justify-center p-3 bg-[#00000014] rounded-xl border border-solid border-neutral-300">
                                    <button type="button" id="mainImageBtn"
                                        class="all-[unset] box-border inline-flex items-center justify-center gap-2 px-[18px] py-2.5 bg-white rounded-xl border border-solid border-neutral-300 shadow-[0_1px_2px_rgba(0,0,0,0.08)] cursor-pointer hover:bg-gray-50 transition-colors">
                                        <img alt="" class="w-6 h-6"
                                            src="https://c.animaapp.com/mnvtah15dkaXN7/img/import.svg" />
                                        <span
                                            class="font-semibold text-[#272343] text-base leading-6">@lang('Click or drop image')</span>
                                    </button>
                                    <input type="file" name="main_image" id="mainImageInput" class="hidden"
                                        accept=".png,.jpg,.jpeg">
                                    @php
                                        $mainImagePath = @$product->main_image ? getImage(getFilePath('product') . '/' . $product->main_image) : null;
                                    @endphp
                                    @if($mainImagePath)
                                        <div class="absolute inset-0 group">
                                            <img id="mainImagePreview" src="{{ $mainImagePath }}"
                                                class="w-full h-full object-cover rounded-xl">
                                            <button type="button" id="mainImageRemoveBtn"
                                                class="absolute top-2 right-2 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <small class="text-muted mt-2 d-block">@lang('Ảnh hiển thị chính.')</small>
                            </div>

                            <!-- Ảnh chi tiết -->
                            <div class="mb-4">
                                <label class="text-[16px] font-medium text-[#272343] mb-2 flex items-center gap-2">
                                    @lang('Ảnh chi tiết (Additional Images)')
                                    <svg class="w-5 h-5 text-[#8a8a8a]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span
                                        class="text-xs bg-black/10 px-2 py-0.5 rounded-md text-[#8a8a8a]">@lang('Tối đa 6 ảnh')</span>
                                </label>
                                <div id="additional-image-box"
                                    class="relative h-[200px] flex items-center justify-center p-3 bg-[#00000014] rounded-xl border border-solid border-neutral-300">
                                    <button type="button" id="additional-images-btn"
                                        class="all-[unset] box-border inline-flex items-center justify-center gap-2 px-[18px] py-2.5 bg-white rounded-xl border border-solid border-neutral-300 shadow-[0_1px_2px_rgba(0,0,0,0.08)] cursor-pointer hover:bg-gray-50 transition-colors">
                                        <img alt="" class="w-6 h-6"
                                            src="https://c.animaapp.com/mnvtah15dkaXN7/img/import.svg" />
                                        <span
                                            class="font-semibold text-[#272343] text-base leading-6">@lang('Click or drop image')</span>
                                    </button>
                                    <input type="file" id="additional-images-input" class="hidden" multiple
                                        accept="image/*">
                                </div>
                                <div id="additional-images-preview" class="flex flex-wrap gap-3 mt-3"></div>
                                @if(isset($images) && count($images) > 0)
                                    <div class="mt-3 flex flex-wrap gap-3">
                                        @foreach($images as $img)
                                            <div class="inline-block relative group existing-image-item" data-id="{{ $img['id'] }}">
                                                <img src="{{ $img['src'] }}" alt=""
                                                    class="w-24 h-24 object-cover rounded-lg border">
                                                <button type="button"
                                                    class="remove-existing-btn absolute bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center cursor-pointer text-sm hover:bg-red-600 transition-colors shadow-sm z-10"
                                                    style="top: 0px; right: 0px;" data-id="{{ $img['id'] }}">×</button>
                                                <input type="hidden" name="old[]" value="{{ $img['id'] }}">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <small class="form-text text-muted mt-2 d-block">
                                <i class="la la-info-circle"></i> @lang('Bạn có thể tải lên tối đa 6 hình ảnh chi tiết.')
                            </small>

                            <small class="text-danger text-xs block mt-2">
                                *
                                @lang('Lưu ý: Ảnh tải lên không được là ảnh sao chép trên internet, không chứa logo, thông tin của website khác, kích thước tối thiểu 300 x 300px')
                            </small>
                        </div>

                        <!-- VI. Thông tin liên hệ -->
                        <div class="bg-white rounded-[12px] p-6 mb-6 border border-gray-100">
                            <h2 class="text-xl font-bold text-kviet-dark mb-6">@lang('VI. Thông tin liên hệ')</h2>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="text-[16px] font-normal text-[#272343] leading-relaxed">
                                        @lang('Tên liên hệ:')
                                    </label>
                                    <input type="text" name="re_contact_name"
                                        class="w-full h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange mt-2"
                                        placeholder="@lang('Nhập họ tên')"
                                        value="{{ old('re_contact_name', @$product->re_contact_name ?? seller()->fullname) }}">
                                </div>
                                <div>
                                    <label class="text-[16px] font-normal text-[#272343] leading-relaxed">
                                        @lang('Số điện thoại:') <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="re_contact_phone"
                                        class="w-full h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange mt-2"
                                        placeholder="@lang('Nhập số điện thoại')" required
                                        value="{{ old('re_contact_phone', @$product->re_contact_phone ?? seller()->mobile) }}">
                                </div>
                                <div>
                                    <label class="text-[16px] font-normal text-[#272343] leading-relaxed">
                                        @lang('Email:')
                                    </label>
                                    <input type="email" name="re_contact_email"
                                        class="w-full h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange mt-2"
                                        placeholder="@lang('Nhập email')"
                                        value="{{ old('re_contact_email', @$product->re_contact_email ?? seller()->email) }}">
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <a href="{{ route('seller.products.all') }}"
                                class="h-[49px] px-6 flex items-center gap-2 bg-gray-100 text-[#272343] font-bold rounded-[12px] hover:bg-gray-200 transition-colors mr-3">
                                @lang('HỦY')
                            </a>
                            <button type="submit"
                                class="bg-[#FF6F0F] text-white px-[18px] py-[10px] rounded-[12px] shadow-[inset_0_1px_2px_0_rgba(255,255,255,0.40),inset_0_-1px_2px_0_rgba(0,0,0,0.24),0_1px_2px_0_rgba(0,0,0,0.08)] font-bold text-sm md:text-[15px] hover:bg-orange-600 transition-colors">
                                @lang('ĐĂNG TIN')
                            </button>
                        </div>
                    </form>
                </section>
            </div>
        </main>
    </div>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/real-estate-form.css') }}">
    <style>
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: 49px;
            border-color: #E6E6E6;
            border-radius: 6px;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 49px;
            padding-left: 16px;
            color: #272343;
            font-size: 16px;
            padding-right: 30px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 47px;
            margin-right: 10px !important;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--multiple {
            min-height: 49px;
            border-color: #E6E6E6;
            border-radius: 6px;
            padding-left: 8px;
            padding-top: 2px;
            padding-bottom: 2px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            padding: 0 8px;
        }

        .select2-container--default .select2-selection--multiple .select2-search--inline .select2-search__field {
            margin-top: 8px;
            line-height: 24px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #f7f7f7;
            border: 1px solid #e6e6e6;
            border-radius: 4px;
            padding: 2px 8px;
            margin-top: 6px;
        }

        @media (max-width: 767px) {

            .select2-container--default .select2-selection--single,
            .select2-container--default .select2-selection--multiple {
                height: 48px;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 48px;
            }
        }

        .re-input-group label span {
            color: red;
        }

        .re-input-group textarea[name="re_map_embed"] {
            font-family: monospace;
            font-size: 12px;
        }

        .re-map-embed iframe {
            width: 100%;
            height: 350px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .re-type-toggle .re-type-btn {
            flex: 1;
            text-align: center;
        }

        .re-type-btn {
            padding: 10px 24px;
            border-radius: 6px;
            border: 1px solid #E6E6E6;
            background: white;
            color: #272343;
            font-weight: 500;
            transition: all 0.2s;
            cursor: pointer;
        }

        .re-type-btn:hover {
            background: #f5f5f5;
        }

        .re-type-btn.active {
            background: #FF6F0F;
            color: white;
            border-color: #FF6F0F;
        }

        .btn-range-toggle {
            padding: 10px 16px;
            border-radius: 6px;
            border: 1px solid #E6E6E6;
            background: white;
            color: #272343;
            font-size: 14px;
            transition: all 0.2s;
        }

        .btn-range-toggle:hover {
            background: #f5f5f5;
        }
    </style>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
@endpush

@push('script')
    <script>
        (function ($) {
            "use strict";

            // jQuery Validation
            const realEstateForm = $('#realEstateForm');

            realEstateForm.validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 255
                    },
                    re_province_id: {
                        required: true
                    },
                    re_ward_id: {
                        required: true
                    },
                    re_price_from: {
                        required: function () {
                            return !$('#negotiable').is(':checked');
                        },
                        number: true,
                        min: 0
                    },
                    re_price_to: {
                        number: true,
                        min: 0
                    },
                    re_area: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    re_area_to: {
                        number: true,
                        min: 0
                    },
                    re_bedrooms: {
                        number: true,
                        min: 0,
                        max: 50
                    },
                    re_bathrooms: {
                        number: true,
                        min: 0,
                        max: 50
                    },
                    re_floor: {
                        number: true,
                        min: 0,
                        max: 200
                    },
                    re_contact_phone: {
                        required: true,
                        maxlength: 20
                    },
                    re_contact_name: {
                        maxlength: 255
                    },
                    re_contact_email: {
                        email: true,
                        maxlength: 255
                    },
                    re_transaction_type: {
                        required: true
                    },
                    re_transaction_method: {
                        required: true
                    },
                    're_listing_conditions[]': {
                        required: true
                    },
                    re_filter_property_type: {
                        required: true
                    },
                    description: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: '@lang("Vui lòng nhập tiêu đề tin đăng")',
                        maxlength: '@lang("Tiêu đề không được vượt quá 255 ký tự")'
                    },
                    re_province_id: {
                        required: '@lang("Vui lòng chọn Tỉnh/Thành phố")'
                    },
                    re_ward_id: {
                        required: '@lang("Vui lòng chọn Xã/Phường")'
                    },
                    re_price_from: {
                        required: '@lang("Vui lòng nhập giá tiền")',
                        number: '@lang("Giá tiền phải là số")',
                        min: '@lang("Giá tiền không được âm")'
                    },
                    re_price_to: {
                        number: '@lang("Giá tiền phải là số")',
                        min: '@lang("Giá tiền không được âm")'
                    },
                    re_area: {
                        required: '@lang("Vui lòng nhập diện tích")',
                        number: '@lang("Diện tích phải là số")',
                        min: '@lang("Diện tích không được âm")'
                    },
                    re_area_to: {
                        number: '@lang("Diện tích phải là số")',
                        min: '@lang("Diện tích không được âm")'
                    },
                    re_bedrooms: {
                        number: '@lang("Số phòng ngủ phải là số")',
                        min: '@lang("Số phòng ngủ không được âm")',
                        max: '@lang("Số phòng ngủ không được vượt quá 50")'
                    },
                    re_bathrooms: {
                        number: '@lang("Số phòng tắm phải là số")',
                        min: '@lang("Số phòng tắm không được âm")',
                        max: '@lang("Số phòng tắm không được vượt quá 50")'
                    },
                    re_floor: {
                        number: '@lang("Số tầng phải là số")',
                        min: '@lang("Số tầng không được âm")',
                        max: '@lang("Số tầng không được vượt quá 200")'
                    },
                    description: {
                        required: '@lang("Vui lòng nhập mô tả")'
                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
                errorPlacement: function (error, element) {
                    if (element.hasClass('select2-basic')) {
                        error.insertAfter(element.next('.select2'));
                    } else if (element.closest('.d-flex').length) {
                        error.insertAfter(element.closest('.d-flex'));
                    } else {
                        error.insertAfter(element);
                    }
                    error.addClass('text-danger text-xs mt-1');
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                },
                submitHandler: function (form) {
                    $(form).find('button[type=submit]').prop('disabled', true);
                    form.submit();
                }
            });

            // Cascading Property Types logic
            const propertyTypes = @json($propertyTypes);
            const parentSelect = $('#parent_type');
            const childSelect = $('#child_type');
            const childWrapper = $('#child_type_wrapper');
            const finalInput = $('#final_property_type');
            const currentVal = finalInput.val();

            function updatePropertyType() {
                const selectedParentId = parentSelect.val();
                const children = propertyTypes.filter(t => t.parent_id == selectedParentId);

                childSelect.html('<option value="">@lang("Chọn loại hình chi tiết")</option>');

                if (children.length > 0) {
                    childWrapper.show();
                    children.forEach(child => {
                        const isSelected = (child.id == currentVal) ? 'selected' : '';
                        childSelect.append(`<option value="${child.id}" ${isSelected}>${child.name}</option>`);
                    });

                    // If a child is selected, use it, otherwise use parent ID
                    finalInput.val(childSelect.val() || selectedParentId);
                } else {
                    childWrapper.hide();
                    finalInput.val(selectedParentId || '');
                }

                // Trigger validation on hidden input
                finalInput.valid();
            }

            parentSelect.on('change', function () {
                updatePropertyType();
            });

            childSelect.on('change', function () {
                finalInput.val($(this).val() || parentSelect.val());
                finalInput.valid();
            });

            if (parentSelect.val()) {
                updatePropertyType();
            }

            // Toggle rent period row based on property type
            function toggleReRentPeriodRow() {
                var isRent = $('input[name=re_type]').val() === 'rent';
                $('.re-rent-period-row').toggle(isRent);
            }

            $('.re-type-btn').on('click', function () {
                $('.re-type-btn').removeClass('active');
                $(this).addClass('active');
                $('input[name=re_type]').val($(this).data('value'));
                toggleReRentPeriodRow();
            });
            toggleReRentPeriodRow();

            // Handle Province change
            $('select[name=re_province_id]').on('change', function () {
                var provinceId = $(this).val();
                var wardSelect = $('select[name=re_ward_id]');
                var currentWard = "{{ old('re_ward_id', @$product->re_ward_id) }}";

                wardSelect.html('<option value="">@lang('Chọn Xã/Phường')</option>');

                if (provinceId) {
                    var url = "{{ route('seller.real_estate.config.get_wards', ':id') }}";
                    $.get(url.replace(':id', provinceId), function (data) {
                        $.each(data, function (key, ward) {
                            wardSelect.append(
                                `<option value="${ward.id}" ${ward.id == currentWard ? 'selected' : ''}>${ward.full_name}</option>`);
                        });
                        wardSelect.trigger('change');
                    });
                }
                wardSelect.trigger('change');
            }).change();

            // Handle Area Range Toggle
            $('.btn-area-toggle').on('click', function () {
                var wrapper = $('.area-to-wrapper');
                if (wrapper.is(':visible')) {
                    wrapper.hide();
                    $('input[name=re_area_to]').val('');
                } else {
                    wrapper.show();
                }
            });

            // Handle Price Range Toggle
            $('.btn-price-toggle').on('click', function () {
                var wrapper = $('.price-to-wrapper');
                if (wrapper.is(':visible')) {
                    wrapper.hide();
                    $('input[name=re_price_to]').val('');
                } else {
                    wrapper.show();
                }
            });

            // Handle Negotiable Price Checkbox
            $('#negotiable').on('change', function () {
                if ($(this).is(':checked')) {
                    $('.price-input').prop('disabled', true).val('');
                    $('.btn-price-toggle').prop('disabled', true);
                } else {
                    $('.price-input').prop('disabled', false);
                    $('.btn-price-toggle').prop('disabled', false);
                }
            }).change();

            // Initialize Select2
            $('select.select2-basic').select2();

            $('select.select2-basic').on('change', function () {
                $(this).valid();
            });

            // Main Image Upload
            const mainImageBtn = document.getElementById('mainImageBtn');
            const mainImageInput = document.getElementById('mainImageInput');
            const mainImageBox = document.getElementById('mainImageBox');

            function showMainImagePreview(file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    const oldPreview = mainImageBox.querySelector('.group');
                    if (oldPreview) oldPreview.remove();

                    const previewWrapper = document.createElement('div');
                    previewWrapper.className = 'absolute inset-0 group';
                    previewWrapper.innerHTML = `
                                    <img src="${event.target.result}" class="w-full h-full object-cover rounded-xl">
                                    <button type="button" class="remove-btn absolute top-2 right-2 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                `;
                    mainImageBox.appendChild(previewWrapper);
                    mainImageBtn.classList.add('hidden');

                    previewWrapper.querySelector('.remove-btn').addEventListener('click', function (e) {
                        e.stopPropagation();
                        removeMainImage();
                    });
                };
                reader.readAsDataURL(file);
            }

            function removeMainImage() {
                const preview = mainImageBox.querySelector('.absolute.inset-0');
                if (preview) preview.remove();
                mainImageInput.value = '';
                mainImageBtn.classList.remove('hidden');
            }

            if (mainImageBtn && mainImageInput) {
                mainImageBtn.addEventListener('click', function () {
                    mainImageInput.click();
                });

                mainImageInput.addEventListener('change', function (e) {
                    if (e.target.files && e.target.files[0]) {
                        showMainImagePreview(e.target.files[0]);
                    }
                });
            }

            mainImageBox.addEventListener('click', function (e) {
                if (e.target.closest('.remove-btn')) {
                    e.stopPropagation();
                    removeMainImage();
                }
            });

            // Additional Images Upload
            let additionalFiles = [];
            const maxImages = 6;
            const additionalImagesInput = document.getElementById('additional-images-input');
            const additionalImagesPreview = document.getElementById('additional-images-preview');
            const additionalImagesBtn = document.getElementById('additional-images-btn');

            if (additionalImagesBtn && additionalImagesInput) {
                additionalImagesBtn.addEventListener('click', function () {
                    additionalImagesInput.click();
                });

                additionalImagesInput.addEventListener('change', function (e) {
                    handleAdditionalFiles(e.target.files);
                    e.target.value = '';
                });
            }

            function handleAdditionalFiles(files) {
                for (let i = 0; i < files.length; i++) {
                    if (additionalFiles.length >= maxImages) break;
                    if (!files[i].type.startsWith('image/')) continue;
                    additionalFiles.push(files[i]);
                }
                renderAdditionalPreviews();
                updateAdditionalHiddenInput();
            }

            function renderAdditionalPreviews() {
                additionalImagesPreview.innerHTML = '';
                additionalFiles.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        const div = document.createElement('div');
                        div.className = 'inline-block relative group';
                        div.innerHTML = `
                                        <img src="${event.target.result}" alt="" class="w-24 h-24 object-cover rounded-lg border">
                                        <button type="button" class="remove-btn absolute bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center cursor-pointer text-sm hover:bg-red-600 transition-colors shadow-sm z-10"
                                            style="top: 0px; right: 0px;"
                                            data-index="${index}">×</button>
                                    `;
                        additionalImagesPreview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });

                if (additionalFiles.length >= maxImages) {
                    additionalImagesBtn.classList.add('hidden');
                } else {
                    additionalImagesBtn.classList.remove('hidden');
                }
            }

            function updateAdditionalHiddenInput() {
                const form = document.getElementById('realEstateForm');
                let photosInput = form.querySelector('input[name="photos[]"]');
                if (photosInput) photosInput.remove();

                const dataTransfer = new DataTransfer();
                additionalFiles.forEach(file => dataTransfer.items.add(file));

                const input = document.createElement('input');
                input.type = 'file';
                input.name = 'photos[]';
                input.multiple = true;
                input.className = 'hidden';
                input.files = dataTransfer.files;
                form.appendChild(input);
            }

            if (additionalImagesPreview) {
                additionalImagesPreview.addEventListener('click', function (e) {
                    if (e.target.classList.contains('remove-btn')) {
                        const index = parseInt(e.target.dataset.index);
                        additionalFiles.splice(index, 1);
                        renderAdditionalPreviews();
                        updateAdditionalHiddenInput();
                    }
                });
            }

            $(document).on('click', '.remove-existing-btn', function () {
                $(this).closest('.existing-image-item').remove();
            });

        })(jQuery);
    </script>
@endpush