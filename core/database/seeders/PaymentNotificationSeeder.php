<?php

namespace Database\Seeders;

use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class PaymentNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $templates = [
            [
                'act' => 'ADMIN_PAYMENT_ALERT',
                'name' => 'Thông báo Admin về thanh toán thành công',
                'subject' => '[Admin] Thông báo thanh toán mới cho đơn hàng #{{order_number}}',
                'email_body' => "Chào Admin,<br><br>Hệ thống Kviet Shop vừa ghi nhận một giao dịch thanh toán thành công mới.<br><br><b>Chi tiết giao dịch:</b><br>- Mã đơn hàng: #{{order_number}}<br>- Số tiền: {{amount}} {{currency}}<br>- Phương thức: {{method_name}}<br>- Người thực hiện: {{buyer_username}}<br>- Mã giao dịch định danh: {{trx}}<br><br>Vui lòng đăng nhập vào trang quản trị để kiểm tra và quản lý đơn hàng.<br><br>Trân trọng,<br>Hệ thống {{site_name}}",
                'shortcodes' => [
                    'order_number' => 'Mã đơn hàng',
                    'amount' => 'Số tiền thanh toán',
                    'currency' => 'Loại tiền tệ',
                    'method_name' => 'Phương thức thanh toán',
                    'buyer_username' => 'Tên đăng nhập người mua',
                    'trx' => 'Mã giao dịch',
                    'site_name' => 'Tên website'
                ],
            ],
            [
                'act' => 'SELLER_NEW_ORDER',
                'name' => 'Thông báo Seller về đơn hàng mới đã thanh toán',
                'subject' => '[Người bán] Bạn có đơn hàng mới đã được thanh toán #{{order_number}}',
                'email_body' => "Chào {{seller_name}},<br><br>Bạn vừa có một đơn hàng mới đã được khách hàng thanh toán thành công trên hệ thống {{site_name}}.<br><br><b>Thông tin đơn hàng của bạn:</b><br>- Mã đơn hàng chính: #{{order_number}}<br>- Mã đơn hàng con của bạn: #{{suborder_number}}<br>- Tổng giá trị sản phẩm: {{subtotal_amount}} {{currency}}<br><br>Vui lòng đăng nhập vào <b>Trang quản lý người bán</b> để chuẩn bị hàng và giao cho đơn vị vận chuyển sớm nhất có thể.<br><br>Trân trọng,<br>Ban quản trị {{site_name}}",
                'shortcodes' => [
                    'seller_name' => 'Tên người bán',
                    'order_number' => 'Mã đơn hàng chính',
                    'suborder_number' => 'Mã đơn hàng con',
                    'subtotal_amount' => 'Giá trị sản phẩm',
                    'currency' => 'Loại tiền tệ',
                    'site_name' => 'Tên website'
                ],
            ],
            [
                'act' => 'ADMIN_COD_ORDER_ALERT',
                'name' => 'Thông báo Admin về đơn hàng COD mới',
                'subject' => '[Admin] Có đơn hàng COD mới chờ xác nhận #{{order_number}}',
                'email_body' => "Chào Admin,<br><br>Hệ thống vừa nhận được một đơn hàng mới theo hình thức <b>Thanh toán khi nhận hàng (COD)</b>.<br><br><b>Chi tiết đơn hàng:</b><br>- Mã đơn hàng: #{{order_number}}<br>- Tổng cộng: {{amount}} {{currency}}<br>- Người đặt: {{buyer_username}}<br><br>Vui lòng kiểm tra và xác nhận đơn hàng sớm để chuyển cho Người bán xử lý.<br><br>Trân trọng,<br>Hệ thống {{site_name}}",
                'shortcodes' => [
                    'order_number' => 'Mã đơn hàng',
                    'amount' => 'Số tiền',
                    'currency' => 'Loại tiền tệ',
                    'buyer_username' => 'Người mua',
                    'site_name' => 'Tên website'
                ],
            ],
            [
                'act' => 'SELLER_COD_ORDER_ALERT',
                'name' => 'Thông báo Seller về đơn hàng COD mới',
                'subject' => '[Người bán] Bạn có đơn hàng COD mới #{{order_number}}',
                'email_body' => "Chào {{seller_name}},<br><br>Bạn vừa có một đơn hàng mới mã #{{order_number}} theo hình thức <b>Thanh toán khi nhận hàng (COD)</b>.<br><br><b>Thông tin đơn hàng:</b><br>- Mã đơn chính: #{{order_number}}<br>- Mã đơn của bạn: #{{suborder_number}}<br>- Tổng giá trị sản phẩm: {{subtotal_amount}} {{currency}}<br><br>Vui lòng chờ Admin xác nhận đơn hàng trước khi tiến hành giao hàng.<br><br>Trân trọng,<br>Ban quản trị {{site_name}}",
                'shortcodes' => [
                    'seller_name' => 'Tên người bán',
                    'order_number' => 'Mã đơn hàng chính',
                    'suborder_number' => 'Mã đơn hàng con',
                    'subtotal_amount' => 'Giá trị sản phẩm',
                    'currency' => 'Loại tiền tệ',
                    'site_name' => 'Tên website'
                ],
            ],
            [
                'act' => 'BUYER_PAYMENT_CONFIRM',
                'name' => 'Xác nhận thanh toán thành công cho người mua',
                'subject' => '[Kviet Shop] Xác nhận thanh toán thành công đơn hàng #{{order_number}}',
                'email_body' => "Chào {{fullname}},<br><br>Chúc mừng bạn đã thanh toán thành công cho đơn hàng #{{order_number}} trên hệ thống {{site_name}}.<br><br><b>Thông tin đơn hàng:</b><br>- Mã đơn hàng: #{{order_number}}<br>- Tổng số tiền đã thanh toán: {{amount}} {{currency}}<br>- Trạng thái: Đã thanh toán (Đang chờ xử lý)<br><br>Chúng tôi sẽ sớm liên hệ với các nhà bán hàng để chuẩn bị và giao hàng cho bạn trong thời gian sớm nhất.<br><br>Cảm ơn bạn đã tin tưởng mua sắm tại {{site_name}}!",
                'shortcodes' => [
                    'fullname' => 'Tên người mua',
                    'order_number' => 'Mã đơn hàng',
                    'amount' => 'Số tiền',
                    'currency' => 'Loại tiền tệ',
                    'site_name' => 'Tên website'
                ],
            ],
            [
                'act' => 'ORDER_ON_PROCESSING_CONFIRMATION',
                'name' => 'Xác nhận đơn hàng COD được tiếp nhận (Người mua)',
                'subject' => '[Kviet Shop] Xác nhận đơn hàng COD #{{order_number}} đã được tiếp nhận',
                'email_body' => "Chào {{fullname}},<br><br>Cảm ơn bạn đã mua sắm tại {{site_name}}. Chúng tôi xin xác nhận đã tiếp nhận đơn hàng của bạn theo hình thức <b>Thanh toán khi nhận hàng (COD)</b>.<br><br><b>Thông tin đơn hàng của bạn:</b><br>- Mã đơn hàng: #{{order_number}}<br>- Tổng giá trị: {{amount}} {{currency}}<br>- Trạng thái: Chờ xác nhận<br><br><i>Lưu ý: Vui lòng chuẩn bị sẵn số tiền mặt tương ứng khi nhận hàng.</i><br><br>Chúng tôi sẽ sớm liên hệ với bạn để xác minh đơn hàng trước khi tiến hành giao hàng.<br><br>Trân trọng,<br>Ban quản trị {{site_name}}",
                'shortcodes' => [
                    'fullname' => 'Tên người mua',
                    'order_number' => 'Mã đơn hàng',
                    'amount' => 'Số tiền cần trả',
                    'currency' => 'Loại tiền tệ',
                    'site_name' => 'Tên website'
                ],
            ],
        ];

        foreach ($templates as $template) {
            NotificationTemplate::updateOrCreate(
                ['act' => $template['act']],
                [
                    'name' => $template['name'],
                    'subject' => $template['subject'],
                    'email_body' => $template['email_body'],
                    'shortcodes' => $template['shortcodes'],
                    'email_status' => 1,
                ]
            );
        }
    }
}
