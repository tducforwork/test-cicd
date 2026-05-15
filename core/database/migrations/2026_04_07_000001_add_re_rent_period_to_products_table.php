<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Chu kỳ giá cho tin cho thuê: day | month | year
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('re_rent_period', 20)->nullable()->after('re_price_to')->comment('rent only: day, month, year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('re_rent_period');
        });
    }
};
