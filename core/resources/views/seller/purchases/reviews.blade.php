@extends('seller.layouts.app')
@section('seller-content')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--md table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('Products')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $item)
                            <tr>
                                <td>
                                    <div class="user">
                                        <div class="thumb">
                                            <img src="{{ getImage(getFilePath('product') . '/thumb_' . $item->main_image, getFileSize('product')) }}" alt="@lang('cart')">
                                        </div>
                                        <span class="name">{{ $item->name }}</span>
                                    </div>
                                </td>
                                <td data-label="@lang('Action')">
                                    @if ($item->userReview)
                                    <button type="button" class="btn btn-sm btn-outline--dark" disabled>@lang('Reviewed')</button>
                                    @else
                                    <button type="button" class="btn btn-sm btn-outline--primary review-btn" data-pid="{{ $item->id }}">@lang('Review Now')</button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="100%" class="text-center text-muted">
                                    {{ __($emptyMessage) }}
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($products->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($products) }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="reviewModal" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Add Review')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="{{ route('seller.purchases.product.review.submit') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="pid" value="">
                    <div class="form-group">
                        <label>@lang('Your Rating')</label>
                        <div class="rating-select d-flex gap-3">
                            @for($i=1; $i<=5; $i++)
                                <div class="form-check">
                                <input class="form-check-input" type="radio" name="rating" id="rating-{{$i}}" value="{{$i}}" required>
                                <label class="form-check-label" for="rating-{{$i}}">
                                    {{$i}} <i class="las la-star text--warning"></i>
                                </label>
                        </div>
                        @endfor
                    </div>
                </div>
                <div class="form-group">
                    <label>@lang('Review')</label>
                    <textarea name="review" class="form-control" rows="4" required></textarea>
                </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn--primary w-100">@lang('Submit Review')</button>
        </div>
        </form>
    </div>
</div>
</div>
@endsection

@push('script')
<script>
    "use strict";
    (function($) {
        $('.review-btn').on('click', function() {
            var modal = $('#reviewModal');
            modal.find('input[name=pid]').val($(this).data('pid'));
            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush