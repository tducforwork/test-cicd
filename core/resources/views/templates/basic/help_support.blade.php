@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <div class="py-12 md:py-20 help-support-header" style="background: linear-gradient(90deg, rgba(28, 57, 142, 1) 0%, rgb(23 22 139) 100%);">
        <div class="container mx-auto px-4 text-center">
            <div class="mb-6 flex justify-center">
                <img src="{{ frontendImage('help_support', @$helpSupport->data_values->icon, '64x64') }}" alt="Icon"
                    class="w-16 h-16 object-contain">
            </div>
            <h1 class="text-white text-[32px] md:text-[42px] font-normal mb-[20px]">
                {{ __(@$helpSupport->data_values->title) }}
            </h1>
            <p class="text-[#fff] max-w-2xl mx-auto mb-[40px] text-center">
                {{ __(@$helpSupport->data_values->description) }}
            </p>
            @if (@$helpSupport->data_values->last_update)
                <p class="text-sm text-[#BEDBFF]">
                    {{ __(@$helpSupport->data_values->last_update) }}
                </p>
            @endif
        </div>
    </div>

    <div class="bg-gray-50 py-12 help-support-page">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                <!-- Sidebar -->
                <aside class="w-full lg:w-1/4 sticky top-[20px]">
                    <div class="bg-white rounded-xl border border-[#E5E7EB] p-6">
                        <h4 class="text-[#101828]  text-sm font-normal tracking-wider mb-[36px]">
                            @lang('Quick Navigation')
                        </h4>
                        <nav class="space-y-2" id="help-nav">
                            @foreach ($elements as $element)
                                <a href="#{{ slug($element->data_values->nav_name) }}"
                                    class="nav-link flex items-center p-3 rounded-lg transition-all duration-300 text-gray-600 hover:bg-orange-50 hover:text-kviet-orange border border-transparent group"
                                    data-target="{{ slug($element->data_values->nav_name) }}">
                                    <div
                                        class="w-[16px] h-[16px] flex items-center justify-center mr-3 bg-gray-50 rounded-lg transition-colors ">
                                        <div class="icon-mask w-full h-full bg-gray-400 group-hover:bg-kviet-orange transition-colors"
                                            style="mask-image: url('{{ frontendImage('help_support', @$element->data_values->section_icon, '32x32') }}'); -webkit-mask-image: url('{{ frontendImage('help_support', @$element->data_values->section_icon, '32x32') }}'); mask-size: contain; -webkit-mask-size: contain; mask-repeat: no-repeat; -webkit-mask-repeat: no-repeat; mask-position: center; -webkit-mask-position: center;">
                                        </div>
                                    </div>
                                    <span
                                        class="font-normal text-[12.25px]">{{ __($element->data_values->nav_name) }}</span>
                                </a>
                            @endforeach
                        </nav>
                    </div>
                </aside>

                <!-- Content -->
                <main class="w-full lg:w-3/4">
                    <div class="support-content-card p-6 md:p-12">
                        @foreach ($elements as $key => $element)
                            @php
                                $isContact =
                                    stripos($element->data_values->nav_name, 'Help') !== false ||
                                    stripos($element->data_values->nav_name, 'Liên hệ') !== false;
                            @endphp
                            <section id="{{ slug($element->data_values->nav_name) }}"
                                class="content-section {{ $isContact ? 'contact-area' : '' }}">
                                <div class="flex items-center mb-3">
                                    <div class="w-5 h-5 flex items-center justify-center bg-orange-50 rounded-lg mr-2 ">
                                        <div class="icon-mask w-full h-full bg-kviet-orange"
                                            style="mask-image: url('{{ frontendImage('help_support', @$element->data_values->content_icon, '32x32') }}'); -webkit-mask-image: url('{{ frontendImage('help_support', @$element->data_values->content_icon, '32x32') }}'); mask-size: contain; -webkit-mask-size: contain; mask-repeat: no-repeat; -webkit-mask-repeat: no-repeat; mask-position: center; -webkit-mask-position: center;">
                                        </div>
                                    </div>
                                    <h2 class="text-[18px] font-medium leading-normal text-[#101828]">
                                        {{ __($element->data_values->title) }}
                                    </h2>
                                </div>
                                <div class="prose prose-slate max-w-none text-[#364153] text-sm leading-relaxed">
                                    @php
                                        $content = $element->data_values->content;
                                        if ($isContact) {
                                            $content = preg_replace(
                                                '/(Email|Phone|Address|Business Hours|Giờ làm việc|Điện thoại|Địa chỉ|Văn phòng):/',
                                                '<strong>$1:</strong>',
                                                $content,
                                            );
                                        }
                                        echo $content;
                                    @endphp
                                </div>
                                @if (!$loop->last)
                                    <div class="custom-divider"></div>
                                @endif
                            </section>
                        @endforeach
                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .nav-link.active {
            background-color: #ff6f0f !important;
            color: white !important;
        }

        .nav-link.active .icon-mask {
            background-color: white !important;
        }

        .nav-link.active .bg-gray-50 {
            background-color: rgba(255, 255, 255, 0.2) !important;
        }

        .icon-mask {
            display: inline-block;
        }

        .bg-dark-navy {
            background-color: #272343;
        }

        .text-kviet-orange {
            color: #ff6f0f;
        }

        .bg-kviet-orange {
            background-color: #ff6f0f;
        }

        .support-content-card {
            border-radius: 8.75px;
            border: 1px solid #E5E7EB;
            background: #FFF;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.10), 0 1px 2px -1px rgba(0, 0, 0, 0.10);
        }

        .custom-divider {
            background: rgba(0, 0, 0, 0.10);
            height: 1px;
            align-self: stretch;
            margin: 35px 0;
        }

        /* Contact Us Specific Styling */
        .contact-area blockquote {
            background: #F9FAFB !important;
            border: none !important;
            border-radius: 12px !important;
            padding: 24px 32px !important;
            margin: 24px 0 !important;
            color: #364153 !important;
        }

        .contact-area blockquote div {
            margin-bottom: 12px !important;
            line-height: 1.5;
        }

        .contact-area blockquote div:last-child {
            margin-bottom: 0 !important;
        }

        .contact-area blockquote strong {
            color: #101828;
            font-weight: 600;
            display: inline-block;
            min-width: 100px;
        }

        html {
            scroll-behavior: smooth;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            // Handle scroll and update active nav link
            $(window).on('scroll', function() {
                var scrollDistance = $(window).scrollTop() + 150;

                $('.content-section').each(function(i) {
                    if ($(this).position().top <= scrollDistance) {
                        $('.nav-link.active').removeClass('active');
                        $('.nav-link').eq(i).addClass('active');
                    }
                });
            }).scroll();

            // Handle internal nav link clicks
            $('.nav-link').on('click', function(e) {
                e.preventDefault();
                var target = $(this).attr('href');
                scrollToTarget(target);
            });

            // Handle hash on page load (for footer links from other pages)
            $(window).on('load', function() {
                if (window.location.hash) {
                    scrollToTarget(window.location.hash);
                }
            });

            function scrollToTarget(target) {
                if ($(target).length) {
                    $('html, body').animate({
                        scrollTop: $(target).offset().top - 100
                    }, 100);
                    
                    // Update URL without jump
                    if (history.pushState) {
                        history.pushState(null, null, target);
                    }
                }
            }

        })(jQuery);
    </script>
@endpush
