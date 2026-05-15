@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.frontend.sections.content', $key) }}" class="disableSubmission" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="element">
                        @if (@$data)
                            <input type="hidden" name="id" value="{{ $data->id }}">
                        @endif
                        <div class="row">
                            @php
                                $imgCount = 0;
                            @endphp
                            @foreach ($section->element as $k => $content)
                                @if ($k == 'images')
                                    @php
                                        $imgCount = collect($content)->count();
                                    @endphp
                                    @foreach ($content as $imgKey => $image)
                                        <div class="col-md-4">
                                            <input type="hidden" name="has_image[]" value="1">
                                            <div class="form-group">
                                                <label>{{ __(keyToTitle($imgKey)) }}</label>
                                                <x-image-uploader class="w-100" :imagePath="frontendImage($key, @$data->data_values->$imgKey, $section->element->images->$imgKey->size)" :image="@$data->data_values->$imgKey" name="image_input[{{ @$imgKey }}]" id="image-upload-input{{ $loop->index }}" :size="$section->element->images->$imgKey->size" :required="false" />

                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="@if ($imgCount > 1) col-md-12 @else col-md-8 @endif">
                                        @push('divend')
                                        </div>
                                    @endpush
                                @elseif($content == 'icon')
                                    <div class="form-group">
                                        <label>{{ keyToTitle($k) }}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control iconPicker icon" autocomplete="off" name="{{ $k }}" value="{{ old($k,@$data->data_values->$k) }}" required>
                                            <span class="input-group-text  input-group-addon" data-icon="las la-home" role="iconpicker"></span>
                                        </div>
                                    </div>
                                @else
                                    @if ($content == 'textarea')
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{ __(keyToTitle($k)) }}</label>
                                                <textarea rows="10" class="form-control" name="{{ $k }}" required>{{ old($k,@$data->data_values->$k) }}</textarea>
                                            </div>
                                        </div>
                                    @elseif($content == 'textarea-nic')
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{ __(keyToTitle($k)) }}</label>
                                                <textarea rows="10" class="form-control ckeditor" name="{{ $k }}">{{ old($k,@$data->data_values->$k) }}</textarea>
                                            </div>
                                        </div>
                                    @elseif($k == 'select')
                                        @php
                                            $selectName = $content->name;
                                        @endphp
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{ __(keyToTitle(@$selectName)) }}</label>
                                                <select class="form-control select2" data-minimum-results-for-search="-1" name="{{ @$selectName }}" required>
                                                    @foreach ($content->options as $selectItemKey => $selectOption)
                                                        <option value="{{ $selectItemKey }}" @if (@$data->data_values->$selectName == $selectItemKey) selected @endif>{{ __($selectOption) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @elseif($k == 'slug')
                                        <div class="col-md-12 d-none">
                                            <div class="form-group">
                                                <div class="d-flex justify-content-between">
                                                    <label>{{ __(keyToTitle($k)) }}</label>
                                                    <div class="slug-verification d-none"></div>
                                                </div>
                                                <input type="text" class="form-control" name="slug" value="{{ old($k,@$data->slug) }}" required>
                                            </div>
                                        </div>
                                    @elseif(is_object($content) || is_array($content))
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{ __(keyToTitle($k)) }}</label>
                                                <select class="form-control" name="{{ $k }}">
                                                    @foreach($content as $optsKey => $optsValue)
                                                        <option value="{{ $optsKey }}" @if (@$data->data_values->$k == $optsKey) selected @endif>{{ __($optsValue) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @elseif($k == 'coupon_code')
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{ __(keyToTitle($k)) }}</label>
                                                <select class="form-control" name="{{ $k }}">
                                                    <option value="">@lang('Select One')</option>
                                                    @foreach($coupons as $coupon)
                                                        <option value="{{ $coupon->coupon_code }}" @if (@$data->data_values->$k == $coupon->coupon_code) selected @endif>{{ __($coupon->name) }} ({{ $coupon->coupon_code }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @elseif($content == 'color')
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{ __(keyToTitle($k)) }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-text p-0 border-0">
                                                        <input type='text' class="form-control colorPicker" value="{{ old($k,@$data->data_values->$k) }}">
                                                    </span>
                                                    <input type="text" class="form-control colorCode" name="{{ $k }}" value="{{ old($k,@$data->data_values->$k) }}">
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($k == 'instruction')
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="alert alert-info" role="alert">
                                                    <i class="las la-info-circle"></i> {{ __($content) }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="d-flex justify-content-between">
                                                    <label>{{ __(keyToTitle($k)) }}</label>

                                                </div>
                                                <input type="text" class="form-control" name="{{ $k }}" value="{{ old($k,@$data->data_values->$k) }}">
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                            @stack('divend')
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn--primary @if(@$section->element->slug && !@$data) disabled @endif w-100 h-45">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.frontend.sections', $key) }}" />
@endpush

@push('style-lib')
    <link href="{{ asset('assets/admin/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/spectrum.css') }}">
    <style>
        .ckeditor-main {
            padding: 15px !important;
            color: #333333 !important;
            line-height: 1.6 !important;
            width: 100% !important;
        }

        .colorPicker {
            width: 50px !important;
            height: 45px !important;
            padding: 0 !important;
            border: none !important;
        }
    </style>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/fontawesome-iconpicker.js') }}"></script>
    <script src="{{ asset('assets/admin/js/spectrum.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.iconPicker').iconpicker().on('iconpickerSelected', function(e) {
                $(this).closest('.form-group').find('.iconpicker-input').val(`<i class="${e.iconpickerValue}"></i>`);
            });

            $('.colorPicker').each(function() {
                var $picker = $(this);
                var $input = $picker.closest('.input-group').find('.colorCode');
                $picker.spectrum({
                    color: $picker.val(),
                    change: function(color) {
                        $input.val(color.toHexString().replace(/^#?/, ''));
                    },
                    move: function(color) {
                        $input.val(color.toHexString().replace(/^#?/, ''));
                    }
                });
            });

            $('.colorCode').on('input', function() {
                var clr = $(this).val();
                $(this).parents('.input-group').find('.colorPicker').spectrum({
                    color: clr,
                });
            });

            @if(@$section->element->slug)
                let slugKey = '{{ @$section->element->slug }}';
                $(`[name=${slugKey}]`).on('input', function() {
                    let title = $(this).val();
                    let closestForm = $(this).closest('form');
                    closestForm.find('[name=slug]').val(title).trigger('input');
                });



                $('[name=slug]').on('input',function(){
                    let closestForm = $(this).closest('form');
                    closestForm.find('[type=submit]').addClass('disabled')
                    let slug = $(this).val();
                    slug = slug.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');
                    $(this).val(slug);
                    if (slug) {
                        closestForm.find('.slug-verification').removeClass('d-none');
                        closestForm.find('.slug-verification').html(`
                            <small class="text--info"><i class="las la-spinner la-spin"></i> @lang('Checking')</small>
                        `);
                        $.get("{{ route('admin.frontend.sections.element.slug.check',[$key,@$data->id]) }}", {slug:slug},function(response){
                            if (!response.exists) {
                                closestForm.find('.slug-verification').html(`
                                    <small class="text--success"><i class="las la-check"></i> @lang('Available')</small>
                                `);
                                closestForm.find('[type=submit]').removeClass('disabled')
                            }
                            if (response.exists) {
                                closestForm.find('.slug-verification').html(`
                                    <small class="text--danger"><i class="las la-times"></i> @lang('Slug already exists')</small>
                                `);
                            }
                        });
                    }else{
                        closestForm.find('.slug-verification').addClass('d-none');
                    }
                })
            @endif
        })(jQuery);
    </script>
@endpush
