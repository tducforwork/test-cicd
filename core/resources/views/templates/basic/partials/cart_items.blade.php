@php
    $subtotal = 0;
    foreach ($data as $cart) {
        if ($cart->product->discount_price > 0) {
            $price = $cart->product->discount_price;
        } else {
            $price = $cart->product->base_price;
        }
        $subtotal += $price * $cart->quantity;
    }
@endphp

<div class="flex flex-col  pt-0">
    @forelse ($data as $item)
        @php
            $price = $item->product->discount_price > 0 ? $item->product->discount_price : $item->product->base_price;
        @endphp
        <div class="flex items-start gap-[15px]  container-row">
            <div class="flex items-center gap-4 grow">
                <a href="{{ route('product.detail', $item->product->slug) }}" class="shrink-0">
                    <img src="{{ getImage(getFilePath('product') . '/thumb_' . @$item->product->main_image, getFileSize('product')) }}"
                        class="w-[70px] h-[70px] flex-shrink-0 rounded-[8px] object-cover aspect-square"
                        alt="@lang('product')">
                </a>

                <div class="flex flex-col gap-1 grow text-left">
                    <a href="{{ route('product.detail', $item->product->slug) }}"
                        class="text-[15px] text-[#272343] font-medium leading-tight line-clamp-2 m-0 hover:text-[#cc0001] transition-colors">
                        {{ __($item->product->name) }}
                    </a>
                    <p class="text-[16px] font-bold text-[#cc0001] m-0">
                        {{ showAmount($price) }} x {{ $item->quantity }}
                    </p>
                </div>
            </div>

            <div class="remove-cart-item cursor-pointer text-[#eb5757] hover:text-red-700 transition-colors flex-shrink-0"
                data-id="{{ $item->id }}">
                <div
                    class="w-5 h-5 bg-[#DE4944] rounded-full flex items-center justify-center cursor-pointer hover:bg-red-600 transition-colors shadow-md ">
                    <span class="text-white text-sm font-bold leading-none -mt-0.5">×</span>
                </div>
            </div>
        </div>

        @if (!$loop->last)
            <div class="h-[1px] w-full bg-[#dddddd] my-4"></div>
        @endif
    @empty
        <div class="flex flex-col items-center justify-center py-20 text-gray-400">
            <i class="las la-shopping-basket text-6xl mb-4"></i>
            <p class="text-lg">@lang('Your cart is empty')</p>
            <a href="{{ route('products') }}" class="mt-4 text-[#FF6F0F] hover:underline">@lang('Go shopping')</a>
        </div>
    @endforelse
</div>

<!-- Footer Data (Hidden, parsed by JS) -->
<input type="hidden" id="ajax-cart-subtotal" value="{{ showAmount($subtotal) }}">
<input type="hidden" id="ajax-cart-count" value="{{ $data->count() }}">
