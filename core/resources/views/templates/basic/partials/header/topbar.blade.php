@php
    $topbarContent = getContent('topbar.content', true);
@endphp
<div class="top-bar">
    <div class="container top-bar-inner d-flex justify-content-between align-items-center">
        <div class="top-bar-left d-flex gap-3">
            <a href="{{ @$topbarContent->data_values->seller_link ?? '#' }}" class="text-decoration-none">@lang('Kênh Người Bán')</a>
            <div class="vr" style="width: 1px; height: 14px; align-self: center;"></div>
            <a href="{{ @$topbarContent->data_values->logistics_link ?? '#' }}" class="text-decoration-none">@lang('Vận chuyển Logistics')</a>
            <div class="vr" style="width: 1px; height: 14px; align-self: center;"></div>
            <a href="{{ @$topbarContent->data_values->app_link ?? '#' }}" class="text-decoration-none">@lang('Tải ứng dụng')</a>
            <div class="vr" style="width: 1px; height: 14px; align-self: center;"></div>
            <div class="d-flex align-items-center gap-2">
                <span>@lang('Kết nối')</span>
                <a href="{{ @$topbarContent->data_values->facebook_link ?? '#' }}"><i class="fa-brands fa-facebook"></i></a>
                <a href="{{ @$topbarContent->data_values->instagram_link ?? '#' }}"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
        <div class="top-bar-right d-flex gap-3 align-items-center">
            <a href="tel:{{ @$topbarContent->data_values->hotline ?? gs('hotline') }}" class="text-decoration-none fw-bold">
                <i class="fa-solid fa-phone me-1"></i>
                @lang('Hotline'): {{ @$topbarContent->data_values->hotline ?? gs('hotline') }}
            </a>
            <a href="{{ @$topbarContent->data_values->support_link ?? '#' }}" class="text-decoration-none"><i class="fa-regular fa-circle-question me-1"></i> @lang('Hỗ trợ')</a>
        </div>
    </div>
</div>