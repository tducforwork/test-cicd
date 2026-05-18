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
                <div class="flex items-center gap-2 mb-6">
                    <h1 class="text-[#272343] text-[24px] font-semibold leading-[normal]">@lang('Account Settings')</h1>
                </div>
                <div class="bg-white rounded-[12px] p-6 mb-6 border border-gray-100">
                    <form action="{{ route('seller.profile.setting') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6 ajax-form" id="profileForm">
                        @csrf
                        <!-- Profile Image -->
                        <div class="flex items-center gap-6 mb-6">
                            <div class="relative">
                                <img id="profilePreview"
                                    src="{{ getAvatar(getFilePath('userProfile') . '/' . $seller->image, $seller->fullname ?? $seller->username) }}"
                                    alt="Profile" class="w-24 h-24 rounded-full object-cover border-2 border-gray-200">
                                <input type="file" name="image" id="profileImage" class="hidden"
                                    accept=".png,.jpg,.jpeg">
                            </div>
                            <button type="button" onclick="document.getElementById('profileImage').click()"
                                class="hover:bg-orange-600 transition-colors flex items-center rounded-[12px] bg-[#FF6F0F] flex px-[18px] py-[10px] justify-center items-center text-[#FFF] text-[15px] font-bold leading-[24px] tracking-[-0.15px] gap-[8px] [box-shadow:0_1px_2px_0_rgba(255,_255,_255,_0.40)_inset,_0_-1px_2px_0_rgba(0,_0,_0,_0.24)_inset,_0_1px_2px_0_rgba(0,_0,_0,_0.08)] gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                @lang('Change Image')
                            </button>
                        </div>

                        <!-- Basic Info -->
                        <div>
                            <label
                                class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">@lang('Full Name')</label>
                            <input type="text" name="fullname" value="{{ old('fullname', $seller->firstname . ' ' . $seller->lastname) }}"
                                class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none">
                        </div>

                        <!-- Email (readonly) -->
                        <div>
                            <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">Email</label>
                            <input type="email" value="{{ $seller->email }}" readonly
                                class="w-full h-[52px] p-4 md:px-[16px] md:py-[14px] rounded-[8px] border border-[#E6E6E6] bg-gray-100 text-[#666] text-[16px] font-normal leading-[150%] cursor-not-allowed">
                        </div>

                        <!-- Phone (readonly) -->
                        <div>
                            <label
                                class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">@lang('Phone Number')</label>
                            <div class="flex">
                                <input type="text" value="{{ old('mobile', $seller->mobile) }}" readonly
                                    class="flex-1 h-[52px] p-4 md:px-[16px] md:py-[14px] rounded-[8px] border border-[#E6E6E6] bg-gray-100 text-[#666] text-[16px] font-normal leading-[150%] cursor-not-allowed">
                            </div>
                        </div>

                        <!-- Shop Name (Hidden) -->
                        <input type="hidden" name="shop_name" value="{{ old('fullname', $seller->firstname . ' ' . $seller->lastname) }}">

                        <!-- Submit button -->
                        <div>
                            <button type="submit"
                                class="text-[#FFF] hover:opacity-90 transition-opacity text-[16px] font-semibold leading-[24px] rounded-[12px] border-[1px] border-[solid] border-[#616161] bg-[#272343] [box-shadow:0_1px_2px_0_rgba(255,_255,_255,_0.40)_inset,_0_-1px_2px_0_rgba(0,_0,_0,_0.24)_inset,_0_1px_2px_0_rgba(0,_0,_0,_0.08)] flex px-[18px] py-[10px] justify-center items-center gap-[8px]">
                                @lang('Save Changes')
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Address Section -->
                <div class="bg-white rounded-[12px] p-6 mb-6 border border-gray-100">
                    <h2 class="text-xl font-bold text-[#272343] mb-6">@lang('Address')</h2>

                    <form action="{{ route('seller.profile.setting.address') }}" method="POST"
                        class="space-y-6 ajax-form" id="addressForm">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">@lang('Province/City')
                                    <span class="text-red-500">*</span></label>
                                <select name="province_id"
                                    class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none select2"
                                    required>
                                    <option value="">@lang('Select...')</option>
                                    @foreach($provinces as $province)
                                    <option value="{{ $province->id }}" @selected(old('province_id', $seller->province_id) == $province->id)>{{ __($province->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">@lang('Ward/Commune')
                                    <span class="text-red-500">*</span></label>
                                <select name="ward_id"
                                    class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none select2"
                                    required>
                                    <option value="">Chọn...</option>
                                    @if($seller->province_id)
                                    @foreach(\App\Models\Ward::where('province_id', $seller->province_id)->orderBy('name')->get() as $ward)
                                    <option value="{{ $ward->id }}" @selected(old('ward_id', $seller->ward_id) == $ward->id)>{{ __($ward->name) }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div>
                            <label
                                class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">Địa chỉ cụ thể</label>
                            <input name="address" value="{{ old('address', $seller->address) }}" class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none resize-none">
                        </div>

                        <div>
                            <button type="submit"
                                class="text-[#FFF] text-[16px] font-semibold leading-[24px] rounded-[12px] border-[1px] border-[solid] border-[#616161] bg-[#272343] [box-shadow:0_1px_2px_0_rgba(255,_255,_255,_0.40)_inset,_0_-1px_2px_0_rgba(0,_0,_0,_0.24)_inset,_0_1px_2px_0_rgba(0,_0,_0,_0.08)] flex px-[18px] py-[10px] justify-center items-center gap-[8px] hover:opacity-90 transition-opacity">
                                @lang('Save Address')
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password Section -->
                <div class="bg-white rounded-[12px] p-6 border border-gray-100">
                    <h2 class="text-xl font-bold text-[#272343] mb-6">@lang('Change Password')</h2>

                    <form action="{{ route('seller.password.update') }}" method="POST" class="ajax-form flex flex-col gap-[24px]"
                        id="passwordForm">
                        @csrf
                        <div>
                            <div class="relative">
                                <label
                                    class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">@lang('Current Password')</label>
                                <input type="password" name="current_password" placeholder="@lang('Enter current password')"
                                    class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none">
                                <button type="button"
                                    class="absolute right-4 top-[32px] text-gray-400 hover:text-gray-600 toggle-password">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                        fill="none">
                                        <path
                                            d="M1.66602 9.99935C1.66602 9.99935 4.69602 4.16602 9.99935 4.16602C15.3027 4.16602 18.3327 9.99935 18.3327 9.99935C18.3327 9.99935 15.3027 15.8327 9.99935 15.8327C4.69602 15.8327 1.66602 9.99935 1.66602 9.99935Z"
                                            stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M10 12.5C10.663 12.5 11.2989 12.2366 11.7678 11.7678C12.2366 11.2989 12.5 10.663 12.5 10C12.5 9.33696 12.2366 8.70107 11.7678 8.23223C11.2989 7.76339 10.663 7.5 10 7.5C9.33696 7.5 8.70107 7.76339 8.23223 8.23223C7.76339 8.70107 7.5 9.33696 7.5 10C7.5 10.663 7.76339 11.2989 8.23223 11.7678C8.70107 12.2366 9.33696 12.5 10 12.5V12.5Z"
                                            stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- New Password -->
                            <div>
                                <div class="relative">
                                    <label
                                        class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">@lang('New Password')</label>
                                    <input type="password" id="password" name="password" placeholder="@lang('Enter new password')"
                                        class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none">
                                    <button type="button"
                                        class="absolute right-4 top-[32px] text-gray-400 hover:text-gray-600 toggle-password">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                            fill="none">
                                            <path
                                                d="M1.66602 9.99935C1.66602 9.99935 4.69602 4.16602 9.99935 4.16602C15.3027 4.16602 18.3327 9.99935 18.3327 9.99935C18.3327 9.99935 15.3027 15.8327 9.99935 15.8327C4.69602 15.8327 1.66602 9.99935 1.66602 9.99935Z"
                                                stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M10 12.5C10.663 12.5 11.2989 12.2366 11.7678 11.7678C12.2366 11.2989 12.5 10.663 12.5 10C12.5 9.33696 12.2366 8.70107 11.7678 8.23223C11.2989 7.76339 10.663 7.5 10 7.5C9.33696 7.5 8.70107 7.76339 8.23223 8.23223C7.76339 8.70107 7.5 9.33696 7.5 10C7.5 10.663 7.76339 11.2989 8.23223 11.7678C8.70107 12.2366 9.33696 12.5 10 12.5V12.5Z"
                                                stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <div class="relative">
                                    <label
                                        class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">@lang('Confirm Password')</label>
                                    <input type="password" name="password_confirmation"
                                        placeholder="@lang('Re-enter new password')"
                                        class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none">
                                    <button type="button"
                                        class="absolute right-4 top-[32px] text-gray-400 hover:text-gray-600 toggle-password">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                            fill="none">
                                            <path
                                                d="M1.66602 9.99935C1.66602 9.99935 4.69602 4.16602 9.99935 4.16602C15.3027 4.16602 18.3327 9.99935 18.3327 9.99935C18.3327 9.99935 15.3027 15.8327 9.99935 15.8327C4.69602 15.8327 1.66602 9.99935 1.66602 9.99935Z"
                                                stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M10 12.5C10.663 12.5 11.2989 12.2366 11.7678 11.7678C12.2366 11.2989 12.5 10.663 12.5 10C12.5 9.33696 12.2366 8.70107 11.7678 8.23223C11.2989 7.76339 10.663 7.5 10 7.5C9.33696 7.5 8.70107 7.76339 8.23223 8.23223C7.76339 8.70107 7.5 9.33696 7.5 10C7.5 10.663 7.76339 11.2989 8.23223 11.7678C8.70107 12.2366 9.33696 12.5 10 12.5V12.5Z"
                                                stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div>
                            <button type="submit"
                                class="text-[#FFF] hover:opacity-90 transition-opacity text-[16px] font-semibold leading-[24px] rounded-[12px] border-[1px] border-[solid] border-[#616161] bg-[#272343] [box-shadow:0_1px_2px_0_rgba(255,_255,_255,_0.40)_inset,_0_-1px_2px_0_rgba(0,_0,_0,_0.24)_inset,_0_1px_2px_0_rgba(0,_0,_0,_0.08)] flex px-[18px] py-[10px] justify-center items-center gap-[8px]">
                                @lang('Update Password')
                            </button>
                        </div>
                    </form>
                </div>

            </div>{{-- end main content --}}
        </div>{{-- end flex row --}}
    </main>
</div>
@endsection

@push('style-lib')
<link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('style')
<style>
    .select2-container--default .select2-selection--single {
        height: 52px;
        border-color: #E6E6E6;
        border-radius: 8px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        display: flex;
        align-items: center;
        padding-left: 16px;
        color: #272343;
        font-size: 16px;
        height: 52px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 52px;
        right: 12px;
    }

    label.error {
        color: #ef4444;
        font-size: 14px;
        margin-top: 4px;
        display: block;
    }

    input.error {
        border-color: #ef4444 !important;
    }
</style>
@endpush

@push('script-lib')
<script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
@endpush

@push('script')
<script>
    'use strict';
    (function($) {
        // Initialize Select2
        $('.select2').select2();

        // Image preview
        $('#profileImage').on('change', function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#profilePreview').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        // Dynamic Ward loading
        $('select[name=province_id]').on('change', function() {
            var provinceId = $(this).val();
            var wardSelect = $('select[name=ward_id]');
            wardSelect.empty().append('<option value="">Chọn...</option>');
            if (provinceId) {
                $.get('{{ route("get.wards", "") }}/' + provinceId, function(data) {
                    $.each(data, function(index, ward) {
                        wardSelect.append('<option value="' + ward.id + '">' + ward.name + '</option>');
                    });
                    wardSelect.trigger('change');
                });
            }
        });

        // Profile form validation
        $('#profileForm').validate({
            rules: {
                fullname: "required",
            },
            messages: {
                fullname: "@lang('Please enter your full name')",
            },
            errorElement: 'label',
            errorPlacement: function(error, element) {
                var container = element.closest('.relative');
                if (container.length > 0) {
                    error.insertAfter(container);
                } else if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2-container'));
                } else {
                    error.insertAfter(element);
                }
            }
        });

        // Password form validation
        $('#passwordForm').validate({
            rules: {
                current_password: "required",
                password: {
                    required: true,
                    minlength: 6
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password"
                }
            },
            messages: {
                current_password: "@lang('Please enter your current password')",
                password: {
                    required: "@lang('Please enter a new password')",
                    minlength: "@lang('Password must be at least 6 characters')"
                },
                password_confirmation: {
                    required: "@lang('Please confirm your new password')",
                    equalTo: "@lang('Confirm password does not match')"
                }
            },
            errorElement: 'label',
            errorPlacement: function(error, element) {
                var container = element.closest('.relative');
                if (container.length > 0) {
                    error.insertAfter(container);
                } else if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2-container'));
                } else {
                    error.insertAfter(element);
                }
            }
        });

        // Address form validation
        $('#addressForm').validate({
            rules: {
                province_id: "required",
                ward_id: "required",
                address: "required",
            },
            messages: {
                province_id: "@lang('Please select a Province/City')",
                ward_id: "@lang('Please select a Ward/Commune')",
                address: "@lang('Please enter a specific address')",
            },
            errorElement: 'label',
            errorPlacement: function(error, element) {
                var container = element.closest('.relative');
                if (container.length > 0) {
                    error.insertAfter(container);
                } else if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2-container'));
                } else {
                    error.insertAfter(element);
                }
            }
        });

        // AJAX Form Submission
        $('.ajax-form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);

            // Check validation if validator is attached
            if (form.data('validator') && !form.valid()) {
                return false;
            }

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
                        if (form.attr('action').includes('password')) {
                            form.trigger('reset');
                        }
                        // Reload page after profile update
                        if (form.attr('action').includes('profile-setting') || form.attr('action').includes('address')) {
                            location.reload();
                        }
                    } else {
                        notify('error', response.message);
                    }
                },
                error: function(xhr) {
                    var response = xhr.responseJSON;
                    if (response && response.errors) {
                        $.each(response.errors, function(key, value) {
                            notify('error', value[0]);
                        });
                    } else if (response && response.message) {
                        notify('error', response.message);
                    } else {
                        notify('error', 'Something went wrong. Please try again.');
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).html(btnText);
                }
            });
        });

        // Password visibility toggle
        $(document).on('click', '.toggle-password', function() {
            var input = $(this).siblings('input');
            var type = input.attr('type') === 'password' ? 'text' : 'password';
            input.attr('type', type);
            var icon = $(this).find('i');
            if (icon.length) {
                icon.toggleClass('la-eye la-eye-slash');
            }
        });
    })(jQuery);
</script>
@endpush