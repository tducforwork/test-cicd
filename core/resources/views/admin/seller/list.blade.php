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
                                    <th>@lang('Seller')</th>
                                    <th>@lang('Email') | @lang('Mobile')</th>
                                    <th>@lang('Products') | @lang('Sale')</th>
                                    <th>@lang('Balance')</th>
                                    <th>@lang('Is Featured')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sellers as $seller)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $seller->fullname }}</span> <br>
                                            <a href="{{ route('admin.sellers.detail', $seller->id) }}"> <span>@</span>{{ $seller->username }}</a>
                                        </td>

                                        <td>
                                            {{ $seller->email }}<br>{{ $seller->mobileNumber }}
                                        </td>

                                        <td>
                                            <span data-s-toggle="tooltip" title="@lang('Total Products')">{{ $seller->products->count() }}</span> <br>
                                            <span class="fw-bold" data-s-toggle="tooltip" title="@lang('Total Sale')">
                                                {{ $seller->totalSold() }} @lang('pcs')
                                            </span>
                                        </td>

                                        <td>
                                            <span class="fw-bold">
                                                {{ showAmount($seller->balance) }}
                                            </span>
                                        </td>

                                        <td>
                                            <x-toggle-switch class="change_status" :checked="$seller->is_featured" data-id="{{ $seller->id }}" />
                                        </td>

                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.sellers.detail', $seller->id) }}" class="btn btn--sm btn-outline--primary">
                                                    <i class="las la-desktop"></i>@lang('Details')
                                                </a>

                                                @if (request()->routeIs('admin.sellers.pending'))
                                                    <button class="btn btn-sm btn-outline--success confirmationBtn" data-action="{{ route('admin.sellers.approve', $seller->id) }}" data-question="@lang('Are you sure to approve this seller?')">
                                                        <i class="las la-check-circle"></i>@lang('Approve')
                                                    </button>
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.sellers.reject', $seller->id) }}" data-question="@lang('Are you sure to reject this seller?')">
                                                        <i class="las la-times-circle"></i>@lang('Reject')
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
                @if ($sellers->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($sellers) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.change_status').on('change', function() {
                let url = "{{ route('admin.sellers.feature', ':id') }}".replace(':id', $(this).data('id'));
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        _token: "{{ csrf_token() }}",
                        featured: $(this).is(':checked') ? 1 : 0
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            notify('success', response.message);
                        } else {
                            notify('error', response.message);
                        }
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
