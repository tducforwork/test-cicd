@extends($activeTemplate . 'layouts.frontend')
@section('content')
<div class="bg-[#F7F7F7]">
    <main class="container mx-auto pb-32 pt-10">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar -->
            <aside class="w-full lg:w-[312px] shrink-0">
                @include($activeTemplate . 'user.partials.sidebar')
            </aside>

            <!-- Main Panel -->
            <div class="flex flex-col items-start gap-6 flex-1 min-w-0">
                <!-- Page Heading -->
                <div class="flex items-center gap-4 md:gap-6">
                    <a href="{{ url()->previous() }}" class="shrink-0 flex items-center justify-center w-10 h-10 rounded-[8px] border-[1px] border-[solid] border-[#D4D4D4] bg-[#FFF] [box-shadow:0_1px_2px_0_rgba(255,_255,_255,_0.40)_inset,_0_-1px_2px_0_rgba(0,_0,_0,_0.24)_inset,_0_1px_2px_0_rgba(0,_0,_0,_0.08)] hover:bg-gray-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M16.75 10C16.75 10.4142 16.4142 10.75 16 10.75L6.31078 10.75L9.0307 13.4696C9.32361 13.7625 9.32363 14.2374 9.03075 14.5303C8.73787 14.8232 8.263 14.8232 7.97009 14.5304L3.96969 10.5304C3.82902 10.3897 3.75 10.1989 3.75 10C3.75 9.80107 3.82902 9.6103 3.96969 9.46964L7.97009 5.46964C8.263 5.17676 8.73787 5.17679 9.03075 5.4697C9.32363 5.7626 9.32361 6.23748 9.0307 6.53036L6.31078 9.25L16 9.25C16.4142 9.25 16.75 9.58579 16.75 10Z" fill="#272343" />
                        </svg>
                    </a>
                    <h1 class="font-semibold text-[#272343] text-xl md:text-2xl leading-normal">Up as Seller</h1>
                </div>

                <!-- Content Card -->
                <div class="flex flex-col items-center gap-6 p-4 md:p-6 w-full bg-white rounded-lg">
                    <h2 class="w-full font-bold text-[#272343] text-xl leading-[30px]">@lang('Deposit to Account')</h2>
                    <p class="text-[#272343] text-[16px] font-normal leading-[150%]">
                        Lorem ipsum dolor sit amet consectetur. Donec urna tempus nulla massa scelerisque sed pretium massa pellentesque. Maecenas eget tellus semper feugiat volutpat sed. Mauris sit mi pharetra consequat urna turpis urna. Pellentesque nulla facilisi mauris dictumst. Massa tristique elit scelerisque enim viverra. At eu viverra enim malesuada risus rutrum ultrices. Convallis in turpis proin risus arcu sapien malesuada. Malesuada mi tincidunt lacus massa justo.
                    </p>
                    <!-- Pricing / QR row -->
                    <form action="{{ route('user.deposit.insert') }}" method="post" class="w-full">
                        @csrf
                        <input type="hidden" name="currency">

                        <div class="flex flex-col items-center md:items-start justify-between gap-6 p-4 md:p-6 w-full bg-[#fff6f0] rounded-[8px] mb-6">
                            <!-- Price Info -->
                            <div class="flex flex-col sm:flex-row gap-2 justify-between flex-1 w-full">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-[#272343] text-[16px] font-normal leading-[normal]">Up as Seller | Lorem ipsum dolor sit amet consectetur.</span>
                                </div>
                                <span class="text-[#FF383C] text-[28px] font-semibold leading-[32px]">
                                    {{ showAmount($order?->total_amount) }}
                                </span>
                            </div>

                            <!-- Payment Methods -->
                            <div class="flex flex-col items-center gap-1 shrink-0 w-full">
                                <div class="flex flex-col gap-4 md:gap-[24px] w-full">
                                    @foreach ($gatewayCurrency as $item)
                                    <label for="data-{{ $loop->index }}" class="payment-method-btn cursor-pointer flex items-center gap-2 p-3 rounded-lg border transition-all {{ $loop->first ? 'border-[#FF6F0F] bg-white' : 'border-gray-200 bg-white hover:border-[#FFB88C]' }}">
                                        <input value="{{ $item->method_code }}" id="data-{{ $loop->index }}" data-gateway="{{ $item }}" class="sr-only" type="radio" name="gateway" required {{ $loop->first ? 'checked' : '' }}>
                                        <div class="w-12 h-8 flex items-center justify-center">
                                            <img src="{{ getImage(getFilePath('gateway') . '/' . @$item->method->image, getFileSize('gateway')) }}" alt="{{ __($item->name) }}" class="max-h-8 object-contain">
                                        </div>
                                        <span class="text-xs font-medium text-[#272343] text-center">{{ __($item->name) }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <p class="text-[#272343] text-[16px] font-normal leading-[150%] mb-6">
                            Lorem ipsum dolor sit amet consectetur. Donec urna tempus nulla massa scelerisque sed pretium massa pellentesque. Maecenas eget tellus semper feugiat volutpat sed. Mauris sit mi pharetra consequat urna turpis urna. Pellentesque nulla facilisi mauris dictumst. Massa tristique elit scelerisque enim viverra. At eu viverra enim malesuada risus rutrum ultrices. Convallis in turpis proin risus arcu sapien malesuada. Malesuada mi tincidunt lacus massa justo.
                        </p>

                        <p class="gateway-conversion hidden text-sm text-[#6B7280] mb-4">
                            <span>@lang('Exchange Rate') </span>
                            <span class="exchange_rate fw-semibold"><span class="text"></span></span>
                        </p>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="bg-[#FF6F0F] text-white px-8 py-3 rounded-lg font-semibold text-base leading-[17.6px] hover:bg-orange-600 transition-colors flex items-center gap-2 shadow-sm">
                                <i class="las la-credit-card text-lg"></i>
                                Pay Now
                            </button>
                        </div>
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
        // Payment method selection
        $('[name=gateway]').on('change', function() {
            let gateway = $(this).data('gateway');
            
            // Update currency hidden input
            $('[name=currency]').val(gateway.currency);

            // Update button styles
            $('.payment-method-btn').removeClass('border-[#FF6F0F] bg-white').addClass('border-gray-200');
            $(this).closest('.payment-method-btn').removeClass('border-gray-200').addClass('border-[#FF6F0F] bg-white');

            $(".gateway-currency").text(gateway.currency);
            $(".gateway-conversion, .conversion-currency").addClass('d-none');
        });

        // Initialize first gateway
        if ($('[name=gateway]:checked').length) {
            $('[name=gateway]:checked').trigger('change');
        } else if ($('[name=gateway]').length) {
            $('[name=gateway]').first().trigger('change');
        }
    })(jQuery);
</script>
@endpush