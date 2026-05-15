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
        Schema::create('real_estate_configs', function (Blueprint $table) {
            $table->id();
            $table->string('type')->comment('direction, legal_status, province, ward');
            $table->string('name');
            $table->tinyInteger('status')->default(1)->comment('1: active, 0: inactive');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('for ward to reference province');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('real_estate_configs');
    }
};
