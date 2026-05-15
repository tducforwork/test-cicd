<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\RealEstateConfig;
use App\Models\Province;
use App\Models\Ward;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Constants\Status;

class RealEstateSeeder extends Seeder
{
    public function run()
    {
        // 1. Delete existing real estate products
        Product::where('is_real_estate', 1)->delete();

        // 2. Clear and Rebuild Property Types
        RealEstateConfig::where('type', 'property_type')->delete();

        $hierarchy = [
            'Apartment' => ['Rent', 'Buy', 'Sell'],
            'Villas' => ['Rent', 'Buy', 'Sell'],
            'Land' => ['Rent', 'Buy', 'Sell'],
            'Office' => ['Rent', 'Buy', 'Sell'],
            'Room rent' => ['One room', 'Two room'],
            'Shophouse' => []
        ];

        foreach ($hierarchy as $parentName => $children) {
            $parent = RealEstateConfig::create([
                'type' => 'property_type',
                'name' => $parentName,
                'status' => 1,
                'parent_id' => 0
            ]);

            foreach ($children as $childName) {
                RealEstateConfig::create([
                    'type' => 'property_type',
                    'name' => $childName,
                    'status' => 1,
                    'parent_id' => $parent->id
                ]);
            }
        }

        // 3. Prepare data for products
        $provinces = Province::active()->get();
        if ($provinces->isEmpty()) return;

        $propertyTypes = RealEstateConfig::where('type', 'property_type')->get();
        $transactionTypes = RealEstateConfig::where('type', 'transaction_type')->pluck('name')->toArray();
        $transactionMethods = RealEstateConfig::where('type', 'transaction_method')->pluck('name')->toArray();
        $listingConditions = RealEstateConfig::where('type', 'listing_condition')->pluck('name')->toArray();

        $sourcePath = 'assets/images/frontend/kviet/bds';
        $destPath = getFilePath('product');
        
        // Ensure destination path exists
        if (!file_exists($destPath)) {
            mkdir($destPath, 0755, true);
        }

        $images = [
            'column-img10.png', 'column-img11.png', 'column-img12.png',
            'column-img13.png', 'column-img14.png', 'column-img15.png',
            'column-img8.png', 'column-img9.png'
        ];

        // Copy images and create thumbnails in product directory if not exists
        foreach ($images as $img) {
            $src = base_path('../' . $sourcePath . '/' . $img);
            $dst = base_path('../' . $destPath . '/' . $img);
            $thumb = base_path('../' . $destPath . '/thumb_' . $img);
            
            if (file_exists($src)) {
                if (!file_exists($dst)) copy($src, $dst);
                if (!file_exists($thumb)) copy($src, $thumb);
            }
        }

        // 4. Create 20 listings
        for ($i = 1; $i <= 20; $i++) {
            $province = $provinces->random();
            $ward = Ward::where('province_id', $province->id)->inRandomOrder()->first() ?? Ward::where('status', 1)->inRandomOrder()->first();
            
            $reType = $i % 2 == 0 ? 'rent' : 'sale';
            $priceType = rand(0, 4) == 0 ? 'negotiable' : 'fixed';
            
            $propType = $propertyTypes->random();
            $transType = !empty($transactionTypes) ? $transactionTypes[array_rand($transactionTypes)] : 'Cố định';
            $transMethod = !empty($transactionMethods) ? $transactionMethods[array_rand($transactionMethods)] : 'Trực tiếp';
            
            $randomConditions = [];
            if (!empty($listingConditions)) {
                $count = rand(1, 3);
                $randomConditions = (array)array_rand(array_flip($listingConditions), $count);
            }

            $extra = [
                ['key' => 'Loại hình BĐS', 'value' => $propType->name],
                ['key' => 'Loại giao dịch/ Giá', 'value' => $transType],
                ['key' => 'Hình thức giao dịch', 'value' => $transMethod],
                ['key' => 'Điều kiện', 'value' => implode(', ', (array)$randomConditions)]
            ];

            $product = Product::create([
                'name' => "Bất động sản mẫu số $i - " . $propType->name,
                'slug' => Str::slug("bds-mau-$i-" . $propType->name . "-" . Str::random(5)),
                'is_real_estate' => 1,
                'seller_id' => $i <= 10 ? 0 : 1, // 10 Admin, 10 Seller
                're_type' => $reType,
                're_rent_period' => $reType == 'rent' ? ['day', 'month', 'year'][rand(0, 2)] : null,
                're_price_type' => $priceType,
                're_price_from' => $priceType == 'fixed' ? rand(1000000, 50000000) : 0,
                're_price_to' => rand(0, 1) ? rand(50000000, 100000000) : 0,
                're_area' => rand(30, 200),
                're_area_to' => rand(0, 1) ? rand(200, 500) : 0,
                're_province_id' => $province->id,
                're_ward_id' => $ward ? $ward->id : 0,
                're_address' => "Số " . rand(1, 200) . " Đường mẫu, Quận/Huyện " . ($ward ? $ward->name : ''),
                're_bedrooms' => rand(1, 5),
                're_bathrooms' => rand(1, 3),
                're_floor' => rand(1, 10),
                're_contact_name' => "Người liên hệ $i",
                're_contact_phone' => "09" . rand(10000000, 99999999),
                're_contact_email' => "contact$i@gmail.com",
                'description' => "Mô tả chi tiết cho bất động sản số $i. Đây là một tin đăng mẫu được tạo tự động để kiểm tra hệ thống lọc phân cấp.",
                'extra_descriptions' => $extra,
                'main_image' => $images[array_rand($images)],
                'status' => Status::ENABLE,
                'is_featured' => rand(0, 1)
            ]);

            // Add some detail images
            $detailCount = rand(3, 5);
            $randomImages = array_rand(array_flip($images), $detailCount);
            foreach ((array)$randomImages as $imgName) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $imgName
                ]);
            }
        }
    }
}
