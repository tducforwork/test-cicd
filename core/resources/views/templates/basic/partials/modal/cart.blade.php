@php
    if (!isset($items) || $items->isEmpty()) {
        $items = App\Models\Cart::where('user_id', auth()->id())
            ->orWhere('session_id', session('session_id'))
            ->with([
                'product' => function ($q) {
                    return $q->publishable();
                },
                'product.categories',
            ])
            ->get();
    }

    $subtotal = 0;
    foreach ($items as $cart) {
        $price = $cart->product->discount_price > 0 ? $cart->product->discount_price : $cart->product->base_price;
        $subtotal += $price * $cart->quantity;
    }
@endphp

<!-- Sidecart Modal -->
<div class="cart-overlay" id="cartOverlay" onclick="toggleSideCart(false)"></div>
<div class="cart-sidebar" id="cartSidebar">
    <div class="cart-header">
        <h3>@lang('Giỏ hàng của bạn')</h3>
        <button class="close-cart" id="closeCart" onclick="toggleSideCart(false)">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <div class="cart-items-list" id="side-cart-items">
        @include('Template::partials.cart_items', ['data' => $items])
    </div>

    <div class="cart-footer">
        <div class="cart-total">
            <span>@lang('Tổng cộng'):</span>
            <span class="cart-total-price" id="side-cart-subtotal">{{ showAmount($subtotal) }}</span>
        </div>
        <button class="btn-checkout" onclick="window.location.href = '{{ route('shopping-cart') }}'">
            @lang('Tiến hành thanh toán')
        </button>
    </div>
</div>

<script>
    function toggleSideCart(show) {
        const cartSidebar = document.getElementById('cartSidebar');
        const cartOverlay = document.getElementById('cartOverlay');
        if (!cartSidebar || !cartOverlay) return;
        
        if (show) {
            cartSidebar.classList.add('active');
            cartOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        } else {
            cartSidebar.classList.remove('active');
            cartOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    }
</script>