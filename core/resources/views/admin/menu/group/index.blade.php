@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('Name')</th>
                                <th>@lang('Location')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($groups as $group)
                                <tr>
                                    <td>{{ __($group->name) }}</td>
                                    <td>{{ $group->location }}</td>
                                    <td>
                                        @php echo $group->statusBadge; @endphp
                                    </td>
                                    <td>
                                        <div class="button--group">
                                            <a href="{{ route('admin.menu.item.index', $group->id) }}" class="btn btn-sm btn-outline--primary">
                                                <i class="la la-list"></i> @lang('Manage Items')
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline--primary editBtn"
                                                    data-id="{{ $group->id }}"
                                                    data-name="{{ $group->name }}"
                                                    data-location="{{ $group->location }}"
                                                    data-status="{{ $group->status }}">
                                                <i class="la la-pencil"></i> @lang('Edit')
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                                    data-action="{{ route('admin.menu.group.delete', $group->id) }}"
                                                    data-question="@lang('Are you sure the delete this menu group?')">
                                                <i class="la la-trash"></i> @lang('Delete')
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table><!-- table end -->
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

    {{-- SAVE MODAL --}}
    <div id="saveModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Menu Group')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.menu.group.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Location (Slug)')</label>
                            <input type="text" class="form-control" name="location" required>
                            <small class="text--small text-muted"><i class="las la-info-circle"></i> @lang('Lowercase without spaces, e.g., main_header')</small>
                        </div>
                        <div class="form-group">
                            <label>@lang('Status')</label>
                            <select name="status" class="form-control" required>
                                <option value="1">@lang('Enable')</option>
                                <option value="0">@lang('Disable')</option>
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
    <button type="button" class="btn btn-sm btn-outline--primary addBtn">
        <i class="las la-plus"></i>@lang('Add New')
    </button>
@endpush

@push('script')
    <script>
        (function ($) {
            "use strict";

            $('.addBtn').on('click', function () {
                var modal = $('#saveModal');
                modal.find('.modal-title').text("@lang('Add Menu Group')");
                modal.find('form').attr('action', "{{ route('admin.menu.group.store') }}");
                modal.find('input[name=name]').val('');
                modal.find('input[name=location]').val('');
                modal.find('select[name=status]').val(1);
                modal.modal('show');
            });

            $('.editBtn').on('click', function () {
                var modal = $('#saveModal');
                var group = $(this).data();
                modal.find('.modal-title').text("@lang('Edit Menu Group')");
                modal.find('form').attr('action', "{{ route('admin.menu.group.store', '') }}/" + group.id);
                modal.find('input[name=name]').val(group.name);
                modal.find('input[name=location]').val(group.location);
                modal.find('select[name=status]').val(group.status);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
