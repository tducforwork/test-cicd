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
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold text-[#272343] mb-6">@lang('Shop Settings')</h1>

                <form action="{{ route('seller.shop') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-6" id="shopForm">
                    @csrf

                    <!-- Shop Images -->
                    <div class="bg-white rounded-[12px] p-6 border border-gray-100">
                        <h2 class="text-xl font-bold text-[#272343] mb-6">@lang('Shop Images')</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">@lang('Shop Logo')</label>
                                <div class="relative group">
                                    <div id="logoPreviewWrapper" class="w-full h-[200px] rounded-[8px] border border-[#E6E6E6] bg-gray-100 flex items-center justify-center overflow-hidden">
                                        @if(@$shop->logo)
                                        <img id="logoPreview" src="{{ getImage(getFilePath('sellerShopLogo') . '/' . $shop->logo, true) }}" alt="Logo"
                                            class="w-full h-full object-cover">
                                        @else
                                        <div id="logoPreview" class="flex flex-col items-center justify-center gap-2 text-gray-400">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="text-xs font-medium">@lang('No logo yet')</span>
                                        </div>
                                        @endif
                                    </div>
                                    <input type="file" name="image" class="hidden" id="logoInput" accept=".png,.jpg,.jpeg">
                                </div>
                                <button type="button" onclick="document.getElementById('logoInput').click()"
                                    class="mt-3 bg-[#FF6F0F] text-white px-5 py-2.5 rounded-[12px] font-bold text-sm shadow-[inset_0_1px_2px_0_rgba(255,255,255,0.40),inset_0_-1px_2px_0_rgba(0,0,0,0.24),0_1px_2px_0_rgba(0,0,0,0.08)] hover:bg-orange-600 transition-colors flex items-center gap-2 w-fit">
                                    @lang('Upload Logo')
                                </button>
                                <small class="text-gray-500 text-sm mt-2 block">@lang('Supported formats'): <b>.png, .jpeg, .jpg</b>. @lang('Max 2MB')</small>
                            </div>

                            <div>
                                <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">@lang('Cover Image')</label>
                                <div class="relative group">
                                    <div id="coverPreviewWrapper" class="w-full h-[200px] rounded-[8px] border border-[#E6E6E6] bg-gray-100 flex items-center justify-center overflow-hidden">
                                        @if(@$shop->cover)
                                        <img id="coverPreview" src="{{ getImage(getFilePath('sellerShopCover') . '/' . $shop->cover, true) }}" alt="Cover"
                                            class="w-full h-full object-cover">
                                        @else
                                        <div id="coverPreview" class="flex flex-col items-center justify-center gap-2 text-gray-400">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="text-xs font-medium">@lang('No cover image yet')</span>
                                        </div>
                                        @endif
                                    </div>
                                    <input type="file" name="cover_image" class="hidden" id="coverInput" accept=".png,.jpg,.jpeg">
                                </div>
                                <button type="button" onclick="document.getElementById('coverInput').click()"
                                    class="mt-3 bg-[#FF6F0F] text-white px-5 py-2.5 rounded-[12px] font-bold text-sm shadow-[inset_0_1px_2px_0_rgba(255,255,255,0.40),inset_0_-1px_2px_0_rgba(0,0,0,0.24),0_1px_2px_0_rgba(0,0,0,0.08)] hover:bg-orange-600 transition-colors flex items-center gap-2 w-fit">
                                    @lang('Upload Cover')
                                </button>
                                <small class="text-gray-500 text-sm mt-2 block">Dịnh dạng hỗ trợ: <b>.png, .jpeg, .jpg</b>. Tối đa 2MB</small>
                            </div>
                        </div>
                    </div>

                    <!-- Basic Information -->
                    <div class="bg-white rounded-[12px] p-6 border border-gray-100">
                        <h2 class="text-xl font-bold text-[#272343] mb-6">@lang('Basic Information')</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">@lang('Shop Name') <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', @$shop->name) }}" required
                                    class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none"
                                    placeholder="@lang('Enter shop name')">
                            </div>

                            <div>
                                <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">@lang('Phone Number') <span class="text-red-500">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone', @$shop->phone) }}" required
                                    class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none"
                                    placeholder="@lang('Enter shop phone number')">
                            </div>

                            <div>
                                <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">@lang('Opening Time')</label>
                                <input type="time" name="opening_time" value="{{ old('opening_time', showDateTime(@$shop->opens_at, 'H:i')) }}"
                                    class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none">
                            </div>

                            <div>
                                <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">@lang('Closing Time')</label>
                                <input type="time" name="closing_time" value="{{ old('closing_time', showDateTime(@$shop->closed_at, 'H:i')) }}"
                                    class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none">
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">@lang('Shop Address') <span class="text-red-500">*</span></label>
                                <input type="text" name="address" value="{{ old('address', @$shop->address) }}" required
                                    class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none"
                                    placeholder="@lang('Enter shop address')">
                            </div>
                        </div>
                    </div>

                    <!-- SEO Contents -->
                    <div class="bg-white rounded-[12px] p-6 border border-gray-100">
                        <h2 class="text-xl font-bold text-[#272343] mb-6">@lang('SEO Contents')</h2>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">@lang('Meta Title')</label>
                                <input type="text" name="meta_title" value="{{ old('meta_title', @$shop->meta_title) }}"
                                    class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none"
                                    placeholder="@lang('Enter meta title')">
                            </div>

                            <div>
                                <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">@lang('Meta Description')</label>
                                <textarea name="meta_description" rows="4"
                                    class="w-full px-4 py-3 rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none resize-none"
                                    placeholder="@lang('Enter meta description')">{{ old('meta_description', @$shop->meta_description) }}</textarea>
                            </div>

                            <div>
                                <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">@lang('Meta Keywords')</label>
                                @php
                                if (old('meta_keywords')) {
                                $metaKeywords = old('meta_keywords');
                                } elseif ($shop && $shop->meta_keywords) {
                                $metaKeywords = $shop->meta_keywords;
                                } else {
                                $metaKeywords = null;
                                }
                                @endphp
                                <select name="meta_keywords[]" class="w-full h-[52px] select2-auto-tokenize" multiple="multiple">
                                    @if ($metaKeywords)
                                    @foreach ($metaKeywords as $option)
                                    <option value="{{ $option }}" selected>{{ __($option) }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <small class="text-gray-500 text-sm mt-2 block"><i class="las la-info-circle"></i> @lang('Enter comma (,) or press Enter to separate keywords')</small>
                            </div>
                        </div>
                    </div>

                    <!-- Social Links -->
                    <!-- <div class="bg-white rounded-[12px] p-6 border border-gray-100">
        <h2 class="text-xl font-bold text-[#272343] mb-6">Liên kết mạng xã hội</h2>
        <div class="grid grid-cols-1 gap-6">
            @php
            if (old('social_links')) {
                $socialLinks = old('social_links');
            } elseif ($shop && $shop->social_links) {
                $socialLinks = $shop->social_links;
            } else {
                $socialLinks = null;
            }
            @endphp

            <div class="socials-wrapper">
                @if ($socialLinks)
                    @foreach ($socialLinks as $key => $item)
                        <div class="socials grid grid-cols-1 md:grid-cols-12 gap-3 mb-3 items-end">
                            <div class="md:col-span-3">
                                <label class="text-[#272343] text-[14px] font-normal leading-[150%] mb-[4px] block">Tên nền tảng</label>
                                <input type="text" class="w-full h-[48px] px-4 rounded-[8px] border text-[#666] text-[16px] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none"
                                    name="social_links[{{ $key }}][name]" value="{{ $item['name'] }}" placeholder="VD: Facebook, Instagram" required>
                            </div>
                            <div class="md:col-span-4">
                                <label class="text-[#272343] text-[14px] font-normal leading-[150%] mb-[4px] block">Mã Icon</label>
                                <div class="relative">
                                    <input type="text" class="iconPicker w-full h-[48px] px-4 rounded-[8px] border text-[#666] text-[16px] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none"
                                        name="social_links[{{ $key }}][icon]" value="{{ $item['icon'] }}" required>
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none icon-preview">
                                        <i class="{{ $item['icon'] }}"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="md:col-span-4">
                                <label class="text-[#272343] text-[14px] font-normal leading-[150%] mb-[4px] block">Đường dẫn liên kết</label>
                                <input type="text" class="w-full h-[48px] px-4 rounded-[8px] border text-[#666] text-[16px] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none"
                                    name="social_links[{{ $key }}][link]" value="{{ $item['link'] }}" placeholder="https://..." required>
                            </div>
                            <div class="md:col-span-1">
                                <button type="button" class="btn btn-outline--danger remove-social w-full h-[48px] flex items-center justify-center"><i class="la la-minus"></i></button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="flex justify-start">
                <button type="button" class="bg-[#272343] text-[#FFF] hover:opacity-90 transition-opacity text-[14px] font-semibold leading-[20px] rounded-[10px] border-[1px] border-[solid] border-[#616161] flex px-[16px] py-[8px] justify-center items-center gap-[6px] add-social">
                    <i class="la la-plus"></i> Thêm liên kết
                </button>
            </div>
        </div>
    </div> -->

                    <!-- Submit Button -->
                    <button type="submit"
                        class="text-[#FFF] hover:opacity-90 transition-opacity text-[16px] font-semibold leading-[24px] rounded-[12px] border-[1px] border-[solid] border-[#616161] bg-[#272343] [box-shadow:0_1px_2px_0_rgba(255,_255,_255,_0.40)_inset,_0_-1px_2px_0_rgba(0,_0,_0,_0.24)_inset,_0_1px_2px_0_rgba(0,_0,_0,_0.08)] flex px-[24px] py-[12px] justify-center items-center gap-[8px] w-full md:w-auto">
                        @lang('Save Changes')
                    </button>
                </form>

            </div>{{-- end main content --}}
        </div>{{-- end flex row --}}
    </main>
</div>
@endsection

@push('style-lib')
<link href="{{ asset('assets/admin/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('style')
<style>
    .select2-container--default .select2-selection--multiple {
        min-height: 52px;
        border-color: #E6E6E6;
        border-radius: 8px;
        padding: 4px 12px;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 4px;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple,
    .select2-container--default .select2-selection--multiple:focus {
        border-color: #FF6F0F;
    }

    .select2-selection__rendered {
        display: flex !important;
        flex-wrap: wrap;
        gap: 4px;
        width: 100%;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #FF6F0F !important;
        border: none !important;
        color: #fff !important;
        padding: 0px 8px !important;
        border-radius: 4px !important;
        margin: 0 !important;
        display: inline-flex !important;
        align-items: center;
        gap: 4px;
        font-size: 12px !important;
        font-weight: 500 !important;
        line-height: 22px !important;
        height: 24px !important;
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
    }

    .iconPicker {
        padding-left: 40px !important;
    }

    label.error {
        color: #ef4444;
        font-size: 14px;
        margin-top: 4px;
        display: block;
    }

    input.error,
    textarea.error {
        border-color: #ef4444 !important;
    }
</style>
@endpush

@push('script-lib')
<script src="{{ asset('assets/admin/js/fontawesome-iconpicker.js') }}"></script>
<script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
@endpush

@push('script')
<script>
    (function($) {
        'use strict';

        // Image preview
        function handleImagePreview(input, wrapperId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var $wrapper = $('#' + wrapperId);
                    $wrapper.html('<img src="' + e.target.result + '" alt="Preview" class="w-full h-full object-cover">');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $('#logoInput').on('change', function() {
            handleImagePreview(this, 'logoPreviewWrapper');
        });
        $('#coverInput').on('change', function() {
            handleImagePreview(this, 'coverPreviewWrapper');
        });

        // Initialize Select2
        $('.select2-auto-tokenize').select2({
            tags: true,
            tokenSeparators: [',', ' '],
            placeholder: "@lang('Type and hit Enter')..."
        });

        // Initialize Validation
        $('#shopForm').validate({
            rules: {
                name: "required",
                phone: "required",
                address: "required"
            },
            messages: {
                name: "@lang('Please enter shop name')",
                phone: "@lang('Please enter phone number')",
                address: "@lang('Please enter shop address')"
            },
            errorElement: 'label',
            errorPlacement: function(error, element) {
                if (element.parent('.relative').length) {
                    error.insertAfter(element.parent('.relative'));
                } else {
                    error.insertAfter(element);
                }
            }
        });

        // Icon Picker Initialization
        function initIconPicker(element) {
            $(element).iconpicker({
                placement: 'bottom',
                iconset: 'fontawesome',
                rows: 5,
                cols: 10
            }).on('iconpickerSelected', function(e) {
                var container = $(this).closest('.relative');
                container.find('.icon-preview').remove();
                $(this).after(`<span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none icon-preview"><i class="${e.iconpickerValue}"></i></span>`);
            });
        }

        initIconPicker('.iconPicker');

        // Add Social Link
        $('.add-social').on('click', function() {
            var socials = $('.socials');
            var length = socials.length;

            var content = `
                <div class="socials grid grid-cols-1 md:grid-cols-12 gap-3 mb-3 items-end">
                    <div class="md:col-span-3">
                        <label class="text-[#272343] text-[14px] font-normal leading-[150%] mb-[4px] block">Tên nền tảng</label>
                        <input type="text" class="w-full h-[48px] px-4 rounded-[8px] border text-[#666] text-[16px] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none"
                            name="social_links[${length}][name]" placeholder="VD: Facebook, Instagram" required>
                    </div>
                    <div class="md:col-span-4">
                        <label class="text-[#272343] text-[14px] font-normal leading-[150%] mb-[4px] block">Mã Icon</label>
                        <div class="relative">
                            <input type="text" class="iconPicker w-full h-[48px] px-4 rounded-[8px] border text-[#666] text-[16px] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none"
                                name="social_links[${length}][icon]" required>
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none icon-preview"><i class="las la-globe"></i></span>
                        </div>
                    </div>
                    <div class="md:col-span-4">
                        <label class="text-[#272343] text-[14px] font-normal leading-[150%] mb-[4px] block">Đường dẫn liên kết</label>
                        <input type="text" class="w-full h-[48px] px-4 rounded-[8px] border text-[#666] text-[16px] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none"
                            name="social_links[${length}][link]" placeholder="https://..." required>
                    </div>
                    <div class="md:col-span-1">
                        <button type="button" class="btn btn-outline--danger remove-social w-full h-[48px] flex items-center justify-center"><i class="la la-minus"></i></button>
                    </div>
                </div>
            `;

            $(content).appendTo('.socials-wrapper').hide().slideDown('slow', function() {
                initIconPicker($(this).find('.iconPicker'));
            });
        });

        // Remove Social Link
        $(document).on('click', '.remove-social', function() {
            $(this).closest('.socials').slideUp('slow', function() {
                $(this).remove();
            });
        });

        // AJAX Form Submission
        $('#shopForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);

            if (!form.valid()) return false;

            var btn = form.find('button[type=submit]');
            var btnText = btn.text();
            var formData = new FormData(this);

            btn.prop('disabled', true).html('<i class="las la-spinner la-spin"></i> @lang('Saving')...');

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status == 'success') {
                        notify('success', response.message);
                    } else if (response.status == 'error') {
                        notify('error', response.message);
                    } else {
                        // Fallback for non-json responses that might be handled by middleware
                        notify('success', 'Cập nhật cửa hàng thành công');
                    }
                },
                error: function(xhr) {
                    var response = xhr.responseJSON;
                    if (xhr.status == 422 && response && response.errors) {
                        $.each(response.errors, function(key, value) {
                            notify('error', value[0]);
                        });
                    } else if (response && response.message) {
                        notify('error', response.message);
                    } else {
                        notify('error', '@lang('Something went wrong. Please try again later').');
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).text(btnText.trim());
                }
            });
        });

    })(jQuery);
</script>
@endpush