<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryThumbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sourcePath = base_path('resources/views/templates/template_figma/assets/images');
        $destinationPath = base_path('../assets/images/category');

        if (!\Illuminate\Support\Facades\File::exists($destinationPath)) {
            \Illuminate\Support\Facades\File::makeDirectory($destinationPath, 0755, true);
        }

        $files = \Illuminate\Support\Facades\File::files($sourcePath);
        $imageFiles = [];
        foreach ($files as $file) {
            if (in_array($file->getExtension(), ['jpg', 'jpeg', 'png', 'webp'])) {
                $imageFiles[] = $file;
            }
        }

        if (empty($imageFiles)) {
            echo "No images found in $sourcePath\n";
            return;
        }

        $categories = \App\Models\Category::all();

        foreach ($categories as $category) {
            $randomImage = $imageFiles[array_rand($imageFiles)];
            $extension = $randomImage->getExtension();
            $newFileName = uniqid() . '_' . time() . '.' . $extension;

            \Illuminate\Support\Facades\File::copy($randomImage->getPathname(), $destinationPath . '/' . $newFileName);

            $category->thumb = $newFileName;
            $category->image = $newFileName;
            $category->save();

            echo "Updated category: {$category->name} with image: $newFileName\n";
        }
    }
}
