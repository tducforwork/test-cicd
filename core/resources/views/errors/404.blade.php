@extends('Template::layouts.frontend')
@section('content')
    <div class="errorPage container ">
        {{-- Breadcrumb --}}
        <div class="product-detail__breadcrumb flex items-center gap-[12px] py-[12px] text-[#606060] text-base">
            <a href="{{ route('home') }}" class="product-detail__breadcrumb-item cursor-pointer">Home</a>
            <img src="{{ asset('assets/images/frontend/kviet/detail-product/img.png') }}" class="w-2" alt="arrow" />
            <p class="product-detail__breadcrumb-item cursor-pointer text-[#000]">@lang('404 Error')</p>
        </div>

        {{-- Main Error Content --}}
        <div class="errorContent flex flex-col items-center justify-center py-[30px] md:py-[80px] text-center gap-[50px]">
            {{-- Big Text --}}
            <h1 class="errorContent__title animate-fade-in">
                @lang('404 Not Found')
            </h1>

            {{-- Small Text --}}
            <p class="errorContent__desc">
                @lang('Your visited page not found. You may go home page.')
            </p>

            {{-- Button --}}
            <div class="errorContent__action mt-2">
                <a href="{{ route('home') }}" class="inline-block px-[48px] py-[16px] bg-[#FF6F0F] text-white font-medium rounded-[4px] hover:bg-[#e6640d] transition-all leading-normal active:scale-95 shadow-sm">
                    @lang('Back to home page')
                </a>
            </div>
        </div>
    </div>

    <style>
        .errorContent__title {
            color: #000;
            font-family: 'Inter', sans-serif;
            font-size: 110px;
            font-style: normal;
            font-weight: 500;
            line-height: 115px; /* 104.545% */
            letter-spacing: 3.3px;
        }

        .errorContent__desc {
            color: #000;
            font-family: 'Inter', sans-serif;
            font-size: 20px;
            font-style: normal;
            font-weight: 400;
            line-height: 24px; /* 120% */
        }

        /* Responsive scaling */
        @media (max-width: 1024px) {
            .errorContent__title {
                font-size: 80px;
                line-height: 85px;
            }
        }

        @media (max-width: 768px) {
            .errorContent {
                gap: 40px;
                padding-top: 60px;
                padding-bottom: 60px;
            }
            .errorContent__title {
                font-size: 48px;
                line-height: 52px;
                letter-spacing: 1.5px;
            }
            .errorContent__desc {
                font-size: 16px;
                max-width: 280px;
                margin: 0 auto;
            }
        }

        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.8s ease-out forwards;
        }
    </style>
@endsection
