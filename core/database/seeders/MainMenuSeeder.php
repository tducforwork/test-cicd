<?php

namespace Database\Seeders;

use App\Models\MenuGroup;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MainMenuSeeder extends Seeder
{
    public function run()
    {
        $group = MenuGroup::updateOrCreate(
            ['location' => 'main_menu'],
            ['name' => 'Main Menu']
        );

        $menus = [
            [
                'title' => 'Trang chủ',
                'url'   => route('home', [], false),
                'has_mega_menu' => false,
            ],
            [
                'title' => 'Quảng Phát Logistics',
                'url'   => route('about', [], false),
                'has_mega_menu' => false,
            ],
            [
                'title' => 'Quảng Phát Mall',
                'url'   => route('products', [], false),
                'has_mega_menu' => true,
            ],
            [
                'title' => 'Liên hệ',
                'url'   => route('contact', [], false),
                'has_mega_menu' => false,
            ],
        ];

        foreach ($menus as $index => $menu) {
            MenuItem::updateOrCreate(
                [
                    'menu_group_id' => $group->id,
                    'title'         => $menu['title']
                ],
                [
                    'url'           => $menu['url'],
                    'has_mega_menu' => $menu['has_mega_menu'],
                    'order'         => $index + 1,
                    'status'        => 1
                ]
            );
        }
    }
}
