@php
    $reviews = $product->reviews()->latest()->take(5)->get();
    $totalReviews = $product->reviews_count;
@endphp

<div class="product-reviews mt-4">
    <h2 class="section-title-sm mb-4">@lang('Đánh giá từ khách hàng') ({{ $totalReviews }})</h2>

    <div id="reviews-list">
        @forelse($reviews as $review)
            @include($activeTemplate . 'partials.review_item', ['review' => $review])
        @empty
            <p class="text-muted text-center py-4 no-reviews-msg">@lang('Chưa có đánh giá nào cho sản phẩm này.')</p>
        @endforelse
    </div>

    @if($totalReviews > 5)
        <div class="text-center mb-4">
            <button id="btn-load-more-reviews" class="btn btn-outline-secondary btn-sm rounded-pill px-4">@lang('Xem thêm đánh giá')</button>
        </div>
    @endif

    <div class="product-reviews__form-wrapper">
        <h3 class="product-reviews__form-title">@lang('Viết đánh giá của bạn')</h3>
        <form id="productReviewForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <div class="product-reviews__form-group">
                <label class="product-reviews__form-label">@lang('Đánh giá của bạn') *</label>
                <div id="star-rating-select" class="product-reviews__star-select">
                    <i class="fa-solid fa-star star-opt" data-value="1"></i>
                    <i class="fa-solid fa-star star-opt" data-value="2"></i>
                    <i class="fa-solid fa-star star-opt" data-value="3"></i>
                    <i class="fa-solid fa-star star-opt" data-value="4"></i>
                    <i class="fa-solid fa-star star-opt" data-value="5"></i>
                </div>
                <input type="hidden" name="rating" id="selected-rating" value="5">
            </div>
            <div class="product-reviews__form-group">
                <textarea name="review" placeholder="@lang('Nhập nội dung đánh giá')..." rows="4"
                    class="product-reviews__textarea" required></textarea>
            </div>

            <div class="product-reviews__form-group d-none">
                <label class="product-reviews__form-label">@lang('Hình ảnh (nếu có)')</label>
                <input type="file" name="images[]" multiple class="form-control" accept="image/*">
            </div>

            <div class="product-reviews__form-row">
                <input type="text" name="name" placeholder="@lang('Họ tên') *"
                    value="{{ auth()->user() ? auth()->user()->fullname : '' }}"
                    class="product-reviews__input" required />
                <input type="text" name="phone" placeholder="@lang('Số điện thoại') *"
                    value="{{ auth()->user() ? auth()->user()->mobile : '' }}"
                    class="product-reviews__input" required />
            </div>
            <button type="submit" class="product-reviews__submit">@lang('Gửi đánh giá')</button>
        </form>
    </div>
</div>

@push('script')
    <script>
        (function ($) {
            "use strict";

            // Xử lý chọn sao
            $('.star-opt').on('mouseover', function () {
                let val = $(this).data('value');
                highlightStars(val);
            }).on('mouseout', function () {
                let val = $('#selected-rating').val();
                highlightStars(val);
            }).on('click', function () {
                let val = $(this).data('value');
                $('#selected-rating').val(val);
                highlightStars(val);
            });

            function highlightStars(val) {
                $('.star-opt').each(function () {
                    if ($(this).data('value') <= val) {
                        $(this).removeClass('fa-regular').addClass('fa-solid');
                    } else {
                        $(this).removeClass('fa-solid').addClass('fa-regular');
                    }
                });
            }

            // Gửi form đánh giá qua AJAX
            $('#productReviewForm').on('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                let $btn = $(this).find('button[type="submit"]');
                $btn.prop('disabled', true).text("@lang('Đang gửi')...");

                $.ajax({
                    url: "{{ route('product.review.store') }}",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.success) {
                            notify('success', response.success);
                            $('#productReviewForm')[0].reset();
                            $('#selected-rating').val(5);
                            highlightStars(5);
                            
                            // Chèn đánh giá mới vào container
                            if (response.html) {
                                $('#reviews-list').prepend(response.html);
                                $('.no-reviews-msg').addClass('d-none');
                            }
                        } else {
                            notify('error', response.error || "@lang('Có lỗi xảy ra')");
                        }
                        $btn.prop('disabled', false).text("@lang('Gửi đánh giá')");
                    },
                    error: function (xhr) {
                        let error = xhr.responseJSON ? xhr.responseJSON.message : "@lang('Gửi đánh giá thất bại')";
                        notify('error', error);
                        $btn.prop('disabled', false).text("@lang('Gửi đánh giá')");
                    }
                });
            });
        })(jQuery);
    </script>
@endpush