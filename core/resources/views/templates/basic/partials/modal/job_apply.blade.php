<!-- Job Apply Modal -->
<div id="job-apply-modal" class="fixed inset-0 z-[100] flex items-center justify-center group">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black/50 backdrop-blur-[2px] transition-opacity duration-300 opacity-0 group-[.job-apply-modal-active]:opacity-100"
        onclick="toggleJobApplyModal(false)"></div>
    <!-- Modal Content -->
    <form id="job-apply-form" enctype="multipart/form-data" class="relative z-[101] w-full max-w-[800px] mx-4">
        @csrf
        <input type="hidden" name="job_id" id="apply-job-id" value="">
        <div
            class="job-apply-modal-content p-6 relative bg-white w-full rounded-xl shadow-2xl flex flex-col overflow-hidden">

            <!-- Header -->
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <h2 class="text-[20px] font-bold text-[#272343] m-0" style="line-height: normal">@lang('Form apply')
                </h2>
                <div class="w-8 h-8 flex items-center justify-center bg-gray-50 rounded-full cursor-pointer hover:bg-gray-100 transition-colors"
                    onclick="toggleJobApplyModal(false)">
                    <i class="las la-times text-lg"></i>
                </div>
            </div>

            <!-- Body -->
            <div class="flex flex-col gap-6 overflow-y-auto max-h-[80vh] pt-4 ">

                <!-- User Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-2">
                        <label class="font-medium text-[#272343]">@lang('Full Name')<span
                                class="text-[#CC0001]">*</span></label>
                        <input type="text" name="name" required placeholder="@lang('Dianne')"
                            class="w-full px-4 py-3 bg-[#F7F7F8] rounded-lg border border-[#E6E6E6] outline-none transition-all placeholder:text-gray-400">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-medium text-[#272343]">@lang('Phone Number')<span
                                class="text-[#CC0001]">*</span></label>
                        <input type="tel" name="phone" required placeholder="09..."
                            class="w-full px-4 py-3 bg-[#F7F7F8] rounded-lg border border-[#E6E6E6] outline-none transition-all placeholder:text-gray-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-2">
                        <label class="font-medium text-[#272343]">@lang('Email')<span
                                class="text-[#CC0001]">*</span></label>
                        <input type="email" name="email" required placeholder="example@mail.com"
                            class="w-full px-4 py-3 bg-[#F7F7F8] rounded-lg border border-[#E6E6E6] outline-none transition-all placeholder:text-gray-400">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-medium text-[#272343]">@lang('Address')<span
                                class="text-[#CC0001]">*</span></label>
                        <input type="text" name="address" required placeholder="@lang('Hanoi, Vietnam')"
                            class="w-full px-4 py-3 bg-[#F7F7F8] rounded-lg border border-[#E6E6E6] outline-none transition-all placeholder:text-gray-400">
                    </div>
                </div>

                <!-- CV Upload -->
                <div class="flex flex-col gap-2">
                    <label class="font-bold text-[#272343] text-lg">CV</label>
                    <div class="flex flex-col gap-3">
                        <button type="button" onclick="$('#cv-file-input').click()"
                            class="flex items-center justify-center gap-2 py-3 px-6 bg-[#272343] text-white rounded-lg hover:bg-[#322d56] transition-all w-fit">
                            <span class="uppercase font-bold tracking-wide">@lang('UPLOAD CV')</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="17 8 12 3 7 8" />
                                <line x1="12" y1="3" x2="12" y2="15" />
                            </svg>
                        </button>
                        <input type="file" name="cv_file" id="cv-file-input" class="hidden" accept=".pdf,.doc,.docx">
                        <p id="cv-filename" class="text-sm text-[#7A7A7A] italic"></p>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submit-application"
                    class="w-full  bg-[#FF6F0F] text-white font-bold rounded-full hover:bg-[#e6640d] transition-all shadow-lg active:scale-95 text-lg">
                    @lang('Apply')
                </button>
            </div>
        </div>
    </form>
</div>

@push('script')
    <script>
        function toggleJobApplyModal(show, jobId = null) {
            const modal = document.getElementById('job-apply-modal');
            if (show) {
                if (jobId) {
                    $('#apply-job-id').val(jobId);
                }
                modal.classList.add('job-apply-modal-active');
                document.body.classList.add('overflow-hidden');
            } else {
                modal.classList.remove('job-apply-modal-active');
                document.body.classList.remove('overflow-hidden');
            }
        }

        $(document).ready(function () {
            $('#cv-file-input').on('change', function () {
                const fileName = this.files[0] ? this.files[0].name : '';
                $('#cv-filename').text(fileName);
            });

            $('#job-apply-form').on('submit', function (e) {
                e.preventDefault();
                const $btn = $('#submit-application');
                const originalText = $btn.text();

                // Simple validation for file if needed
                if (!$('#cv-file-input')[0].files[0]) {
                    notify('error', 'Please upload your CV');
                    return;
                }

                const formData = new FormData(this);

                $btn.prop('disabled', true).text('Applying...');

                $.ajax({
                    url: "{{ route('job.apply') }}",
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.success) {
                            notify('success', response.success);
                            toggleJobApplyModal(false);
                            $('#job-apply-form')[0].reset();
                            $('#cv-filename').text('');
                        } else {
                            notify('error', response.error || 'Something went wrong');
                        }
                    },
                    error: function (xhr) {
                        const errors = xhr.responseJSON.errors;
                        if (errors) {
                            Object.values(errors).forEach(err => notify('error', err[0]));
                        } else {
                            notify('error', 'Failed to submit application');
                        }
                    },
                    complete: function () {
                        $btn.prop('disabled', false).text(originalText);
                    }
                });
            });
        });
    </script>
@endpush

<style>
    #job-apply-modal:not(.job-apply-modal-active) {
        display: none !important;
    }

    .job-apply-modal-active {
        display: flex !important;
    }

    .job-apply-modal-content {
        opacity: 0;
        transform: scale(0.95);
        transition: all 0.3s ease-in-out;
    }

    .job-apply-modal-active .job-apply-modal-content {
        opacity: 1 !important;
        transform: scale(1) !important;
    }

    .job-apply-modal-active .group-\[\.job-apply-modal-active\]\:opacity-100 {
        opacity: 1 !important;
    }
</style>