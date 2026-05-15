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
                                    <th>@lang('Name')</th>
                                    <th>@lang('Options Count')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Order')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($groups as $group)
                                    <tr>
                                        <td>{{ __($group->name) }}</td>
                                        <td>{{ $group->options_count }}</td>
                                        <td>
                                            @php echo $group->status ? '<span class="badge badge--success">'.trans('Active').'</span>' : '<span class="badge badge--danger">'.trans('Inactive').'</span>' @endphp
                                        </td>
                                        <td>{{ $group->sort_order }}</td>
                                        <td>
                                            <a href="{{ route('admin.filter.options', $group->id) }}" class="btn btn-sm btn-outline--info"><i class="la la-list"></i> @lang('Manage Options')</a>
                                            <button type="button" class="btn btn-sm btn-outline--primary cuModalBtn" data-resource="{{ $group }}" data-modal_title="@lang('Update Filter Group')"><i class="la la-pencil"></i> @lang('Edit')</button>
                                            <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.filter.delete', $group->id) }}" data-question="@lang('Are you sure to delete this filter group?')"><i class="la la-trash"></i> @lang('Delete')</button>
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
                @if ($groups->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($groups) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Add/Update Modal --}}
    <div id="cuModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.filter.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Sort Order')</label>
                            <input type="number" class="form-control" name="sort_order" value="0" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Status')</label>
                            <select name="status" class="form-control" required>
                                <option value="1">@lang('Active')</option>
                                <option value="0">@lang('Inactive')</option>
                            </select>
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
    <button type="button" class="btn btn-outline--primary cuModalBtn" data-modal_title="@lang('Add New Filter Group')">
        <i class="las la-plus"></i>@lang('Add New')
    </button>
@endpush
