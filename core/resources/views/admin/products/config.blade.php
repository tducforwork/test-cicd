@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.products.config') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('VAT Percentage') (%)</label>
                                    <input class="form-control" type="number" step="0.01" name="vat_percentage" value="{{ getAmount($config->vat_percentage) }}">
                                    <small class="text-muted">@lang('Tax rate percentage (e.g. 10)')</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('CNY Exchange Rate') (1 CNY = ? VND)</label>
                                    <input class="form-control" type="number" step="0.01" name="cny_exchange_rate" value="{{ getAmount($config->cny_exchange_rate) }}">
                                    <small class="text-muted">@lang('Exchange rate used to convert CNY to VND automatically')</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
