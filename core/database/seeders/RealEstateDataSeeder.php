<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class RealEstateDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $template = Product::find(24);
        if (!$template) {
            $this->command->error("Template product ID 24 not found.");
            return;
        }

        $sourceDir = base_path('../assets/images/frontend/kviet/bds/');
        $targetDir = base_path('../assets/images/product/');

        if (!File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        $images = File::files($sourceDir);
        if (empty($images)) {
             $this->command->error("No images found in $sourceDir");
             return;
        }

        $categories = $template->categories->pluck('id')->toArray();
        $manager = new ImageManager(new Driver());

        $sellerIds = [0, 1];
        $propertyTypes = ['Apartment', 'Villa', 'Townhouse', 'Land'];
        $directions = ['North', 'South', 'East', 'West', 'Northeast', 'Northwest', 'Southeast', 'Southwest'];
        $legalStatuses = ['Pink Book', 'Red Book', 'Sales Contract', 'Waiting for book'];

        foreach ($sellerIds as $sellerId) {
            for ($i = 1; $i <= 5; $i++) {
                $product = new Product();
                
                // Copy basic logic from template
                $product->re_type = $template->re_type; // 'sale' or 'rent'
                $product->re_map_embed = $template->re_map_embed;
                $product->status = 1;
                $product->show_in_frontend = 1;
                $product->track_inventory = 0;
                $product->seller_id = $sellerId;

                // Random property-specific data
                $nameType = $propertyTypes[array_rand($propertyTypes)];
                $product->name = $nameType . " " . Str::upper(Str::random(3)) . " - " . ($sellerId == 0 ? "Admin" : "Seller") . " Listing #$i";
                $product->slug = Str::slug($product->name) . '-' . time();
                
                // Fill newly added columns
                $product->re_property_type = $nameType;
                $product->re_direction = $directions[array_rand($directions)];
                $product->re_legal_status = $legalStatuses[array_rand($legalStatuses)];
                
                $product->re_address = rand(10, 500) . " Nguyen Trai St, District " . rand(1, 10) . ", District " . rand(1, 10) . ", Ho Chi Minh City";
                $product->re_bedrooms = rand(1, 5);
                $product->re_bathrooms = rand(1, 3);
                $product->re_floor = rand(1, 10);
                $product->re_area = rand(45, 250);
                $product->base_price = rand(1500, 30000) * 1000000; // 1.5B to 30B VND

                // Random Image Selection & Processing
                $randomImageSource = $images[array_rand($images)];
                $filename = uniqid() . time() . '.' . $randomImageSource->getExtension();
                
                try {
                    $image = $manager->read($randomImageSource->getPathname());
                    $image->resize(800, 800)->save($targetDir . $filename);
                    
                    $thumbImage = $manager->read($randomImageSource->getPathname());
                    $thumbImage->resize(400, 400)->save($targetDir . 'thumb_' . $filename);
                    
                    $product->main_image = $filename;
                    $product->save();

                    // Attach to template's categories
                    $product->categories()->attach($categories);
                    
                    $this->command->info("Created real estate listing: {$product->name}");
                } catch (\Exception $e) {
                    $this->command->error("Failed to process listing #$i: " . $e->getMessage());
                }
            }
        }

        $this->command->info("Real estate seeding completed successfully!");
    }
}
