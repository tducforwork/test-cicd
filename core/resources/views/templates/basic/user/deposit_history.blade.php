@extends($activeTemplate . 'layouts.frontend')
@section('content')
<div class="bg-[#F7F7F7]">
    <main class="container mx-auto pb-32 pt-10">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar -->
            <aside class="w-full lg:w-[312px] shrink-0">
                @include($activeTemplate . 'user.partials.sidebar')
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 min-w-0">
                <!-- Table Card -->
                <div class="bg-white rounded-[8px] p-6">
                    <h2 class="text-xl md:text-[20px] font-bold text-[#272343] mb-6">@lang('Deposit History')</h2>

                    <!-- Desktop Table -->
                    <div class="hidden md:block">
                        <div class="flex flex-col gap-4">
                            @forelse($deposits as $k => $deposit)
                            <div class="flex items-center justify-between p-5 border rounded-[12px] border-[#E6E6E6] hover:bg-gray-50 transition-colors bg-white">
                                <div class="w-[15%]">
                                    <span class="text-[#272343] text-[14px] font-bold leading-[20px]">{{ $deposit->trx }}</span>
                                </div>
                                <div class="w-[15%] text-center">
                                    <span class="text-[#6B7280] text-[14px] font-medium">{{ __(@$deposit->gateway->name ?? 'Cash On Delivery') }}</span>
                                </div>
                                <div class="w-[20%] text-center">
                                    <div class="flex flex-col gap-1 justify-center">
                                        <span class="text-[#FF6F0F] text-[14px] font-bold">{{ showAmount($deposit->final_amount, currencyFormat: false) }} {{ __($deposit->method_currency) }}</span>
                                    </div>
                                </div>
                                <div class="w-[15%] text-center">
                                    @php echo $deposit->statusBadge @endphp
                                </div>
                                <div class="w-[20%] text-center">
                                    <span class="text-[#6B7280] text-[14px]">{{ showDateTime($deposit->created_at, 'd M, Y h:i A') }}</span>
                                </div>
                                <div class="text-right">
                                    @php
                                        $details = [];
                                        if ($deposit->method_code >= 1000 && $deposit->method_code <= 5000) {
                                            foreach (@$deposit->detail ?? [] as $key => $info) {
                                                $details[] = $info;
                                                if ($info->type == 'file') {
                                                    $details[$key]->value = route('user.download.attachment', encrypt(getFilePath('verify') . '/' . $info->value));
                                                }
                                            }
                                        }
                                    @endphp

                                    @if ($deposit->method_code >= 1000 && $deposit->method_code <= 5000)
                                        <button type="button" class="detail-btn text-[#2563EB] font-bold text-[14px] hover:underline" data-info="{{ json_encode($details) }}" @if ($deposit->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $deposit->admin_feedback }}" @endif>
                                            @lang('View')
                                        </button>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-green-600 font-medium text-[14px]">
                                            <i class="las la-check"></i> @lang('Complete')
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="py-12 text-center text-gray-400 border border-[#E6E6E6] rounded-[12px]">
                                {{ __($emptyMessage) }}
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="md:hidden space-y-4">
                        @forelse($deposits as $k => $deposit)
                        <div class="border border-gray-200 rounded-xl p-4 space-y-3">
                            @php
                                $details = [];
                                if ($deposit->method_code >= 1000 && $deposit->method_code <= 5000) {
                                    foreach (@$deposit->detail ?? [] as $key => $info) {
                                        $details[] = $info;
                                        if ($info->type == 'file') {
                                            $details[$key]->value = route('user.download.attachment', encrypt(getFilePath('verify') . '/' . $info->value));
                                        }
                                    }
                                }
                            @endphp
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="font-bold text-gray-800 text-[14px]">{{ $deposit->trx }}</span>
                                    <div class="text-[12px] text-[#6B7280] mt-1">{{ __(@$deposit->gateway->name ?? 'Cash On Delivery') }}</div>
                                    @php echo $deposit->statusBadge @endphp
                                </div>
                                <span class="text-[12px] text-[#6B7280]">{{ showDateTime($deposit->created_at, 'd M, Y') }}</span>
                            </div>
                            <div class="text-sm">
                                <span class="text-[#6B7280]">{{ showAmount(1) }} = {{ showAmount($deposit->rate, currencyFormat: false) }} {{ __($deposit->method_currency) }}</span>
                                <div class="font-bold text-[#FF6F0F] text-[16px]">{{ showAmount($deposit->final_amount, currencyFormat: false) }} {{ __($deposit->method_currency) }}</div>
                            </div>
                            @if ($deposit->method_code >= 1000 && $deposit->method_code <= 5000)
                                <button type="button" class="detail-btn block text-center bg-blue-50 text-[#2563EB] font-bold text-sm py-2 rounded-lg hover:bg-blue-100 transition-colors w-full" data-info="{{ json_encode($details) }}" @if ($deposit->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $deposit->admin_feedback }}" @endif>
                                    @lang('View Details')
                                </button>
                            @else
                                <div class="flex items-center justify-center gap-1 text-green-600 font-medium text-sm py-2 bg-green-50 rounded-lg">
                                    <i class="las la-check"></i> @lang('Complete')
                                </div>
                            @endif
                        </div>
                        @empty
                        <div class="py-12 text-center text-gray-400">
                            {{ __($emptyMessage) }}
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($deposits->hasPages())
                    <div class="mt-6">
                        {{ paginateLinks($deposits) }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>

{{-- Detail MODAL --}}
<div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content rounded-[12px] overflow-hidden">
            <div class="modal-header bg-[#272343] text-white px-6 py-4">
                <h5 class="modal-title font-semibold">@lang('Transaction Details')</h5>
                <button type="button" class="text-white hover:text-gray-200 transition-colors" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times text-xl"></i>
                </button>
            </div>
            <div class="modal-body p-6">
                <ul class="list-group divide-y divide-gray-100 rounded-lg border border-gray-100 overflow-hidden">
                </ul>
                <div class="feedback mt-4"></div>
            </div>
            <div class="modal-footer border-t border-gray-100 px-6 py-4">
                <button type="button" class="bg-[#272343] text-white px-6 py-2.5 rounded-[8px] font-medium hover:opacity-90 transition-opacity" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    (function($) {
        "use strict";

        // Detail button click
        $(document).on('click', '.detail-btn', function() {
            var modal = $('#detailModal');
            var userData = $(this).data('info');
            var html = '';

            if (userData && userData.length > 0) {
                userData.forEach(function(element) {
                    if (element.type != 'file') {
                        html += `
                        <li class="d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-[#6B7280] text-sm">${element.name}</span>
                            <span class="text-[#272343] text-sm font-medium">${element.value}</span>
                        </li>`;
                    } else {
                        html += `
                        <li class="d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-[#6B7280] text-sm">${element.name}</span>
                            <a href="${element.value}" class="text-[#FF6F0F] text-sm font-medium hover:underline flex items-center gap-1">
                                <i class="las la-file text-sm"></i> @lang('Attachment')
                            </a>
                        </li>`;
                    }
                });
            } else {
                html = '<li class="px-4 py-6 text-center text-[#6B7280] text-sm">@lang("No additional details available")</li>';
            }

            modal.find('.modal-body ul.list-group').html(html);

            if ($(this).data('admin_feedback') != undefined) {
                var adminFeedback = `
                    <div class="bg-red-50 border border-red-100 rounded-lg p-4 mt-4">
                        <strong class="text-red-600 text-sm font-medium">@lang('Admin Feedback')</strong>
                        <p class="text-red-500 text-sm mt-1">${$(this).data('admin_feedback')}</p>
                    </div>
                `;
            } else {
                var adminFeedback = '';
            }

            modal.find('.feedback').html(adminFeedback);
            modal.modal('show');
        });

        // Tooltip initialization
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title], [data-title], [data-bs-title]'))
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

    })(jQuery);
</script>
@endpush
