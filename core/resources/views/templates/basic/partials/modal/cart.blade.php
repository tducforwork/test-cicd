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
<div id="side-cart" class="fixed inset-0 z-[99999] invisible group ">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black/40 transition-opacity duration-300 opacity-0 group-[.side-cart-active]:opacity-100 group-[.side-cart-active]:visible pointer-events-none group-[.side-cart-active]:pointer-events-auto backdrop-blur-[2px] z-[100]"
        onclick="toggleSideCart(false)"></div>

    <!-- Sidebar -->
    <div class="fixed cart-sidebar-container top-0 h-full w-full max-w-[560px] py-4 md:px-8 px-5 bg-white transition-all duration-300 flex flex-col shadow-2xl pointer-events-auto z-[200]"
        style="position: fixed !important; right: var(--sidebar-right, -100%) !important; left: auto !important; top: 0 !important; height: 100% !important; transition: right 0.3s ease-in-out !important;">


        <!-- Header -->
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-[#eeeeee]">
            <div class="flex items-center gap-2 font-bold">
                <h2 class="text-[18px] md:text-[20px] text-[#272343] m-0 font-bold"
                    style="line-height: normal !important">@lang('Cart')</h2>
                <span class="text-[16px] md:text-[18px] font-normal text-[#666] m-0"
                    id="side-cart-count">({{ $items->count() }})</span>
            </div>

            <div class="relative w-8 h-8 flex-shrink-0 bg-[#F4F4F4] rounded-full flex items-center justify-center cursor-pointer hover:bg-gray-200 transition-colors"
                onclick="toggleSideCart(false)">
                <i class="las la-times text-[#1b1b1b] text-lg"></i>
            </div>
        </div>

        <!-- Items Area -->
        <div class="flex-grow overflow-y-auto flex flex-col gap-4" id="side-cart-items">
            @include('Template::partials.cart_items', ['data' => $items])
        </div>

        <!-- Footer / Total Section -->
        <div class="py-6 md:py-8 flex flex-col gap-4 bg-white border-t border-[#eeeeee] mt-auto">
            <div class="flex items-center justify-between font-bold mb-2">
                <p class="text-[#272343] m-0 uppercase text-sm tracking-wider">@lang('Total'):</p>
                <p class="text-[#cc0001] m-0 text-xl"><span id="side-cart-subtotal">{{ showAmount($subtotal) }}</span>
                </p>
            </div>

            <a href="{{ route('shopping-cart') }}"
                class="w-full bg-[#FF6F0F] text-white py-3.5 rounded-[8px] font-bold text-center hover:brightness-110 transition-all text-[15px] shadow-lg shadow-orange-200">
                @lang('Buy it now')
            </a>
            <button onclick="toggleSideCart(false)"
                class="w-full border border-[#dddddd] py-3 text-[#272343] rounded-[8px] font-semibold transition-all hover:bg-gray-50 text-[14px]">
                @lang('Return To Shop')
            </button>
        </div>
    </div>
</div>

<script>
    function toggleSideCart(show) {
        const cart = document.getElementById('side-cart');
        if (show) {
            cart.classList.add('side-cart-active');
            document.body.classList.add('overflow-hidden');
        } else {
            cart.classList.remove('side-cart-active');
            document.body.classList.remove('overflow-hidden');
        }
    }
</script>

<style>
    .side-cart-active {
        visibility: visible !important;
        opacity: 1 !important;
        pointer-events: auto !important;
        --sidebar-right: 0 !important;
    }

    .side-cart-active .cart-sidebar-container {
        right: var(--sidebar-right) !important;
    }


    @container container-row (width < 455px) {
        .container-row {
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .container-row>div:first-child {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .container-row .text-left {
            text-align: center;
        }
    }
</style>
