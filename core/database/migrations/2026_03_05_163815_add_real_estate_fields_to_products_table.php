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
            $table->boolean('is_real_estate')->default(false)->after('id');
            $table->string('re_type')->nullable()->comment('sale, rent')->after('is_real_estate');
            $table->string('re_price_type')->nullable()->comment('fixed, negotiable')->after('re_type');
            $table->decimal('re_price_from', 28, 8)->default(0)->after('re_price_type');
            $table->decimal('re_price_to', 28, 8)->default(0)->after('re_price_from');
            $table->decimal('re_area', 18, 8)->default(0)->after('re_price_to');
            $table->unsignedBigInteger('re_direction_id')->nullable()->after('re_area');
            $table->unsignedBigInteger('re_legal_status_id')->nullable()->after('re_direction_id');
            $table->unsignedBigInteger('re_province_id')->nullable()->after('re_legal_status_id');
            $table->unsignedBigInteger('re_ward_id')->nullable()->after('re_province_id');
            $table->text('re_address')->nullable()->after('re_ward_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'is_real_estate',
                're_type',
                're_price_type',
                're_price_from',
                're_price_to',
                're_area',
                're_direction_id',
                're_legal_status_id',
                're_province_id',
                're_ward_id',
                're_address'
            ]);
        });
    }
};
