@props(['items' => []])

<div class="breadcrumb-section" style="background-color: #f8f9fa; padding: 20px 0; border-bottom: 1px solid #eaeaea;">
    <div class="container">
        <a href="{{ route('home') }}" style="color: #666; font-size: 14px">{{ __('Trang chủ') }}</a>
        @foreach($items as $item)
            <span style="margin: 0 10px; color: #ccc">/</span>
            @if(isset($item['url']))
                <a href="{{ $item['url'] }}" style="color: #666; font-size: 14px">{{ __($item['name']) }}</a>
            @else
                <span style="color: var(--primary); font-size: 14px; font-weight: 600">{{ __($item['name']) }}</span>
            @endif
        @endforeach
    </div>
</div>
