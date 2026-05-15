<?php

namespace Database\Seeders;

use App\Models\GeneralSetting;
use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $template = '
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    body { font-family: \'Open Sans\', Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; -webkit-text-size-adjust: none; }
    .wrapper { width: 100%; table-layout: fixed; background-color: #f4f7f6; padding-bottom: 40px; }
    .container { width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; margin-top: 20px; }
    .header { background-color: #272343; padding: 30px; text-align: center; border-bottom: 4px solid #FF6F0F; }
    .header h1 { color: #ffffff; margin: 0; font-size: 28px; letter-spacing: 1px; }
    .content { padding: 40px; color: #404040; line-height: 1.8; }
    .content h2 { color: #272343; font-size: 22px; margin-bottom: 10px; text-align: center; }
    .divider { height: 3px; width: 60px; background-color: #FF6F0F; margin: 0 auto 30px; }
    .message-body { font-size: 16px; color: #505050; }
    .footer { background-color: #f9f9f9; padding: 25px; text-align: center; border-top: 1px solid #eeeeee; }
    .footer p { color: #95a5a6; font-size: 13px; margin: 0; line-height: 1.5; }
    .footer a { color: #FF6F0F; text-decoration: none; font-weight: bold; }
    .btn { display: inline-block; padding: 12px 30px; background-color: #FF6F0F; color: #ffffff !important; text-decoration: none; border-radius: 4px; font-weight: bold; margin-top: 20px; }
</style>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <div class="header">
             <h1>{{site_name}}</h1>
        </div>
        <div class="content">
            <h2>Hello {{fullname}}</h2>
            <div class="divider"></div>
            <div class="message-body">
                {{message}}
            </div>
        </div>
        <div class="footer">
            <p>© ' . date('Y') . ' <a href="' . url('/') . '">{{site_name}}</a>. All Rights Reserved.</p>
        </div>
    </div>
</div>
</body>
</html>';

        // Update Global Template
        $gs = GeneralSetting::first();
        $gs->email_template = $template;
        $gs->save();

        // Admin Notification Template
        NotificationTemplate::updateOrCreate(
            ['act' => 'CONTACT_ADMIN_NOTIFICATION'],
            [
                'name' => 'Contact Admin Notification',
                'subject' => 'New Contact Message: {{subject}}',
                'email_body' => '
                    <p>You have received a new contact message from your website.</p>
                    <p><strong>Sender Details:</strong></p>
                    <ul>
                        <li><strong>Name:</strong> {{name}}</li>
                        <li><strong>Email:</strong> {{email}}</li>
                        <li><strong>Subject:</strong> {{subject}}</li>
                    </ul>
                    <hr>
                    <p><strong>Message Content:</strong></p>
                    <p>{{content}}</p>
                    <p>You can reply directly to this email or manage it via Admin Panel.</p>',
                'shortcodes' => '{"name":"User Name","email":"User Email","subject":"Message Subject","content":"Message Content"}',
                'email_status' => 1
            ]
        );

        // User Confirmation Template
        NotificationTemplate::updateOrCreate(
            ['act' => 'CONTACT_USER_CONFIRMATION'],
            [
                'name' => 'Contact User Confirmation',
                'subject' => 'We received your message: {{subject}}',
                'email_body' => '
                    <p>Thank you for contacting us. We have received your message regarding "<strong>{{subject}}</strong>".</p>
                    <p>Our team will review your inquiry and get back to you within 24 hours.</p>
                    <p><strong>Your Message Summary:</strong></p>
                    <blockquote style="border-left: 4px solid #FF6F0F; padding-left: 15px; font-style: italic;">
                        {{content}}
                    </blockquote>
                    <p>Best Regards,<br>The {{site_name}} Team</p>',
                'shortcodes' => '{"subject":"Message Subject","content":"Message Content","site_name":"Site Name"}',
                'email_status' => 1
            ]
        );
    }
}
