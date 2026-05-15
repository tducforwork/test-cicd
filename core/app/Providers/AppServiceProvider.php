<?php
/*
|--------------------------------------------------------------------------
| App Service Provider
|--------------------------------------------------------------------------
*/

namespace App\Providers;

use App\Constants\Status;
use App\Lib\Searchable;
use App\Models\AdminNotification;
use App\Models\Category;
use App\Models\Deposit;
use App\Models\Frontend;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use App\Models\SubOrder;
use App\Models\SupportTicket;
use App\Models\Withdrawal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Builder::mixin(new Searchable);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (!cache()->get('SystemInstalled')) {
            $envFilePath = base_path('.env');
            if (!file_exists($envFilePath)) {
                header('Location: install');
                exit;
            }
            $envContents = file_get_contents($envFilePath);
            if (empty($envContents)) {
                header('Location: install');
                exit;
            } else {
                cache()->put('SystemInstalled', true);
            }
        }


        $viewShare['emptyMessage'] = 'Data not found';

        $viewShare['allCategories'] = Category::isParent()->with('subcategories')->get();
       

        $viewShare['mainMenus'] = \App\Models\MenuGroup::where('location', 'main_menu')->with([
            'menuItems' => function ($q) {
                $q->active()->where('parent_id', 0)->orderBy('order');
            }
        ])->first();

        $viewShare['topMenus'] = \App\Models\MenuGroup::where('location', 'top_bar')->with([
            'menuItems' => function ($q) {
                $q->active()->where('parent_id', 0)->orderBy('order');
            }
        ])->first();

        view()->share($viewShare);

        // Sidebar is now loaded directly from JSON in layouts/app.blade.php

        view()->composer('admin.partials.sidenav', function ($view) {
            $view->with([
                'bannedUsersCount'             => User::banned()->count(),
                'emailUnverifiedUsersCount'    => User::emailUnverified()->count(),
                'mobileUnverifiedUsersCount'   => User::mobileUnverified()->count(),
                'bannedSellersCount'           => User::seller()->banned()->count(),
                'kycPendingSellersCount'       => User::seller()->kycPending()->count(),
                'kycUnverifiedSellersCount'    => User::seller()->kycUnverified()->count(),
                'emailUnverifiedSellersCount'  => User::seller()->emailUnverified()->count(),
                'mobileUnverifiedSellersCount' => User::seller()->mobileUnverified()->count(),
                'pendingSellersCount'          => User::sellerPending()->count(),
                'pendingTicketCount'           => SupportTicket::whereIN('status', [Status::TICKET_OPEN, Status::TICKET_REPLY])->count(),
                'pendingDepositsCount'         => Deposit::pending()->count(),
                'pendingWithdrawCount'         => Withdrawal::pending()->count(),
                'pendingProductsCount'         => Product::where('seller_id', '!=', 0)->pending()->count(),
                'pendingOrdersCount'           => SubOrder::valid()->orderNotCanceled()->pending()->count(),
                'processingOrdersCount'        => SubOrder::valid()->orderNotCanceled()->processing()->count(),
                'dispatchedOrdersCount'        => SubOrder::valid()->orderNotCanceled()->dispatched()->count(),
                'deliveredOrdersCount'         => SubOrder::valid()->orderNotCanceled()->delivered()->count(),
                'settledOrdersCount'           => SubOrder::valid()->orderNotCanceled()->completed()->count(),
                'disputedOrdersCount'          => SubOrder::valid()->orderNotCanceled()->disputed()->count(),
                'readyToPickupSubOrdersCount'  => SubOrder::valid()->orderNotCanceled()->readyToPickup()->count(),
                'readyToDeliverOrdersCount'    => Order::readyToDeliver()->count(),

                'pendingSubOrdersCount'        => SubOrder::admin()->valid()->orderNotCanceled()->pending()->count(),
                'processingSubOrdersCount'     => SubOrder::admin()->valid()->orderNotCanceled()->processing()->count(),
                'readyToPickupSubOrdersCountAdmin' => SubOrder::admin()->valid()->orderNotCanceled()->readyToPickup()->count(),
                'dispatchedSubOrdersCount'     => SubOrder::admin()->valid()->orderNotCanceled()->dispatched()->count(),
                'deliveredSubOrdersCount'      => SubOrder::admin()->valid()->orderNotCanceled()->delivered()->count(),
                'settledSubOrdersCountAdmin'   => SubOrder::admin()->valid()->orderNotCanceled()->completed()->count(),
                'disputedSubOrdersCountAdmin'  => SubOrder::admin()->valid()->orderNotCanceled()->disputed()->count(),

                'updateAvailable'              => version_compare(gs('available_version'), systemDetails()['version'], '>') ? 'v' . gs('available_version') : false,
            ]);
        });

        view()->composer(['seller.partials.sidenav', 'seller.partials.sidebar'], function ($view) {
            $view->with([
                'pendingProductsCount'         => Product::belongsToSeller()->pending()->count(),
                'pendingOrdersCount'           => SubOrder::belongsToSeller()->valid()->orderNotCanceled()->pending()->count(),
                'processingOrdersCount'        => SubOrder::belongsToSeller()->valid()->orderNotCanceled()->processing()->count(),
                'readyToPickupOrdersCount'     => SubOrder::belongsToSeller()->valid()->orderNotCanceled()->readyToPickup()->count(),
                'userPendingOrdersCount'       => Order::where('user_id', \Illuminate\Support\Facades\Auth::id())->pending()->count(),
            ]);
        });

        view()->composer('admin.partials.topnav', function ($view) {
            $view->with([
                'adminNotifications'     => AdminNotification::where('is_read', Status::NO)->with('user')->orderBy('id', 'desc')->take(10)->get(),
                'adminNotificationCount' => AdminNotification::where('is_read', Status::NO)->count(),
            ]);
        });

        view()->composer('partials.seo', function ($view) {
            $seo = Frontend::where('data_keys', 'seo.data')->first();
            $view->with([
                'seo' => $seo ? $seo->data_values : $seo,
            ]);
        });

        if (gs('force_ssl')) {
            \URL::forceScheme('https');
        }

        Paginator::useBootstrapFive();

        \Illuminate\Support\Facades\View::addNamespace('Template', resource_path('views/templates/' . activeTemplateName()));

        Category::observe(\App\Observers\KeyObserver::class);
        Product::observe(\App\Observers\KeyObserver::class);
        \App\Models\News::observe(\App\Observers\KeyObserver::class);
        \App\Models\NewsCategory::observe(\App\Observers\KeyObserver::class);
    }
}
