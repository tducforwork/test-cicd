<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shop;
use App\Constants\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sellers = [
            [
                'firstname' => 'Nguyen',
                'lastname' => 'Van A',
                'username' => 'seller_a',
                'email' => 'seller_a@example.com',
                'shop_name' => 'A-Z Electronics',
                'logo' => '69de12df73d621776161503.jpg',
                'cover' => '69de12df80b861776161503.png',
            ],
            [
                'firstname' => 'Tran',
                'lastname' => 'Thi B',
                'username' => 'seller_b',
                'email' => 'seller_b@example.com',
                'shop_name' => 'Fashion Boutique B',
                'logo' => '69de0fbe5eaa01776160702.jpeg',
                'cover' => '69df6ec5687ac1776250565.jpg',
            ],
            [
                'firstname' => 'Le',
                'lastname' => 'Van C',
                'username' => 'seller_c',
                'email' => 'seller_c@example.com',
                'shop_name' => 'Organic Garden C',
                'logo' => '69ddbf4bbf2aa1776140107.png',
                'cover' => '69de0fc249b2f1776160706.png',
            ],
        ];

        foreach ($sellers as $data) {
            // Check if user already exists
            $user = User::where('username', $data['username'])->orWhere('email', $data['email'])->first();
            
            if (!$user) {
                $user = new User();
                $user->firstname = $data['firstname'];
                $user->lastname = $data['lastname'];
                $user->username = $data['username'];
                $user->email = $data['email'];
                $user->password = Hash::make('12345678'); // Default password
                $user->mobile = '09' . rand(10000000, 99999999);
                $user->address = 'Hanoi, Vietnam';
                $user->status = Status::USER_ACTIVE;
                $user->ev = Status::VERIFIED;
                $user->sv = Status::VERIFIED;
                $user->is_seller = Status::YES;
                $user->seller_active = 1;
                $user->seller_activated_at = now();
                $user->save();
            } else {
                $user->is_seller = Status::YES;
                $user->seller_active = 1;
                $user->seller_activated_at = now();
                $user->save();
            }

            // Create or Update Shop
            $shop = Shop::where('seller_id', $user->id)->first();
            if (!$shop) {
                $shop = new Shop();
                $shop->seller_id = $user->id;
            }
            
            $shop->name = $data['shop_name'];
            $shop->phone = $user->mobile;
            $shop->address = $user->address;
            $shop->logo = $data['logo'];
            $shop->cover = $data['cover'];
            $shop->save();
        }
    }
}
