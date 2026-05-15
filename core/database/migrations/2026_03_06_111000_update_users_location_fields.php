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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('province_id')->nullable()->after('address');
            $table->unsignedBigInteger('ward_id')->nullable()->after('province_id');

            // Optionally remove old fields
            $table->dropColumn(['city', 'state', 'zip', 'country_name', 'country_code']);

            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('set null');
            $table->foreign('ward_id')->references('id')->on('wards')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['province_id']);
            $table->dropForeign(['ward_id']);
            $table->dropColumn(['province_id', 'ward_id']);

            $table->string('country_name')->nullable();
            $table->string('country_code')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
        });
    }
};
