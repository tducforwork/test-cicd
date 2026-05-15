<?php

use Illuminate\Support\Facades\Route;


Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});


// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{id}', 'replyTicket')->name('reply');
    Route::post('close/{id}', 'closeTicket')->name('close');
    Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
});

Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');

// Product Details
Route::controller('ShopController')->group(function () {
    Route::get('quang-phat-mall', 'productsByCategoryNew')->name('quang_phat_mall');
    Route::get('products', 'products')->name('products');
    Route::get('products/filter', 'productsFilter')->name('products.filter');

    Route::get('quick-view', 'quickView')->name('quick.view');
    Route::get('product/{slug}', 'productDetailsNew')->name('product.detail');

    Route::get('product-variant-stock', 'getVariantStock')->name('product.variant.stock');
    Route::get('product-variant-image', 'getVariantImage')->name('product.variant.image');
    Route::get('products/search', 'productSearch')->name('product.search');
    Route::get('ajax-search', 'ajaxSearch')->name('ajax.search');
    Route::get('products/search/{perpage?}', 'productSearch')->name('product.search.filter');
    Route::get('product-reviews', 'loadMoreReviews')->name('product.review.load.more');
    Route::post('product-review/store', 'storeReview')->name('product.review.store');


    //Compare
    Route::get('add_to_compare/', 'addToCompare')->name('addToCompare');
    Route::get('get_compare_data/', 'getCompare')->name('get-compare-data');
    Route::get('compare/', 'compare')->name('compare');
    Route::post('remove_from_compare/{id}', 'removeFromCompare')->name('del-from-compare');


    // Categories
    Route::get('categories', 'categories')->name('categories');
    Route::get('category/{slug}', 'productsByCategoryNew')->name('products.category');
    Route::get('category/filter/{slug}', 'productsByCategoryNew')->name('category.filter');

    // Brands
    Route::get('brands', 'brands')->name('brands');
    Route::get('brands/{id}/{slug}', 'productsByBrand')->name('products.brand');
    Route::get('brands/filter/{id}/{slug}', 'productsByBrand')->name('brands.filter');

    Route::get('our-sellers', 'allSellers')->name('all.sellers');
    Route::get('seller/{identifier}', 'sellerDetails')->name('seller.details');
});

//Cart
Route::controller('CartController')->group(function () {
    Route::post('add-to-cart/', 'addToCart')->name('add-to-cart');
    Route::get('cart-data', 'getCart')->name('get-cart-data');
    Route::get('get_cart-total/', 'getCartTotal')->name('get-cart-total');
    Route::get('cart/shipping-charge', 'getCartShippingCharge')->name('cart.shipping.charge');
    Route::get('my-cart/', 'shoppingCart')->name('shopping-cart');
    Route::post('remove_cart_item/{id}', 'removeCartItem')->name('remove-cart-item');
    Route::post('update_cart_item/{id}', 'updateCartItem')->name('update-cart-item');
});


Route::controller('CouponController')->group(function () {
    Route::post('apply_coupon/', 'applyCoupon')->name('applyCoupon');
    Route::post('remove_coupon/', 'removeCoupon')->name('removeCoupon');
    Route::get('get-coupon/{code}', 'getCoupon')->name('get-coupon');
});

//Wishlist
Route::controller('WishlistController')->group(function () {
    Route::post('wishlist/add', 'addToWishList')->name('wishlist.add');
    Route::get('get-wishlist-total', 'getWishListTotal')->name('get-wishlist-total');
});

Route::controller('WishlistController')->middleware('auth')->group(function () {
    Route::get('wishlist', 'wishList')->name('wishlist');
    Route::get('danh-sach-yeu-thich', 'wishList')->name('danh-sach-yeu-thich');
    Route::post('wishlist/remove/{id?}', 'removeFromWishList')->name('wishlist.remove');
});

Route::controller('OrderController')->group(function () {
    Route::get('track-order', 'trackOrder')->name('orderTrack');
    Route::post('track-order', 'getOrderTrackData')->name('order.track');
});
Route::get('/chi-tiet-san-pham', function () {
    return view('templates.basic.html.chi-tiet-san-pham', ['pageTitle' => 'Chi tiết sản phẩm']);
})->name('temp.product.detail');

Route::get('/danh-muc-san-pham', function () {
    return view('templates.basic.html.danh-muc-san-pham', ['pageTitle' => 'Danh mục sản phẩm']);
})->name('temp.category.product');

Route::get('/gio-hang', function () {
    return view('templates.basic.html.gio-hang', ['pageTitle' => 'Giỏ hàng']);
})->name('temp.cart');

Route::get('/thanh-toan', function () {
    return view('templates.basic.html.thanh-toan', ['pageTitle' => 'Thanh toán']);
})->name('temp.checkout');


Route::get('/seller-detail', function () {
    return view('templates.basic.html.seller', ['pageTitle' => 'Seller']);
})->name('temp.seller');

Route::get('/danh-sach-tin-tuc', function () {
    return view('templates.basic.html.danh-sach-tin-tuc', ['pageTitle' => 'Danh sách tin tức']);
})->name('temp.danh-sach-tin-tuc');

Route::get('/chi-tiet-bai-viet', function () {
    return view('templates.basic.html.chi-tiet-bai-viet', ['pageTitle' => 'Chi tiết bài viết']);
})->name('temp.blog-detail');

Route::get('/lien-he', function () {
    return view('templates.basic.html.lien-he', ['pageTitle' => 'Liên hệ']);
})->name('temp.contact');

Route::get('/loi-404', function () {
    return view('templates.basic.html.loi-404', ['pageTitle' => '404 Not Found']);
})->name('temp.404');




Route::get('payos/return', 'App\Http\Controllers\Gateway\Payos\ProcessController@paymentReturn')->name('payos.return');
Route::get('payos/cancel', 'App\Http\Controllers\Gateway\Payos\ProcessController@paymentReturn')->name('payos.cancel');
Route::post('ipn/payos', 'App\Http\Controllers\Gateway\Payos\ProcessController@ipn')->name('ipn.payos');

// Momo IPN (webhook tu server MoMo)
Route::post('ipn/Momo', 'App\Http\Controllers\Gateway\Momo\ProcessController@ipn')->name('ipn.Momo');
Route::controller('SiteController')->group(function () {
    Route::get('/gioi-thieu', 'about')->name('about');
    Route::get('lien-he', 'contact')->name('contact');
    Route::post('lien-he/submit', 'contactSubmit')->name('contact.submit');
    Route::get('change-language/{lang?}', 'changeLanguage')->name('change.language');
    Route::get('policy-pages/{slug}', 'policyPages')->name('policy.pages');
    Route::get('help-support', 'helpSupport')->name('help.support');

    Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image');
    Route::get('pages/{slug}', 'pageDetails')->name('page.details');
    Route::get('policy/{slug}', 'policyPages')->name('policy.pages');
    Route::post('subscribe', 'subscribe')->name('subscribe');
    Route::get('blog/{slug}', 'blogDetails')->name('blog.details');
    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');
    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');
    Route::get('placeholder-image/{size}', 'placeholderImage')->withoutMiddleware('maintenance')->name('placeholder.image');
    Route::get('maintenance-mode', 'maintenance')->withoutMiddleware('maintenance')->name('maintenance');

    // News Routes
    Route::get('tin-tuc', 'newsIndex')->name('news.index');
    Route::get('tin-tuc/{slug}', 'newsDetails')->name('news.details');
    Route::get('danh-muc/{slug}', 'newsByCategory')->name('news.category');

    Route::get('/', 'index')->name('home');
    Route::get('secondhand', 'secondhand')->name('secondhand');
    Route::get('the-mall', 'theMall')->name('the_mall');

    Route::get('get-wards/{id}', function($id) {
        return \App\Models\Ward::active()->where('province_id', $id)->orderBy('name')->get();
    })->name('get.wards');

    Route::get('/{slug}', 'checkKey')->name('pages');
});
