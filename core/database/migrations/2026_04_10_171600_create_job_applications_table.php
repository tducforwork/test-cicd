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
        Schema::create('job_applications', function (Blueprint $row) {
            $row->id();
            $row->unsignedBigInteger('job_id')->index();
            $row->unsignedBigInteger('user_id')->default(0)->index();
            $row->string('name', 100);
            $row->string('email', 100);
            $row->string('phone', 40);
            $row->text('address')->nullable();
            $row->string('cv_file')->nullable();
            $row->tinyInteger('status')->default(0)->comment('0: Pending, 1: Reviewed, 2: Rejected');
            $row->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_applications');
    }
};
