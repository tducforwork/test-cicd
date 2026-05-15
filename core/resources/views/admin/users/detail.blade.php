@extends('admin.layouts.app')
@php
    use App\Constants\Status;
@endphp

@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-3 col-lg-5 col-md-5 mb-30">
            <div class="card b-radius--10 overflow-hidden">
                <div class="card-body p-0">
                    <div class="p-3 bg--white">
                        <div class="">
                            <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile')) }}"
                                alt="@lang('Profile Image')" class="b-radius--10 w-100">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card b-radius--10 overflow-hidden mt-20">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('User Information')</h5>
                    <ul class="list-group">

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span>{{ $user->username }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Joined At') <strong>{{ showDateTime($user->created_at, 'd M, Y h:i A') }}</strong>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Status')
                            @if ($user->status == Status::USER_ACTIVE)
                                <span class="badge badge-pill bg--success">@lang('Active')</span>
                            @else
                                <span class="badge badge-pill bg--danger">@lang('Banned')</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card b-radius--10 overflow-hidden mt-20">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('User Action')</h5>
                    @if ($user->is_seller && $user->shop)
                        <a href="{{ route('admin.sellers.shop.details', $user->id) }}"
                            class="btn btn--primary w-100 btn-lg mb-2">
                            @lang('Shop Info.')
                        </a>
                    @endif

                    <a href="{{ route('admin.report.user.login.history') }}?search={{ $user->username }}"
                        class="btn btn--secondary w-100 btn-lg mb-2">
                        @lang('Logins')
                    </a>

                    <a href="{{ route('admin.users.notification.log', $user->id) }}" class="btn btn--warning w-100 btn-lg">
                        @lang('Notifications')
                    </a>

                    <div class="mt-2">
                        @if ($user->status == Status::USER_ACTIVE)
                            <button type="button" class="btn btn--danger btn--shadow w-100 btn-lg userStatus"
                                data-bs-toggle="modal" data-bs-target="#userStatusModal">
                                <i class="las la-ban"></i>@lang('Ban User')
                            </button>
                        @else
                            <button type="button" class="btn btn--success btn--shadow w-100 btn-lg userStatus"
                                data-bs-toggle="modal" data-bs-target="#userStatusModal">
                                <i class="las la-undo"></i>@lang('Unban User')
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-7 col-md-7 mb-30">
            @if($user->is_seller)
                <h5 class="mb-3 mt-4">@lang('Seller Stats')</h5>
                <div class="row gy-4">
                    <div class="col-xxl-3 col-lg-6 col-sm-6">
                        <x-widget style="7" link="{{ route('admin.sellers.products', $user->id) }}" icon="las la-tshirt"
                            title="{{ __('Total Products') }}" value="{{ $totalProducts }}" bg="17" type="2" />
                    </div>
                </div>
            @endif

            <div class="card mt-30">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Information of') {{ $user->fullname }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', [$user->id]) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Full Name')</label>
                                    <input class="form-control" type="text" name="fullname" required
                                        value="{{ $user->fullname }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Email')</label>
                                    <input class="form-control" type="email" name="email" value="{{ $user->email }}"
                                        required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Mobile Number')</label>
                                    <div class="input-group ">
                                        <span class="input-group-text mobile-code">+{{ $user->dial_code }}</span>
                                        <input type="number" name="mobile" value="{{ $user->mobile }}" id="mobile"
                                            class="form-control checkUser" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group ">
                                    <label>@lang('Address')</label>
                                    <input class="form-control" type="text" name="address" value="{{ @$user->address }}">
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label>@lang('Province')</label>
                                    <select name="province_id" class="form-control select2" required>
                                        <option value="">@lang('Select Province')</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province->id }}" @selected($user->province_id == $province->id)>
                                                {{ __($province->full_name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label>@lang('Ward')</label>
                                    <select name="ward_id" class="form-control select2" required>
                                        <option value="">@lang('Select Ward')</option>
                                        @if($user->province_id)
                                            @foreach(\App\Models\Ward::where('province_id', $user->province_id)->orderBy('name')->get() as $ward)
                                                <option value="{{ $ward->id }}" @selected($user->ward_id == $ward->id)>
                                                    {{ __($ward->full_name) }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-12 col-md-12">
                                <div class="form-group">
                                    <label>@lang('Country') <span class="text--danger">*</span></label>
                                    <input class="form-control" type="text" value="Vietnam" readonly>
                                </div>
                            </div>

                            @if($user->is_seller)
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('ID Card (Số CCCD)')</label>
                                        <input class="form-control" type="text" name="id_card" value="{{ $user->id_card }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Bank Name')</label>
                                        <input class="form-control" type="text" name="bank_name" value="{{ $user->bank_name }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Bank Branch')</label>
                                        <input class="form-control" type="text" name="bank_branch"
                                            value="{{ $user->bank_branch }}">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('Bank Account Number')</label>
                                        <input class="form-control" type="text" name="bank_account_number"
                                            value="{{ $user->bank_account_number }}">
                                    </div>
                                </div>
                            @endif

                            <div class="col-xl-4 col-md-4 col-12">
                                <div class="form-group">
                                    <label>@lang('Email Verification')</label>
                                    <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                        data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')"
                                        name="ev" @if ($user->ev) checked @endif>
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-4 col-12">
                                <div class="form-group">
                                    <label>@lang('Mobile Verification')</label>
                                    <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                        data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')"
                                        name="sv" @if ($user->sv) checked @endif>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-4 col-12">
                                <div class="form-group">
                                    <label>@lang('2FA Verification') </label>
                                    <input type="checkbox" data-width="100%" data-height="50" data-onstyle="-success"
                                        data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Enable')"
                                        data-off="@lang('Disable')" name="ts" @if ($user->ts) checked @endif>
                                </div>
                            </div>

                            @if($user->is_seller)
                                <div class="col-xl-4 col-md-4 col-12">
                                    <div class="form-group">
                                        <label>@lang('KYC Verification') </label>
                                        <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                            data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')"
                                            name="kv" @if ($user->kv) checked @endif>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-12">
                                <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- User Status Modal --}}
    <div id="userStatusModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if ($user->status == Status::USER_ACTIVE)
                            @lang('Ban User')
                        @else
                            @lang('Unban User')
                        @endif
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.users.status', $user->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @if ($user->status == Status::USER_ACTIVE)
                            <h6 class="mb-2">@lang('If you ban this user he/she won\'t able to access his/her dashboard.')</h6>
                            <div class="form-group">
                                <label>@lang('Reason')</label>
                                <textarea class="form-control" name="reason" rows="4" required></textarea>
                            </div>
                        @else
                            <p><span>@lang('Ban reason was'):</span></p>
                            <p>{{ $user->ban_reason }}</p>
                            <h4 class="text-center mt-3">@lang('Are you sure to unban this user?')</h4>
                        @endif
                    </div>
                    <div class="modal-footer">
                        @if ($user->status == Status::USER_ACTIVE)
                            <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                        @else
                            <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('No')</button>
                            <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

{{-- @push('breadcrumb-plugins')
<a href="{{ route('admin.users.login', $user->id) }}" target="_blank" class="btn btn-sm btn-outline--primary"><i
        class="las la-sign-in-alt"></i>@lang('Login as User')</a>
@if($user->is_seller)
<a href="{{ route('admin.sellers.login', $user->id) }}" target="_blank" class="btn btn-sm btn-outline--info"><i
        class="las la-sign-in-alt"></i>@lang('Login as Seller')</a>
@endif
@endpush --}}

@push('script')
    <script>
        (function ($) {
            "use strict"

            $('.bal-btn').on('click', function () {
                var act = $(this).data('act');
                $('#addSubModal').find('input[name=act]').val(act);
                if (act == 'add') {
                    $('.type').text('Add');
                } else {
                    $('.type').text('Subtract');
                }
            });

            let mobileElement = $('.mobile-code');
            $('select[name=province_id]').on('change', function () {
                var provinceId = $(this).val();
                var wardSelect = $('select[name=ward_id]');
                wardSelect.empty().append('<option value="">@lang("Select Ward")</option>');
                if (provinceId) {
                    $.get('{{ route("get.wards", "") }}/' + provinceId, function (data) {
                        $.each(data, function (index, ward) {
                            wardSelect.append('<option value="' + ward.id + '">' + ward.full_name + '</option>');
                        });
                        wardSelect.trigger('change');
                    });
                }
                wardSelect.trigger('change');
            });

            // AJAX Form Submission
            $('.ajax-form').on('submit', function (e) {
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
                    success: function (response) {
                        if (response.status == 'success') {
                            notify('success', response.message);
                        } else {
                            notify('error', response.message);
                        }
                    },
                    error: function (xhr) {
                        var response = xhr.responseJSON;
                        if (response && response.errors) {
                            $.each(response.errors, function (key, value) {
                                notify('error', value[0]);
                            });
                        } else if (response && response.message) {
                            notify('error', response.message);
                        } else {
                            notify('error', '@lang("Something went wrong. Please try again.")');
                        }
                    },
                    complete: function () {
                        btn.prop('disabled', false).html(btnText);
                    }
                });
            });

        })(jQuery);
    </script>
@endpush