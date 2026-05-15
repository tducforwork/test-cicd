<?php

namespace Database\Seeders;

use App\Models\Frontend;
use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoPageSeeder extends Seeder
{
    public function run()
    {
        // 1. Clear existing demo data for these specific keys
        $keys = ['modern_hero.content', 'featured_categories.element', 'promo_banner.content'];
        Frontend::whereIn('data_keys', $keys)->delete();

        // 2. Seed Modern Hero Content
        Frontend::create([
            'data_keys' => 'modern_hero.content',
            'data_values' => [
                'title' => 'Revolutionize Your Shopping Experience',
                'subtitle' => 'Explore the finest selection of premium products curated just for you. Quality meets affordability in our latest 2026 collection.',
                'button_text' => 'Start Exploring',
                'button_url' => '/products',
                'background_image' => 'demo_hero.jpg'
            ]
        ]);

        // 3. Seed Featured Categories Elements
        $categories = [
            ['name' => 'Fashion & Apparel', 'url' => '/category/fashion', 'icon' => 'fashion.png'],
            ['name' => 'Electronics & Gadgets', 'url' => '/category/electronics', 'icon' => 'electronics.png'],
            ['name' => 'Home & Living', 'url' => '/category/home', 'icon' => 'home.png'],
            ['name' => 'Health & Beauty', 'url' => '/category/beauty', 'icon' => 'beauty.png'],
        ];

        foreach ($categories as $cat) {
            Frontend::create([
                'data_keys' => 'featured_categories.element',
                'data_values' => [
                    'name' => $cat['name'],
                    'url' => $cat['url'],
                    'icon' => $cat['icon']
                ]
            ]);
        }

        // 4. Seed Promo Banner Content
        Frontend::create([
            'data_keys' => 'promo_banner.content',
            'data_values' => [
                'title' => 'Summer Flash Sale: Up to 60% Off!',
                'description' => 'Get ready for the summer with our massive discounts across all categories. Use code SUMMER26 at checkout for an extra 10% off.',
                'button_text' => 'Claim Discount',
                'button_url' => '/promotions',
                'banner_image' => 'demo_promo.jpg'
            ]
        ]);

        // 5. Create or Update the Demo Page
        $page = Page::updateOrCreate(
            ['slug' => 'demo-home', 'tempname' => activeTemplateName()],
            [
                'name' => 'Demo Home Page',
                'secs' => json_encode(['modern_hero', 'featured_categories', 'promo_banner']),
                'is_default' => 0
            ]
        );

        echo "Demo data seeded successfully! You can view it at /demo-home\n";
    }
}
