@php
    $footerContent = getContent('footer.content', true);
    $footerMenus = \App\Models\MenuGroup::where('location', 'LIKE', 'footer_menu_%')->where('status', 1)->with('menuItems')->get();
@endphp
<footer class="footer-section">
    <div class="container">
        <div class="footer-main">
            <div class="footer-col"
                style="grid-column: span 1.5; display:flex; flex-direction:column; align-items:center; text-align: center;">
                <a href="{{ route('home') }}">
                    <img src="{{ frontendImage('footer', @$footerContent->data_values->footer_logo, '150x50') }}"
                        alt="@lang('Logo')" style="height: 90px; margin-bottom: 20px" />
                </a>
                <p style="color: #999; font-size: 13px; line-height: 1.6; margin-bottom: 20px">
                    {{ __(@$footerContent->data_values->about_text) }}
                </p>
                <div class="footer-social">
                    <a href="#" class="social-icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fa-brands fa-youtube"></i></a>
                    <a href="#" class="social-icon"><i class="fa-brands fa-tiktok"></i></a>
                </div>
            </div>

            @foreach($footerMenus as $menu)
                <div class="footer-col">
                    <h4 style="color: white; margin-bottom: 20px; font-size: 14px">
                        {{ __($menu->name) }}
                    </h4>
                    @foreach($menu->menuItems as $item)
                        <a href="{{ url($item->url) }}" class="footer-link">{{ __($item->title) }}</a>
                    @endforeach
                </div>
            @endforeach

            <div class="footer-col" style="grid-column: span 1.5">
                <h4 style="color: white; margin-bottom: 20px; font-size: 14px">
                    @lang('LIÊN HỆ')
                </h4>
                <p style="color: #999; font-size: 13px; margin-bottom: 10px">
                    <i class="fa-solid fa-location-dot" style="margin-right: 10px"></i>
                    {{ __(@$footerContent->data_values->address) }}
                </p>
                <p style="color: #999; font-size: 13px; margin-bottom: 10px">
                    <i class="fa-solid fa-phone" style="margin-right: 10px"></i>
                    @lang('Hotline'): {{ @$footerContent->data_values->hotline }}
                </p>
                <p style="color: #999; font-size: 13px; margin-bottom: 10px">
                    <i class="fa-solid fa-envelope" style="margin-right: 10px"></i>
                    @lang('Email'): {{ @$footerContent->data_values->email }}
                </p>
            </div>
        </div>
        <div
            style="height: 40px;  display: flex; align-items: center; justify-content: center; color: #666; font-size: 12px;">
            <p>&copy; {{ @$footerContent->data_values->copyright_text }}</p>
        </div>
    </div>
</footer>