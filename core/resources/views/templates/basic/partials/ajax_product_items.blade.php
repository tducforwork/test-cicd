@forelse($products as $product)
    @include('Template::partials.product_card', ['product' => $product])
@empty
    <div class="col-span-full text-center py-12">
        <p class="text-muted">{{ __('Không tìm thấy sản phẩm nào.') }}</p>
    </div>
@endforelse