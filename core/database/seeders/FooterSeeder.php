<?php

namespace Database\Seeders;

use App\Models\Frontend;
use App\Models\MenuGroup;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class FooterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $footerContent = [
            'about_text' => 'Cầu nối giao thương toàn cầu. Quảng Phát Logistic mang đến giải pháp vận chuyển và thương mại điện tử trọn gói cho doanh nghiệp.',
            'address' => 'Số 123, Đường ABC, Quận XYZ, TP. Hồ Chí Minh',
            'hotline' => '0987.xxx.xxx',
            'email' => 'contact@quangphat.com',
            'copyright_text' => '2026 Quảng Phát Logistic. Tất cả quyền được bảo lưu.'
        ];
        Frontend::updateOrCreate(['data_keys' => 'footer.content'], ['data_values' => $footerContent]);

        // Cleanup old elements
        Frontend::where('data_keys', 'footer.element')->delete();

        // Footer Menu 1
        $group1 = MenuGroup::updateOrCreate(
            ['location' => 'footer_menu_1'],
            ['name' => 'GIỚI THIỆU', 'status' => 1]
        );
        
        $group1->menuItems()->delete();
        $group1Items = [
            ['title' => 'Về chúng tôi', 'url' => '#'],
            ['title' => 'Hướng dẫn mua hàng', 'url' => '#'],
            ['title' => 'Quy trình xử lý khiếu nại', 'url' => '#'],
            ['title' => 'Chính sách & Điều khoản', 'url' => '#'],
        ];
        foreach ($group1Items as $item) {
            $group1->menuItems()->create($item + ['status' => 1, 'order' => 0]);
        }

        // Footer Menu 2
        $group2 = MenuGroup::updateOrCreate(
            ['location' => 'footer_menu_2'],
            ['name' => 'CHÍNH SÁCH', 'status' => 1]
        );
        
        $group2->menuItems()->delete();
        $group2Items = [
            ['title' => 'Chính sách giao hàng', 'url' => '#'],
            ['title' => 'Chính sách bảo mật', 'url' => '#'],
            ['title' => 'Chính sách thanh toán', 'url' => '#'],
            ['title' => 'Đối tác vận chuyển', 'url' => '#'],
        ];
        foreach ($group2Items as $item) {
            $group2->menuItems()->create($item + ['status' => 1, 'order' => 0]);
        }
    }
}
