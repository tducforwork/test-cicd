@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="bg-[#F7F7F7]">
    <main class="container mx-auto pb-32 pt-10">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar -->
            <aside class="w-full lg:w-[312px] shrink-0">
                @include('seller.partials.sidebar')
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 min-w-0">
                <div class="bg-[#f7f7f7]">
                    <!-- Main Content -->
                    <div class="max-w-[1320px] mx-auto px-4 md:px-0 pb-[60px]">
                        <!-- Page heading -->
                        <div class="flex items-center gap-4 md:gap-6 mb-6">
                            <a href="{{ route('seller.products.all') }}">
                                <img alt="back" src="https://c.animaapp.com/mnvtah15dkaXN7/img/button.svg" class="w-10 h-10 cursor-pointer" />
                            </a>
                            <span class="text-[#272343] text-lg md:text-[20px] font-bold leading-[150%]">@lang('Product')</span>
                        </div>

                        <!-- Form and Preview Wrapper -->
                        <div class="flex flex-col xl:flex-row gap-6 md:gap-[24px] items-start">
                            <form action="{{ route('seller.products.product.store', $product->id ?? 0) }}" id="addForm" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col gap-6 w-full">
                                @csrf

                                <!-- Name & Description Card -->
                                <div class="flex flex-col gap-4 md:gap-6 p-4 md:p-6 bg-white rounded-lg">
                                    <h2 class="text-[#272343] text-lg md:text-[20px] font-bold leading-[150%]">@lang('Name & Description')</h2>

                                    <!-- Product Name -->
                                    <div class="flex flex-col gap-2">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <div class="inline-flex items-center gap-1">
                                                <span class="text-[#272343] text-[14px] font-semibold leading-[24px] tracking-[-0.14px]">@lang('Product title')</span>
                                                <img alt="" class="w-5 h-5" src="https://c.animaapp.com/mnvtah15dkaXN7/img/info.svg" />
                                            </div>
                                            <div class="inline-flex items-center justify-center px-2 py-0.5 bg-[#00000014] rounded-md">
                                                <span class="text-[#8a8a8a] text-xs">@lang('Maximum 100 characters')</span>
                                            </div>
                                        </div>
                                        <input type="text" class="w-full h-[48px] md:h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none"
                                            value="{{ old('name', @$product->name) }}" name="name" required placeholder="@lang('Enter product name...')" />
                                    </div>

                                    <!-- Description -->
                                    <div class="flex flex-col gap-2">
                                        <div class="inline-flex items-center gap-1">
                                            <span class="text-[#272343] text-[14px] font-semibold leading-[24px] tracking-[-0.14px]">@lang('Description')</span>
                                        </div>
                                        <textarea id="description" name="description" rows="6"
                                            class="w-full px-4 py-3 rounded-xl border border-[#e6e6e6] bg-white text-base focus:outline-none focus:ring-1 focus:ring-[#FF6F0F]">{{ old('description', @$product->description) }}</textarea>
                                    </div>

                                    <!-- Categories -->
                                    <div class="flex flex-col gap-2">
                                        <div class="inline-flex items-center gap-1">
                                            <span class="text-[#272343] text-[14px] font-semibold leading-[24px] tracking-[-0.14px]">@lang('Category')</span>
                                        </div>
                                        <div class="select2-parent">
                                            <select class="category-select2 w-full h-[48px] md:h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none" name="categories[]" id="categories">
                                                <option value="">@lang('Select One')</option>
                                                @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" data-title="{{ __($category->name) }}">@lang($category->name)</option>
                                                @php $prefix = '--'; @endphp
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

                                    <!-- SKU -->
                                    <div class=" flex-col gap-2 d-none">
                                        <div class="inline-flex items-center gap-1">
                                            <span class="text-[#272343] text-[14px] font-semibold leading-[24px] tracking-[-0.14px]">@lang('SKU')</span>
                                        </div>
                                        <input type="text" class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none"
                                            value="{{ old('sku', @$product->sku) }}" name="sku" placeholder="@lang('Enter SKU...')" />
                                    </div>

                                    <!-- Tags -->
                                    <div class="flex flex-col gap-2">
                                        <div class="inline-flex items-center gap-1">
                                            <span class="text-[#272343] text-[14px] font-semibold leading-[24px] tracking-[-0.14px]">@lang('Tags')</span>
                                            <img alt="" class="w-5 h-5" src="https://c.animaapp.com/mnvtah15dkaXN7/img/info.svg" />
                                        </div>
                                        <div class="select2-parent">
                                            <select class="select2-auto-tokenize-seller" name="meta_keywords[]" multiple="multiple">
                                                @php
                                                $metaKeywords = null;
                                                if (old('meta_keywords')) {
                                                $metaKeywords = old('meta_keywords');
                                                } elseif (isset($product) && $product->meta_keywords) {
                                                $metaKeywords = $product->meta_keywords;
                                                }
                                                @endphp
                                                @if ($metaKeywords)
                                                @foreach ($metaKeywords as $option)
                                                <option value="{{ $option }}" selected>{{ __($option) }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Images Card -->
                                <div class="flex flex-col gap-4 md:gap-6 p-4 md:p-6 bg-white rounded-lg">
                                    <h2 class="font-bold text-[#272343] text-lg md:text-xl leading-[30px]">@lang('Images')</h2>

                                    <!-- Cover Image -->
                                    <div class="flex flex-col gap-2">
                                        <div class="inline-flex items-center gap-1">
                                            <span class="text-[#272343] text-[14px] font-semibold leading-[24px] tracking-[-0.14px]">@lang('Cover images')</span>
                                        </div>
                                        <div id="mainImageBox" class="relative rounded-[12px] border-[1px] border-[#D4D4D4] bg-[rgba(0,_0,_0,_0.08)] flex h-[180px] md:h-[200px] p-[12px] justify-center items-center self-stretch">
                                            <button type="button" id="mainImageBtn"
                                                class="all-[unset] box-border inline-flex items-center justify-center gap-2 px-[18px] py-2.5 bg-white rounded-xl border border-solid border-neutral-300 shadow-[0_1px_2px_rgba(0,0,0,0.08)] cursor-pointer hover:bg-gray-50 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M12.9002 5.1002C12.9002 4.60314 12.4973 4.2002 12.0002 4.2002C11.5031 4.2002 11.1002 4.60314 11.1002 5.1002V13.1274L9.03659 11.0638C8.68512 10.7123 8.11527 10.7123 7.7638 11.0638C7.41233 11.4153 7.41233 11.9851 7.7638 12.3366L11.3638 15.9366C11.7153 16.2881 12.2851 16.2881 12.6366 15.9366L16.2366 12.3366C16.5881 11.9851 16.5881 11.4153 16.2366 11.0638C15.8851 10.7123 15.3153 10.7123 14.9638 11.0638L12.9002 13.1274L12.9002 5.1002Z" fill="#272343" />
                                                    <path d="M19.8002 16.5002C19.8002 16.0031 19.3973 15.6002 18.9002 15.6002C18.4031 15.6002 18.0002 16.0031 18.0002 16.5002V17.4602C18.0002 17.9573 17.5973 18.3602 17.1002 18.3602L6.9002 18.3602C6.40314 18.3602 6.0002 17.9572 6.0002 17.4602L6.0002 16.5002C6.0002 16.0031 5.59725 15.6002 5.1002 15.6002C4.60314 15.6002 4.2002 16.0031 4.2002 16.5002V17.4602C4.2002 18.9514 5.40903 20.1602 6.9002 20.1602L17.1002 20.1602C18.5914 20.1602 19.8002 18.9514 19.8002 17.4602V16.5002Z" fill="#272343" />
                                                </svg>
                                                <span class="text-[#272343] text-[15px] font-bold leading-[24px] tracking-[-0.15px]">@lang('Click or drag and drop image')</span>
                                            </button>
                                            <input type="file" name="main_image" id="mainImageInput" class="hidden" accept="image/png, image/jpeg, image/jpg">
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
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Additional Images -->
                                    <div class="flex flex-col gap-2">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <div class="inline-flex items-center gap-1">
                                                <span class="text-[#272343] text-[14px] font-semibold leading-[24px] tracking-[-0.14px]">@lang('Images')</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M12.9002 5.1002C12.9002 4.60314 12.4973 4.2002 12.0002 4.2002C11.5031 4.2002 11.1002 4.60314 11.1002 5.1002V13.1274L9.03659 11.0638C8.68512 10.7123 8.11527 10.7123 7.7638 11.0638C7.41233 11.4153 7.41233 11.9851 7.7638 12.3366L11.3638 15.9366C11.7153 16.2881 12.2851 16.2881 12.6366 15.9366L16.2366 12.3366C16.5881 11.9851 16.5881 11.4153 16.2366 11.0638C15.8851 10.7123 15.3153 10.7123 14.9638 11.0638L12.9002 13.1274L12.9002 5.1002Z" fill="#272343" />
                                                    <path d="M19.8002 16.5002C19.8002 16.0031 19.3973 15.6002 18.9002 15.6002C18.4031 15.6002 18.0002 16.0031 18.0002 16.5002V17.4602C18.0002 17.9573 17.5973 18.3602 17.1002 18.3602L6.9002 18.3602C6.40314 18.3602 6.0002 17.9572 6.0002 17.4602L6.0002 16.5002C6.0002 16.0031 5.59725 15.6002 5.1002 15.6002C4.60314 15.6002 4.2002 16.0031 4.2002 16.5002V17.4602C4.2002 18.9514 5.40903 20.1602 6.9002 20.1602L17.1002 20.1602C18.5914 20.1602 19.8002 18.9514 19.8002 17.4602V16.5002Z" fill="#272343" />
                                                </svg>
                                            </div>
                                            <div class="inline-flex items-center justify-center px-2 py-0.5 bg-[#00000014] rounded-md">
                                                <span class="text-[#8a8a8a] text-xs">@lang('Maximum 6 images')</span>
                                            </div>
                                        </div>
                                        <div id="additional-image-box" class="relative rounded-[12px] border-[1px] border-[#D4D4D4] bg-[rgba(0,_0,_0,_0.08)] flex h-[180px] md:h-[200px] p-[12px] justify-center items-center self-stretch">
                                            <button type="button" id="additional-images-btn"
                                                class="all-[unset] box-border inline-flex items-center justify-center gap-2 px-[18px] py-2.5 bg-white rounded-xl border border-solid border-neutral-300 shadow-[0_1px_2px_rgba(0,0,0,0.08)] cursor-pointer hover:bg-gray-50 transition-colors">
                                                <img alt="" class="w-6 h-6" src="https://c.animaapp.com/mnvtah15dkaXN7/img/import.svg" />
                                                <span class="text-[#272343] text-[15px] font-bold leading-[24px] tracking-[-0.15px]">@lang('Click or drag and drop image')</span>
                                            </button>
                                            <input type="file" id="additional-images-input" class="hidden" multiple accept="image/*">
                                        </div>
                                        <div id="additional-images-preview" class="flex flex-wrap gap-3 mt-3"></div>
                                        @if(isset($images) && count($images) > 0)
                                        <div class="mt-3 flex flex-wrap gap-3">
                                            @foreach($images as $img)
                                            <div class="inline-block relative group existing-image-item" data-id="{{ $img['id'] }}">
                                                <img src="{{ $img['src'] }}" alt="" class="w-20 h-20 md:w-24 h-24 object-cover rounded-lg border">
                                                <button type="button" class="remove-existing-btn absolute bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center cursor-pointer text-sm hover:bg-red-600 transition-colors shadow-sm z-10"
                                                    style="top: 0px; right: 0px;"
                                                    data-id="{{ $img['id'] }}">×</button>
                                                <input type="hidden" name="old[]" value="{{ $img['id'] }}">
                                            </div>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Price Card -->
                                <div class="flex flex-col gap-4 md:gap-6 p-4 md:p-6 bg-white rounded-lg">
                                    <h2 class="font-bold text-[#272343] text-lg md:text-xl leading-[30px]">@lang('Price')</h2>

                                    @php
                                        $productConfig = \App\Models\ProductConfig::firstOrCreate([]);
                                    @endphp
                                    <div class="flex flex-col gap-2">
                                        <div class="inline-flex items-center gap-1">
                                            <span class="text-[#272343] text-[14px] font-semibold leading-[24px] tracking-[-0.14px]">@lang('Currency')</span>
                                        </div>
                                        <div class="select2-parent">
                                            <select name="currency_type" class="w-full h-[48px] md:h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none" id="currency_type">
                                                <option value="1" @selected(old('currency_type', @$product->currency_type) == 1)>VND</option>
                                                <option value="2" @selected(old('currency_type', @$product->currency_type) == 2)>Tệ (CNY)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-2 cny-price-wrapper hidden">
                                        <div class="inline-flex items-center gap-1">
                                            <span class="text-[#272343] text-[14px] font-semibold leading-[24px] tracking-[-0.14px]">@lang('Base Price (CNY)')</span>
                                        </div>
                                        <div class="price-input-group flex items-center rounded-[12px] border-[1px] border-[solid] border-[#272343] bg-[#FFF] overflow-hidden transition-all duration-200">
                                            <div class="inline-flex items-center justify-center px-4 py-2.5 bg-[#00000014]">
                                                <span class="font-medium text-[#8a8a8a]">CNY</span>
                                            </div>
                                            <input type="number" step="any" min="0" class="flex-1 h-[44px] md:h-[49px] px-4 bg-white text-[#272343] text-base focus:outline-none border-none shadow-none"
                                                name="cny_price" id="cny_price" value="{{ old('cny_price', @$product->cny_price ? getAmount($product->cny_price) : '') }}" placeholder="0.00" />
                                        </div>
                                    </div>

                                    <!-- Base Price -->
                                    <div class="flex flex-col gap-2">
                                        <div class="inline-flex items-center gap-1">
                                            <span class="text-[#272343] text-[14px] font-semibold leading-[24px] tracking-[-0.14px]">@lang('Amount (VND)')</span>
                                        </div>
                                        <div class="price-input-group flex items-center rounded-[12px] border-[1px] border-[solid] border-[#272343] bg-[#FFF] overflow-hidden transition-all duration-200">
                                            <div class="inline-flex items-center justify-center px-4 py-2.5 bg-[#00000014]">
                                                <span class="font-medium text-[#8a8a8a]">{{ gs('cur_sym') }}</span>
                                            </div>
                                            <input type="number" step="any" min="0" class="flex-1 h-[44px] md:h-[49px] px-4 bg-white text-[#272343] text-base focus:outline-none border-none shadow-none"
                                                name="base_price" id="base_price" value="{{ old('base_price', @$product->base_price ? getAmount($product->base_price) : '') }}" required placeholder="0.00" />
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-2 cny-discount-price-wrapper hidden">
                                        <div class="inline-flex items-center gap-1">
                                            <span class="text-[#272343] text-[14px] font-semibold leading-[24px] tracking-[-0.14px]">@lang('Discount Price (CNY)')</span>
                                        </div>
                                        <div class="price-input-group flex items-center rounded-[12px] border-[1px] border-[solid] border-[#272343] bg-[#FFF] overflow-hidden transition-all duration-200">
                                            <div class="inline-flex items-center justify-center px-4 py-2.5 bg-[#00000014]">
                                                <span class="font-medium text-[#8a8a8a]">CNY</span>
                                            </div>
                                            <input type="number" step="any" min="0" class="flex-1 h-[44px] md:h-[49px] px-4 bg-white text-[#272343] text-base focus:outline-none border-none shadow-none"
                                                name="cny_discount_price" id="cny_discount_price" value="{{ old('cny_discount_price', @$product->cny_discount_price ? getAmount($product->cny_discount_price) : '') }}" placeholder="0.00" />
                                        </div>
                                    </div>

                                    <!-- Discount Price -->
                                    <div class="flex flex-col gap-2">
                                        <div class="inline-flex items-center gap-1">
                                            <span class="text-[#272343] text-[14px] font-semibold leading-[24px] tracking-[-0.14px]">@lang('Discount Price (VND)')</span>
                                        </div>
                                        <div class="price-input-group flex items-center rounded-[12px] border-[1px] border-[solid] border-[#272343] bg-[#FFF] overflow-hidden transition-all duration-200">
                                            <div class="inline-flex items-center justify-center px-4 py-2.5 bg-[#00000014]">
                                                <span class="font-medium text-[#8a8a8a]">{{ gs('cur_sym') }}</span>
                                            </div>
                                            <input type="number" step="any" min="0" class="flex-1 h-[44px] md:h-[49px] px-4 bg-white text-[#272343] text-base focus:outline-none border-none shadow-none"
                                                name="discount_price" id="discount_price" value="{{ old('discount_price', @$product->discount_price ? getAmount($product->discount_price) : '') }}" placeholder="0.00" />
                                        </div>
                                    </div>

                                    <!-- Negotiable Toggle -->
                                    <div class="flex flex-col gap-4">
                                        <div class="w-full h-px bg-neutral-300 rounded-full"></div>
                                        <div class="flex items-center gap-4">
                                            <div class="flex items-center gap-2 flex-1">
                                                <div class="inline-flex items-center gap-1">
                                                    <span class="text-[rgba(39,_35,_67,_0.24)] text-[14px] font-semibold leading-[24px] tracking-[-0.14px]">@lang('Negotiable')</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                        <path d="M9.99998 14C9.58577 14 9.24999 13.6642 9.25 13.25L9.25006 9.74999C9.25007 9.33577 9.58586 8.99999 10.0001 9C10.4143 9.00001 10.7501 9.3358 10.7501 9.75001L10.75 13.25C10.75 13.6642 10.4142 14 9.99998 14Z" fill="#272343" fill-opacity="0.24" />
                                                        <path d="M9 7C9 6.44772 9.44772 6 10 6C10.5523 6 11 6.44772 11 7C11 7.55228 10.5523 8 10 8C9.44772 8 9 7.55228 9 7Z" fill="#272343" fill-opacity="0.24" />
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10ZM15.5 10C15.5 13.0376 13.0376 15.5 10 15.5C6.96243 15.5 4.5 13.0376 4.5 10C4.5 6.96243 6.96243 4.5 10 4.5C13.0376 4.5 15.5 6.96243 15.5 10Z" fill="#272343" fill-opacity="0.24" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="negotiable" value="1" @checked(old('negotiable', @$product->negotiable)) class="sr-only peer">
                                                <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#FF6F0F] shadow-[0_1px_2px_rgba(0,0,0,0.08)]"></div>
                                            </label>
                                        </div>
                                    </div>

                                    @php
                                    $trackInventory = old('track_inventory', @$product->track_inventory ?? 1);
                                    $showInFrontend = old('show_in_frontend', @$product->show_in_frontend ?? 0);
                                    $hasVariants = old('has_variants', @$product->has_variants ?? 0);
                                    @endphp
                                    <input type="hidden" name="track_inventory" value="{{ $trackInventory ? 1 : 0 }}">
                                    <input type="hidden" name="show_in_frontend" value="{{ $showInFrontend ? 1 : 0 }}">
                                    <input type="hidden" name="has_variants" value="{{ $hasVariants ? 1 : 0 }}">
                                </div>

                                <!-- Product Filters -->
                                <div class="flex flex-col gap-4 md:gap-6 p-4 md:p-6 bg-white rounded-lg shadow-sm border border-gray-100">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-6 bg-[#FF6F0F] rounded-full"></div>
                                        <h2 class="font-bold text-[#272343] text-lg md:text-xl leading-[30px]">@lang('Product Filters')</h2>
                                    </div>
                                    
                                    <div class="flex flex-col gap-6">
                                        @forelse($filterGroups as $group)
                                            <div class="flex flex-col gap-3">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[#272343] text-[15px] font-bold tracking-tight opacity-90 uppercase">{{ __($group->name) }}</span>
                                                </div>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                                    @foreach($group->options as $option)
                                                        <label class="filter-item-check flex items-center gap-3 p-3 rounded-xl cursor-pointer hover:bg-orange-50 transition-all group border border-gray-100 hover:border-orange-100 shadow-sm hover:shadow-md">
                                                            <div class="flex items-center justify-center">
                                                                <input type="checkbox" name="filter_options[]" value="{{ $option->id }}" id="option_{{ $option->id }}" class="cursor-pointer m-0" @checked(in_array($option->id, old('filter_options', @$product->filterOptions ? $product->filterOptions->pluck('id')->toArray() : []))) style="width: 18px; height: 18px;">
                                                            </div>
                                                            <span class="text-sm font-medium text-[#666] group-hover:text-[#FF6F0F] transition-colors leading-tight">{{ __($option->value) }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @if(!$loop->last)
                                                <div class="w-full h-px bg-neutral-100 my-2"></div>
                                            @endif
                                        @empty
                                            <div class="text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-200 text-gray-400 italic">
                                                @lang('No filter groups available.')
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- Save / Publish Actions -->
                                <div class="flex items-center gap-4 flex-wrap">
                                    <div class="flex items-center gap-2 flex-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M18.9366 7.16399C19.2881 7.51547 19.2881 8.08531 18.9366 8.43679L11.1366 16.2368C10.7851 16.5883 10.2153 16.5883 9.8638 16.2368L5.9638 12.3368C5.61233 11.9853 5.61233 11.4155 5.9638 11.064C6.31527 10.7125 6.88512 10.7125 7.23659 11.064L10.5002 14.3276L17.6638 7.16399C18.0153 6.81252 18.5851 6.81252 18.9366 7.16399Z" fill="#272343" />
                                        </svg>
                                        <p class="text-[#8A8A8A] text-[13px] font-semibold leading-[16px] tracking-[-0.13px]">
                                            <span>@lang('Last saved')</span>
                                            <span class="text-[#272343] text-[13px] font-semibold leading-[16px] tracking-[-0.13px]">{{ now()->format('M d, Y - H:i') }}</span>
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2 w-full md:w-auto flex-wrap">
                                        <button type="button" class="flex-1 md:flex-none rounded-[12px] border-[1px] border-[solid] border-[#D4D4D4] bg-[#FFF] flex px-[18px] py-[10px] justify-center text-[#272343] font-[Inter] text-[16px] not-italic font-semibold leading-[24px] items-center gap-[8px] [box-shadow:0_1px_2px_0_rgba(255,_255,_255,_0.40)_inset,_0_-1px_2px_0_rgba(0,_0,_0,_0.24)_inset,_0_1px_2px_0_rgba(0,_0,_0,_0.08)] cursor-pointer hover:bg-gray-50 transition-colors">
                                            <span class="font-semibold text-[#272343] text-base leading-6">@lang('Save Draft')</span>
                                        </button>
                                        <button type="submit" class="flex-1 md:flex-none rounded-[12px] border-[1px] border-[solid] border-[#616161] bg-[#272343] flex px-[18px] py-[10px] justify-center items-center gap-[8px] text-[#FFF] text-[16px] font-semibold leading-[24px] [box-shadow:0_1px_2px_0_rgba(255,_255,_255,_0.40)_inset,_0_-1px_2px_0_rgba(0,_0,_0,_0.24)_inset,_0_1px_2px_0_rgba(0,_0,_0,_0.08)] cursor-pointer hover:opacity-90 transition-opacity">
                                            <span class="font-semibold text-white text-base leading-6">@lang('Publish now')</span>
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <!-- Preview Column -->
                            <aside class="w-full xl:w-[312px] sticky top-6">
                                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                                    <h3 class="text-[#272343] text-lg md:text-[20px] font-bold leading-[150%] mb-4 md:mb-[16px]">@lang('Preview')</h3>

                                    <div class="product-cardoverflow-hidden bg-white transition-all duration-300 flex flex-col gap-2 md:gap-[12px]">
                                        <!-- Image Preview -->
                                        <div class="relative aspect-square bg-[#F7F7F7] flex items-center justify-center">
                                            @php
                                            $mainImagePath = @$product->main_image ? getImage(getFilePath('product') . '/' . $product->main_image) : null;
                                            @endphp
                                            <img id="previewImage" src="{{ $mainImagePath ?? 'https://via.placeholder.com/400x400?text=No+Image' }}"
                                                class="w-full h-full object-cover {{ $mainImagePath ? '' : 'opacity-20 grayscale' }}">
                                        </div>

                                        <!-- Card Body -->
                                        <div>
                                            <h4 id="previewTitle" class="overflow-hidden text-[#272343] overflow-ellipsis whitespace-nowrap font-[Inter] text-[16px] font-normal leading-[130%] capitalize">
                                                {{ old('name', @$product->name) ?: __('Product Name') }}
                                            </h4>

                                            <div class="flex items-center justify-between">
                                                <div class="flex-1 min-w-0">
                                                    <span id="previewPrice" class="text-[#CC0001] text-[18px] font-semibold leading-[110%]">
                                                        @if(old('base_price', @$product->base_price))
                                                        {{ showAmount($product->base_price, currencyFormat: false) }} {{ gs('cur_sym') }}
                                                        @else
                                                        0.00 {{ gs('cur_sym') }}
                                                        @endif
                                                    </span>
                                                    <div class="flex items-center gap-1 mt-1">
                                                        <svg class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"></path>
                                                            <circle cx="12" cy="10" r="3"></circle>
                                                        </svg>
                                                        <p class="text-[10px] text-gray-400 truncate">@lang('Location & Time')</p>
                                                    </div>
                                                </div>

                                                <button type="button" class="w-10 h-10 rounded-xl bg-[#F7F7F7] flex items-center justify-center text-[#272343] hover:bg-[#FF6F0F] hover:text-white transition-all">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </aside>
                        </div>
                    </div>
                </div>
            </div>
    </main>
</div>
@endsection

@push('breadcrumb-plugins')
<x-back route="{{ route('seller.products.all') }}" />
@endpush

@push('script-lib')
<script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
<script src="https://cdn.tiny.cloud/1/az09l5hhv4r2bolg5fnhgy1vju0dri2amq12cvtmovqeeb52/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
@endpush

@push('style-lib')
<link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('style')
<style>
    /* Select2 Styling */
    .select2-container--default .select2-selection--multiple {
        min-height: 48px;
        border-color: #E6E6E6;
        border-radius: 8px;
        padding: 4px 12px;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
    }

    @media (min-width: 768px) {
        .select2-container--default .select2-selection--multiple {
            min-height: 52px;
        }
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple,
    .select2-container--default .select2-selection--multiple:focus {
        border-color: #FF6F0F;
        box-shadow: none;
    }

    .select2-selection__rendered {
        display: flex !important;
        flex-wrap: wrap;
        gap: 6px;
        width: 100%;
        padding: 0 !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #FF6F0F !important;
        border: none !important;
        color: #fff !important;
        padding: 0px 10px !important;
        border-radius: 4px !important;
        margin: 0 !important;
        display: inline-flex !important;
        align-items: center;
        gap: 4px;
        font-size: 12px !important;
        font-weight: 500 !important;
        line-height: 24px !important;
        height: 26px !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff !important;
        margin: 0 !important;
        padding: 0 !important;
        font-size: 14px !important;
        font-weight: bold !important;
        border: none !important;
        background: transparent !important;
        line-height: 1 !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        background: transparent !important;
        opacity: 0.8;
    }

    .select2-container--default .select2-search--inline {
        display: flex;
        align-items: center;
        margin: 0 !important;
    }

    .select2-container--default .select2-search--inline .select2-search__field {
        margin: 0 !important;
        height: 32px !important;
        line-height: 32px !important;
        font-family: inherit !important;
        font-size: 15px !important;
        color: #666 !important;
    }

    .select2-container--default .select2-selection--single {
        height: 48px;
        border-color: #E6E6E6;
        border-radius: 8px;
    }

    @media (min-width: 768px) {
        .select2-container--default .select2-selection--single {
            height: 52px;
        }
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 48px;
        padding-left: 16px !important;
        color: #666;
        font-size: 16px;
    }

    @media (min-width: 768px) {
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 52px;
        }
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 48px;
        margin-right: 10px !important;
    }

    @media (min-width: 768px) {
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 52px;
        }
    }

    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default .select2-selection--single:focus {
        border-color: #FF6F0F;
    }

    /* Input & Textarea Focus */
    input:focus,
    textarea:focus {
        outline: none !important;
        box-shadow: none !important;
    }

    .price-input-group:focus-within {
        box-shadow: none !important;
    }

    /* Validation Error Styling */
    .error-message {
        color: #dc2626;
        font-size: 0.875rem;
        display: block;
        animation: fadeIn 0.2s ease-out;
    }

    input.error,
    select.error,
    textarea.error {
        border-color: #ffa9a9 !important;
        background-color: #fef2f2 !important;
    }

    input.error:focus,
    select.error:focus,
    textarea.error:focus {
        box-shadow: 0 0 0 1px #dc2626 !important;
    }

    .select2-container--default.error .select2-selection {
        border-color: #dc2626 !important;
        background-color: #fef2f2 !important;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-4px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@push('script')
<script>
    'use strict';
    (function($) {
        $('.select2-auto-tokenize-seller').select2({
            tags: true,
            tokenSeparators: [',', ' '],
            placeholder: '@lang("Type and press Enter...")',
            dropdownParent: $('.select2-auto-tokenize-seller').parent('.select2-parent')
        });

        // Categories Select2
        var categories = @json(old('categories') ?? (isset($product) && $product->categories ? $product->categories->pluck('id') : []));

        let categoriesSelect = $('.category-select2');
        categoriesSelect.val(categories).select2({
            dropdownParent: categoriesSelect.parent('.select2-parent')
        });

        // Main Image Upload
        const mainImageBtn = document.getElementById('mainImageBtn');
        const mainImageInput = document.getElementById('mainImageInput');
        const mainImageBox = document.getElementById('mainImageBox');

        function showMainImagePreview(file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const oldPreview = mainImageBox.querySelector('.group');
                if (oldPreview) oldPreview.remove();

                const previewWrapper = document.createElement('div');
                previewWrapper.className = 'absolute inset-0 group';
                previewWrapper.innerHTML = `
                    <img id="mainImagePreview" src="${event.target.result}" class="w-full h-full object-cover rounded-xl">
                    <button type="button" class="remove-btn absolute top-2 right-2 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;
                mainImageBox.appendChild(previewWrapper);
                mainImageBtn.classList.add('hidden');

                // Update Preview Card
                const previewImg = document.getElementById('previewImage');
                if (previewImg) {
                    previewImg.src = event.target.result;
                    previewImg.classList.remove('opacity-20', 'grayscale');
                }

                previewWrapper.querySelector('.remove-btn').addEventListener('click', function(e) {
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

            // Reset Preview Card Image
            const previewImg = document.getElementById('previewImage');
            if (previewImg) {
                previewImg.src = 'https://via.placeholder.com/400x400?text=No+Image';
                previewImg.classList.add('opacity-20', 'grayscale');
            }
        }

        if (mainImageBtn && mainImageInput) {
            mainImageBtn.addEventListener('click', function() {
                mainImageInput.click();
            });

            mainImageInput.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    showMainImagePreview(e.target.files[0]);
                    $(this).valid();
                }
            });
        }

        mainImageBox.addEventListener('click', function(e) {
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
            additionalImagesBtn.addEventListener('click', function() {
                additionalImagesInput.click();
            });

            additionalImagesInput.addEventListener('change', function(e) {
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
                reader.onload = function(event) {
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
            const form = document.getElementById('addForm');
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
            additionalImagesPreview.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-btn')) {
                    const index = parseInt(e.target.dataset.index);
                    additionalFiles.splice(index, 1);
                    renderAdditionalPreviews();
                    updateAdditionalHiddenInput();
                }
            });
        }

        $(document).on('click', '.remove-existing-btn', function() {
            $(this).closest('.existing-image-item').remove();
        });

        // TinyMCE
        tinymce.init({
            selector: '#description',
            plugins: 'link image lists table',
            toolbar: 'bold italic underline | link image | bullist numlist | alignleft aligncenter alignright',
            menubar: false,
            height: 300,
            border_width: 1,
            statusbar: false
        });


        // jQuery Validation
        $('#addForm').validate({
            ignore: [],
            rules: {
                name: {
                    required: true,
                    maxlength: 100
                },
                base_price: {
                    required: true,
                    number: true,
                    min: 0
                },
                'categories[]': {
                    required: true
                },
                sku: {
                    maxlength: 50
                },
                main_image: {
                    required: function() {
                        return $('#mainImagePreview').length == 0;
                    },
                    extension: "jpg|jpeg|png"
                }
            },
            messages: {
                name: {
                    required: "@lang('Please enter product name')",
                    maxlength: "@lang('Product name cannot exceed 100 characters')"
                },
                base_price: {
                    required: "@lang('Please enter product price')",
                    number: "@lang('Please enter a valid number')",
                    min: "@lang('Product price cannot be less than 0')"
                },
                'categories[]': {
                    required: "@lang('Please select at least one category')"
                },
                sku: {
                    maxlength: "@lang('SKU code cannot exceed 50 characters')"
                },
                main_image: {
                    required: "@lang('Please select a cover image for the product')",
                    extension: "@lang('Only image files (png, jpg, jpeg) are allowed')"
                }
            },
            errorElement: 'span',
            errorClass: 'error-message',
            errorPlacement: function(error, element) {
                if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.closest('.select2-parent'));
                } else if (element.attr('name') == 'main_image') {
                    error.insertAfter(element.closest('#mainImageBox'));
                } else if (element.attr('name') == 'base_price') {
                    error.insertAfter(element.closest('.price-input-group'));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('error');
                if ($(element).hasClass('select2-hidden-accessible')) {
                    $(element).next('.select2-container').addClass('error');
                }
                if ($(element).attr('name') == 'main_image') {
                    $('#mainImageBox').addClass('border-red-500 bg-red-50').removeClass('border-[#D4D4D4] bg-[rgba(0,0,0,0.08)]');
                }
                if ($(element).attr('name') == 'base_price') {
                    $(element) .closest('.price-input-group').addClass('border-red-500 bg-red-50');
                }
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('error');
                if ($(element).hasClass('select2-hidden-accessible')) {
                    $(element).next('.select2-container').removeClass('error');
                }
                if ($(element).attr('name') == 'main_image') {
                    $('#mainImageBox').removeClass('border-red-500 bg-red-50').addClass('border-[#D4D4D4] bg-[rgba(0,0,0,0.08)]');
                }
                if ($(element).attr('name') == 'base_price') {
                    $(element).closest('.price-input-group').removeClass('border-red-500 bg-red-50');
                }
            },
            submitHandler: function(form) {
                if (typeof tinymce !== 'undefined') {
                    tinymce.triggerSave();
                }
                form.submit();
            }
        });

        // Real-time Preview Sync
        const curSym = "{{ gs('cur_sym') }}";

        $('input[name=name]').on('input', function() {
            const val = $(this).val();
            $('#previewTitle').text(val.length > 0 ? val : '@lang("Product Name")');
        });

        $('input[name=base_price]').on('input', function() {
            let val = $(this).val();
            if (val) {
                val = parseFloat(val).toLocaleString('en-US', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                });
                $('#previewPrice').text(val + ' ' + curSym);
            } else {
                $('#previewPrice').text('0.00 ' + curSym);
            }
        });

        var cnyExchangeRate = {{ $productConfig->cny_exchange_rate }};
        
        $('#currency_type').on('change', function() {
            if($(this).val() == '2') {
                $('.cny-price-wrapper').removeClass('hidden').addClass('flex');
                $('.cny-discount-price-wrapper').removeClass('hidden').addClass('flex');
                $('#base_price, #discount_price').attr('readonly', true).addClass('bg-gray-100');
            } else {
                $('.cny-price-wrapper').addClass('hidden').removeClass('flex');
                $('.cny-discount-price-wrapper').addClass('hidden').removeClass('flex');
                $('#base_price, #discount_price').attr('readonly', false).removeClass('bg-gray-100');
            }
        }).change();

        $('#cny_price').on('input', function() {
            var cny = parseFloat($(this).val()) || 0;
            var vnd = Math.round(cny * cnyExchangeRate);
            $('#base_price').val(vnd).trigger('input');
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


    })(jQuery);
</script>
@endpush