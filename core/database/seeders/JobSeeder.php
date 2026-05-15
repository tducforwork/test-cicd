<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\JobIndustry;
use App\Models\JobLevel;
use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class JobSeeder extends Seeder
{
    public function run()
    {
        // 1. Update existing jobs to job_type = 1 and set short_description if empty
        Job::whereNull('job_type')->orWhere('job_type', 0)->update(['job_type' => 1]);
        
        foreach(Job::whereNull('short_description')->get() as $job) {
            $job->short_description = Str::limit(strip_tags($job->description), 150);
            $job->save();
        }

        // 2. Add sample Job Seekers (job_type = 2)
        $industries = JobIndustry::active()->pluck('id')->toArray();
        $levels = JobLevel::active()->pluck('id')->toArray();
        $provinces = Province::pluck('id')->toArray();

        $sourceImages = [
            'assets/images/frontend/kviet/job/job-1.jpg',
            'assets/images/frontend/kviet/job/job-2.jpg',
            'assets/images/frontend/kviet/job/job-3.jpg',
        ];

        $targetPath = base_path('../assets/images/job');
        if (!File::exists($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
        }

        $seekers = [
            [
                'title' => 'Ứng viên tìm việc: Thiết kế đồ họa 3 năm kinh nghiệm',
                'name' => 'Nguyễn Văn A',
                'short' => 'Tôi có 3 năm kinh nghiệm thiết kế UI/UX và Branding. Thành thạo Photoshop, Figma, Illustrator.',
            ],
            [
                'title' => 'Tìm việc: Kế toán trưởng tại Hà Nội',
                'name' => 'Trần Thị B',
                'short' => 'Hơn 5 năm kinh nghiệm làm kế toán tổng hợp và kế toán trưởng. Cẩn thận, trung thực, có chứng chỉ hành nghề.',
            ],
            [
                'title' => 'Ứng viên: Lập trình viên PHP/Laravel mong muốn tìm dự án mới',
                'name' => 'Lê Văn C',
                'short' => 'Chuyên gia Laravel, nắm vững RESTful API, MySQL, VueJS. Có kinh nghiệm triển khai nhiều dự án e-commerce.',
            ],
        ];

        foreach ($seekers as $seeker) {
            $sourceImageFile = $sourceImages[array_rand($sourceImages)];
            $sourcePath = base_path('../' . $sourceImageFile);
            $newFileName = 'seeker_' . uniqid() . '.jpg';
            
            if (File::exists($sourcePath)) {
                File::copy($sourcePath, $targetPath . '/' . $newFileName);
            }

            Job::create([
                'title'                 => $seeker['title'],
                'slug'                  => Str::slug($seeker['title'] . '-' . Str::random(5)),
                'company_name'          => $seeker['name'],
                'company_logo'          => $newFileName,
                'industry_id'           => !empty($industries) ? $industries[array_rand($industries)] : 1,
                'job_level_id'          => !empty($levels) ? $levels[array_rand($levels)] : 1,
                'work_location'         => 'Hà Nội / Online',
                'province_id'           => !empty($provinces) ? $provinces[array_rand($provinces)] : 1,
                'salary_type'           => 'negotiable',
                'application_deadline'  => now()->addDays(60),
                'short_description'     => $seeker['short'],
                'description'           => '<p>' . $seeker['short'] . '</p><p>Liên hệ với tôi để biết thêm chi tiết về hồ sơ năng lực.</p>',
                'seller_id'             => 0,
                'status'                => 1,
                'job_type'              => 2,
            ]);
        }
    }
}
