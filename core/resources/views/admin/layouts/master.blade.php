<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ gs()->siteName($pageTitle ?? '') }}</title>

    <link rel="shortcut icon" type="image/png" href="{{ siteFavicon() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/admin/css/vendor/bootstrap-toggle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}">

    @stack('style-lib')

    <link rel="stylesheet" href="{{ asset('assets/global/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/custom.css') }}">

    @stack('style')
</head>

<body>
    @yield('content')

    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/bootstrap-toggle.min.js') }}"></script>

    @include('partials.notify')
    @stack('script-lib')

    <script src="{{ asset('assets/global/js/magnific-popup.min.js') }}"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/app.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom.js') }}"></script>
    <script src="{{ asset('assets/admin/js/cu-modal.js') }}"></script>

    {{-- LOAD CKEDITOR 5 --}}
    <script>
        "use strict";
        class MyUploadAdapter {
            constructor( loader ) {
                this.loader = loader;
            }
            upload() {
                return this.loader.file
                    .then( file => new Promise( ( resolve, reject ) => {
                        const data = new FormData();
                        data.append( 'image', file );
                        data.append( '_token', $('meta[name="csrf-token"]').attr('content') );

                        $.ajax({
                            url: "{{ route('admin.editor.upload') }}",
                            method: 'POST',
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                if(response.data && response.data.link) {
                                    resolve( { default: response.data.link } );
                                } else if(response.url) {
                                    resolve( { default: response.url } );
                                } else {
                                    reject( 'Upload failed' );
                                }
                            },
                            error: function(err) {
                                reject( 'Upload failed' );
                            }
                        });
                    } ) );
            }
            abort() {
            }
        }

        function MyCustomUploadAdapterPlugin( editor ) {
            editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
                return new MyUploadAdapter( loader );
            };
        }

        $(document).ready(function() {
            window.editors = {};
            $(".ckeditor").each(function(index) {
                var el = this;
                ClassicEditor.create(el, {
                    extraPlugins: [ MyCustomUploadAdapterPlugin ],
                }).then(editor => {
                    window.editors[el.id || el.name || 'editor_' + index] = editor;
                    
                    // On change, update the original textarea so validation and standard form submits work
                    editor.model.document.on('change:data', () => {
                        $(el).val(editor.getData());
                    });
                }).catch(error => {
                    console.error(error);
                });
            });
        });

        function createSlug(inputString) {
            if (!inputString) return '';
            return inputString
                .trim()
                .toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        (function($) {
            $(document).on('mouseover ', '.ckeditor-main,.ckeditor-panelContain', function() {
                $('.ckeditor-main').focus();
            });

            $('.breadcrumb-nav-open').on('click', function() {
                $(this).toggleClass('active');
                $('.breadcrumb-nav').toggleClass('active');
            });

            $('.breadcrumb-nav-close').on('click', function() {
                $('.breadcrumb-nav').removeClass('active');
            });

            if ($('.topTap').length) {
                $('.breadcrumb-nav-open').removeClass('d-none');
            }

            $('.image-popup').magnificPopup({
                type: 'image'
            });

            let disableSubmission = false;
            $('.disableSubmission').on('submit', function(e) {
                if (disableSubmission) {
                    e.preventDefault()
                } else {
                    disableSubmission = true;
                }
            });
        })(jQuery);
    </script>

    @stack('script')

</body>

</html>