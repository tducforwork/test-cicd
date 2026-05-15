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
                                    <th>@lang('Title')</th>
                                    <th>@lang('URL')</th>
                                    <th>Sắp xếp</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    @include('admin.menu.item.row', ['items' => [$item], 'level' => 0])
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SAVE MODAL --}}
    <div id="saveModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Menu Item')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.menu.item.store', $group->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Title')</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('URL')</label>
                            <input type="text" class="form-control" name="url">
                        </div>
                        <div class="form-group">
                            <label>@lang('Parent')</label>
                            <select name="parent_id" class="form-control" required>
                                <option value="0">@lang('No Parent (Root)')</option>
                                @foreach ($allParents as $parent)
                                    <option value="{{ $parent->id }}">{{ __($parent->title) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Sort Order')</label>
                            <input type="number" class="form-control" name="order" value="0" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Status')</label>
                            <select name="status" class="form-control" required>
                                <option value="1">@lang('Enable')</option>
                                <option value="0">@lang('Disable')</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Mega Menu')</label>
                            <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disable')" name="has_mega_menu" value="1">
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
    <a href="{{ route('admin.menu.group.index') }}" class="btn btn-sm btn-outline--dark">
        <i class="las la-undo"></i>@lang('Back to Groups')
    </a>
    <button type="button" class="btn btn-sm btn-outline--primary addBtn">
        <i class="las la-plus"></i>@lang('Add New')
    </button>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.addBtn').on('click', function() {
                var modal = $('#saveModal');
                modal.find('.modal-title').text("@lang('Add Menu Item')");
                modal.find('form').attr('action', "{{ route('admin.menu.item.store', $group->id) }}");
                modal.find('input[name=title]').val('');
                modal.find('input[name=url]').val('');
                modal.find('select[name=parent_id]').val(0);
                modal.find('input[name=order]').val(0);
                modal.find('select[name=status]').val(1);
                modal.find('input[name=has_mega_menu]').bootstrapToggle('off');
                modal.modal('show');
            });

            $('.editBtn').on('click', function() {
                var modal = $('#saveModal');
                var item = $(this).data();
                modal.find('.modal-title').text("@lang('Edit Menu Item')");
                modal.find('form').attr('action', "{{ route('admin.menu.item.store', $group->id) }}/" + item
                .id);
                modal.find('input[name=title]').val(item.title);
                modal.find('input[name=url]').val(item.url);
                modal.find('select[name=parent_id]').val(item.parent_id);
                modal.find('input[name=order]').val(item.order);
                modal.find('select[name=status]').val(item.status);

                if (item.has_mega_menu == 1) {
                    modal.find('input[name=has_mega_menu]').bootstrapToggle('on');
                } else {
                    modal.find('input[name=has_mega_menu]').bootstrapToggle('off');
                }

                // Prevent selecting itself as parent
                modal.find('select[name=parent_id] option').prop('disabled', false);
                modal.find('select[name=parent_id] option[value=' + item.id + ']').prop('disabled', true);

                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
