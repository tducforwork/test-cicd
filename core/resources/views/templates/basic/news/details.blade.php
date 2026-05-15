@extends('Template::layouts.frontend')
@section('content')
    <div class="blogDetailPage container pb-[100px]">
        {{-- Breadcrumb --}}
        <div class="product-detail__breadcrumb flex items-center gap-[12px] py-[12px] text-[#606060] text-base">
            <a href="{{ route('home') }}" class="product-detail__breadcrumb-item cursor-pointer">Home</a>
            <img src="{{ asset('assets/images/frontend/kviet/detail-product/img.png') }}" class="w-2" alt="arrow" />
            <a href="{{ route('news.index') }}" class="product-detail__breadcrumb-item cursor-pointer">@lang('Tin Tức')</a>
            <img src="{{ asset('assets/images/frontend/kviet/detail-product/img.png') }}" class="w-2" alt="arrow" />
            <p class="product-detail__breadcrumb-item cursor-pointer line-clamp-1">{{ __($news->title) }}</p>
        </div>

        {{-- Main Wrapper using Grid --}}
        <div class="blogDetail__grid grid grid-cols-1 md:grid-cols-12 gap-10 pt-4 items-start">

            {{-- Main Content Column --}}
            <div class="blogDetail__main md:col-span-8 flex flex-col gap-[18px]">
                {{-- Main Image --}}
                <div class="blogDetail__image rounded-xl overflow-hidden shadow-sm">
                    <img src="{{ getImage(getFilePath('news') . '/' . $news->featured_image) }}"
                        class="w-full h-auto object-cover" alt="{{ __($news->title) }}"
                        onerror="this.src='{{ asset('assets/images/frontend/kviet/bg-1.png') }}'" />
                </div>

                {{-- Post Meta --}}
                <div class="blogDetail__meta flex items-center gap-2 text-sm ">
                    <span class="text-[#FF6F0F] font-bold">@lang('By Admin')</span>
                    <span class="text-[#272343]">/</span>
                    <span class="text-[#272343] font-medium">@lang('Date') -
                        {{ showDateTime($news->published_at, 'd/m/Y') }}</span>
                </div>

                {{-- Title --}}
                <h1 class="blogDetail__title text-[28px] md:text-[36px] font-bold text-[#272343] leading-tight">
                    {{ __($news->title) }}
                </h1>

                {{-- Content Area --}}
                <div class="blogDetail__content text-[#404040] leading-[1.8] text-[16px]">
                    {!! $news->content !!}
                </div>
            </div>

            {{-- Sidebar Column --}}
            <div class="md:col-span-4 lg:pl-4">
                <div class="blogDetail__sidebar sticky top-[120px] flex flex-col gap-6">
                    @foreach ($recentNews as $post)
                        <div class="sidebar__post flex gap-4 items-center group cursor-pointer"
                            onclick="location.href='{{ route('news.details', $post->slug) }}'">
                            <div
                                class="sidebar__post-img shrink-0 w-[90px] h-[60px] rounded-md overflow-hidden bg-gray-100 shadow-sm">
                                <img src="{{ getImage(getFilePath('news') . '/' . $post->featured_image) }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                    alt="{{ __($post->title) }}"
                                    onerror="this.src='{{ asset('assets/images/frontend/kviet/bg-1.png') }}'" />
                            </div>
                            <div class="sidebar__post-content flex-1">
                                <p
                                    class="text-[14px] font-semibold text-[#272343] leading-snug line-clamp-2 group-hover:text-[#CC0001] transition-colors">
                                    {{ __($post->title) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <style>
        .blogDetail__content p {
            margin-bottom: 15px;
        }

        .blogDetail__content h2 {
            color: #272343;
            font-weight: 700;
            font-size: 32px;
            margin: 0 0 15px;
        }

        .blogDetail__content img {
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            max-width: 100%;
            height: auto;
        }
    </style>
@endsection