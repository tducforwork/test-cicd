@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="bg-[#F7F7F7]">
    <main class="container mx-auto pb-32 pt-10">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar -->
            <aside class="w-full lg:w-[312px] shrink-0">
                @include('seller.partials.sidebar')
            </aside>

            <!-- Main Content -->
            <section class="flex-1 min-w-0">

                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('seller.products.all') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 text-[#272343]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                        <h1 class="text-2xl font-bold text-[#272343]">@lang('Manage Stock')</h1>
                    </div>
                    <span class="text-sm text-gray-500">@lang('Sản phẩm:') <span class="font-semibold text-[#272343]">{{ __($product->name) }}</span></span>
                </div>

                <!-- Table Card -->
                <div class="bg-white rounded-[12px] p-6 border border-gray-100">
                    <div class="hidden md:block">
                        @if ($data && $product->has_variants)
                        <div class="flex flex-col gap-4">
                            <!-- Table Header -->
                            <div class="flex items-center px-4 py-3 bg-[#f1f1f1] rounded-[12px]">
                                <div class="w-16">
                                    <span class="text-sm font-semibold text-[#8a8a8a]">@lang('No.')</span>
                                </div>
                                <div class="flex-1">
                                    <span class="text-sm font-semibold text-[#8a8a8a]">@lang('Biến thể')</span>
                                </div>
                                <div class="w-32 text-center">
                                    <span class="text-sm font-semibold text-[#8a8a8a]">@lang('SKU')</span>
                                </div>
                                <div class="w-24 text-center">
                                    <span class="text-sm font-semibold text-[#8a8a8a]">@lang('Quantity')</span>
                                </div>
                                <div class="w-28 text-center">
                                    <span class="text-sm font-semibold text-[#8a8a8a]">@lang('Actions')</span>
                                </div>
                            </div>

                            <!-- Rows -->
                            @foreach ($data as $item)
                            <div class="flex items-center px-4 py-4 rounded-[12px] border border-[#E6E6E6] hover:bg-gray-50 transition-colors">
                                <div class="w-16">
                                    <span class="text-sm font-semibold text-[#303030]">{{ $loop->iteration }}</span>
                                </div>
                                <div class="flex-1">
                                    <span class="text-sm font-medium text-[#272343]">{{ __($item['combination']) }}</span>
                                </div>
                                <div class="w-32 text-center">
                                    <span class="inline-flex items-center px-3 py-1 bg-gray-100 rounded-lg text-sm font-medium text-[#303030]">
                                        {{ @$item['sku'] }}
                                    </span>
                                </div>
                                <div class="w-24 text-center">
                                    @if ($item['quantity'] == 0)
                                    <span class="inline-flex items-center justify-center px-3 py-1 bg-[#fed3d1] rounded-lg text-sm font-semibold text-[#ef4d2f]">
                                        {{ $item['quantity'] ?? 0 }}
                                    </span>
                                    @elseif($item['quantity'] < 10)
                                    <span class="inline-flex items-center justify-center px-3 py-1 bg-[#fef3c7] rounded-lg text-sm font-semibold text-[#92400e]">
                                        {{ $item['quantity'] ?? 0 }}
                                    </span>
                                    @else
                                    <span class="inline-flex items-center justify-center px-3 py-1 bg-[#e3ffed] rounded-lg text-sm font-semibold text-[#32a06e]">
                                        {{ $item['quantity'] ?? 0 }}
                                    </span>
                                    @endif
                                </div>
                                <div class="w-28 flex items-center justify-center gap-2">
                                    <button type="button" data-sku="{{ $item['sku'] }}" data-attributes="{{ $item['attributes'] }}"
                                        class="editBtn w-9 h-9 flex items-center justify-center bg-[#FF6F0F] text-white rounded-lg hover:bg-orange-600 transition-colors"
                                        title="@lang('Update Stock')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @elseif(!$data && $product->has_variants)
                        <div class="text-center py-16">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <p class="text-gray-500 text-lg mb-4">@lang("Bạn chưa thêm biến thể nào cho sản phẩm này.")</p>
                            <a href="{{ route('seller.products.variant.store', [$product->id]) }}"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#FF6F0F] text-white font-bold text-sm rounded-[12px] hover:bg-orange-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                @lang('Thêm biến thể')
                            </a>
                        </div>

                        @else
                        {{-- Non-variant product --}}
                        @php $stock = \App\Models\ProductStock::showAvailableStock($product->id, $attr_val = null); @endphp
                        <div class="flex items-center px-4 py-4 rounded-[12px] border border-[#E6E6E6]">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-[#272343]">@lang('SKU'): {{ $product->sku }}</span>
                            </div>
                            <div class="w-32 text-center">
                                @if ($stock && $stock->quantity == 0)
                                <span class="inline-flex items-center justify-center px-3 py-1 bg-[#fed3d1] rounded-lg text-sm font-semibold text-[#ef4d2f]">
                                    {{ sprintf('%02d', $stock->quantity ?? 0) }}
                                </span>
                                @elseif($stock && $stock->quantity < 10)
                                <span class="inline-flex items-center justify-center px-3 py-1 bg-[#fef3c7] rounded-lg text-sm font-semibold text-[#92400e]">
                                    {{ sprintf('%02d', $stock->quantity ?? 0) }}
                                </span>
                                @else
                                <span class="inline-flex items-center justify-center px-3 py-1 bg-[#e3ffed] rounded-lg text-sm font-semibold text-[#32a06e]">
                                    {{ sprintf('%02d', $stock->quantity ?? 0) }}
                                </span>
                                @endif
                            </div>
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" data-sku="{{ $product->sku }}" data-attributes="0"
                                    class="editBtn w-9 h-9 flex items-center justify-center bg-[#FF6F0F] text-white rounded-lg hover:bg-orange-600 transition-colors"
                                    title="@lang('Cập nhật kho')">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Mobile Cards -->
                    <div class="md:hidden space-y-4">
                        @if ($data && $product->has_variants)
                            @foreach ($data as $item)
                            <div class="border border-[#E6E6E6] rounded-[12px] p-4 space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-[#272343]">{{ $loop->iteration }}. {{ __($item['combination']) }}</span>
                                    @if ($item['quantity'] == 0)
                                    <span class="inline-flex items-center justify-center px-3 py-1 bg-[#fed3d1] rounded-lg text-sm font-semibold text-[#ef4d2f]">{{ $item['quantity'] ?? 0 }}</span>
                                    @elseif($item['quantity'] < 10)
                                    <span class="inline-flex items-center justify-center px-3 py-1 bg-[#fef3c7] rounded-lg text-sm font-semibold text-[#92400e]">{{ $item['quantity'] ?? 0 }}</span>
                                    @else
                                    <span class="inline-flex items-center justify-center px-3 py-1 bg-[#e3ffed] rounded-lg text-sm font-semibold text-[#32a06e]">{{ $item['quantity'] ?? 0 }}</span>
                                    @endif
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <div><span class="text-gray-500">SKU:</span> <span class="font-semibold">{{ @$item['sku'] }}</span></div>
                                </div>
                                <div class="flex gap-2">
                                    <button type="button" data-sku="{{ $item['sku'] }}" data-attributes="{{ $item['attributes'] }}"
                                        class="editBtn flex-1 py-2 bg-[#FF6F0F] text-white font-bold text-sm rounded-[8px] hover:bg-orange-600 transition-colors">
                                        @lang('Cập nhật')
                                    </button>
                                    <a href="{{ route('seller.products.stock.log', $item['stock_id'] ?? 0) }}"
                                        class="flex-1 py-2 bg-[#272343] text-white font-bold text-sm rounded-[8px] text-center hover:opacity-90 transition-opacity">
                                        @lang('Lịch sử')
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        @elseif(!$data && $product->has_variants)
                        <div class="text-center py-10">
                            <p class="text-gray-500 mb-4">@lang("Bạn chưa thêm biến thể nào.")</p>
                            <a href="{{ route('seller.products.variant.store', [$product->id]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#FF6F0F] text-white font-bold text-sm rounded-[12px]">
                                @lang('Thêm biến thể')
                            </a>
                        </div>
                        @else
                        @php $stock = \App\Models\ProductStock::showAvailableStock($product->id, $attr_val = null); @endphp
                        <div class="border border-[#E6E6E6] rounded-[12px] p-4 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-[#303030]">SKU: {{ $product->sku }}</span>
                                @if ($stock && $stock->quantity == 0)
                                <span class="inline-flex items-center justify-center px-3 py-1 bg-[#fed3d1] rounded-lg text-sm font-semibold text-[#ef4d2f]">{{ sprintf('%02d', $stock->quantity ?? 0) }}</span>
                                @elseif($stock && $stock->quantity < 10)
                                <span class="inline-flex items-center justify-center px-3 py-1 bg-[#fef3c7] rounded-lg text-sm font-semibold text-[#92400e]">{{ sprintf('%02d', $stock->quantity ?? 0) }}</span>
                                @else
                                <span class="inline-flex items-center justify-center px-3 py-1 bg-[#e3ffed] rounded-lg text-sm font-semibold text-[#32a06e]">{{ sprintf('%02d', $stock->quantity ?? 0) }}</span>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                <button type="button" data-sku="{{ $product->sku }}" data-attributes="0"
                                    class="editBtn flex-1 py-2 bg-[#FF6F0F] text-white font-bold text-sm rounded-[8px] hover:bg-orange-600 transition-colors">
                                    @lang('Cập nhật kho')
                                </button>
                                <a href="{{ route('seller.products.stock.log', $product->stocks[0] ?? 0) }}"
                                    class="flex-1 py-2 bg-[#272343] text-white font-bold text-sm rounded-[8px] text-center hover:opacity-90 transition-opacity">
                                    @lang('Lịch sử')
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

<!-- Modal -->
<div class="modal fade" id="editModal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Cập nhật tồn kho')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>

            <form action="{{ route('seller.products.stock.add', $product->id) }}" method="POST">
                @csrf
                <input type="hidden" name="attr" value="">
                <div class="modal-body">
                    <div class="form-group sku">
                        <label class="text-sm font-medium text-[#272343] mb-2 block">@lang('SKU')</label>
                        <input type="text" name="sku" class="w-full h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-[#FF6F0F]">
                        <small class="text-[#FF6F0F] text-xs mt-1 block"><i class="fas fa-info-circle"></i> @lang('Nếu chỉ cập nhật SKU, giữ Số lượng = 0')</small>
                    </div>

                    <div class="form-group mt-4">
                        <label class="text-sm font-medium text-[#272343] mb-2 block">@lang('Số lượng')</label>
                        <div class="flex gap-3">
                            <select name="type" class="h-[49px] w-16 px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-[#FF6F0F]">
                                <option value="1">+</option>
                                <option value="2">-</option>
                            </select>
                            <input type="text" class="flex-1 h-[49px] px-4 rounded-[6px] border border-[#E6E6E6] bg-white text-[16px] focus:outline-none focus:ring-1 focus:ring-[#FF6F0F] integer-validation" name="quantity" placeholder="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="w-full h-[49px] bg-[#FF6F0F] text-white font-bold rounded-[12px] hover:bg-orange-600 transition-colors">@lang('Update')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    'use strict';
    (function($) {

        $('.editBtn').on('click', function() {
            var modal = $('#editModal');
            var attrArray = $(this).data('attributes');
            modal.find('input[name=sku]').val($(this).data('sku'));
            if (attrArray != 0) {
                modal.find('input[name=attr]').val(JSON.stringify(attrArray));
            } else {
                modal.find('.sku').hide();
                modal.find('input[name=attr]').remove();
            }
            modal.modal('show');
        });
    })(jQuery)
</script>
@endpush
