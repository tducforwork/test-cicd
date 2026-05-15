<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('notification_templates')->insert([
            [
                'act' => 'SUBSCRIBE_CONFIRMATION',
                'name' => 'Newsletter Subscription Confirmation',
                'subject' => 'Đăng ký nhận bản tin thành công',
                'email_body' => '<p>Chào bạn,</p><p>Cảm ơn bạn đã đăng ký nhận bản tin từ <strong>{{site_name}}</strong>. Chúng tôi sẽ gửi cho bạn những cập nhật mới nhất về sản phẩm và ưu đãi.</p><p>Trân trọng!</p>',
                'shortcodes' => json_encode(['email' => 'Subscriber Email', 'site_name' => 'Site Name']),
                'email_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'act' => 'ADMIN_SUBSCRIBE_NOTIFICATION',
                'name' => 'New Newsletter Subscription Notification',
                'subject' => 'Có người đăng ký nhận bản tin mới',
                'email_body' => '<p>Chào Admin,</p><p>Một email mới đã đăng ký nhận bản tin trên hệ thống:</p><ul><li>Email: <strong>{{email}}</strong></li></ul><p>Trân trọng!</p>',
                'shortcodes' => json_encode(['email' => 'Subscriber Email', 'site_name' => 'Site Name']),
                'email_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('notification_templates')->whereIn('act', ['SUBSCRIBE_CONFIRMATION', 'ADMIN_SUBSCRIBE_NOTIFICATION'])->delete();
    }
};
