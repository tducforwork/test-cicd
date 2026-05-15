@php
  $suggestionContent = getContent('suggestion.content', true);
  $categoryIds = (array) @$suggestionContent->data_values->category_ids;
  $suggestionCategories = \App\Models\Category::whereIn('id', $categoryIds)
    ->with([
      'products' => function ($q) {
        $q->publishable()->where('is_suggestion', 1)->with(['brands', 'reviews', 'orderDetails', 'tags'])->limit(18);
      }
    ])->get();
@endphp

@if($suggestionCategories->count() > 0)
  <!-- GỢI Ý CHO BẠN -->
  <section class="recommend-section py-lg-4 py-4">
    <div class="container">
      <div class="product-section-header">
        <div style="display: flex; align-items: center; gap: 40px">
          <h2 class="section-title">{{ __(@$suggestionContent->data_values->title ?? 'Gợi ý cho bạn') }}</h2>
          <div class="sale-tabs">
            @foreach($suggestionCategories as $category)
              <div class="tab-btn @if($loop->first) active @endif" data-tab="recommend-cat-{{ $category->id }}">
                {{ __($category->name) }}
              </div>
            @endforeach
          </div>
        </div>
        <a href="{{ route('quang_phat_mall') }}" class="view-more-link">XEM THÊM</a>
      </div>

      <div class="shopee-cat-wrap">
        @foreach($suggestionCategories as $category)
          <div class="tab-content @if($loop->first) active @endif" id="recommend-cat-{{ $category->id }}">
            <div class="recommend-grid">
              @forelse($category->products as $product)
                @include($activeTemplate . 'partials.product_card', ['product' => $product])
              @empty
                <div class="text-center w-100 py-5">
                  <p class="text-muted">@lang('Chưa có sản phẩm nào trong danh mục này.')</p>
                </div>
              @endforelse
            </div>
          </div>
        @endforeach
      </div>

      <div class="text-center mt-4">
        <a href="{{ route('quang_phat_mall') }}" class="btn btn-qp-outline-primary">Xem thêm sản phẩm</a>
      </div>
    </div>
  </section>

  @push('style')
    <style>
      .tab-content {
        display: none;
      }

      .tab-content.active {
        display: block;
      }
    </style>
  @endpush

  @push('script')
    <script>
      (function ($) {
        "use strict";

        // Tab Switching for Suggestion
        $('.recommend-section .tab-btn').on('click', function () {
          const tabId = $(this).data('tab');
          $('.recommend-section .tab-btn').removeClass('active');
          $(this).addClass('active');
          $('.recommend-section .tab-content').removeClass('active');
          $('#' + tabId).addClass('active');
        });

      })(jQuery);
    </script>
  @endpush
@endif