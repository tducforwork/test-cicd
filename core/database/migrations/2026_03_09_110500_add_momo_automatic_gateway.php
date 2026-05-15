<?php

use App\Constants\Status;
use App\Models\Gateway;
use App\Models\GatewayCurrency;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Remove Previous Manual MoMo Gateway
        GatewayCurrency::where('method_code', 1000)->delete();
        Gateway::where('code', 1000)->delete();

        // 2. Create Automatic Gateway for MoMo
        $gateway = new Gateway();
        $gateway->code = 511; // Next available code for automatic gateways
        $gateway->name = 'MoMo';
        $gateway->alias = 'Momo';
        $gateway->image = 'momo.png';
        $gateway->status = Status::ENABLE;
        $gateway->gateway_parameters = json_encode([
            'partner_code' => [
                'title' => 'Partner Code',
                'global' => true,
                'value' => 'MOMOBKUN20180529' // Default Sandbox
            ],
            'access_key' => [
                'title' => 'Access Key',
                'global' => true,
                'value' => 'klm05TvNBqg7n6uD' // Default Sandbox
            ],
            'secret_key' => [
                'title' => 'Secret Key',
                'global' => true,
                'value' => 'at67qH6mk8w5Y1n71oty6hcS9K807vS8' // Default Sandbox
            ],
        ]);
        $gateway->supported_currencies = json_encode(['VND' => 'VND']);
        $gateway->crypto = Status::DISABLE;
        $gateway->save();

        // 3. Create Gateway Currency for MoMo
        $gatewayCurrency = new GatewayCurrency();
        $gatewayCurrency->name = 'MoMo';
        $gatewayCurrency->gateway_alias = 'Momo';
        $gatewayCurrency->currency = 'VND';
        $gatewayCurrency->symbol = 'đ';
        $gatewayCurrency->method_code = 511;
        $gatewayCurrency->min_amount = 10000;
        $gatewayCurrency->max_amount = 50000000;
        $gatewayCurrency->fixed_charge = 0;
        $gatewayCurrency->percent_charge = 0;
        $gatewayCurrency->rate = 1;
        $gatewayCurrency->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        GatewayCurrency::where('method_code', 511)->delete();
        Gateway::where('code', 511)->delete();
    }
};
