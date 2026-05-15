@extends($activeTemplate . 'layouts.frontend')
@section('content')
<div class="bg-[#F7F7F7]">
    <main class="container mx-auto px-4 lg:px-8 xl:px-16 pb-32 pt-10">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar -->
            <aside class="w-full lg:w-[312px] shrink-0">
                @include($activeTemplate . 'user.partials.sidebar')
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-4 md:gap-6 mb-6">
                    <a href="{{ url()->previous() }}" class="shrink-0 flex items-center justify-center w-10 h-10 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 text-[#272343]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <h1 class="font-semibold text-[#272343] text-xl md:text-2xl leading-normal">Up as Seller</h1>
                </div>

                <!-- Info Card -->
                <div class="bg-white rounded-[12px] p-6 mb-6 border border-gray-100">
                    <div class="text-center mb-8">
                        <div class="w-20 h-20 mx-auto bg-[#fff6f0] rounded-full flex items-center justify-center mb-4">
                            <i class="las la-store-alt text-4xl text-[#FF6F0F]"></i>
                        </div>
                        <h2 class="text-xl md:text-2xl font-bold text-[#272343] mb-2">Mở rộng kinh doanh của bạn</h2>
                        <p class="text-[#6b7280] text-sm md:text-base max-w-md mx-auto">
                            Bằng cách nâng cấp lên tài khoản người bán, bạn có thể đăng bán sản phẩm của mình trên hệ thống của chúng tôi và tiếp cận hàng ngàn khách hàng.
                        </p>
                        @if(gs('seller_registration_fee') > 0)
                        <div class="inline-block mt-4 px-4 py-2 bg-[#fff6f0] rounded-lg">
                            <span class="text-sm text-[#6b7280]">Phí đăng ký:</span>
                            <span class="text-lg font-bold text-[#FF6F0F] ml-2">{{ showAmount(gs('seller_registration_fee')) }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Benefits -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-[#272343] mb-4">Quyền lợi khi là Người bán:</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="flex items-center gap-3 p-3 bg-[#f9fafb] rounded-lg">
                                <div class="w-8 h-8 bg-[#dcfce7] rounded-full flex items-center justify-center shrink-0">
                                    <i class="las la-check text-[#16a34a]"></i>
                                </div>
                                <span class="text-sm text-[#272343]">Đăng bán không giới hạn sản phẩm</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-[#f9fafb] rounded-lg">
                                <div class="w-8 h-8 bg-[#dcfce7] rounded-full flex items-center justify-center shrink-0">
                                    <i class="las la-check text-[#16a34a]"></i>
                                </div>
                                <span class="text-sm text-[#272343]">Quản lý đơn hàng chuyên nghiệp</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-[#f9fafb] rounded-lg">
                                <div class="w-8 h-8 bg-[#dcfce7] rounded-full flex items-center justify-center shrink-0">
                                    <i class="las la-check text-[#16a34a]"></i>
                                </div>
                                <span class="text-sm text-[#272343]">Rút tiền doanh thu nhanh chóng</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-[#f9fafb] rounded-lg">
                                <div class="w-8 h-8 bg-[#dcfce7] rounded-full flex items-center justify-center shrink-0">
                                    <i class="las la-check text-[#16a34a]"></i>
                                </div>
                                <span class="text-sm text-[#272343]">Hỗ trợ công cụ marketing cơ bản</span>
                            </div>
                        </div>
                    </div>

                    <!-- Form -->
                    <form action="{{ route('user.become.seller') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="terms" required class="w-5 h-5 rounded border-gray-300 text-[#FF6F0F] focus:ring-[#FF6F0F]">
                                <span class="text-sm text-[#6b7280]">Tôi đồng ý với các điều khoản và điều kiện dành cho người bán.</span>
                            </label>
                        </div>
                        <button type="submit" class="bg-[#FF6F0F] text-white px-8 py-3 rounded-[12px] font-bold text-sm shadow-sm hover:bg-orange-600 transition-colors flex items-center gap-2">
                            <i class="las la-rocket text-lg"></i>
                            Xác nhận Nâng cấp ngay
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('script')
<script>
    'use strict';
    (function($) {
        $('.ajax-form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = form.find('button[type=submit]');
            var btnText = btn.html();
            var formData = new FormData(this);

            btn.prop('disabled', true).html('<i class="las la-spinner la-spin"></i> Processing...');

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status == 'success') {
                        notify('success', response.message);
                        if (response.redirect_url) {
                            setTimeout(() => window.location.href = response.redirect_url, 1500);
                        }
                    } else {
                        notify('error', response.message);
                    }
                },
                error: function(xhr) {
                    var response = xhr.responseJSON;
                    if (response && response.errors) {
                        $.each(response.errors, function(key, value) {
                            notify('error', value[0]);
                        });
                    } else if (response && response.message) {
                        notify('error', response.message);
                    } else {
                        notify('error', 'Something went wrong. Please try again.');
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).html(btnText);
                }
            });
        });
    })(jQuery);
</script>
@endpush