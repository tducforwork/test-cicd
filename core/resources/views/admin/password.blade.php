@extends('admin.layouts.app')
@section('panel')

    <div class="row mb-none-30">
        <div class="col-lg-3 col-md-3 mb-30">

            <div class="card b-radius--5 overflow-hidden">
                <div class="card-body p-0">
                    <div class="d-flex p-3 bg--primary align-items-center">
                        <div class="avatar avatar--lg">
                            <img src="{{ getImage(getFilePath('adminProfile').'/'. $admin->image,getFileSize('adminProfile'))}}" alt="Image">
                        </div>
                        <div class="ps-3">
                            <h4 class="text--white">{{__($admin->name)}}</h4>
                        </div>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Name')
                            <span class="fw-bold">{{ __($admin->name) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span  class="fw-bold">{{ __($admin->username) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Email')
                            <span  class="fw-bold">{{ $admin->email }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-md-9 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4 border-bottom pb-2">@lang('Change Password')</h5>

                    <form action="{{ route('admin.password.update') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
                        @csrf

                        <div class="form-group">
                            <label>@lang('Password')</label>
                            <div class="input-group">
                                <input class="form-control" type="password" name="old_password" required>
                                <button type="button" class="input-group-text toggle-password"><i class="las la-eye"></i></button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>@lang('New Password')</label>
                            <div class="input-group">
                                <input class="form-control" type="password" name="password" required>
                                <button type="button" class="input-group-text toggle-password"><i class="las la-eye"></i></button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>@lang('Confirm Password')</label>
                            <div class="input-group">
                                <input class="form-control" type="password" name="password_confirmation" required>
                                <button type="button" class="input-group-text toggle-password"><i class="las la-eye"></i></button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn--primary w-100 btn-lg h-45">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('breadcrumb-plugins')
    <a href="{{route('admin.profile')}}" class="btn btn-sm btn-outline--primary" ><i class="las la-user"></i>@lang('Profile Setting')</a>
@endpush
@push('style')
    <style>
        .list-group-item:first-child{
            border-top-left-radius:unset;
            border-top-right-radius:unset;
        }
    </style>
@endpush

@push('script')
<script>
    (function($) {
        "use strict";
        // AJAX Form Submission
        $('.ajax-form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = form.find('button[type=submit]');
            var btnText = btn.text();
            var formData = new FormData(this);

            btn.prop('disabled', true).html('<i class="las la-spinner la-spin"></i> @lang("Saving...")');

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status == 'success') {
                        notify('success', response.message);
                        form.trigger('reset');
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
                        notify('error', '@lang("Something went wrong. Please try again.")');
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
