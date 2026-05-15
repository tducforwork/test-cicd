@if ($paginator->hasPages())
    <div class="kviet-pagination-wrapper flex justify-center mt-8 custom-pagination" data-type="{{ $type ?? '' }}">
        <ul class="kviet-pagination list-none p-0 m-0">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="kviet-pagination__item p-0">
                    <span class="kviet-pagination__link kviet-pagination__link--arrow kviet-pagination__link--disabled">
                        @lang('Previous')
                    </span>
                </li>
            @else
                <li class="kviet-pagination__item p-0">
                    <a href="{{ $paginator->previousPageUrl() }}" class="kviet-pagination__link kviet-pagination__link--arrow">
                        @lang('Previous')
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="kviet-pagination__item p-0">
                        <span
                            class="kviet-pagination__link kviet-pagination__link--disabled kviet-pagination__link--inactive">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="kviet-pagination__item p-0">
                                <span class="kviet-pagination__link kviet-pagination__link--active">{{ $page }}</span>
                            </li>
                        @else
                            <li class="kviet-pagination__item p-0">
                                <a href="{{ $url }}"
                                    class="kviet-pagination__link kviet-pagination__link--inactive">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="kviet-pagination__item p-0">
                    <a href="{{ $paginator->nextPageUrl() }}" class="kviet-pagination__link kviet-pagination__link--arrow">
                        @lang('Next')
                    </a>
                </li>
            @else
                <li class="kviet-pagination__item p-0">
                    <span class="kviet-pagination__link kviet-pagination__link--arrow kviet-pagination__link--disabled">
                        @lang('Next')
                    </span>
                </li>
            @endif
        </ul>
    </div>
@endif
