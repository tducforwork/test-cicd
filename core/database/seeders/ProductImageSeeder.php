<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sourceDir = base_path('../assets/images/frontend/kviet/detail-product/column/');
        $targetDir = base_path('../assets/images/product/');

        if (!File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        $images = File::files($sourceDir);
        
        if (empty($images)) {
            $this->command->error("No source images found in $sourceDir");
            return;
        }

        $products = Product::whereNull('re_type')->get();
        
        $this->command->info("Updating " . $products->count() . " products...");

        $manager = new ImageManager(new Driver());

        foreach ($products as $product) {
            $randomImage = $images[array_rand($images)];
            $extension = $randomImage->getExtension();
            $filename = uniqid() . time() . '.' . $extension;

            try {
                // Save Original/Max size (800x800)
                $image = $manager->read($randomImage->getPathname());
                $image->resize(800, 800)->save($targetDir . $filename);

                // Save Thumbnail (400x400)
                $thumbImage = $manager->read($randomImage->getPathname());
                $thumbImage->resize(400, 400)->save($targetDir . 'thumb_' . $filename);

                $product->main_image = $filename;
                $product->save();
            } catch (\Exception $e) {
                $this->command->error("Failed to process product ID {$product->id}: " . $e->getMessage());
            }
        }

        $this->command->info("Product images updated successfully!");
    }
}
