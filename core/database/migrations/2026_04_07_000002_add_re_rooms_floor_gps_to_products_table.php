<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Thêm trường BĐS: phòng ngủ, phòng tắm, số tầng
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedTinyInteger('re_bedrooms')->default(0)->after('re_rent_period')->comment('Số phòng ngủ');
            $table->unsignedTinyInteger('re_bathrooms')->default(0)->after('re_bedrooms')->comment('Số phòng tắm');
            $table->unsignedTinyInteger('re_floor')->default(0)->after('re_bathrooms')->comment('Số tầng');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                're_bedrooms',
                're_bathrooms',
                're_floor',
            ]);
        });
    }
};
