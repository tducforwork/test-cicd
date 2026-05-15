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
        Schema::create('product_configs', function (Blueprint $table) {
            $table->id();
            $table->decimal('vat_percentage', 5, 2)->default(0);
            $table->decimal('cny_exchange_rate', 15, 2)->default(3500);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_configs');
    }
};
