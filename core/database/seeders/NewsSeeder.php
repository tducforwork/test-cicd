<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $admin = Admin::first();
        if (!$admin) {
            $this->command->error('No admin found. Please create an admin first.');
            return;
        }

        $sourcePath = base_path('../assets/images/frontend/kviet/card-frame');
        $destPath = base_path('../assets/images/news');

        if (!File::exists($destPath)) {
            File::makeDirectory($destPath, 0755, true);
        }

        $sourceImages = [
            'card-frame1.png',
            'card-frame2.png',
            'card-frame3.png',
        ];

        // Check if source images exist
        foreach ($sourceImages as $img) {
            if (!File::exists($sourcePath . '/' . $img)) {
                $this->command->error("Source image not found: {$sourcePath}/{$img}");
                return;
            }
        }

        $this->command->info('Seeding 20 news articles...');

        for ($i = 1; $i <= 20; $i++) {
            $title = $faker->sentence(rand(6, 10));
            $sourceImage = $sourceImages[($i - 1) % count($sourceImages)];
            $extension = File::extension($sourceImage);
            $newImageName = uniqid() . time() . '.' . $extension;

            // Copy file to dest
            File::copy($sourcePath . '/' . $sourceImage, $destPath . '/' . $newImageName);

            News::create([
                'title'          => $title,
                'slug'           => Str::slug($title) . '-' . uniqid(),
                'content'        => '<p>' . implode('</p><p>', $faker->paragraphs(rand(5, 10))) . '</p>',
                'excerpt'        => $faker->paragraph(rand(2, 4)),
                'category_id'    => null,
                'featured_image' => $newImageName,
                'admin_id'       => $admin->id,
                'published_at'   => now()->subDays(rand(0, 30)),
                'view_count'     => rand(100, 5000),
            ]);
        }

        $this->command->info('20 news articles seeded successfully.');
    }
}
