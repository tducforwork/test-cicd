@extends($activeTemplate . 'layouts.frontend')
@section('content')
<div class="bg-[#F7F7F7]">
    <main class="container mx-auto pb-32 pt-10">
        <div class="flex flex-col lg:flex-row items-start gap-6">
            <!-- Sidebar -->
            <aside class="w-full lg:w-[312px] shrink-0">
                @include($activeTemplate . 'user.partials.user_sidebar')
            </aside>

            <!-- Main Content -->
            <main class="profile-main-content flex-1 min-w-0">
                <div class="content-header">
                    <h2>@lang('Thông tin cá nhân')</h2>
                    <p style="color: var(--text-muted); font-size: 14px;">@lang('Quản lý thông tin hồ sơ để bảo mật tài khoản')</p>
                </div>

                <form action="{{ route('user.profile.setting') }}" method="post" enctype="multipart/form-data" class="profile-form ajax-form" id="profileForm">
                    @csrf
                    <!-- Avatar Upload Section -->
                    <div class="avatar-upload-wrapper" style="text-align: center; margin-bottom: 40px;">
                        <div class="avatar-preview-container" style="position: relative; display: inline-block;">
                            <img src="{{ getAvatar(getFilePath('userProfile') . '/' . $user->image, $user->fullname ?? $user->username) }}"
                                id="avatarPreview" alt="Avatar"
                                style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid white; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                            <label for="avatarInput"
                                style="position: absolute; bottom: 5px; right: 5px; width: 35px; height: 35px; background: var(--accent); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 2px solid white; transition: var(--transition);">
                                <i class="fa-solid fa-camera" style="font-size: 14px;"></i>
                            </label>
                            <input type="file" name="image" id="avatarInput" accept=".png, .jpg, .jpeg" style="display: none;">
                        </div>
                        <p style="margin-top: 10px; font-size: 13px; color: var(--text-muted);">@lang('Hỗ trợ định dạng JPG, PNG. Tối đa 2MB.')</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">@lang('Họ và tên') <span class="text-red-500">*</span></label>
                                <input type="text" name="fullname" class="form-control" value="{{ $user->fullname }}" placeholder="@lang('Nhập họ và tên')" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">@lang('Số điện thoại')</label>
                                <input type="tel" class="form-control bg-[#F7F7F7] cursor-not-allowed" value="{{ $user->mobile }}" readonly disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control bg-[#F7F7F7] cursor-not-allowed" value="{{ $user->email }}" readonly disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">@lang('Ngày sinh')</label>
                                <input type="date" name="birth_date" class="form-control" value="{{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('Y-m-d') : '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">@lang('Tỉnh / Thành phố') <span class="text-red-500">*</span></label>
                                <select name="province_id" class="form-control select2" required>
                                    <option value="">@lang('Chọn Tỉnh/Thành phố')</option>
                                    @foreach($provinces as $province)
                                    <option value="{{ $province->id }}" {{ $user->province_id == $province->id ? 'selected' : '' }}>{{ __($province->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">@lang('Phường / Xã') <span class="text-red-500">*</span></label>
                                <select name="ward_id" class="form-control select2" required>
                                    <option value="">@lang('Chọn Phường/Xã')</option>
                                    @if($user->province_id)
                                    @foreach(\App\Models\Ward::where('province_id', $user->province_id)->orderBy('name')->get() as $ward)
                                    <option value="{{ $ward->id }}" {{ $user->ward_id == $ward->id ? 'selected' : '' }}>{{ __($ward->name) }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">@lang('Địa chỉ chi tiết (Số nhà, tên đường...)') <span class="text-red-500">*</span></label>
                                <input type="text" name="address" class="form-control" value="{{ $user->address }}" placeholder="@lang('Nhập địa chỉ chi tiết')" required>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 20px;">
                        <button type="submit" class="btn btn-primary" style="padding: 12px 30px;">@lang('Lưu thay đổi')</button>
                    </div>
                </form>
            </main>
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
        $('#avatarInput').on('change', function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#avatarPreview').attr('src', e.target.result);
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
                fullname: "required",
                province_id: "required",
                ward_id: "required",
                address: "required"
            },
            messages: {
                fullname: "Vui lòng nhập họ tên",
                province_id: "Vui lòng chọn Tỉnh/Thành phố",
                ward_id: "Vui lòng chọn Phường/Xã",
                address: "Vui lòng nhập địa chỉ cụ thể"
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
    })(jQuery)
</script>
@endpush