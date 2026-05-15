<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use App\Models\RealEstateConfig;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Thay đổi kiểu dữ liệu cột
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('re_property_type')->nullable()->change();
            $table->unsignedBigInteger('re_transaction_type')->nullable()->change();
            $table->unsignedBigInteger('re_transaction_method')->nullable()->change();
            $table->text('re_listing_condition')->nullable()->change(); // Để lưu JSON IDs
        });

        // 2. Đồng bộ dữ liệu
        $configs = RealEstateConfig::all();
        
        Product::withTrashed()->where('is_real_estate', 1)->chunk(100, function ($products) use ($configs) {
            foreach ($products as $product) {
                $extra = $product->extra_descriptions;
                if (!is_array($extra)) continue;

                $dataToUpdate = [];
                foreach ($extra as $item) {
                    $key = @$item['key'];
                    $value = @$item['value'];
                    if (!$key || !$value) continue;

                    $keySlug = Str::slug($key);
                    $config = null;

                    if ($keySlug == 'loai-hinh-bds') {
                        $config = $configs->where('type', 'property_type')->filter(function($c) use ($value) {
                            return Str::slug($c->name) == Str::slug($value);
                        })->first();
                        if ($config) $dataToUpdate['re_property_type'] = $config->id;
                        
                    } elseif ($keySlug == 'loai-giao-dich-gia') {
                        $config = $configs->where('type', 'transaction_type')->filter(function($c) use ($value) {
                            return Str::slug($c->name) == Str::slug($value);
                        })->first();
                        if ($config) $dataToUpdate['re_transaction_type'] = $config->id;

                    } elseif ($keySlug == 'hinh-thuc-giao-dich') {
                        $config = $configs->where('type', 'transaction_method')->filter(function($c) use ($value) {
                            return Str::slug($c->name) == Str::slug($value);
                        })->first();
                        if ($config) $dataToUpdate['re_transaction_method'] = $config->id;

                    } elseif ($keySlug == 'dieu-kien') {
                        $conditionNames = array_map('trim', explode(',', $value));
                        $conditionIds = [];
                        foreach ($conditionNames as $cName) {
                            $cConfig = $configs->where('type', 'listing_condition')->filter(function($c) use ($cName) {
                                return Str::slug($c->name) == Str::slug($cName);
                            })->first();
                            if ($cConfig) $conditionIds[] = $cConfig->id;
                        }
                        if (!empty($conditionIds)) {
                            $dataToUpdate['re_listing_condition'] = json_encode($conditionIds);
                        }
                    }
                }

                if (!empty($dataToUpdate)) {
                    Product::withTrashed()->where('id', $product->id)->update($dataToUpdate);
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
            $table->string('re_property_type')->nullable()->change();
            $table->string('re_transaction_type')->nullable()->change();
            $table->string('re_transaction_method')->nullable()->change();
            $table->text('re_listing_condition')->nullable()->change();
        });
    }
};
