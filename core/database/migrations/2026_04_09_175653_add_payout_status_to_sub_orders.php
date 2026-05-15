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
        Schema::table('sub_orders', function (Blueprint $col) {
            $col->tinyInteger('is_payout')->default(0)->comment('0: Pending, 1: Paid to Seller');
            $col->dateTime('payout_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_orders', function (Blueprint $col) {
            $col->dropColumn(['is_payout', 'payout_at']);
        });
    }
};
