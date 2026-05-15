<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                're_property_type_id',
                're_direction_id',
                're_legal_status_id',
            ]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('re_property_type', 100)->nullable()->after('re_type')->comment('Loại BĐS text');
            $table->string('re_direction', 50)->nullable()->after('re_floor')->comment('Hướng nhà text');
            $table->string('re_legal_status', 100)->nullable()->after('re_direction')->comment('Tình trạng pháp lý text');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                're_property_type',
                're_direction',
                're_legal_status',
            ]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('re_property_type_id')->default(0)->after('re_type');
            $table->unsignedBigInteger('re_direction_id')->nullable()->after('re_area');
            $table->unsignedBigInteger('re_legal_status_id')->nullable()->after('re_direction_id');
        });
    }
};
