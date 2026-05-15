@extends('admin.layouts.app')
@section('panel')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('User')</th>
                                    <th>@lang('Role')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Mobile')</th>
                                    {{-- <th>@lang('Location')</th> --}}
                                    <th>@lang('Joined At')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $users->firstItem() + $loop->index }}</td>
                                        <td>
                                            <span class="fw-bold">{{ $user->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a
                                                    href="{{ route('admin.users.detail', $user->id) }}"><span>@</span>{{ $user->username }}</a>
                                            </span>
                                        </td>
                                        <td>
                                            @if($user->is_seller)
                                                <span class="badge badge--primary">@lang('Seller')</span>
                                            @else
                                                <span class="badge badge--success">@lang('Customer')</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->mobileNumber }}</td>
                                        {{-- <td>
                                            @if($user->province)
                                            <span class="fw-bold">{{ $user->province->name }}</span>
                                            @else
                                            <span class="text--muted">N/A</span>
                                            @endif
                                        </td> --}}
                                        <td>
                                            {{ showDateTime($user->created_at) }} <br> {{ diffForHumans($user->created_at) }}
                                        </td>
                                        <td>
                                            @if ($user->status == 1)
                                                <span class="badge badge--success">@lang('Active')</span>
                                            @else
                                                <span class="badge badge--danger">@lang('Banned')</span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.users.detail', $user->id) }}"
                                                    class="btn btn-sm btn-outline--primary">
                                                    <i class="las la-desktop"></i> @lang('Details')
                                                </a>
                                                @if ($user->status == 1)
                                                    <button type="button" class="btn btn-sm btn-outline--danger userStatus"
                                                        data-id="{{ $user->id }}" data-status="{{ $user->status }}"
                                                        data-bs-toggle="modal" data-bs-target="#userStatusModal">
                                                        <i class="las la-ban"></i> @lang('Ban')
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-outline--success userStatus"
                                                        data-id="{{ $user->id }}" data-status="{{ $user->status }}"
                                                        data-ban_reason="{{ $user->ban_reason }}" data-bs-toggle="modal"
                                                        data-bs-target="#userStatusModal">
                                                        <i class="las la-undo"></i> @lang('Unban')
                                                    </button>
                                                @endif
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
                @if ($users->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($users) }}
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- User Status Modal --}}
    <div id="userStatusModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <span class="modal-title-text"></span>
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="ban-reason-view">
                            <p><span>@lang('Ban reason was'):</span></p>
                            <p class="ban-reason-text"></p>
                            <h4 class="text-center mt-3">@lang('Are you sure to unban this user?')</h4>
                        </div>
                        <div class="ban-reason-input">
                            <h6 class="mb-2">@lang('If you ban this user he/she won\'t able to access his/her dashboard.')
                            </h6>
                            <div class="form-group">
                                <label>@lang('Reason')</label>
                                <textarea class="form-control" name="reason" rows="4" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function ($) {
            "use strict";
            $('.userStatus').on('click', function () {
                var modal = $('#userStatusModal');
                var id = $(this).data('id');
                var status = $(this).data('status');
                var banReason = $(this).data('ban_reason');
                var url = "{{ route('admin.users.status', '') }}/" + id;

                modal.find('form').attr('action', url);

                if (status == 1) {
                    modal.find('.modal-title-text').text("@lang('Ban User')");
                    modal.find('.ban-reason-input').show();
                    modal.find('.ban-reason-input textarea').attr('required', true);
                    modal.find('.ban-reason-view').hide();
                } else {
                    modal.find('.modal-title-text').text("@lang('Unban User')");
                    modal.find('.ban-reason-input').hide();
                    modal.find('.ban-reason-input textarea').attr('required', false);
                    modal.find('.ban-reason-view').show();
                    modal.find('.ban-reason-text').text(banReason);
                }
            });
        })(jQuery);
    </script>
@endpush

@push('breadcrumb-plugins')
    <div class="d-flex flex-wrap justify-content-end gap-2 align-items-center">
        <form action="" method="GET">
            <div class="input-group">
                <select name="role" class="form-control" onchange="this.form.submit()">
                    <option value="">Loại tài khoản</option>
                    <option value="customer" @selected(request()->role == 'customer')>@lang('Customer')</option>
                    <option value="seller" @selected(request()->role == 'seller')>@lang('Seller')</option>
                </select>
            </div>
        </form>
        <x-search-form placeholder="Username / Email" />
    </div>
@endpush