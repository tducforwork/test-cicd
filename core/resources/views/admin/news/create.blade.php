@extends('admin.layouts.app')
@section('panel')

<div class="row justify-content-center">
    <div class="col-lg-12">
        <form action="{{ route('admin.news.store', @$news->id) }}" id="addForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card p-2 has-select2">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Thông Tin Tin Tức')</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-2">
                            <label>@lang('Tiêu đề')</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="{{ old('title', @$news->title) }}" name="title" required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">
                            <label>@lang('Danh mục')</label>
                        </div>
                        <div class="col-md-10">
                            <select name="category_id" class="form-control select2-basic">
                                <option value="">@lang('Chọn danh mục')</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id', @$news->category_id) == $cat->id)>
                                    {{ __($cat->name) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">
                            <label>@lang('Hiển thị trang chủ')</label>
                        </div>
                        <div class="col-md-10">
                            <x-toggle-switch name="is_show_home" :checked="@$news->is_show_home" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="card p-2 my-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Nội Dung')</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-2">
                            <label>@lang('Tóm tắt')</label>
                        </div>
                        <div class="col-md-10">
                            <textarea name="excerpt" class="form-control" rows="3">{{ old('excerpt', @$news->excerpt) }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">
                            <label>@lang('Nội dung')</label>
                        </div>
                        <div class="col-md-10">
                            <textarea rows="8" class="form-control ckeditor" name="content">{!! old('content', @$news->content) !!}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card p-2 my-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Ảnh Đại Diện')</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-2">
                            <label>@lang('Hình ảnh')</label>
                        </div>
                        <div class="col-md-10">
                            <x-image-uploader class="w-50" type="news" :image="@$news->featured_image" :name="'featured_image'" :required="!@$news" />
                            <small class="form-text text-muted">
                                <i class="las la-info-circle"></i> @lang('Kích thước đề nghị: 1200x630px')
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn w-100 h-45 btn--primary">@lang('Submit')</button>
                </div>
            </div>
        </form>
    </div>
</div>

<x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
<x-back route="{{ route('admin.news.index') }}" />
@endpush

@push('script')
<script>
    'use strict';
    (function($) {
        $('.select2-basic').select2();

    })(jQuery)
</script>
@endpush
