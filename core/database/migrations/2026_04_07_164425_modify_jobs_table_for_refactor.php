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
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('company_name')->nullable()->change();
            $table->string('company_logo')->nullable()->change();
            $table->unsignedBigInteger('job_level_id')->nullable()->after('company_logo');
            $table->string('employment_type')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('company_name')->nullable(false)->change();
            $table->dropColumn('job_level_id');
        });
    }
};
