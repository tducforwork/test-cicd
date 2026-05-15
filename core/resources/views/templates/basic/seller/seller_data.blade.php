@extends($activeTemplate . 'layouts.frontend')
@section('content')
<div class="padding-bottom padding-top">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="card shadow-md border-0">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="mb-0">{{ __($pageTitle) }}</h5>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('seller.data.submit') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">@lang('Username')</label>
                                        <input type="text" class="form-control form--control checkUser" name="username" value="{{ old('username') }}">
                                        <small class="text--danger usernameExist"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('Country')</label>
                                        <input type="text" class="form-control form--control" value="Vietnam" readonly>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('Mobile')</label>
                                        <div class="input-group">
                                            <span class="input-group-text mobile-code">

                                            </span>
                                            <input type="hidden" name="mobile_code">
                                            <input type="hidden" name="country_code">
                                            <input type="number" name="mobile" value="{{ old('mobile') }}" class="form-control form--control checkUser" required>
                                        </div>
                                        <small class="text--danger mobileExist"></small>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="form-label">@lang('Province')</label>
                                    <select name="province_id" class="form-control form--control select2" required>
                                        <option value="">@lang('Select Province')</option>
                                        @foreach($provinces as $province)
                                        <option value="{{ $province->id }}">{{ __($province->full_name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="form-label">@lang('Ward')</label>
                                    <select name="ward_id" class="form-control form--control select2" required>
                                        <option value="">@lang('Select Ward')</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label class="form-label">@lang('Address')</label>
                                    <input type="text" class="form-control form--control" name="address" value="{{ old('address') }}" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style-lib')
<link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script-lib')
<script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('script')
<script>
    "use strict";
    (function($) {

        @if($mobileCode)
        $(`option[data-code={{ $mobileCode }}]`).attr('selected', '');
        @endif

        $('.select2').select2();

        $('.mobile-code').text('+84');


        $('select[name=province_id]').on('change', function() {
            var provinceId = $(this).val();
            var wardSelect = $('select[name=ward_id]');
            wardSelect.empty().append('<option value="">@lang("Select Ward")</option>');
            if (provinceId) {
                $.get('{{ route("get.wards", "") }}/' + provinceId, function(data) {
                    $.each(data, function(index, ward) {
                        wardSelect.append('<option value="' + ward.id + '">' + ward.full_name + '</option>');
                    });
                });
            }
        });


        $('.checkUser').on('focusout', function(e) {
            var value = $(this).val();
            var name = $(this).attr('name')
            checkUser(value, name);
        });

        function checkUser(value, name) {
            var url = '{{ route('
            seller.check.seller ') }}';
            var token = '{{ csrf_token() }}';

            if (name == 'mobile') {
                var mobile = `${value}`;
                var data = {
                    mobile: mobile,
                    mobile_code: $('.mobile-code').text().substr(1),
                    _token: token
                }
            }
            if (name == 'username') {
                var data = {
                    username: value,
                    _token: token
                }
            }
            $.post(url, data, function(response) {
                if (response.data != false) {
                    $(`.${response.type}Exist`).text(`${response.field} already exist`);
                } else {
                    $(`.${response.type}Exist`).text('');
                }
            });
        }
    })(jQuery);
</script>
@endpush

@push('style')
<style>
    .form-group {
        margin-bottom: 16px;
    }
</style>
@endpush