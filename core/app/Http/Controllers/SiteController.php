<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\AdminNotification;
use App\Models\Brand;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\Offer;
use App\Models\Product;
use App\Models\User;
use App\Models\Subscriber;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

use App\Models\Key;
use App\Models\News;
use App\Models\NewsCategory;
use App\Http\Controllers\ShopController;
use App\Models\Category;

class SiteController extends Controller
{
    public function checkKey($slug)
    {
        $key = Key::where('slug', $slug)->first();

        if ($key) {
            switch ($key->type) {
                case Key::TYPE_CATEGORY:
                    return app(ShopController::class)->productsByCategoryNew($slug);
                case Key::TYPE_BRAND:
                    return app(ShopController::class)->productsByBrand(request(), $key->key_id);
                case Key::TYPE_PRODUCT_TYPE:
                    return app(ShopController::class)->productsByProductType(request(), $key->key_id);
                case Key::TYPE_PRODUCT:
                    return app(ShopController::class)->productDetailsNew($slug);
                case Key::TYPE_NEWS:
                    return $this->newsDetails($slug);
                case Key::TYPE_NEWS_CATEGORY:
                    return $this->newsByCategory($slug);
            }
        }

        return $this->pages($slug);
    }

    public function newsByCategory($slug)
    {
        $category = NewsCategory::where('slug', $slug)->firstOrFail();
        $pageTitle = $category->name;
        $news = News::where('category_id', $category->id)
            ->published()
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->paginate(getPaginate(15));

        return view('Template::news.index', compact('pageTitle', 'news', 'category'));
    }
    public function index()
    {
        $page = \App\Models\Page::where('tempname', activeTemplateName())->where('slug', '/')->first();
        $sections = $page ? $page->secs : null;

        $topSellingProducts = Product::topSales(9);
        $featuredProducts   = Product::publishable()->featured()->inRandomOrder()->take(8)->get();
        $latestProducts     = Product::publishable()->latest()->inRandomOrder()->take(12)->get();
        $featuredSeller     = User::seller()->active()->featured()->whereHas('shop')->with('shop')->inRandomOrder()->take(16)->get();
        $topBrands          = Brand::top()->inRandomOrder()->take(16)->get();
        $pageTitle          = 'Home';
        $allCategoriesHome  = Category::all();
        $offers             = Offer::where('status', Status::YES)->where('end_date', '>', now())
            ->with([
                'products' => function ($q) {
                    return $q->whereHas('categories')->whereHas('brand');
                },
                'products.reviews'
            ])->get();
        $latestNews         = \App\Models\News::published()->orderBy('published_at', 'desc')->take(3)->get();

        return view('Template::home', compact('pageTitle', 'offers', 'topSellingProducts', 'featuredProducts', 'featuredSeller', 'topBrands', 'latestProducts', 'latestNews', 'sections', 'allCategoriesHome'));
    }


    public function contact()
    {
        $pageTitle = "Contact Us";
        $user = auth()->user();
        return view('Template::contact', compact('pageTitle', 'user'));
    }

    public function about()
    {
        $pageTitle = "Giới thiệu";
        $latestNews = \App\Models\News::published()->orderBy('published_at', 'desc')->take(3)->get();
        return view('Template::about', compact('pageTitle', 'latestNews'));
    }


    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        $request->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $random = getNumber();

        $ticket = new SupportTicket();
        $ticket->user_id = auth()->id() ?? 0;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->priority = Status::PRIORITY_MEDIUM;


        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = Status::TICKET_OPEN;
        $ticket->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user() ? auth()->id() : 0;
        $adminNotification->title = 'A new contact message has been submitted';
        $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);
        $adminNotification->save();

        $message = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        // Contact User Notification
        notify($ticket, 'CONTACT_USER_CONFIRMATION', [
            'subject' => $ticket->subject,
            'content' => $request->message,
            'site_name' => gs('site_name'),
        ], ['email']);

        // Contact Admin Notification
        $adminNotify = [
            'email' => gs('contact_email') ?? gs('email_from'),
            'fullname' => 'Admin',
            'username' => 'admin'
        ];
        notify($adminNotify, 'CONTACT_ADMIN_NOTIFICATION', [
            'name' => $ticket->name,
            'email' => $ticket->email,
            'subject' => $ticket->subject,
            'content' => $request->message,
        ], ['email']);

        $notify[] = ['success', 'Message sent successfully!'];

        return back()->withNotify($notify);
    }

    public function policyPages($slug)
    {
        $policy = Frontend::where('slug', $slug)->where('data_keys', 'policy_pages.element')->firstOrFail();
        $pageTitle = $policy->data_values->title;
        $seoContents = $policy->seo_content;
        $seoImage = @$seoContents->image ? frontendImage('policy_pages', $seoContents->image, getFileSize('seo'), true) : null;
        return view('Template::policy', compact('policy', 'pageTitle', 'seoContents', 'seoImage'));
    }

    public function helpSupport()
    {
        $pageTitle = "Help & Support";
        $helpSupport = Frontend::where('data_keys', 'help_support.content')->firstOrFail();
        $elements = Frontend::where('data_keys', 'help_support.element')->orderBy('id', 'asc')->get();
        return view('Template::help_support', compact('pageTitle', 'helpSupport', 'elements'));
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) $lang = 'en';
        session()->put('lang', $lang);
        return back();
    }

    public function pageDetails($slug)
    {
        $pageDetails  = Frontend::where('slug', $slug)->where('data_keys', 'pages.element')->firstOrFail();
        $pageTitle = $pageDetails->data_values->title;
        return view('Template::page_details', compact('pageTitle', 'pageDetails'));
    }

    public function blogDetails($slug)
    {
        $blog = Frontend::where('slug', $slug)->where('data_keys', 'blog.element')->firstOrFail();
        $pageTitle = $blog->data_values->title;
        $seoContents = $blog->seo_content;
        $seoImage = @$seoContents->image ? frontendImage('blog', $seoContents->image, getFileSize('seo'), true) : null;
        return view('Template::blog_details', compact('blog', 'pageTitle', 'seoContents', 'seoImage'));
    }


    public function cookieAccept()
    {
        Cookie::queue('gdpr_cookie', gs('site_name'), 43200);
    }

    public function cookiePolicy()
    {
        $cookieContent = Frontend::where('data_keys', 'cookie.data')->first();
        abort_if($cookieContent->data_values->status != Status::ENABLE, 404);
        $pageTitle = 'Cookie Policy';
        $cookie = Frontend::where('data_keys', 'cookie.data')->first();
        return view('Template::cookie', compact('pageTitle', 'cookie'));
    }

    public function placeholderImage($size = null)
    {
        $imgWidth = explode('x', $size)[0];
        $imgHeight = explode('x', $size)[1];
        $text = $imgWidth . '×' . $imgHeight;
        $fontFile = realpath('assets/font/solaimanLipi_bold.ttf');
        $fontSize = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if ($imgHeight < 100 && $fontSize > 30) {
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $bgFill);
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function maintenance()
    {
        $pageTitle = 'Maintenance Mode';
        if (gs('maintenance_mode') == Status::DISABLE) {
            return to_route('home');
        }
        $maintenance = Frontend::where('data_keys', 'maintenance.data')->first();
        return view('Template::maintenance', compact('pageTitle', 'maintenance'));
    }

    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribers,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()]);
        }

        $subscriber = new Subscriber();
        $subscriber->email = $request->email;
        $subscriber->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = 0;
        $adminNotification->title = 'Có email mới đăng ký nhận bản tin: ' . $request->email;
        $adminNotification->click_url = urlPath('admin.subscriber.index');
        $adminNotification->save();

        // User Notification
        $receiverName = explode('@', $request->email)[0];
        $user = [
            'username' => $request->email,
            'email'    => $request->email,
            'fullname' => $receiverName,
        ];
        notify($user, 'SUBSCRIBE_CONFIRMATION', [
            'email' => $request->email,
        ], ['email']);

        // Admin Notification
        $adminNotify = [
            'email' => gs('email_from'),
            'fullname' => 'Admin',
            'username' => 'admin'
        ];
        notify($adminNotify, 'ADMIN_SUBSCRIBE_NOTIFICATION', [
            'email' => $request->email,
        ], ['email']);

        return response()->json(['success' => 'Đăng ký nhận bản tin thành công!']);
    }

    // ==================== News ====================

    public function newsIndex()
    {
        $pageTitle = 'Tin Tức';

        $news = \App\Models\News::published()
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->paginate(getPaginate(15));

        return view('Template::news.index', compact('pageTitle', 'news'));
    }

    public function newsDetails($slug)
    {
        $news = \App\Models\News::where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Increment view count
        $news->increment('view_count');

        $pageTitle = $news->title;

        // Recent news for sidebar
        $recentNews = \App\Models\News::published()
            ->where('id', '!=', $news->id)
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        return view('Template::news.details', compact('pageTitle', 'news', 'recentNews'));
    }
    public function pages($slug)
    {
        $page = \App\Models\Page::where('tempname', activeTemplateName())->where('slug', $slug)->first();
        if ($page) {
            $pageTitle = $page->name;
            $sections = $page->secs;
            $seoContents = $page->seo_content;
            $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . $seoContents->image, getFileSize('seo')) : null;
            return view('Template::pages', compact('pageTitle', 'sections', 'seoContents', 'seoImage'));
        }

        $page = Frontend::where('slug', $slug)->where('data_keys', 'pages.element')->first();
        if (!$page) {
            return $this->policyPages($slug);
        }
        $pageTitle = $page->data_values->title;
        $sections = null; // Frontend pages don't have builder sections in the old system
        return view('Template::pages', compact('pageTitle', 'page', 'sections'));
    }

    public function secondhand()
    {
        $pageTitle = 'Second Hand';
        $emptyMessage = 'No second hand products found';
        $products = Product::publishable()
            ->where('seller_id', '>', 0)
            ->with(['categories', 'reviews', 'offer.activeOffer'])
            ->paginate(25);

        if (request()->ajax()) {
            return view('Template::partials.ajax_product_items', compact('products'));
        }

        return view('Template::secondhand', compact('pageTitle', 'products', 'emptyMessage'));
    }

    public function theMall()
    {
        $pageTitle = 'The Mall';
        $emptyMessage = 'No products found in The Mall';
        $products = Product::publishable()
            ->where('seller_id', 0)
            ->with(['categories', 'reviews', 'offer.activeOffer'])
            ->paginate(25);

        if (request()->ajax()) {
            return view('Template::partials.ajax_product_items', compact('products'));
        }

        return view('Template::html.the-mall', compact('pageTitle', 'products', 'emptyMessage'));
    }
}
