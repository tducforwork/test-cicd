<a href="{{ route('pages', $product->slug) }}" class="p-card">
    @if ($product->base_price > $product->final_price)
        <div class="discount-badge">-{{ round(100 - ($product->final_price / $product->base_price * 100)) }}%
        </div>
    @endif
    <div class="p-image-box">
        <img src="{{ $product->productImage() }}" alt="{{ __($product->name) }}" />
        @if($product->tags->count() > 0)
            <div class="p-tags">
                @foreach($product->tags as $tag)
                    <div class="p-tag {{ $tag->type }}">{{ __($tag->name) }}</div>
                @endforeach
            </div>
        @endif
    </div>
    <div class="p-info">
        <p class="p-title">{{ __($product->name) }}</p>
        <div class="p-rating">
            <i class="fa-solid fa-star"></i>
            <span>{{ number_format($product->reviews->avg('rating'), 1) ?: '5.0' }} | Đã bán
                {{ $product->orderDetails->sum('quantity') }}</span>
        </div>
        @php $brand = $product->brand; @endphp
        <p class="p-brand">Thương hiệu: <strong>{{ $brand ? __($brand->name) : __('Không có') }}</strong></p>

        @if($product->flash_percentage !== null)
            <div class="flash-progress">
                <div class="flash-progress-bar" style="width: {{ $product->flash_percentage }}%"></div>
                <div class="flash-progress-text">{{ __($product->flash_text) }}</div>
            </div>
        @endif

        <p class="p-price">
            {{ showAmount($product->final_price) }}
            @if ($product->base_price > $product->final_price)
                <span class="p-price-old">{{ showAmount($product->base_price) }}</span>
            @endif
        </p>
        <button class="btn-add-cart" style="margin-top: 10px">
            <i class="fa-solid fa-cart-plus"></i> @lang('Thêm vào giỏ')
        </button>
    </div>
</a>