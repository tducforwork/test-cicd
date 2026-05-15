<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Api')->name('api.')->group(function () {

    Route::controller('AppController')->group(function () {
        Route::get('general-setting', 'generalSetting');
        Route::get('get-countries', 'getCountries');
        Route::get('language/{key?}', 'getLanguage');
        Route::get('policies', 'policies');
        Route::get('policy/{slug}', 'policyContent');
        Route::get('faq', 'faq');
        Route::get('seo', 'seo');
        Route::get('get-extension/{act}', 'getExtension');
        Route::post('contact', 'submitContact');
        Route::get('cookie', 'cookie');
        Route::post('cookie/accept', 'cookieAccept');
        Route::get('custom-pages', 'customPages');
        Route::get('custom-page/{slug}', 'customPageData');
        Route::get('section-data/{section}', 'sectionData');
        Route::get('ticket/{ticket}', 'viewTicket');
        Route::post('ticket/ticket-reply/{id}', 'replyTicket');
    });

    Route::controller('ProductController')->group(function () {
        Route::get('products', 'index');
        Route::get('product/detail/{id}', 'detail');
        Route::get('product/related/{id}', 'relatedProducts');
        Route::get('categories', 'categories');
        Route::get('brands', 'brands');
        Route::get('check-coupon', 'checkCoupon');
    });

    Route::controller('ShopController')->group(function () {
        Route::get('sellers', 'allSellers');
        Route::get('seller/details/{id}', 'sellerDetails');
        Route::get('featured-sellers', 'featuredSellers');
    });

    Route::namespace('Auth')->group(function () {
        Route::controller('LoginController')->group(function () {
            Route::post('login', 'login');
            Route::post('check-token', 'checkToken');
            Route::post('social-login', 'socialLogin');
        });
//        Route::post('register', 'RegisterController@register');

        Route::controller('ForgotPasswordController')->group(function () {
            Route::post('password/email', 'sendResetCodeEmail');
            Route::post('password/verify-code', 'verifyCode');
            Route::post('password/reset', 'reset');
        });
    });

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('user-data-submit', 'UserController@userDataSubmit');

        //authorization
/*
        Route::middleware('registration.complete')->controller('AuthorizationController')->group(function () {
            Route::get('authorization', 'authorization');
            Route::get('resend-verify/{type}', 'sendVerifyCode');
            Route::post('verify-email', 'emailVerification');
            Route::post('verify-mobile', 'mobileVerification');
            Route::post('verify-g2fa', 'g2faVerification');
        });
*/

        Route::middleware(['check.status'])->group(function () {

            Route::middleware('registration.complete')->group(function () {
                Route::get('dashboard', function () {
                    return auth()->user();
                });

                Route::controller('UserController')->group(function () {
                    Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');
                    Route::post('profile-setting', 'submitProfile');
                    Route::post('change-password', 'changePassword');

                    Route::get('user-info', 'userInfo');

                    //Report
                    Route::any('deposit/history', 'depositHistory');
                    Route::get('transactions', 'transactions');

                    Route::post('add-device-token', 'addDeviceToken');
                    Route::get('push-notifications', 'pushNotifications');
                    Route::post('push-notifications/read/{id}', 'pushNotificationsRead');

                    //2FA
                    Route::get('twofactor', 'show2faForm');
                    Route::post('twofactor/enable', 'create2fa');
                    Route::post('twofactor/disable', 'disable2fa');

                    Route::post('delete-account', 'deleteAccount');
                });

                Route::controller('ProductController')->prefix('product')->group(function () {
                    Route::get('list', 'index');
                    Route::get('detail/{id}', 'detail');
                    Route::post('add-review', 'addReview');
                });

                Route::controller('WishlistController')->prefix('wishlist')->group(function () {
                    Route::get('list', 'wishlist');
                    Route::post('add', 'addToWishlist');
                    Route::post('remove/{id}', 'removeFromWishlist');
                });

                Route::controller('OrderController')->prefix('order')->group(function () {
                    Route::get('list', 'list');
                    Route::get('detail/{id}', 'detail');
                    Route::post('create', 'create');
                });

                Route::controller('TicketController')->prefix('ticket')->group(function () {
                    Route::get('/', 'supportTicket');
                    Route::post('create', 'storeSupportTicket');
                    Route::get('view/{ticket}', 'viewTicket');
                    Route::post('reply/{id}', 'replyTicket');
                    Route::post('close/{id}', 'closeTicket');
                    Route::get('download/{attachment_id}', 'ticketDownload');
                });

                Route::controller('CartController')->prefix('cart')->group(function () {
                    Route::get('get', 'getCart');
                    Route::post('add', 'addToCart');
                    Route::post('update/{id}', 'updateCartItem');
                    Route::post('remove/{id}', 'removeCartItem');
                });

                // Seller Routes
                Route::namespace('Seller')->prefix('seller')->group(function () {
                    Route::controller('SellerController')->group(function () {
                        Route::get('dashboard', 'dashboard');
                        Route::get('products', 'products');
                        Route::get('orders', 'orders');
                        Route::get('order-detail/{id}', 'orderDetail');
                        Route::post('order-processing/{id}', 'markAsProcessing');
                        Route::post('order-ready-to-pickup/{id}', 'markAsReadyToPickUp');
                        Route::post('order-reject/{id}', 'reject');
                        Route::get('sell-logs', 'sellLogs');
                        Route::get('transactions', 'transactions');
                        Route::get('shop', 'shop');
                        Route::post('shop-update', 'shopUpdate');
                    });

                    Route::controller('ProductController')->prefix('product')->group(function () {
                        Route::get('list', 'index');
                        Route::get('real-estate-list', 'realEstateIndex');
                        Route::get('configs', 'productCreateConfigs');
                        Route::get('edit/{id}', 'edit');
                        Route::post('store/{id}', 'store');
                        Route::post('delete/{id}', 'delete');
                        Route::get('get-wards/{id}', 'getWards');

                        // Variants
                        Route::get('variants/{product_id}', 'variants');
                        Route::post('variant/store/{product_id}', 'storeVariant');
                        Route::post('variant/update/{variant_id}', 'updateVariant');
                        Route::post('variant/delete/{variant_id}', 'deleteVariant');
                        Route::get('variant/images/{variant_id}', 'variantImages');
                        Route::post('variant/images/save/{variant_id}', 'saveVariantImages');
                    });

                    Route::controller('WithdrawController')->prefix('withdraw')->group(function () {
                        Route::get('methods', 'withdrawMethods');
                        Route::post('store', 'withdrawStore');
                        Route::post('submit', 'withdrawSubmit');
                        Route::get('history', 'history');
                    });

                    Route::controller('TicketController')->prefix('ticket')->group(function () {
                        Route::get('list', 'supportTicket');
                        Route::post('create', 'storeSupportTicket');
                        Route::get('view/{ticket}', 'viewTicket');
                        Route::post('reply/{id}', 'replyTicket');
                        Route::post('close/{id}', 'closeTicket');
                        Route::get('download/{attachment_id}', 'ticketDownload');
                    });
                });
            });

            // Payment - ngoài check.status, vẫn cần auth:sanctum + registration.complete
            Route::middleware('registration.complete')->controller('PaymentController')->prefix('deposit')->group(function () {
                Route::get('methods', 'methods');
                Route::post('insert', 'depositInsert');
                Route::any('confirm', 'appPaymentConfirm')->name('deposit.confirm');
                Route::post('manual-confirm', 'manualDepositConfirm');
            });
        });

        Route::get('logout', 'Auth\LoginController@logout');
    });
});
