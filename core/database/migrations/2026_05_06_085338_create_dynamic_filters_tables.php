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
        Schema::create('filter_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->tinyInteger('status')->default(1)->comment('1: Active, 0: Inactive');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('filter_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('filter_group_id')->constrained('filter_groups')->onDelete('cascade');
            $table->string('value', 255);
            $table->timestamps();
        });

        Schema::create('product_filter_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('filter_option_id')->constrained('filter_options')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_filter_values');
        Schema::dropIfExists('filter_options');
        Schema::dropIfExists('filter_groups');
    }
};
