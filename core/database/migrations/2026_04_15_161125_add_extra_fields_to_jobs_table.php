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
        Schema::table('jobs', function (Blueprint $table) {
            $table->tinyInteger('job_type')->default(1)->after('id')->comment('1: Recruitment, 2: Job Searching');
            $table->string('short_description', 160)->nullable()->after('description');
            $table->string('cv_file')->nullable()->after('application_link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn(['job_type', 'short_description', 'cv_file']);
        });
    }
};
