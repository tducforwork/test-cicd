<?php

namespace Database\Seeders;

use App\Constants\Status;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('vi_VN');
        
        // Get existing resources
        $categoryIds = Category::pluck('id')->toArray();
        $brandIds = Brand::pluck('id')->toArray();
        $sellerIds = User::pluck('id')->toArray();

        // If no categories, create a default one
        if (empty($categoryIds)) {
            $category = Category::create([
                'name' => 'General',
                'slug' => 'general',
                'status' => Status::ENABLE
            ]);
            $categoryIds = [$category->id];
        }

        // If no sellers, create a default one (or use first user)
        if (empty($sellerIds)) {
            $seller = User::create([
                'firstname' => 'Sample',
                'lastname' => 'Seller',
                'username' => 'seller_sample',
                'email' => 'seller@example.com',
                'password' => bcrypt('password'),
                'status' => Status::ENABLE,
                'ev' => Status::YES,
                'sv' => Status::YES,
            ]);
            $sellerIds = [$seller->id];
        }

        // Scan images
        $imagePath = base_path('../assets/images/product');
        $images = [];
        if (is_dir($imagePath)) {
            $files = scandir($imagePath);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && !str_starts_with($file, 'thumb_') && is_file($imagePath . '/' . $file)) {
                    $images[] = $file;
                }
            }
        }

        // If no images found, use a placeholder or keep null
        $defaultImage = !empty($images) ? $images[0] : null;

        $productNames = [
            'Nước hoa Dior Rouge Trafalgar',
            'Áo thun nam cao cấp',
            'Giày sneaker trắng basic',
            'Đồng hồ thông minh thế hệ mới',
            'Tai nghe không dây chống ồn',
            'Túi xách da thật thời trang',
            'Kính mát nam phong cách Ý',
            'Ví da nam cầm tay',
            'Thắt lưng da bò xịn',
            'Sáp vuốt tóc nam 24h',
            'Sữa rửa mặt sáng da',
            'Kem chống nắng thể thao',
            'Balo laptop chống nước',
            'Loa bluetooth mini',
            'Sạc dự phòng 20000mAh',
            'Ốp lưng iPhone silicon',
            'Máy cạo râu điện đa năng',
            'Xịt toàn thân nam tính',
            'Quần jean nam ống đứng',
            'Sơ mi trắng công sở'
        ];

        foreach ($productNames as $index => $name) {
            $price = $faker->numberBetween(100, 5000) * 1000; // 100k to 5M
            $mainImage = !empty($images) ? $faker->randomElement($images) : $defaultImage;
            
            $product = Product::create([
                'seller_id' => $faker->randomElement($sellerIds),
                'brand_id' => !empty($brandIds) ? $faker->randomElement($brandIds) : 0,
                'sku' => strtoupper(Str::random(8)),
                'name' => $name . ' ' . $faker->word,
                'slug' => Str::slug($name) . '-' . Str::random(5),
                'model' => $faker->bothify('MODEL-##??'),
                'has_variants' => Status::NO,
                'track_inventory' => Status::YES,
                'show_in_frontend' => Status::YES,
                'main_image' => $mainImage,
                'description' => $faker->paragraphs(3, true),
                'summary' => $faker->sentence(20),
                'base_price' => $price,
                'is_featured' => $faker->randomElement([Status::YES, Status::NO]),
                'status' => Status::ENABLE,
                'created_at' => now()->subDays($faker->numberBetween(1, 30)),
            ]);

            // Assign random categories
            $randomCats = $faker->randomElements($categoryIds, $faker->numberBetween(1, 2));
            $product->categories()->attach($randomCats);
        }

        echo "Seeded " . count($productNames) . " products successfully.\n";
    }
}
