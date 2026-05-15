@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('STT')</th>
                                    <th>@lang('Tiêu đề')</th>
                                    <th>@lang('Danh mục')</th>
                                    <th>@lang('Lượt xem')</th>
                                    <th>@lang('Ngày đăng')</th>
                                    <th>@lang('Hiện trang chủ')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($news as $item)
                                    <tr>
                                        <td>{{ $news->firstItem() + $loop->index }}</td>
                                        <td>
                                            <span class="name fw-bold" @if(strlen($item->title) > 50) data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="{{ __($item->title) }}" @endif>
                                                {{ strLimit(__($item->title), 50) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($item->category)
                                                <span class="badge bg--primary">{{ __($item->category->name) }}</span>
                                            @else
                                                <span class="badge bg--secondary">@lang('Chưa phân loại')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted"><i class="la la-eye"></i>
                                                {{ number_format($item->view_count) }}</span>
                                        </td>
                                        <td>
                                            @if($item->published_at)
                                                {{ showDateTime($item->published_at, 'd/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <x-toggle-switch class="update_home_status" :checked="$item->is_show_home"
                                                data-id="{{ $item->id }}" />
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end flex-wrap gap-2">
                                                <a href="{{ route('admin.news.edit', $item->id) }}"
                                                    class="btn btn-sm btn-outline--primary">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </a>

                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline--dark dropdown-toggle" type="button"
                                                        id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                                        @lang('More')
                                                    </button>

                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                        <a target="_blank" href="{{ route('news.details', $item->slug) }}"
                                                            class="dropdown-item">
                                                            <i class="la la-eye"></i> @lang('Xem trước')
                                                        </a>

                                                        <a href="javascript:void(0)"
                                                            class="confirmationBtn dropdown-item text--danger"
                                                            data-action="{{ route('admin.news.delete') }}"
                                                            data-question="@lang('Are you sure to delete this news?')"
                                                            data-id="{{ $item->id }}">
                                                            <i class="la la-trash"></i> @lang('Delete')
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">
                                            {{ __($emptyMessage ?? 'Chưa có tin tức nào') }}
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($news->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($news) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.news.create') }}" class="btn btn-sm btn--primary"><i
            class="las la-plus"></i>@lang('Add New')</a>
    <a href="{{ route('admin.news.category.index') }}" class="btn btn-sm btn-outline--primary"><i
            class="las la-folder"></i>@lang('Danh mục')</a>
@endpush

@push('script')
    <script>
        (function ($) {
            "use strict";

            // Custom confirmation for delete
            $(document).on('click', '.confirmationBtn[data-id]', function () {
                var btn = $(this);
                var action = btn.data('action');
                var question = btn.data('question');
                var id = btn.data('id');

                if (confirm(question)) {
                    $.ajax({
                        url: action,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id
                        },
                        success: function (response) {
                            if (response.success) {
                                window.location.reload();
                            }
                        }
                    });
                }
            });

            $('.update_home_status').on('change', function () {
                var id = $(this).data('id');
                $.ajax({
                    url: `{{ route('admin.news.update.home.status', '') }}/${id}`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        notify(response.status, response.message);
                    }
                });
            });

        })(jQuery);
    </script>
@endpush