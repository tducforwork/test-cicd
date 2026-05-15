@php
    $introContent = getContent('intro_section.content', true);
    $introElements = getContent('intro_section.element', false, null, true);
@endphp
<!-- GIỚI THIỆU -->
<section class="intro-section py-lg-5 py-4">
    <div class="container intro-grid">
        <div class="intro-content">
            <span class="intro-label">{{ __(@$introContent->data_values->label) }}</span>
            <h2 class="intro-title">
                {{ __(@$introContent->data_values->title) }}
            </h2>
            <p class="intro-desc">
                {{ __(@$introContent->data_values->description) }}
            </p>
            <ul class="intro-features">
                @foreach($introElements as $item)
                <li>
                    <i class="fa-solid fa-check"></i>
                    <span>{{ __(@$item->data_values->feature) }}</span>
                </li>
                @endforeach
            </ul>
            <a href="{{ @$introContent->data_values->button_url }}" class="btn btn-primary">{{ __(@$introContent->data_values->button_text) }}</a>
        </div>
        <div class="intro-image-wrapper">
            <img src="{{ getImage('assets/images/frontend/intro_section/' . @$introContent->data_values->intro_image, '600x400') }}" alt="{{ __(@$introContent->data_values->title) }}" class="intro-image" />
        </div>
    </div>
</section>
