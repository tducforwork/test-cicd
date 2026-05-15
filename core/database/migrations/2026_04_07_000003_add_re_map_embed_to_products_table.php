<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Thêm trường lưu iframe embed bản đồ Google Maps
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('re_map_embed')->nullable()->after('re_floor')->comment('Iframe embed Google Maps');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('re_map_embed');
        });
    }
};
