@extends('Template::layouts.app')
@section('panel')


    @include('Template::partials.header')
    @yield('content')
    @include('Template::partials.footer')

    @php
        $cookie = App\Models\Frontend::where('data_keys', 'cookie.data')->first();
    @endphp

    @if (@$cookie->data_values->status && !\Cookie::get('gdpr_cookie'))
        <div class="cookie__wrapper">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <p class="text--white my-2">
                        @php echo @$cookie->data_values->description @endphp
                        <a class="btn btn--white my-2" href="{{ @$cookie->data_values->link }}"
                            target="_blank">@lang('Read Policy')</a>
                    </p>
                    <button type="button" class="btn btn--base policy h-unset">@lang('Accept')</button>
                </div>
            </div>
        </div>
    @endif
@endsection