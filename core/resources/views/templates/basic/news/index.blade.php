@extends('Template::layouts.frontend')
@section('content')
    <div class="newsPage container pb-[100px] ">
        <div class="product-detail__breadcrumb flex items-center gap-[12px] py-[12px] text-[#606060] text-base">
            <a href="{{ route('home') }}" class="product-detail__breadcrumb-item cursor-pointer">Home</a>
            <img src="{{ asset('assets/images/frontend/kviet/detail-product/img.png') }}" class="w-2" alt="arrow" />
            <p class="product-detail__breadcrumb-item cursor-pointer">@lang('Tin Tức')</p>
        </div>
        <div class="newPage__wrapper pt-3">
            <div class="news-grid">
                @forelse ($news as $item)
                    <div class="news-card">
                        <div class="news-card__image-wrapper">
                            <a href="{{ route('news.details', $item->slug) }}" class="block h-full">
                                <img src="{{ getImage(getFilePath('news') . '/' . $item->featured_image) }}" class="image"
                                    alt="{{ __($item->title) }}" />
                                <div class="news-card__date-badge">
                                    <p class="news-card__day">{{ showDateTime($item->published_at, 'd') }}</p>
                                    <p class="news-card__month">T{{ showDateTime($item->published_at, 'm') }}</p>
                                </div>
                            </a>
                        </div>

                        <div class="news-card__body">
                            <a href="{{ route('news.details', $item->slug) }}">
                                <p class="news-card__title">{{ __($item->title) }}</p>
                            </a>
                            <p class="news-card__excerpt">{{ Str::limit(strip_tags($item->excerpt ?: $item->content), 120) }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-12 text-center py-10">
                        <p class="text-muted">@lang('Chưa có tin tức nào.')</p>
                    </div>
                @endforelse
            </div>
            <div class="mt-10">
                {{ paginateLinks($news, 'Template::partials.pagination') }}
            </div>
        </div>
    </div>
@endsection