<?php

use Illuminate\Support\Facades\Route;
use Webkul\Shop\Http\Controllers\BookingProductController;
use Webkul\Shop\Http\Controllers\CompareController;
use Webkul\Shop\Http\Controllers\HomeController;
use Webkul\Shop\Http\Controllers\PageController;
use Webkul\Shop\Http\Controllers\ProductController;
use Webkul\Shop\Http\Controllers\ProductsCategoriesProxyController;
use Webkul\Shop\Http\Controllers\SearchController;
use Webkul\Shop\Http\Controllers\SubscriptionController;
use App\Http\Controllers\LamGamePageController;

/**
 * CMS pages.
 */
Route::get('page/{slug}', [PageController::class, 'view'])
    ->name('shop.cms.page')
    ->middleware('cache.response');

/**
 * Fallback route.
 */
Route::fallback(ProductsCategoriesProxyController::class.'@index')
    ->name('shop.product_or_category.index')
    ->middleware('cache.response');

/**
 * Store front home.
 */
Route::get('/', [HomeController::class, 'index'])
    ->name('shop.home.index')
    ->middleware('cache.response');

Route::get('contact-us', [HomeController::class, 'contactUs'])
    ->name('shop.home.contact_us')
    ->middleware('cache.response');

Route::post('contact-us/send-mail', [HomeController::class, 'sendContactUsMail'])
    ->name('shop.home.contact_us.send_mail')
    ->middleware('cache.response');

/**
 * Store front search.
 */
Route::get('search', [SearchController::class, 'index'])
    ->name('shop.search.index')
    ->middleware('cache.response');

Route::post('search/upload', [SearchController::class, 'upload'])->name('shop.search.upload');

/**
 * Subscription routes.
 */
Route::controller(SubscriptionController::class)->group(function () {
    Route::post('subscription', 'store')->name('shop.subscription.store');

    Route::get('subscription/{token}', 'destroy')->name('shop.subscription.destroy');
});

/**
 * Compare products
 */
Route::get('compare', [CompareController::class, 'index'])
    ->name('shop.compare.index')
    ->middleware('cache.response');

/**
 * Downloadable products
 */
Route::controller(ProductController::class)->group(function () {
    Route::get('downloadable/download-sample/{type}/{id}', 'downloadSample')->name('shop.downloadable.download_sample');

    Route::get('product/{id}/{attribute_id}', 'download')->defaults('_config', [
        'view' => 'shop.products.index',
    ])->name('shop.product.file.download');
});

/**
 * Booking products
 */
Route::get('booking-slots/{id}', [BookingProductController::class, 'index'])
    ->name('shop.booking-product.slots.index');

/**
 * Course Registration API - Landing Page Integration
 */
Route::controller(\Webkul\Shop\Http\Controllers\CourseRegistrationController::class)
    ->withoutMiddleware(['web']) // Remove CSRF protection for API
    ->group(function () {
        // API để đăng ký khóa học từ landing page
        Route::post('api/course/register', 'registerCourse')
            ->name('api.course.register');
        
        // API để lấy thông tin khóa học
        Route::get('api/course/info', 'getCourseInfo')
            ->name('api.course.info');
        
        // API để kiểm tra trạng thái đăng ký
        Route::post('api/course/status', 'checkRegistrationStatus')
            ->name('api.course.status');
    });

/**
 * LamGame Pages Routes
 */
Route::controller(LamGamePageController::class)->group(function () {
    // Static pages
    Route::get('gioi-thieu', 'gioiThieu')->name('lamgame.gioi-thieu');
    Route::get('lien-he', 'lienHe')->name('lamgame.lien-he');
    Route::post('lien-he/gui', 'submitContact')->name('lamgame.lien-he.submit');
    
    // Blog
    Route::get('blog', 'blog')->name('lamgame.blog');
    Route::get('blog/{slug}', 'blogShow')->name('blog.show');
    
    // Viec lam game
    Route::get('viec-lam-game', 'viecLamGame')->name('lamgame.viec-lam-game');
    
    // Course detail
    Route::get('khoa-hoc/{slug}', 'courseDetail')->name('lamgame.khoa-hoc');
    
    // Source game
    Route::get('source-game', 'sourceGame')->name('lamgame.source-game');
    Route::get('source-game/{slug}', 'sourceGameDetail')->name('lamgame.source-game.detail');
    
    // Community
    Route::get('cong-dong', 'congDong')->name('lamgame.cong-dong');
    Route::get('chia-se-y-tuong', 'chiaSeyTuong')->name('lamgame.chia-se-y-tuong');
    Route::post('tao-y-tuong', 'taoYTuong')->name('lamgame.tao-y-tuong');
    
    // Community posts
    Route::get('bai-viet/{id}', 'xemBaiViet')->name('lamgame.bai-viet');
    Route::post('binh-luan', 'binhLuan')->name('lamgame.binh-luan.store');
});
