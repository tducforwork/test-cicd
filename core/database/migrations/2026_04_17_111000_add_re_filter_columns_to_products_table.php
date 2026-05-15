<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('products', 're_property_type')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('re_property_type')->nullable()->after('extra_descriptions');
                $table->string('re_transaction_type')->nullable()->after('re_property_type');
                $table->string('re_transaction_method')->nullable()->after('re_transaction_type');
                $table->text('re_listing_condition')->nullable()->after('re_transaction_method');
            });
        }

        // Di chuyển dữ liệu từ extra_descriptions (JSON) sang các cột mới
        Product::withTrashed()->where('is_real_estate', 1)->chunk(100, function ($products) {
            foreach ($products as $product) {
                $extra = $product->extra_descriptions;
                if (!is_array($extra)) continue;

                $dataToUpdate = [];
                foreach ($extra as $item) {
                    $key = @$item['key'];
                    $value = @$item['value'];
                    if (!$key) continue;

                    $keySlug = \Illuminate\Support\Str::slug($key);

                    if ($keySlug == 'loai-hinh-bds') {
                        $dataToUpdate['re_property_type'] = $value;
                    } elseif ($keySlug == 'loai-giao-dich-gia') {
                        $dataToUpdate['re_transaction_type'] = $value;
                    } elseif ($keySlug == 'hinh-thuc-giao-dich') {
                        $dataToUpdate['re_transaction_method'] = $value;
                    } elseif ($keySlug == 'dieu-kien') {
                        $dataToUpdate['re_listing_condition'] = $value;
                    }
                }

                if (!empty($dataToUpdate)) {
                    $product->update($dataToUpdate);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                're_property_type',
                're_transaction_type',
                're_transaction_method',
                're_listing_condition'
            ]);
        });
    }
};
