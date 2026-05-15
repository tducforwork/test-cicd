@extends('Template::layouts.frontend')
@php
    $contact = getContent('contact.content', true)->data_values;
@endphp
@section('content')
    <div class="contactPage container pb-[100px]">
        {{-- Breadcrumb --}}
        <div class="product-detail__breadcrumb flex items-center gap-[12px] py-[12px] text-[#606060] text-base">
            <a href="{{ route('home') }}" class="product-detail__breadcrumb-item cursor-pointer">Home</a>
            <img src="{{ asset('assets/images/frontend/kviet/detail-product/img.png') }}" class="w-2" alt="arrow" />
            <p class="product-detail__breadcrumb-item cursor-pointer">@lang('Contact')</p>
        </div>

        <div class="contactPage__grid grid grid-cols-1 md:grid-cols-12 gap-12 pt-6 items-start">
            {{-- Left column --}}
            <div
                class="md:col-span-4 h-full bg-white p-8 rounded-[4px] shadow-[0_1px_12px_0_rgba(0,0,0,0.12)] flex flex-col gap-8">
                {{-- section 1: call to us --}}
                <div class="contact-info-item flex flex-col gap-4">
                    <div class="flex items-center gap-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                            <rect width="40" height="40" rx="20" fill="#FF6F0F" />
                            <path
                                d="M18.5542 14.24L15.1712 10.335C14.7812 9.88503 14.0662 9.88703 13.6132 10.341L10.8312 13.128C10.0032 13.957 9.76623 15.188 10.2452 16.175C13.1069 22.1 17.8853 26.8851 23.8062 29.755C24.7922 30.234 26.0222 29.997 26.8502 29.168L29.6582 26.355C30.1132 25.9 30.1142 25.181 29.6602 24.791L25.7402 21.426C25.3302 21.074 24.6932 21.12 24.2822 21.532L22.9182 22.898C22.8484 22.9712 22.7565 23.0195 22.6566 23.0354C22.5567 23.0513 22.4543 23.0339 22.3652 22.986C20.1357 21.7021 18.2862 19.8503 17.0052 17.619C16.9573 17.5298 16.9399 17.4273 16.9558 17.3272C16.9717 17.2271 17.02 17.135 17.0932 17.065L18.4532 15.704C18.8652 15.29 18.9102 14.65 18.5542 14.239V14.24Z"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <h3 class="text-base font-medium text-[#272343]">{{ __(@$contact->title_1) }}</h3>
                    </div>
                    <div class="flex flex-col gap-3 text-sm text-[#272343]">
                        <p>{{ __(@$contact->desc_1) }}</p>
                        <p>@lang('Phone'):
                            <a href="tel:{{ @$contact->phone }}">{{ @$contact->phone }}</a>
                        </p>
                    </div>
                </div>

                <div class="w-full h-px bg-[#000]/10"></div>

                {{-- section 2: write to us --}}
                <div class="contact-info-item flex flex-col gap-4">
                    <div class="flex items-center gap-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                            <rect width="40" height="40" rx="20" fill="#FF6F0F" />
                            <path d="M10 13L20 20L30 13M10 27H30V13H10V27Z" stroke="white" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <h3 class="text-base font-medium text-[#272343]">{{ __(@$contact->title_2) }}</h3>
                    </div>
                    <div class="flex flex-col gap-3 text-sm text-[#272343]">
                        <p>{{ __(@$contact->desc_2) }}</p>
                        <p>@lang('Emails'): {{ @$contact->email_1 }}</p>
                        {{-- @if (@$contact->email_2)
                        <p>@lang('Emails'): {{ @$contact->email_2 }}</p>
                        @endif --}}
                        <p>@lang('Address'): số nhà 68, tổ 2, khu Giếng Đáy 1, phường Việt Hưng, tỉnh Quảng Ninh, Việt Nam
                        </p>
                    </div>
                </div>
            </div>

            {{-- Right column: Form --}}
            <div class="md:col-span-8 bg-white p-8 rounded-[4px] shadow-[0_1px_12px_0_rgba(0,0,0,0.12)]">
                <form action="{{ route('contact.submit') }}" method="POST" class="flex flex-col gap-8">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Name Input --}}
                        <div class="bg-[#F5F5F5] rounded border-none relative group">
                            <input type="text" name="name" id="contact-name" placeholder=" "
                                class="w-full px-4 py-[13px] bg-transparent border-none outline-none text-sm text-[#272343] relative z-10"
                                required value="{{ old('name') }}">
                            <label for="contact-name"
                                class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-[#606060] pointer-events-none transition-opacity duration-200">
                                @lang('Your Name') <span class="text-[#FF6F0F]">*</span>
                            </label>
                        </div>

                        {{-- Email Input --}}
                        <div class="bg-[#F5F5F5] rounded border-none relative group">
                            <input type="email" name="email" id="contact-email" placeholder=" "
                                class="w-full px-4 py-[13px] bg-transparent border-none outline-none text-sm text-[#272343] relative z-10"
                                required value="{{ old('email') }}">
                            <label for="contact-email"
                                class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-[#606060] pointer-events-none transition-opacity duration-200">
                                @lang('Your Email') <span class="text-[#FF6F0F]">*</span>
                            </label>
                        </div>

                        {{-- Subject Input --}}
                        <div class="bg-[#F5F5F5] rounded border-none relative group">
                            <input type="text" name="subject" id="contact-subject" placeholder=" "
                                class="w-full px-4 py-[13px] bg-transparent border-none outline-none text-sm text-[#272343] relative z-10"
                                required value="{{ old('subject') }}">
                            <label for="contact-subject"
                                class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-[#606060] pointer-events-none transition-opacity duration-200">
                                @lang('Subject') <span class="text-[#FF6F0F]">*</span>
                            </label>
                        </div>
                    </div>

                    <div class="bg-[#F5F5F5] rounded border-none relative min-h-[200px]">
                        <textarea name="message" id="contact-message" placeholder=" "
                            class="w-full h-full min-h-[176px] px-4 py-[13px] bg-transparent border-none outline-none text-sm text-[#272343] resize-none relative z-10"
                            required>{{ old('message') }}</textarea>
                        <label for="contact-message"
                            class="absolute left-4 top-4 text-sm text-[#606060] pointer-events-none transition-opacity duration-200">
                            @lang('Your Message') <span class="text-[#FF6F0F]">*</span>
                        </label>
                    </div>

                    <x-captcha />

                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="w-full md:w-auto px-12 py-3 bg-[#FF6F0F] text-white font-medium rounded-[4px] hover:bg-[#e6640d] transition-all leading-normal shadow-sm active:scale-95">
                            @lang('Send Message')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Logic for hiding "fake placeholder" labels */
        .contactPage input:focus+label,
        .contactPage input:not(:placeholder-shown)+label,
        .contactPage textarea:focus+label,
        .contactPage textarea:not(:placeholder-shown)+label {
            opacity: 0;
            visibility: hidden;
        }

        /* Ensure standard placeholder is hidden if browser fallback kicks in */
        .contactPage input::placeholder,
        .contactPage textarea::placeholder {
            color: transparent;
        }
    </style>
@endsection