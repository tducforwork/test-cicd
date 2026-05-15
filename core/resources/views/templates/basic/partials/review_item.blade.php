@php
    $initials = '';
    if ($review->user_id == 0) {
        $names = explode(' ', $review->name);
        foreach ($names as $n) {
            $initials .= strtoupper(substr($n, 0, 1));
        }
        $initials = substr($initials, 0, 2);
    }
@endphp

<div class="product-reviews__item">
    <div class="product-reviews__header">
        @if ($review->user_id == 0)
            <div class="product-reviews__avatar product-reviews__avatar--initials">
                {{ $initials }}
            </div>
        @else
            <img src="{{ getImage(getFilePath('userProfile') . '/' . @$review->user->image, getFileSize('userProfile')) }}"
                class="product-reviews__avatar" />
        @endif
        <div class="product-reviews__info">
            <h4 class="product-reviews__name">{{ $review->name ?: @$review->user->fullname }}</h4>
            <div class="product-reviews__rating">
                @for ($i = 1; $i <= 5; $i++)
                    <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                @endfor
            </div>
        </div>
    </div>
    <p class="product-reviews__text">{{ $review->review }}</p>
    <div class="product-reviews__meta">
        {{ showDateTime($review->created_at, 'd/m/Y H:i') }}
        @if($review->variant) | @lang('Phân loại'): {{ $review->variant }} @endif
    </div>

    @if ($review->images->count() > 0)
        <div class="product-reviews__gallery">
            @foreach ($review->images as $img)
                <a href="{{ getImage(getFilePath('review') . '/' . $img->image) }}"
                    data-fancybox="review-{{ $review->id }}">
                    <img src="{{ getImage(getFilePath('review') . '/' . $img->image) }}"
                        class="product-reviews__gallery-img" />
                </a>
            @endforeach
        </div>
    @endif
</div>
