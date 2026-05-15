<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gateway;
use App\Models\GatewayCurrency;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AutomaticGatewayController extends Controller
{
    public function index()
    {
        $pageTitle = __('Automatic Gateways');
        $gateways = Gateway::automatic()->with('currencies')->get();
        return view('admin.gateways.automatic.list', compact('pageTitle', 'gateways'));
    }

    public function edit($alias)
    {
        $gateway = Gateway::automatic()->with('currencies', 'currencies.method')->where('alias', $alias)->firstOrFail();
        $pageTitle = __('Update Gateway');

        $supportedCurrencies = collect($gateway->supported_currencies)->except($gateway->currencies->pluck('currency'));
        $parameters = collect(json_decode($gateway->gateway_parameters));
        $globalParameters = null;
        $hasCurrencies = false;
        $currencyIndex = 1;

        if ($gateway->currencies->count()) {
            $globalParameters = json_decode($gateway->currencies->first()->gateway_parameter);
            $hasCurrencies = true;
        }

        return view('admin.gateways.automatic.edit', compact('pageTitle', 'gateway', 'supportedCurrencies', 'parameters', 'hasCurrencies', 'currencyIndex', 'globalParameters'));
    }


    public function update(Request $request, $code)
    {
        $gateway = Gateway::where('code', $code)->firstOrFail();
        $this->gatewayValidator($request)->validate();
        $this->gatewayCurrencyValidator($request, $gateway)->validate();

        $parameters = collect(json_decode($gateway->gateway_parameters));

        foreach ($parameters->where('global', true) as $key => $pram) {
            $parameters[$key]->value = $request->global[$key];
        }

        $filename = $gateway->image;
        if ($request->has('image')) {
            try {
                $filename = fileUploader($request->image, getFilePath('gateway'), old: $filename);
            } catch (\Exception $exp) {
                $notify[] = ['errors', __('Image could not be uploaded')];
                return back()->withNotify($notify);
            }
        }

        $gateway->alias = $request->alias;
        $gateway->gateway_parameters = json_encode($parameters);
        $gateway->image = $filename;
        $gateway->save();

        if ($request->has('currency')) {
            foreach ($request->currency as $key => $currencyData) {
                // Tìm currency hiện tại theo name (hoặc ID nếu có, nhưng ở đây dùng logic name)
                $gatewayCurrency = $gateway->currencies()->where('name', $currencyData['name'])->first();

                if (!$gatewayCurrency) {
                    $gatewayCurrency = new GatewayCurrency();
                    $gatewayCurrency->method_code = $code;
                }

                $param = [];
                foreach ($parameters->where('global', true) as $pkey => $pram) {
                    $param[$pkey] = $pram->value;
                }

                foreach ($parameters->where('global', false) as $paramKey => $paramValue) {
                    $param[$paramKey] = $currencyData['param'][$paramKey];
                }

                $gatewayCurrency->name = $currencyData['name'];
                $gatewayCurrency->gateway_alias = $gateway->alias;

                // Cập nhật thông minh: nếu không có trong request thì giữ nguyên giá trị cũ
                $gatewayCurrency->currency       = $currencyData['currency'] ?? $gatewayCurrency->currency;
                $gatewayCurrency->symbol         = $currencyData['symbol'] ?? $gatewayCurrency->symbol;
                $gatewayCurrency->min_amount     = $currencyData['min_amount'] ?? $gatewayCurrency->min_amount;
                $gatewayCurrency->max_amount     = $currencyData['max_amount'] ?? $gatewayCurrency->max_amount;
                $gatewayCurrency->fixed_charge   = $currencyData['fixed_charge'] ?? $gatewayCurrency->fixed_charge;
                $gatewayCurrency->percent_charge = $currencyData['percent_charge'] ?? $gatewayCurrency->percent_charge;
                $gatewayCurrency->rate           = $currencyData['rate'] ?? $gatewayCurrency->rate;

                $gatewayCurrency->gateway_parameter = json_encode($param);
                $gatewayCurrency->save();
            }
        } else {
            foreach ($gateway->currencies as $gatewayCurrency) {
                $param = json_decode($gatewayCurrency->gateway_parameter, true);
                foreach ($parameters->where('global', true) as $pkey => $pram) {
                    $param[$pkey] = $pram->value;
                }
                $gatewayCurrency->gateway_parameter = json_encode($param);
                $gatewayCurrency->save();
            }
        }

        $notify[] = ['success', $gateway->name . ' ' . __('updated successfully')];
        return to_route('admin.gateway.automatic.edit', $gateway->alias)->withNotify($notify);
    }

    public function remove($id)
    {
        $gatewayCurrency = GatewayCurrency::findOrFail($id);
        fileManager()->removeFile(getFilePath('gateway') . '/' . $gatewayCurrency->image);
        $gatewayCurrency->delete();
        $notify[] = ['success', __('Gateway currency removed successfully')];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Gateway::changeStatus($id);
    }

    public function gatewayValidator(Request $request)
    {
        $validationRule = [
            'alias' => 'required',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ];
        $validator = Validator::make($request->all(), $validationRule);
        return $validator;
    }

    public function gatewayCurrencyValidator(Request $request, Gateway $gateway)
    {
        $customAttributes = [];
        $validationRule = [];

        $paramList = collect(json_decode($gateway->gateway_parameters));
        $supportedCurrencies = collect((array)$gateway->supported_currencies)->keys()->implode(',');

        foreach ($paramList->where('global', true) as $key => $pram) {
            $validationRule['global.' . $key] = 'required';
            $customAttributes['global.' . $key] = keyToTitle($key);
        }


        if ($request->has('currency')) {
            foreach ($request->currency as $key => $currency) {
                // Chấp nhận nullable nếu là cập nhật và các trường này bị ẩn trong UI
                $validationRule['currency.' . $key . '.currency']       = 'sometimes|required|string|in:' . $supportedCurrencies;
                $validationRule['currency.' . $key . '.symbol']         = 'sometimes|required|string';
                $validationRule['currency.' . $key . '.name']           = 'required';
                $validationRule['currency.' . $key . '.min_amount']     = 'sometimes|required|numeric|gt:0';
                $validationRule['currency.' . $key . '.max_amount']     = 'sometimes|required|numeric|gt:0';
                $validationRule['currency.' . $key . '.fixed_charge']   = 'sometimes|required|numeric|gte:0';
                $validationRule['currency.' . $key . '.percent_charge'] = 'sometimes|required|numeric|gte:0|max:100';
                $validationRule['currency.' . $key . '.rate']           = 'sometimes|required|numeric|gt:0';

                $currencyName = $currency['name'] ?? 'Currency';
                $currencyIdentifier = $this->currencyIdentifier($currencyName, $gateway->name);

                $customAttributes['currency.' . $key . '.name']           = $currencyIdentifier . ' name';
                $customAttributes['currency.' . $key . '.min_amount']     = $currencyIdentifier . ' ' . keyToTitle('min_amount');
                $customAttributes['currency.' . $key . '.max_amount']     = $currencyIdentifier . ' ' . keyToTitle('max_amount');
                $customAttributes['currency.' . $key . '.fixed_charge']   = $currencyIdentifier . ' ' . keyToTitle('fixed_charge');
                $customAttributes['currency.' . $key . '.percent_charge'] = $currencyIdentifier . ' ' . keyToTitle('percent_charge');
                $customAttributes['currency.' . $key . '.rate']           = $currencyIdentifier . ' ' . keyToTitle('rate');
                $customAttributes['currency.' . $key . '.currency']           = $currencyIdentifier . ' ' . keyToTitle('currency');
                $customAttributes['currency.' . $key . '.symbol']           = $currencyIdentifier . ' ' . keyToTitle('symbol');

                foreach ($paramList->where('global', false) as $param_key => $param_value) {
                    $validationRule['currency.' . $key . '.param.' . $param_key] = 'required';
                    $customAttributes['currency.' . $key . '.param.' . $param_key] = $currencyIdentifier . ' ' . keyToTitle($param_value->title);
                }
            }
        }

        $validator = Validator::make($request->all(), $validationRule, $customAttributes);
        return $validator;
    }

    private function currencyIdentifier($name, $default = '')
    {
        return $name ?? $default;
    }
}
