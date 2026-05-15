<!-- Review Modal -->
<div id="review-modal" class="fixed inset-0 z-[100] flex items-center justify-center group ">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black/50 backdrop-blur-[2px] transition-opacity duration-300 opacity-0 group-[.review-modal-active]:opacity-100"
        onclick="toggleReviewModal(false)"></div>

    <!-- Modal Content -->
    <div
        class="review-modal-content p-6 relative bg-white w-full max-w-[800px] rounded-xl shadow-2xl flex flex-col mx-4 overflow-hidden">

        <!-- Header -->
        <div class="flex items-center justify-between  border-b border-gray-100 pb-4">
            <h2 class="text-[20px] font-bold text-[#272343] m-0 " style="line-height: normal">@lang('Review Product')</h2>
            <div class="w-8 h-8 flex items-center justify-center bg-gray-50 rounded-full cursor-pointer hover:bg-gray-100 transition-colors"
                onclick="toggleReviewModal(false)">
                <i class="las la-times text-lg"></i>
            </div>
        </div>

        <!-- Body -->
        <div class=" flex flex-col gap-6 overflow-y-auto max-h-[80vh] pt-4">

            <!-- Rating Selection -->
            <div class="flex items-center gap-4">
                <p class="font-medium text-[#272343] text-[20px] m-0">@lang('Choose your rating'):</p>
                <div class="flex items-center gap-1">
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="lar la-star text-2xl text-orange-400 cursor-pointer hover:text-orange-500 star-rating"
                            data-rating="{{ $i }}"></i>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="rating-value" value="0">
            </div>

            <!-- User Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex flex-col gap-2">
                    <label class=" font-medium text-[#272343]">@lang('Name')</label>
                    <input type="text" name="name" id="review-name" placeholder="Dianne"
                        class="w-full px-4 py-3 !bg-white rounded-lg !border !border-[#E6E6E6] !outline-none transition-all placeholder:text-gray-400"
                        style="border: 1px solid var(--Gray-Scale-Gray-100, #E6E6E6);">
                </div>
                <div class="flex flex-col gap-2">
                    <label class=" font-medium text-[#272343]">@lang('Phone Number')</label>
                    <input type="tel" name="phone" id="review-phone" placeholder="09..."
                        class="w-full px-4 py-3 !bg-white rounded-lg !border !border-[#E6E6E6] !outline-none transition-all placeholder:text-gray-400"
                        style="border: 1px solid var(--Gray-Scale-Gray-100, #E6E6E6);">
                </div>
            </div>

            <!-- Review Content -->
            <div class="flex flex-col gap-2">
                <label class=" font-medium text-[#272343]">@lang('Write a review')</label>
                <textarea name="review" id="review-content" rows="4" placeholder="..."
                    class="w-full px-4 py-3 !bg-white rounded-lg !border !border-[#E6E6E6] !outline-none transition-all placeholder:text-gray-400 resize-none"
                    style="border: 1px solid var(--Gray-Scale-Gray-100, #E6E6E6);
"></textarea>
            </div>

            <!-- Media Upload -->
            <button type="button" onclick="$('#review-images').click()"
                class="flex items-center w-full justify-center gap-2 py-4 border !border-[#E6E6E6] rounded-xl text-[#1b1b1b] hover:bg-gray-50 transition-all group/upload"
                style="max-width:356px">
                <div
                    class="w-10 h-10 flex items-center justify-center bg-gray-100 rounded-full group-hover/upload:bg-gray-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23"
                        fill="none">
                        <path
                            d="M11.5 15.3333C13.0878 15.3333 14.375 14.0461 14.375 12.4583C14.375 10.8705 13.0878 9.58331 11.5 9.58331C9.91218 9.58331 8.625 10.8705 8.625 12.4583C8.625 14.0461 9.91218 15.3333 11.5 15.3333Z"
                            stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M11.5 3.83331H9.58333C9.32917 3.83331 9.08541 3.93428 8.90569 4.114C8.72597 4.29372 8.625 4.53748 8.625 4.79165C8.625 5.29998 8.42307 5.78749 8.06362 6.14693C7.70418 6.50638 7.21666 6.70831 6.70833 6.70831H4.79167C4.28334 6.70831 3.79582 6.91025 3.43638 7.26969C3.07693 7.62914 2.875 8.11665 2.875 8.62498V17.25C2.875 17.7583 3.07693 18.2458 3.43638 18.6053C3.79582 18.9647 4.28334 19.1666 4.79167 19.1666H18.2083C18.7167 19.1666 19.2042 18.9647 19.5636 18.6053C19.9231 18.2458 20.125 17.7583 20.125 17.25V10.5416"
                            stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M14.375 5.75H20.125" stroke="black" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M17.25 2.875V8.625" stroke="black" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
                <span class="font-medium text-[16px]">@lang('Share a video or photo')</span>
            </button>
            <input type="file" name="images[]" id="review-images" class="hidden" multiple accept="image/*">
            <div id="image-preview" class="flex flex-wrap gap-2"></div>

            <!-- Captcha Placeholder -->
            {{-- <div
                class="w-full max-w-[300px] border border-gray-200 rounded p-3 bg-gray-50/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 border-2 border-gray-300 bg-white rounded"></div>
                    <span class="text-sm text-gray-600">I'm not a robot</span>
                </div>
                <div class="flex flex-col items-center">
                    <img src="https://www.gstatic.com/recaptcha/api2/logo_48.png" class="w-6 h-6 opacity-30 grayscale"
                        alt="captcha">
                    <span class="text-[8px] text-gray-400">reCAPTCHA</span>
                </div>
            </div> --}}

            <!-- Submit Button -->
            <button type="button" id="submit-review"
                class="w-fit !px-[18px] !py-[10px] !h-auto bg-[#272343] text-white font-semibold rounded-lg hover:brightness-125 transition-all shadow-lg active:scale-95">
                @lang('Submit')
            </button>
        </div>
    </div>
</div>

@push('script')
    <script>
        function toggleReviewModal(show) {
            const modal = document.getElementById('review-modal');
            if (show) {
                modal.classList.add('review-modal-active');
                document.body.classList.add('overflow-hidden');
            } else {
                modal.classList.remove('review-modal-active');
                document.body.classList.remove('overflow-hidden');
            }
        }
    </script>
@endpush

<style>
    #review-modal:not(.review-modal-active) {
        display: none !important;
    }

    .review-modal-active {
        display: flex !important;
    }

    .review-modal-content {
        opacity: 0;
        transform: scale(0.95);
        transition: all 0.3s ease-in-out;
    }

    .review-modal-active .review-modal-content {
        opacity: 1 !important;
        transform: scale(1) !important;
    }

    .review-modal-active .group-\[\.review-modal-active\]\:opacity-100 {
        opacity: 1 !important;
    }
</style>
