<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brands = [
            ['name' => 'Nike', 'slug' => 'nike', 'logo' => 'nike.png', 'top' => 1],
            ['name' => 'Apple', 'slug' => 'apple', 'logo' => 'apple.png', 'top' => 1],
            ['name' => 'Sony', 'slug' => 'sony', 'logo' => 'sony.png', 'top' => 1],
            ['name' => 'Samsung', 'slug' => 'samsung', 'logo' => 'samsung.png', 'top' => 1],
            ['name' => 'Adidas', 'slug' => 'adidas', 'logo' => 'adidas.png', 'top' => 1],
            ['name' => 'Canon', 'slug' => 'canon', 'logo' => 'canon.png', 'top' => 1],
            ['name' => 'HP', 'slug' => 'hp', 'logo' => 'hp.png', 'top' => 1],
            ['name' => 'Dell', 'slug' => 'dell', 'logo' => 'dell.png', 'top' => 1],
            ['name' => 'Asus', 'slug' => 'asus', 'logo' => 'asus.png', 'top' => 1],
            ['name' => 'Lenovo', 'slug' => 'lenovo', 'logo' => 'lenovo.png', 'top' => 1],
        ];

        $destinationPath = base_path('../assets/images/brand');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        // Source images from the artifacts directory (using absolute paths provided by the system)
        $sourceImages = [
            'nike.png' => 'C:\Users\duc.phamtuan\.gemini\antigravity\brain\730bd651-3a58-435c-a291-de19d9736857\brand_nike_logo_1778237363624.png',
            'apple.png' => 'C:\Users\duc.phamtuan\.gemini\antigravity\brain\730bd651-3a58-435c-a291-de19d9736857\brand_apple_logo_1778237381461.png',
            'sony.png' => 'C:\Users\duc.phamtuan\.gemini\antigravity\brain\730bd651-3a58-435c-a291-de19d9736857\brand_sony_logo_1778237402946.png',
        ];

        // For other brands, we can just reuse one of these or use a placeholder if available
        $defaultSource = $sourceImages['sony.png'];

        foreach ($brands as $brandData) {
            $logoName = $brandData['logo'];
            $source = isset($sourceImages[$logoName]) ? $sourceImages[$logoName] : $defaultSource;
            
            if (File::exists($source)) {
                File::copy($source, $destinationPath . '/' . $logoName);
            }

            Brand::updateOrCreate(
                ['slug' => $brandData['slug']],
                [
                    'name' => $brandData['name'],
                    'logo' => $logoName,
                    'top' => $brandData['top']
                ]
            );
        }
    }
}
