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
        if (!Schema::hasTable('job_industries')) {
            Schema::create('job_industries', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('jobs')) {
            Schema::table('jobs', function (Blueprint $table) {
                if (!Schema::hasColumn('jobs', 'industry_id')) {
                    $table->unsignedBigInteger('industry_id')->nullable()->after('seller_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('jobs') && Schema::hasColumn('jobs', 'industry_id')) {
            Schema::table('jobs', function (Blueprint $table) {
                $table->dropColumn('industry_id');
            });
        }
        
        Schema::dropIfExists('job_industries');
    }
};
