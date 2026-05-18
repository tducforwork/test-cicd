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

@forelse ($data as $item)
    @php
        $price = $item->product->discount_price > 0 ? $item->product->discount_price : $item->product->base_price;
    @endphp
    <div class="cart-item">
        <div class="cart-item-img">
            <img src="{{ getImage(getFilePath('product') . '/thumb_' . @$item->product->main_image, getFileSize('product')) }}"
                alt="{{ __($item->product->name) }}" />
        </div>
        <div class="cart-item-info">
            <a href="{{ route('product.detail', $item->product->slug) }}" class="cart-item-name">{{ __($item->product->name) }}</a>
            <div class="cart-item-price">{{ showAmount($price) }}</div>
            <div class="cart-item-qty">@lang('Số lượng'): {{ $item->quantity }}</div>
        </div>
        <div class="remove-item remove-cart-item cursor-pointer" data-id="{{ $item->id }}">
            <i class="fa-solid fa-trash-can"></i>
        </div>
    </div>
@empty
    <div class="cart-empty" style="text-align: center; padding: 2rem;">
        <p>@lang('Your cart is empty')</p>
    </div>
@endforelse

<!-- Footer Data (Hidden, parsed by JS) -->
<input type="hidden" id="ajax-cart-subtotal" value="{{ showAmount($subtotal) }}">
<input type="hidden" id="ajax-cart-count" value="{{ $data->count() }}">
