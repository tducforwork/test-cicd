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
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'icon')) {
                $table->string('icon', 255)->nullable()->after('name');
            }
            if (!Schema::hasColumn('categories', 'icon_color')) {
                $table->string('icon_color', 20)->nullable()->after('icon');
            }
            if (!Schema::hasColumn('categories', 'bg_color')) {
                $table->string('bg_color', 20)->nullable()->after('icon_color');
            }
            if (Schema::hasColumn('categories', 'thumb')) {
                $table->dropColumn('thumb');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'is_search')) {
                $table->tinyInteger('is_search')->default(0)->after('is_featured');
            }
            if (!Schema::hasColumn('products', 'is_topdeal')) {
                $table->tinyInteger('is_topdeal')->default(0)->after('is_search');
            }
            if (!Schema::hasColumn('products', 'is_suggestion')) {
                $table->tinyInteger('is_suggestion')->default(0)->after('is_topdeal');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['icon_color', 'bg_color']);
            if (!Schema::hasColumn('categories', 'thumb')) {
                $table->string('thumb', 255)->nullable()->after('image');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_search', 'is_topdeal', 'is_suggestion']);
        });
    }
};
