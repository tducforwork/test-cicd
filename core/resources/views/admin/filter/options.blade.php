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
                                    <th>@lang('Value')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($options as $option)
                                    <tr>
                                        <td>{{ __($option->value) }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary cuModalBtn" data-resource="{{ $option }}" data-modal_title="@lang('Update Option')"><i class="la la-pencil"></i> @lang('Edit')</button>
                                            <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.filter.options.delete', $option->id) }}" data-question="@lang('Are you sure to delete this option?')"><i class="la la-trash"></i> @lang('Delete')</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($options->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($options) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Add Modal --}}
    <div id="addModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add New Option')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.filter.options.store', $group->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Value')</label>
                            <input type="text" class="form-control" name="value" required placeholder="@lang('e.g. Chính hãng, Xách tay, Mới 100%...')">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="cuModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Update Option')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="" method="POST" id="editForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Value')</label>
                            <input type="text" class="form-control" name="value" required>
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
    <a href="{{ route('admin.filter.index') }}" class="btn btn-outline--primary"><i class="la la-undo"></i> @lang('Back')</a>
    <button type="button" class="btn btn-outline--primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="las la-plus"></i>@lang('Add New')
    </button>
@endpush

@push('script')
    <script>
        (function($){
            "use strict";
            $('.cuModalBtn').on('click', function() {
                var resource = $(this).data('resource');
                $('#editForm').attr('action', `{{ route('admin.filter.options.update', '') }}/${resource.id}`);
                $('#editForm').find('[name=value]').val(resource.value);
            });
        })(jQuery);
    </script>
@endpush
