@php
    $subBannerElements = getContent('sub_banners.element', false, 4, true);
@endphp
<!-- SUB BANNERS -->
<section class="sub-banners-section py-lg-5 py-4">
    <div class="container">
        <div class="shopee-cat-wrap">
            <button class="shopee-cat-arrow sub-prev" aria-label="Trước">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button class="shopee-cat-arrow sub-next" aria-label="Sau">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
            <div class="sub-banners-slider swiper">
                <div class="swiper-wrapper">
                    @foreach($subBannerElements as $item)
                        <div class="sub-banner-item swiper-slide" onclick="openCoupon('{{ @$item->data_values->coupon_code }}')">
                            <div class="sub-banner-item-bg" style="
                                background: #1e293b
                                    url({{ getImage('assets/images/frontend/sub_banners/' . @$item->data_values->banner_image, '500x300') }})
                                    center/cover;
                                "></div>
                            <div>
                                <h3 style="color: white; margin-bottom: 10px">
                                    {{ __(@$item->data_values->title) }}
                                </h3>
                                <p style="font-size: 14px; opacity: 0.8; margin-bottom: 15px">
                                    {{ __(@$item->data_values->description) }}
                                </p>
                                <span style="
                                    font-size: 12px;
                                    font-weight: 700;
                                    color: var(--accent);
                                    text-transform: uppercase;
                                    cursor: pointer;
                                    ">{{ __(@$item->data_values->button_text) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@push('modal')
    <!-- COUPON POPUP -->
    <div class="coupon-overlay" id="couponOverlay">
        <div class="coupon-card">
            <i class="fa-solid fa-xmark close-coupon" onclick="closeCoupon()"></i>
            <div class="coupon-icon">
                <i class="fa-solid fa-gift"></i>
            </div>
            <h3 class="coupon-title" id="couponTitle">Voucher Độc Quyền</h3>
            <p class="coupon-desc">
                Sử dụng mã bên dưới khi thanh toán để nhận ưu đãi ngay lập tức!
            </p>
            <div class="coupon-box">
                <span class="coupon-code" id="couponCode"></span>
                <button class="btn-copy" onclick="copyCoupon()">Copy mã</button>
            </div>
            <p class="coupon-exp" id="couponExp">
                Hạn dùng: --/--/---- • Áp dụng cho mọi đơn hàng
            </p>
        </div>
    </div>
@endpush

@push('script')
    <script>
        function openCoupon(code) {
            if (!code) return;

            $.get("{{ route('get-coupon', '') }}/" + code, function (response) {
                if (response.success) {
                    $('#couponCode').text(response.coupon.coupon_code);
                    $('#couponTitle').text(response.coupon.name);
                    $('#couponExp').text('Hạn dùng: ' + response.formatted_end_date + ' • Áp dụng cho mọi đơn hàng');
                    $('#couponOverlay').addClass('active');
                } else {
                    notify('error', response.error);
                }
            });
        }

        function closeCoupon() {
            $('#couponOverlay').removeClass('active');
            // Reset copy button state
            $('.btn-copy').text('Copy mã').removeClass('copied');
        }

        function copyCoupon() {
            const code = $('#couponCode').text();
            const $btn = $('.btn-copy');

            navigator.clipboard.writeText(code).then(() => {
                $btn.text('Đã Copy!');
                $btn.addClass('copied');
                setTimeout(() => {
                    closeCoupon();
                }, 1000);
            });
        }

        $(document).on('keydown', function (e) {
            if (e.key === "Escape") closeCoupon();
        });

        $('#couponOverlay').on('click', function (e) {
            if (e.target === this) closeCoupon();
        });
    </script>
@endpush