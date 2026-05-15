@extends($activeTemplate . 'layouts.frontend')
@section('content')
<div class="bg-[#F7F7F7]">
    <main class="container mx-auto pb-32 pt-10">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar -->
            <aside class="w-full lg:w-[312px] shrink-0">
                @include($activeTemplate . 'user.partials.sidebar')
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 min-w-0">
                    <h1 class="text-2xl font-semibold text-[#272343]">@lang('Account Settings')</h1>

                <!-- Account Settings Section -->
                <div class="bg-white rounded-[12px] p-6 mb-6 border border-gray-100">
                    <form action="{{ route('user.profile.setting') }}" method="post" enctype="multipart/form-data" class="flex flex-col gap-6 ajax-form" id="profileForm">
                        @csrf
                        <div class="flex flex-col sm:flex-row items-center gap-6 md:gap-[36px]">
                            <div class="relative group">
                                <img id="imagePreview" src="{{ getAvatar(getFilePath('userProfile') . '/' . $user->image) }}" alt="Avatar"
                                    class="w-24 h-24 rounded-full object-cover border-2 border-gray-100">
                                <input type="file" name="image" id="profileImage" class="hidden" accept=".png, .jpg, .jpeg">
                            </div>
                            <button type="button" onclick="document.getElementById('profileImage').click()"
                                class="bg-[#FF6F0F] text-white px-[18px] py-[10px] rounded-[12px] shadow-[inset_0_1px_2px_0_rgba(255,255,255,0.40),inset_0_-1px_2px_0_rgba(0,0,0,0.24),0_1px_2px_0_rgba(0,0,0,0.08)] font-bold text-sm md:text-[15px] hover:bg-orange-600 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                @lang('Change Image')
                            </button>
                        </div>

                        <div class="flex flex-col gap-6">
                            <div>
                                <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">@lang('Full Name') <span class="text-red-500">*</span></label>
                                <input type="text" name="fullname" value="{{ $user->fullname }}"
                                    class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none"
                                    placeholder="@lang('Enter your full name')" required>
                            </div>
                            <div>
                                <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">Email</label>
                                <input type="email" value="{{ $user->email }}" readonly
                                    class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#F7F7F7] focus:outline-none cursor-not-allowed">
                            </div>
                            <div>
                                <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">Số điện thoại</label>
                                <input type="text" value="{{ $user->mobile }}" readonly
                                    class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#F7F7F7] focus:outline-none cursor-not-allowed">
                            </div>
                        </div>

                        <div>
                            <button type="submit"
                                class="text-[#FFF] hover:opacity-90 transition-opacity text-[16px] font-semibold leading-[24px] rounded-[12px] border-[1px] border-[solid] border-[#616161] bg-[#272343] [box-shadow:0_1px_2px_0_rgba(255,_255,_255,_0.40)_inset,_0_-1px_2px_0_rgba(0,_0,_0,_0.24)_inset,_0_1px_2px_0_rgba(0,_0,_0,_0.08)] flex px-[18px] py-[10px] justify-center items-center gap-[8px]">
                                @lang('Update Profile')
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Address Section -->
                <div class="bg-white rounded-[12px] p-6 mb-6 border border-gray-100">
                    <h2 class="flex text-[#272343] text-[20px] font-bold leading-[150%] mb-6">@lang('Address')</h2>
                    <form action="{{ route('user.profile.setting.address') }}" method="post" class="flex flex-col gap-6 ajax-form" id="addressForm">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">@lang('Province/City') <span class="text-red-500">*</span></label>
                                <select name="province_id"
                                    class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none select2" required>
                                    <option value="">@lang('Select Province/City')</option>
                                    @foreach($provinces as $province)
                                    <option value="{{ $province->id }}" {{ $user->province_id == $province->id ? 'selected' : '' }}>{{ __($province->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">@lang('Ward/Commune') <span class="text-red-500">*</span></label>
                                <select name="ward_id"
                                    class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none select2" required>
                                    <option value="">@lang('Select Ward/Commune')</option>
                                    @if($user->province_id)
                                    @foreach(\App\Models\Ward::where('province_id', $user->province_id)->orderBy('name')->get() as $ward)
                                    <option value="{{ $ward->id }}" {{ $user->ward_id == $ward->id ? 'selected' : '' }}>{{ __($ward->name) }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">@lang('Specific Address') <span class="text-red-500">*</span></label>
                            <input type="text" name="address" value="{{ $user->address }}"
                                class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none"
                                placeholder="@lang('Enter your specific address')" required>
                        </div>

                        <div>
                            <button type="submit"
                                class="text-[#FFF] hover:opacity-90 transition-opacity text-[16px] font-semibold leading-[24px] rounded-[12px] border-[1px] border-[solid] border-[#616161] bg-[#272343] [box-shadow:0_1px_2px_0_rgba(255,_255,_255,_0.40)_inset,_0_-1px_2px_0_rgba(0,_0,_0,_0.24)_inset,_0_1px_2px_0_rgba(0,_0,_0,_0.08)] flex px-[18px] py-[10px] justify-center items-center gap-[8px]">
                                @lang('Update Address')
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password Section -->
                <div class="bg-white rounded-[12px] p-6 border border-gray-100">
                    <h2 class="flex text-[#272343] text-[20px] font-bold leading-[150%] mb-6">@lang('Change Password')</h2>
                    <form action="{{ route('user.change.password') }}" method="post" class="flex flex-col gap-6 ajax-form" id="passwordForm">
                        @csrf
                        <div>
                            <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">@lang('Current Password') <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="password" name="current_password" placeholder="@lang('Enter current password')" required
                                    class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none">
                                <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 toggle-password">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M1.66602 9.99935C1.66602 9.99935 4.69602 4.16602 9.99935 4.16602C15.3027 4.16602 18.3327 9.99935 18.3327 9.99935C18.3327 9.99935 15.3027 15.8327 9.99935 15.8327C4.69602 15.8327 1.66602 9.99935 1.66602 9.99935Z" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M10 12.5C10.663 12.5 11.2989 12.2366 11.7678 11.7678C12.2366 11.2989 12.5 10.663 12.5 10C12.5 9.33696 12.2366 8.70107 11.7678 8.23223C11.2989 7.76339 10.663 7.5 10 7.5C9.33696 7.5 8.70107 7.76339 8.23223 8.23223C7.76339 8.70107 7.5 9.33696 7.5 10C7.5 10.663 7.76339 11.2989 8.23223 11.7678C8.70107 12.2366 9.33696 12.5 10 12.5V12.5Z" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">@lang('New Password') <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="password" name="password" id="password" placeholder="@lang('Enter new password')" required
                                        class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none">
                                    <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 toggle-password">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M1.66602 9.99935C1.66602 9.99935 4.69602 4.16602 9.99935 4.16602C15.3027 4.16602 18.3327 9.99935 18.3327 9.99935C18.3327 9.99935 15.3027 15.8327 9.99935 15.8327C4.69602 15.8327 1.66602 9.99935 1.66602 9.99935Z" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M10 12.5C10.663 12.5 11.2989 12.2366 11.7678 11.7678C12.2366 11.2989 12.5 10.663 12.5 10C12.5 9.33696 12.2366 8.70107 11.7678 8.23223C11.2989 7.76339 10.663 7.5 10 7.5C9.33696 7.5 8.70107 7.76339 8.23223 8.23223C7.76339 8.70107 7.5 9.33696 7.5 10C7.5 10.663 7.76339 11.2989 8.23223 11.7678C8.70107 12.2366 9.33696 12.5 10 12.5V12.5Z" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="text-[#272343] text-[16px] font-normal leading-[150%] mb-[6px] block">@lang('Confirm Password') <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" placeholder="@lang('Confirm new password')" required
                                        class="w-full h-[52px] px-4 md:px-[16px] md:py-[14px] rounded-[8px] border text-[#666] text-[16px] font-normal leading-[150%] border-[solid] border-[#E6E6E6] bg-[#FFF] focus:outline-none">
                                    <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 toggle-password">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M1.66602 9.99935C1.66602 9.99935 4.69602 4.16602 9.99935 4.16602C15.3027 4.16602 18.3327 9.99935 18.3327 9.99935C18.3327 9.99935 15.3027 15.8327 9.99935 15.8327C4.69602 15.8327 1.66602 9.99935 1.66602 9.99935Z" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M10 12.5C10.663 12.5 11.2989 12.2366 11.7678 11.7678C12.2366 11.2989 12.5 10.663 12.5 10C12.5 9.33696 12.2366 8.70107 11.7678 8.23223C11.2989 7.76339 10.663 7.5 10 7.5C9.33696 7.5 8.70107 7.76339 8.23223 8.23223C7.76339 8.70107 7.5 9.33696 7.5 10C7.5 10.663 7.76339 11.2989 8.23223 11.7678C8.70107 12.2366 9.33696 12.5 10 12.5V12.5Z" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
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
    (function($) {
        'use strict';

        // Image preview
        $('#profileImage').on('change', function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        // Initialize Select2
        $('.select2').select2();

        // Dynamic Ward loading
        $('select[name=province_id]').on('change', function() {
            var provinceId = $(this).val();
            var wardSelect = $('select[name=ward_id]');
            wardSelect.empty().append('<option value="">Chọn Phường/Xã</option>');
            if (provinceId) {
                $.get('{{ route("user.get.wards", "") }}/' + provinceId, function(data) {
                    $.each(data, function(index, ward) {
                        wardSelect.append('<option value="' + ward.id + '">' + ward.name + '</option>');
                    });
                    wardSelect.trigger('change');
                });
            }
        });

        // Validation Rules
        $('#profileForm').validate({
            rules: {
                fullname: "required"
            },
            messages: {
                fullname: "Vui lòng nhập họ tên"
            },
            errorElement: 'label',
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            }
        });

        $('#addressForm').validate({
            rules: {
                province_id: "required",
                ward_id: "required",
                address: "required",
            },
            messages: {
                province_id: "Vui lòng chọn Tỉnh/Thành phố",
                ward_id: "Vui lòng chọn Phường/Xã",
                address: "Vui lòng nhập địa chỉ cụ thể",
            },
            errorElement: 'label',
            errorPlacement: function(error, element) {
                if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2-container'));
                } else {
                    error.insertAfter(element);
                }
            }
        });

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
                current_password: "Vui lòng nhập mật khẩu hiện tại",
                password: {
                    required: "Vui lòng nhập mật khẩu mới",
                    minlength: "Mật khẩu phải có ít nhất 6 ký tự"
                },
                password_confirmation: {
                    required: "Vui lòng xác nhận mật khẩu mới",
                    equalTo: "Mật khẩu xác nhận không khớp"
                }
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

        // AJAX Form Submission
        $('.ajax-form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);

            if (!form.valid()) return false;

            var btn = form.find('button[type=submit]');
            var btnText = btn.text();
            var formData = new FormData(this);

            btn.prop('disabled', true).html('<i class="las la-spinner la-spin"></i> Đang lưu...');

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status == 'success') {
                        notify('success', response.message);
                        if (form.attr('id') == 'passwordForm') {
                            form.trigger('reset');
                        }
                    } else {
                        notify('error', response.message);
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
                        notify('error', 'Có lỗi xảy ra. Vui lòng thử lại sau.');
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).text(btnText);
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
    })(jQuery)
</script>
@endpush