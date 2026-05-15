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
                    <div class="max-w-[1320px] mx-auto">
                        <h1 class="text-2xl font-semibold text-[#272343] mb-6">{{ __($pageTitle) }}</h1>

                        <form id="jobForm" action="{{ route('seller.jobs.store', @$job->id) }}" method="POST"
                            enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <input type="hidden" name="job_type" value="{{ request()->type ?? @$job->job_type ?? 1 }}">

                            <!-- I. Hồ sơ & Công việc mong muốn -->
                            <div class="bg-white rounded-[12px] p-6 border border-gray-100">
                                <h2 class="text-xl font-bold text-[#272343] mb-6">@lang('I. Hồ sơ & Công việc mong muốn')</h2>

                                <div class="space-y-6">
                                    <!-- Tiêu đề -->
                                    <div>
                                        <label class="text-[16px] font-normal text-[#272343] leading-relaxed mb-2 block">
                                            @lang('Vị trí mong muốn / Tiêu đề hồ sơ') <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="title"
                                            class="w-full h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[#272343] text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange"
                                            placeholder="@lang('Ví dụ: Nhân viên kinh doanh BĐS / Kế toán tổng hợp')"
                                            value="{{ old('title', @$job->title) }}" required>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Ngành nghề -->
                                        <div>
                                            <label
                                                class="text-[16px] font-normal text-[#272343] leading-relaxed mb-2 block">
                                                @lang('Ngành nghề mong muốn') <span class="text-danger">*</span>
                                            </label>
                                            <select name="industry_id" class="select2 rounded-[6px] h-[49px]" required>
                                                <option value="">@lang('Chọn ngành nghề')</option>
                                                @foreach ($industries as $ind)
                                                    <option value="{{ $ind->id }}" @selected(old('industry_id', @$job->industry_id) == $ind->id)>
                                                        {{ __($ind->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- Cấp bậc -->
                                        <div>
                                            <label
                                                class="text-[16px] font-normal text-[#272343] leading-relaxed mb-2 block">
                                                @lang('Cấp bậc hiện tại / mong muốn') <span class="text-danger">*</span>
                                            </label>
                                            <select name="job_level_id" class="select2 rounded-[6px] h-[49px]" required>
                                                <option value="">@lang('Chọn cấp bậc')</option>
                                                @foreach ($levels as $level)
                                                    <option value="{{ $level->id }}" @selected(old('job_level_id', @$job->job_level_id) == $level->id)>
                                                        {{ __($level->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Mô tả công việc -->
                                    <div>
                                        <label class="text-[16px] font-normal text-[#272343] leading-relaxed mb-2 block">
                                            @lang('Giới thiệu bản thân & Kinh nghiệm') <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="description" id="description" rows="10"
                                            class="w-full px-4 py-3 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange">{{ old('description', @$job->description) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- II. Thông tin cá nhân & Địa điểm -->
                            <div class="bg-white rounded-[12px] p-6 border border-gray-100">
                                <h2 class="text-xl font-bold text-[#272343] mb-6">@lang('II. Thông tin cá nhân & Địa điểm')</h2>

                                <div class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Tên cá nhân -->
                                        <div>
                                            <label
                                                class="text-[16px] font-normal text-[#272343] leading-relaxed mb-2 block">
                                                @lang('Họ và tên hồ sơ') <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="company_name"
                                                class="w-full h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[#272343] text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange"
                                                placeholder="@lang('Nhập họ tên đầy đủ')"
                                                value="{{ old('company_name', @$job->company_name) }}" required>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <!-- Email -->
                                            <div>
                                                <label class="text-[16px] font-normal text-[#272343] leading-relaxed mb-2 block">
                                                    @lang('Email liên hệ')
                                                </label>
                                                <input type="email" name="email"
                                                    class="w-full h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[#272343] text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange"
                                                    placeholder="@lang('Nhập email liên hệ')"
                                                    value="{{ old('email', @$job->email) }}">
                                            </div>

                                            <!-- Số điện thoại -->
                                            <div>
                                                <label class="text-[16px] font-normal text-[#272343] leading-relaxed mb-2 block">
                                                    @lang('Số điện thoại')
                                                </label>
                                                <input type="text" name="phone"
                                                    class="w-full h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[#272343] text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange"
                                                    placeholder="@lang('Nhập số điện thoại')"
                                                    value="{{ old('phone', @$job->phone) }}">
                                            </div>
                                        </div>

                                        <!-- Ảnh chân dung -->
                                        <div>
                                            <label
                                                class="text-[16px] font-normal text-[#272343] leading-relaxed mb-2 block">@lang('Ảnh chân dung / Đại diện')</label>
                                            <label for="logoInput"
                                                class="relative group w-32 h-32 flex items-center justify-center p-2 bg-[#00000014] rounded-xl border border-dashed border-neutral-300 cursor-pointer hover:border-[#ff6f0f] transition-colors">
                                                @php
                                                    $logoPath = @$job->company_logo ? getImage(getFilePath('job') . '/' . $job->company_logo) : null;
                                                @endphp
                                                <span id="logoBtn"
                                                    class="inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg transition-colors {{ $logoPath ? 'hidden' : '' }}">
                                                    <i class="la text-[#fff] text-2xl md:text-5xl la-cloud-upload-alt"></i>
                                                </span>
                                                <input type="file" name="company_logo" id="logoInput" class="hidden"
                                                    accept=".png,.jpg,.jpeg">

                                                @if($logoPath)
                                                    <div class="inline-block absolute inset-0">
                                                        <img id="logoPreview" src="{{ $logoPath }}"
                                                            class="w-full h-full object-cover rounded-xl border border-[#E6E6E6]">
                                                        <button type="button" id="logoRemoveBtn"
                                                            class="absolute bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center hover:bg-red-600 transition-colors z-10 opacity-0 logo-remove-btn"
                                                            style="top: -10px; right: -10px;">
                                                            <i class="la la-times text-xs"></i>
                                                        </button>
                                                    </div>
                                                @endif
                                            </label>
                                        </div>
                                        <!-- Hình ảnh mô tả (Nhiều ảnh) -->
                                        <div class="md:col-span-2">
                                            <label class="text-[16px] font-normal text-[#272343] leading-relaxed mb-2 block">@lang('Hình ảnh bằng cấp / Chứng chỉ (Nhiều ảnh)')</label>
                                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4"
                                                id="multiImageContainer">
                                                <label for="multiImageInput"
                                                    class="aspect-square flex flex-col items-center justify-center p-2 bg-[#00000014] rounded-xl border border-dashed border-neutral-300 cursor-pointer hover:border-[#ff6f0f] transition-colors">
                                                    <i class="la la-plus text-2xl text-gray-400"></i>
                                                    <span class="text-xs text-gray-400 mt-1">@lang('Thêm ảnh')</span>
                                                    <input type="file" name="images[]" id="multiImageInput" class="hidden"
                                                        accept=".png,.jpg,.jpeg" multiple>
                                                </label>

                                                @foreach(@$job->images ?? [] as $img)
                                                    <div class="relative aspect-square">
                                                        <img src="{{ getImage(getFilePath('job') . '/' . $img->image) }}"
                                                            class="w-full h-full object-cover rounded-xl border border-[#E6E6E6]">
                                                        <button type="button"
                                                            class="absolute -top-2 -right-2 bg-red-500 text-white w-5 h-5 rounded-full flex items-center justify-center hover:bg-red-600 transition-colors">
                                                            <i class="la la-times text-[10px]"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Tỉnh/Thành -->
                                        <div>
                                            <label
                                                class="text-[16px] font-normal text-[#272343] leading-relaxed mb-2 block">@lang('Tỉnh/Thành phố')</label>
                                            <select name="province_id" class="select2 rounded-[6px] h-[49px]" required>
                                                <option value="">@lang('Chọn Tỉnh/Thành')</option>
                                                @foreach ($provinces as $province)
                                                    <option value="{{ $province->id }}" @selected(old('province_id', @$job->province_id) == $province->id)>
                                                        {{ $province->full_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- Quận/Huyện -->
                                        <div>
                                            <label
                                                class="text-[16px] font-normal text-[#272343] leading-relaxed mb-2 block">@lang('Quận/Huyện')</label>
                                            <select name="ward_id" class="select2 rounded-[6px] h-[49px]"
                                                data-current="{{ @$job->ward_id }}" required>
                                                <option value="">@lang('Chọn Quận/Huyện')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="text-[16px] font-normal text-[#272343] leading-relaxed mb-2 block">@lang('Địa chỉ chi tiết')</label>
                                        <input type="text" name="work_location"
                                            class="w-full h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[#272343] text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange"
                                            placeholder="@lang('Số nhà, tên đường...')"
                                            value="{{ old('work_location', @$job->work_location) }}">
                                    </div>
                                </div>
                            </div>
                            <!-- III. Hạn nộp -->
                            <div class="bg-white rounded-[12px] p-6 border border-gray-100">
                                <h2 class="text-xl font-bold text-[#272343] mb-6">@lang('III. Hạn nộp hồ sơ')</h2>

                                <div class="space-y-6">
                                    <div class="hidden">
                                        <select name="salary_type" class="select2 rounded-[6px] h-[49px]" id="salaryType">
                                            <option value="negotiable" selected>@lang('Thỏa thuận')</option>
                                        </select>
                                    </div>


                                    <div>
                                        <label
                                            class="text-[16px] font-normal text-[#272343] leading-relaxed mb-2 block">@lang('Hạn nộp hồ sơ')</label>
                                        <input type="date" name="application_deadline"
                                            class="w-full h-[49px] px-4 rounded-[6px] border text-[#272343] border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange"
                                            value="{{ old('application_deadline', @$job->application_deadline?->format('Y-m-d')) }}">
                                    </div>

                                    @if(request()->type == 2 || @$job->job_type == 2)
                                        <div class="cv-upload-section">
                                            <label
                                                class="text-[16px] font-normal text-[#272343] leading-relaxed mb-2 block">@lang('Tải hồ sơ năng lực / CV')</label>
                                            <input type="file" name="cv_file"
                                                class="w-full px-4 py-2 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-kviet-orange">
                                            @if(@$job->cv_file)
                                                <div class="mt-2 text-sm text-gray-500">
                                                    <i class="la la-file"></i> <a
                                                        href="{{ asset(getFilePath('job_cv') . '/' . $job->cv_file) }}"
                                                        target="_blank" class="text-kviet-orange">@lang('Xem CV hiện tại')</a>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <a href="{{ route('seller.jobs.index') }}"
                                        class="inline-flex items-center justify-center gap-2 w-full px-8 py-3 bg-white border border-[#E6E6E6] rounded-[12px] text-sm font-bold text-[#272343] hover:bg-gray-50 transition-colors">
                                        <i class="la la-arrow-left"></i> @lang('Quay lại')
                                    </a>

                                    <button type="submit"
                                        class="w-full bg-[#272343] text-white px-8 py-3 rounded-[12px] font-bold text-sm shadow-sm hover:opacity-90 transition-opacity">
                                        <i class="la la-save"></i>
                                        @if(request()->type == 2 || @$job->job_type == 2)
                                            {{ @$job->id ? __('Cập nhật hồ sơ') : __('Đăng tìm việc') }}
                                        @else
                                            {{ @$job->id ? __('Cập nhật tin') : __('Đăng tuyển dụng') }}
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>
    </main>
    </div>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('style')
    <style>
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
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 47px;
            margin-right: 10px !important;
            display: flex;
            align-items: center;
        }

        @media (max-width: 767px) {
            .select2-container--default .select2-selection--single {
                height: 48px;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 48px;
            }
        }
    </style>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
    <script src="https://cdn.tiny.cloud/1/az09l5hhv4r2bolg5fnhgy1vju0dri2amq12cvtmovqeeb52/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
@endpush

@push('script')
    <script>
        'use strict';
        (function ($) {
            // Initialize Select2
            $('.select2').select2().on('change', function () {
                $(this).valid();
            });

            // Custom validation method
            $.validator.addMethod("greaterThanEqual", function (value, element, param) {
                var target = $(param);
                if (this.settings.onfocusout && target.not(".validate-greaterThanEqual-blur").length) {
                    target.addClass("validate-greaterThanEqual-blur").on("blur.validate-greaterThanEqual", function () {
                        $(element).valid();
                    });
                }
                return value === "" || target.val() === "" || parseFloat(value) >= parseFloat(target.val());
            }, '@lang("Giá trị đến phải lớn hơn hoặc bằng giá trị từ")');

            // Salary Type Toggle
            var salaryType = $('#salaryType').val();
            if (salaryType == 'range') {
                $('.salary-input').removeClass('hidden');
            } else {
                $('.salary-input').addClass('hidden');
            }

            $('#salaryType').on('change', function () {
                if ($(this).val() == 'range') {
                    $('.salary-input').removeClass('hidden');
                } else {
                    $('.salary-input').addClass('hidden');
                }
            });

            // Province -> Ward Ajax
            $('select[name=province_id]').on('change', function () {
                var provinceId = $(this).val();
                var wardSelect = $('select[name=ward_id]');
                var currentWard = wardSelect.data('current');

                wardSelect.html('<option value="">@lang('Chọn Quận/Huyện')</option>');

                if (provinceId) {
                    var url = "{{ route('seller.jobs.get_wards') }}";
                    $.get(url, { province_id: provinceId }, function (data) {
                        $.each(data.wards, function (key, ward) {
                            var selected = (currentWard == ward.id) ? 'selected' : '';
                            wardSelect.append(`<option value="${ward.id}" ${selected}>${ward.name}</option>`);
                        });
                        wardSelect.trigger('change');
                    });
                }
            }).change();

            // Logo Image Preview Logic
            function showLogoPreview(file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    const logoLabel = logoInput.closest('label');
                    const oldPreview = logoLabel.querySelector('.absolute.inset-0');
                    if (oldPreview) oldPreview.remove();

                    const previewWrapper = document.createElement('div');
                    previewWrapper.className = 'inline-block absolute inset-0';
                    previewWrapper.innerHTML = `
                        <img id="logoPreview" src="${event.target.result}" class="w-full h-full object-cover rounded-xl border border-[#E6E6E6]">
                        <button type="button" id="logoRemoveBtn"
                            class="absolute bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center hover:bg-red-600 transition-colors z-10 opacity-0"
                            style="top: -10px; right: -10px;">
                            <i class="la la-times text-xs"></i>
                        </button>
                    `;
                    logoLabel.appendChild(previewWrapper);
                    logoBtn.classList.add('hidden');

                    const newRemoveBtn = previewWrapper.querySelector('#logoRemoveBtn');
                    previewWrapper.addEventListener('mouseenter', function () {
                        newRemoveBtn.classList.remove('opacity-0');
                    });
                    previewWrapper.addEventListener('mouseleave', function () {
                        newRemoveBtn.classList.add('opacity-0');
                    });
                    newRemoveBtn.addEventListener('click', function (e) {
                        e.stopPropagation();
                        removeLogo();
                    });
                };
                reader.readAsDataURL(file);
            }

            function removeLogo() {
                const logoLabel = logoInput.closest('label');
                const preview = logoLabel.querySelector('.absolute.inset-0');
                if (preview) preview.remove();
                logoInput.value = '';
                logoBtn.classList.remove('hidden');
            }

            const logoInput = document.getElementById('logoInput');
            const logoBtn = document.getElementById('logoBtn');

            if (logoInput) {
                logoInput.addEventListener('change', function (e) {
                    if (e.target.files && e.target.files[0]) {
                        showLogoPreview(e.target.files[0]);
                    }
                });
            }

            // Multi Image Preview
            const multiImageInput = document.getElementById('multiImageInput');
            const multiImageContainer = document.getElementById('multiImageContainer');

            if (multiImageInput) {
                multiImageInput.addEventListener('change', function (e) {
                    const files = e.target.files;
                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];
                        const reader = new FileReader();
                        reader.onload = function (event) {
                            const wrapper = document.createElement('div');
                            wrapper.className = 'relative aspect-square';
                            wrapper.innerHTML = `
                                <img src="${event.target.result}" class="w-full h-full object-cover rounded-xl border border-[#E6E6E6]">
                                <button type="button" class="absolute -top-2 -right-2 bg-red-500 text-white w-5 h-5 rounded-full flex items-center justify-center hover:bg-red-600 transition-colors remove-prev-img">
                                    <i class="la la-times text-[10px]"></i>
                                </button>
                            `;
                            multiImageContainer.insertBefore(wrapper, multiImageContainer.children[0]);

                            wrapper.querySelector('.remove-prev-img').onclick = function () {
                                wrapper.remove();
                            };
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // TinyMCE Initialization
            tinymce.init({
                selector: '#description',
                plugins: 'link image lists table',
                toolbar: 'bold italic underline | link image | bullist numlist | alignleft aligncenter alignright',
                menubar: false,
                height: 350,
                border_width: 1,
                statusbar: false,
                setup: function (editor) {
                    editor.on('change', function () {
                        editor.save();
                        $(editor.getElement()).valid();
                    });
                    editor.on('keyup', function () {
                        editor.save();
                        $(editor.getElement()).valid();
                    });
                }
            });

            // jQuery Validation
            $('#jobForm').validate({
                ignore: [],
                rules: {
                    title: { required: true, maxlength: 255 },
                    industry_id: { required: true },
                    job_level_id: { required: true },
                    description: {
                        required: true,
                        minlength: 10
                    },
                    company_name: { required: true, maxlength: 255 },
                    province_id: { required: true },
                    ward_id: { required: true },
                    salary_from: {
                        required: function () { return $('#salaryType').val() == 'range'; },
                        number: true,
                        min: 0
                    },
                    salary_to: {
                        required: function () { return $('#salaryType').val() == 'range'; },
                        number: true,
                        min: 0,
                        greaterThanEqual: '[name=salary_from]'
                    },
                    application_deadline: { required: false, date: true },
                    email: { email: true, maxlength: 255 },
                    phone: { maxlength: 40 },
                    company_logo: {
                        required: function () { return $('#logoPreview').length == 0; },
                        extension: "jpg|jpeg|png"
                    },
                    cv_file: {
                        extension: "pdf|doc|docx",
                        accept: false
                    }
                },
                messages: {
                    title: { required: '@lang("Vui lòng nhập tiêu đề công việc")' },
                    industry_id: { required: '@lang("Vui lòng chọn ngành nghề")' },
                    job_level_id: { required: '@lang("Vui lòng chọn cấp bậc")' },
                    description: {
                        required: '@lang("Vui lòng nhập mô tả công việc")',
                        minlength: '@lang("Mô tả công việc quá ngắn")'
                    },
                    company_name: { required: '@lang("Vui lòng nhập tên công ty")' },
                    province_id: { required: '@lang("Vui lòng chọn Tỉnh/Thành phố")' },
                    ward_id: { required: '@lang("Vui lòng chọn Quận/Huyện")' },
                    salary_from: { required: '@lang("Vui lòng nhập lương từ")', number: '@lang("Lương phải là số")' },
                    salary_to: { required: '@lang("Vui lòng nhập lương đến")', number: '@lang("Lương phải là số")' },
                    application_deadline: { required: '@lang("Vui lòng chọn hạn nộp hồ sơ")', date: '@lang("Định dạng ngày không hợp lệ")' },
                    company_logo: { required: '@lang("Vui lòng chọn logo công ty")', extension: '@lang("Định dạng ảnh phải là png, jpg, jpeg")' },
                    cv_file: { extension: '@lang("Định dạng CV phải là pdf, doc hoặc docx")' }
                },
                errorElement: 'span',
                errorClass: 'error-message',
                errorPlacement: function (error, element) {
                    if (element.hasClass('select2-hidden-accessible')) {
                        error.insertAfter(element.next('.select2-container'));
                    } else if (element.attr('name') == 'company_logo') {
                        error.insertAfter(element.closest('label'));
                    } else if (element.attr('name') == 'description') {
                        error.insertAfter(element.next('.tox-tinymce'));
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('error');
                    if ($(element).hasClass('select2-hidden-accessible')) {
                        $(element).next('.select2-container').addClass('error');
                    }
                    if (element.name == 'description') {
                        $(element).next('.tox-tinymce').addClass('error');
                    }
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('error');
                    if ($(element).hasClass('select2-hidden-accessible')) {
                        $(element).next('.select2-container').removeClass('error');
                    }
                    if (element.name == 'description') {
                        $(element).next('.tox-tinymce').removeClass('error');
                    }
                },
                submitHandler: function (form) {
                    if (typeof tinymce !== 'undefined') {
                        tinymce.triggerSave();
                    }
                    // Double check description after sync
                    if ($(form).validate().element('#description')) {
                        $(form).find('button[type=submit]').prop('disabled', true);
                        form.submit();
                    }
                }
            });

        })(jQuery);
    </script>
@endpush