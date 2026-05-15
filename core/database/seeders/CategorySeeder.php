<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Điện thoại & Máy tính' => [
                'iPhone',
                'Samsung',
                'Laptop Gaming',
                'Phụ kiện điện thoại'
            ],
            'Điện máy & Gia dụng'   => [
                'Tủ lạnh',
                'Máy giặt',
                'Lò vi sóng',
                'Tivi 4K'
            ],
            'Mẹ & Bé'               => [
                'Tã bỉm',
                'Sữa bột',
                'Đồ chơi giáo dục',
                'Quần áo sơ sinh'
            ],
            'Sức khỏe & Y tế'       => [
                'Thực phẩm chức năng',
                'Khẩu trang y tế',
                'Thiết bị đo huyết áp',
                'Vitamin & Khoáng chất'
            ],
            'Thể thao & Du lịch'    => [
                'Giày chạy bộ',
                'Vali du lịch',
                'Dụng cụ Gym',
                'Đồ cắm trại'
            ],
            'Đồ dùng văn phòng'     => [
                'Giấy in A4',
                'Bút ký cao cấp',
                'Bàn ghế làm việc',
                'Máy in & Máy Fax'
            ],
            'Ô tô & Xe máy'         => [
                'Phụ tùng ô tô',
                'Mũ bảo hiểm',
                'Dầu nhớt chính hãng',
                'Đồ chơi xe hơi'
            ],
            'Thực phẩm' => [
                'Đồ uống & Giải khát',
                'Gia vị & Phụ gia',
                'Thực phẩm khô',
                'Thực phẩm tươi sống'
            ],
            'Công nghiệp' => [
                'Dụng cụ cầm tay',
                'Linh kiện & Phụ tùng',
                'Máy móc công nghiệp',
                'Thiết bị điện tử'
            ],
            'Thời trang' => [
                'Mỹ phẩm & Làm đẹp',
                'Phụ kiện cao cấp',
                'Thời trang Nam',
                'Thời trang Nữ'
            ],
        ];

        foreach ($categories as $parentName => $children) {
            $parent = Category::updateOrCreate(
                ['name' => $parentName],
                [
                    'slug' => Str::slug($parentName),
                    'parent_id' => null
                ]
            );

            foreach ($children as $childName) {
                Category::updateOrCreate(
                    ['name' => $childName, 'parent_id' => $parent->id],
                    [
                        'slug' => Str::slug($childName)
                    ]
                );
            }
        }
    }
}
