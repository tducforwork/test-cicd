@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('STT')</th>
                                    <th>@lang('Tên danh mục')</th>
                                    <th>@lang('Slug')</th>
                                    <th>@lang('Số bài viết')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="name">{{ __($category->name) }}</span>
                                        </td>
                                        <td>
                                            <code>{{ $category->slug }}</code>
                                        </td>
                                        <td>
                                            <span class="badge bg--primary">{{ $category->news_count }}</span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary editBtn"
                                                data-id="{{ $category->id }}" 
                                                data-name="{{ $category->name }}">
                                                <i class="la la-pencil"></i> @lang('Edit')
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                                data-action="{{ route('admin.news.category.delete', $category->id) }}"
                                                data-question="@lang('Are you sure to delete this category?')">
                                                <i class="la la-trash"></i> @lang('Delete')
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage ?? 'Chưa có danh mục nào') }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($categories->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($categories) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Add/Edit Modal --}}
    <div id="categoryModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Tên danh mục')</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <button type="button" class="btn btn-sm btn-outline--primary addBtn"><i class="las la-plus"></i>@lang('Add New')</button>
    <x-back route="{{ route('admin.news.index') }}" />
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            const modal = $('#categoryModal');
            const action = `{{ route('admin.news.category.store') }}`;

            $('.addBtn').on('click', function() {
                modal.find('.modal-title').text("@lang('Thêm Danh Mục')");
                modal.find('form').attr('action', `${action}/0`);
                modal.find('form')[0].reset();
                modal.modal('show');
            });

            $('.editBtn').on('click', function() {
                let data = $(this).data();
                modal.find('.modal-title').text("@lang('Sửa Danh Mục')");
                modal.find('form').attr('action', `${action}/${data.id}`);
                modal.find('[name=name]').val(data.name);
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush
