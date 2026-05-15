<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('orange'); // orange, green, red, purple
            $table->timestamps();
        });

        Schema::create('product_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('tag_id');
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->integer('flash_percentage')->nullable()->after('is_suggestion');
            $table->string('flash_text')->nullable()->after('flash_percentage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
        Schema::dropIfExists('product_tags');
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['flash_percentage', 'flash_text']);
        });
    }
};
