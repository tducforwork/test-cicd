@if ($products->hasPages())
    <div class="pagination-wrapper">
        {{-- Previous Page Link --}}
        @if ($products->onFirstPage())
            <button class="pag-btn prev-pag disabled" disabled>
                <i class="fa-solid fa-chevron-left"></i>
            </button>
        @else
            <button class="pag-btn prev-pag" onclick="filterProducts({{ $products->currentPage() - 1 }})">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
        @endif

        <div class="pag-numbers">
            {{-- Pagination Elements --}}
            @foreach ($products->links()->elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="pag-dots">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $products->currentPage())
                            <button class="pag-num active">{{ $page }}</button>
                        @else
                            <button class="pag-num" onclick="filterProducts({{ $page }})">{{ $page }}</button>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($products->hasMorePages())
            <button class="pag-btn next-pag" onclick="filterProducts({{ $products->currentPage() + 1 }})">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        @else
            <button class="pag-btn next-pag disabled" disabled>
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        @endif
    </div>
@endif
