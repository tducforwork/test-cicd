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
                                    <th>@lang('Duration')</th>
                                    <th>@lang('Discount')</th>
                                    <th>@lang('Categories')</th>
                                    <th>@lang('Products')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($promotions as $promotion)
                                    <tr>
                                        <td>{{ __($promotion->name) }}</td>
                                        <td>
                                            {{ showDateTime($promotion->start_date, 'd M, Y') }} - <br>
                                            {{ showDateTime($promotion->end_date, 'd M, Y') }}
                                        </td>
                                        <td>
                                            {{ showAmount($promotion->discount_value, currencyFormat:false) }} {{ $promotion->discount_type == 1 ? __(gs('cur_text')) : '%' }}
                                        </td>
                                        <td>
                                            <span class="badge badge--dark">{{ $promotion->categories_count }} @lang('Categories')</span>
                                        </td>
                                        <td>
                                            <span class="badge badge--info">{{ $promotion->products_count }} @lang('Products')</span>
                                        </td>
                                        <td>
                                            @php echo $promotion->statusBadge; @endphp
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.promotions.edit', $promotion->id) }}" class="btn btn-sm btn-outline--primary">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline--info confirmationBtn" data-action="{{ route('admin.promotions.status', $promotion->id) }}" data-question="@lang('Are you sure to change status of this promotion?')">
                                                    @if($promotion->status == 1)
                                                        <i class="la la-eye-slash"></i>@lang('Disable')
                                                    @else
                                                        <i class="la la-eye"></i>@lang('Enable')
                                                    @endif
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
                        </table>
                    </div>
                </div>
                @if($promotions->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($promotions) }}
                </div>
                @endif
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.promotions.create') }}" class="btn btn-sm btn-outline--primary"><i class="las la-plus"></i>@lang('Add New')</a>
@endpush
