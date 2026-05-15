<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shop;
use App\Constants\Status;
use Illuminate\Database\Seeder;

class ShopFeaturedAndImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Mark all current sellers as featured to populate the section
        User::seller()->update(['featured' => Status::YES]);

        // 2. Ensure every seller has a shop and update images with placeholders if needed
        $sellers = User::seller()->get();
        
        $logos = [
            'https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=200&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1472851294608-062f824d29cc?q=80&w=200&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=200&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=200&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=200&auto=format&fit=crop',
        ];

        $covers = [
            'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1000&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1464863979621-258859e62245?q=80&w=1000&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1534452285544-d4ef50272396?q=80&w=1000&auto=format&fit=crop',
        ];

        foreach ($sellers as $index => $seller) {
            $shop = Shop::where('seller_id', $seller->id)->first();
            if (!$shop) {
                $shop = new Shop();
                $shop->seller_id = $seller->id;
                $shop->name = $seller->username . ' Store';
                $shop->phone = $seller->mobile;
                $shop->address = $seller->address ?? 'Hanoi, Vietnam';
            }

            $shop->logo = $logos[$index % count($logos)];
            $shop->cover = $covers[$index % count($covers)];
            $shop->save();
        }
    }
}
