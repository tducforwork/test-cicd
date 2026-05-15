<?php

use App\Constants\Status;
use App\Models\Form;
use App\Models\Gateway;
use App\Models\GatewayCurrency;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Create Form for MoMo
        $form = new Form();
        $form->act = 'manual_deposit';
        $form->form_data = [
            'transaction_id' => [
                'name' => 'Mã giao dịch / Transaction ID',
                'label' => 'transaction_id',
                'is_required' => 'required',
                'instruction' => 'Nhập mã giao dịch sau khi chuyển khoản thành công.',
                'extensions' => '',
                'options' => [],
                'type' => 'text',
                'width' => '12',
            ]
        ];
        $form->save();

        // 2. Create Gateway for MoMo
        $gateway = new Gateway();
        $gateway->code = 1000;
        $gateway->form_id = $form->id;
        $gateway->name = 'MoMo';
        $gateway->alias = 'momo';
        $gateway->image = 'momo.png';
        $gateway->status = Status::ENABLE;
        $gateway->gateway_parameters = json_encode([]);
        $gateway->supported_currencies = [];
        $gateway->crypto = Status::DISABLE;
        $gateway->description = 'Vui lòng chuyển khoản qua ví MoMo. <br> Số điện thoại: 0123456789 <br> Tên: NGUYEN VAN A <br> Nội dung: [Mã đơn hàng]';
        $gateway->save();

        // 3. Create Gateway Currency for MoMo
        $gatewayCurrency = new GatewayCurrency();
        $gatewayCurrency->name = 'MoMo';
        $gatewayCurrency->gateway_alias = 'momo';
        $gatewayCurrency->currency = 'VND';
        $gatewayCurrency->symbol = 'đ';
        $gatewayCurrency->method_code = 1000;
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
        $gateway = Gateway::where('code', 1000)->first();
        if ($gateway) {
            Form::where('id', $gateway->form_id)->delete();
            GatewayCurrency::where('method_code', 1000)->delete();
            $gateway->delete();
        }
    }
};
