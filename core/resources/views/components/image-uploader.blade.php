@props([
'type' => null,
'image' => null,
'imagePath' => null,
'size' => null,
'hasSize' => true,
'name' => 'image',
'id' => 'image-upload-input1',
'accept' => '.png, .jpg, .jpeg',
'required' => true,
'darkMode' => false,
'showMessage' => true,
])
@php
    if (!$size && $hasSize && $type) {
        $size = getFileSize($type);
    }

    $isVideo = str_contains(strtolower($name), 'video');
    if ($isVideo) {
        $accept = '.mp4, .mov, .avi';
    }

    if (filter_var($image, FILTER_VALIDATE_URL)) {
        $imagePath = $image;
    } else {
        $imagePath = $imagePath ?? getImage(getFilePath($type) . '/' . $image, $size);
    }
@endphp
<div {{ $attributes->merge(['class' => 'image--uploader ' . ($isVideo ? 'video--uploader' : '')]) }}>
    <style>
        .image--uploader .image-upload-wrapper {
            border: 2px dashed #fb4d1b !important;
            border-radius: 12px;
            transition: all 0.3s ease;
            aspect-ratio: 1/1;
            width: 100%;
            position: relative;
            overflow: hidden;
            background: #fff8f6;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .image--uploader .image-upload-preview {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 10px;
            z-index: 1;
            background-size: 100% 100% !important;
            background-position: center !important;
        }

        .image--uploader .image-upload-input-wrapper {
            z-index: 2;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .image--uploader .image-upload-input-wrapper label {
            width: 100%;
            height: auto;
            padding: 20px;
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            border-radius: 8px;
            display: flex !important;
            flex-direction: column !important;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin-bottom: 0;
            color: #fb4d1b !important;
            position: relative !important;
            z-index: 3;
        }

        .image--uploader .image-upload-input-wrapper label i {
            font-size: 2.5rem !important;
            margin-bottom: 8px !important;
        }

        .image--uploader .image-upload-input-wrapper label::after {
            content: "{{ $isVideo ? __('Chọn video') : __('Chọn ảnh') }}";
            font-weight: 600;
            color: #333;
            font-size: 1rem;
        }

        .image--uploader .image-upload-preview:not([style*="default.png"]):not([style*="520x600"]):not([style*="300x300"]):not([style*="placeholder-image"])+.image-upload-input-wrapper label {
            opacity: 0;
        }

        .image--uploader .image-upload-preview[style*="default.png"],
        .image--uploader .image-upload-preview[style*="520x600"],
        .image--uploader .image-upload-preview[style*="300x300"],
        .image--uploader .image-upload-preview[style*="placeholder-image"] {
            background-image: none !important;
        }

        .image--uploader.video--uploader .image-upload-preview {
            background-image: url('{{ asset('assets/images/frontend/kviet/vid-img-1.png') }}') !important;
            background-size: 50% !important;
            background-repeat: no-repeat !important;
            opacity: 0.3;
        }

        .image--uploader .image-upload-wrapper:hover .image-upload-input-wrapper label {
            opacity: 1 !important;
        }
    </style>
    <div class="image-upload-wrapper">
        <div class="image-upload-preview {{ $darkMode ? 'bg--dark' : '' }}" style="background-image: url({{ $imagePath }})">
            <div class="video-preview-overlay d-flex flex-column align-items-center justify-content-center h-100 w-100 {{ ($isVideo && $image) ? '' : 'd-none' }}" style="background: rgba(0,0,0,0.6); color: #fff;">
                <i class="la la-play-circle" style="font-size: 4rem;"></i>
                <span class="mt-2 fw-bold">@lang('Video đã tải lên')</span>
                <small class="text-white-50 video-name-display text-center px-2">{{ $image }}</small>
            </div>
        </div>
        <div class="image-upload-input-wrapper">
            <input type="file" class="image-upload-input" name="{{ $name }}" id="{{ $id }}" accept="{{ $accept }}" @required($required)>
            <label for="{{ $id }}"><i class="{{ $isVideo ? 'la la-video' : 'la la-cloud-upload' }}"></i></label>
        </div>
    </div>

    @if ($showMessage)
    <div class="mt-2 text-center text-sm-start">
        <small class="text-muted text--small"> @lang('Supported Files:')
            <b class="text--primary">{{ $accept }}.</b>
            @if ($size && !$isVideo)
            <br>@lang('Image will be resized into') <b>{{ $size }}</b>@lang('px')
            @endif
        </small>
    </div>
    @endif
</div>

@push('script')
<script>
    (function($) {
        "use strict";
        // Handle drag and drop events
        $(document).on('dragover', '.image--uploader', function(e) {
            e.preventDefault();
            $(this).addClass('dragging');
        });

        $(document).on('dragleave', '.image--uploader', function(e) {
            e.preventDefault();
            $(this).removeClass('dragging');
        });

        $(document).on('drop', '.image--uploader', function(e) {
            e.preventDefault();
            $(this).removeClass('dragging');

            const files = e.originalEvent.dataTransfer.files;

            if (files.length) {
                const fileInput = $(this).find('.image-upload-input')[0];
                fileInput.files = files;
                proPicURL(fileInput);
            }
        });
    })
    (jQuery);
</script>
@endpush