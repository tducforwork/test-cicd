<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\RealEstateConfig;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('real_estate_configs', function (Blueprint $col) {
            $col->string('slug')->nullable()->after('name');
        });

        // Tự động tạo slug cho các bản ghi cũ
        $configs = RealEstateConfig::all();
        foreach ($configs as $config) {
            $config->slug = Str::slug($config->name);
            $config->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('real_estate_configs', function (Blueprint $col) {
            $col->dropColumn('slug');
        });
    }
};
