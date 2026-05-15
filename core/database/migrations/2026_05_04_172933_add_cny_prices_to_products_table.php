<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('cny_price', 15, 2)->nullable();
            $table->decimal('cny_discount_price', 15, 2)->nullable();
            $table->tinyInteger('currency_type')->default(1)->comment('1: VND, 2: CNY');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['cny_price', 'cny_discount_price', 'currency_type']);
        });
    }
};
