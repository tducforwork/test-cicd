@extends('Template::layouts.app')

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/vendor/bootstrap-toggle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/custom.css') }}">
@endpush

@push('style')
    <style>
        .dashboard-menu {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

    .dashboard-section .card {
        border: 1px solid rgba(0, 0, 0, .1);
        border-radius: 10px;
        overflow: hidden;
    }

        .page-title {
            font-weight: 600;
            color: #333;
            margin: 0;
        }
    </style>
@endpush

@section('panel')
<div class="dashboard-section padding-bottom padding-top">
    <div class="container">
        @yield('seller-content')
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/bootstrap-toggle.min.js') }}"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/app.js') }}"></script>
    <script src="{{ asset('assets/admin/js/cu-modal.js') }}"></script>
@endpush
