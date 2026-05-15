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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();

            // Thông tin việc làm
            $table->string('title')->comment('Tên vị trí tuyển dụng');
            $table->string('slug')->nullable();
            $table->text('description')->nullable()->comment('Mô tả công việc');

            // Thông tin công ty
            $table->string('company_name')->comment('Tên công ty');
            $table->string('company_logo')->nullable()->comment('Logo công ty');

            // Thông tin tuyển dụng
            $table->string('job_level')->nullable()->comment('Cấp bậc: nhân viên, senior, manager...');
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'internship', 'freelance'])->default('full_time')->comment('Loại hình công việc');
            $table->string('experience_level')->nullable()->comment('Yêu cầu kinh nghiệm');

            // Địa điểm
            $table->string('work_location')->comment('Nơi làm việc (thành phố/quận)');
            $table->string('work_address')->nullable()->comment('Địa chỉ chi tiết');
            $table->unsignedBigInteger('province_id')->nullable();
            $table->unsignedBigInteger('ward_id')->nullable();

            // Lương
            $table->enum('salary_type', ['range', 'from', 'to', 'negotiable'])->default('negotiable')->comment('Kiểu hiển thị lương');
            $table->decimal('salary_from', 28, 8)->default(0)->comment('Lương từ');
            $table->decimal('salary_to', 28, 8)->default(0)->comment('Lương đến');
            $table->string('salary_label')->nullable()->comment('Nhãn lương ngắn cho card listing');

            // Ngày hết hạn
            $table->date('application_deadline')->nullable()->comment('Hạn nộp hồ sơ');
            $table->date('job_expires_at')->nullable()->comment('Ngày hết hạn tin đăng');

            // Quản lý
            $table->unsignedBigInteger('seller_id')->nullable()->comment('Người đăng tin (seller/user)');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=active, 0=inactive');
            $table->tinyInteger('is_featured')->default(0)->comment('1=featured, 0=normal');

            // SEO
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();

            // Thông tin liên hệ
            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();

            // Cách ứng tuyển
            $table->enum('application_method', ['platform', 'email', 'external_link'])->default('platform')->comment('Phương thức ứng tuyển');
            $table->string('application_email')->nullable()->comment('Email nhận hồ sơ');
            $table->string('application_link')->nullable()->comment('Link đăng tuyển bên ngoài');

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['seller_id', 'status']);
            $table->index(['province_id', 'status']);
            $table->index('application_deadline');
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
