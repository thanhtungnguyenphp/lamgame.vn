<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LamGamePageController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

// Homepage route (shop.home.index alias needed by Bagisto)
Route::get('/', [HomeController::class, 'index'])->name('shop.home.index');

// Checkout routes (override Bagisto)
Route::get('checkout/cart', fn() => view('checkout.cart'))->name('shop.checkout.cart.index');
Route::get('checkout/onepage', fn() => view('checkout.onepage'))->name('shop.checkout.onepage.index');
Route::get('checkout/onepage/success', [\Webkul\Shop\Http\Controllers\OnepageController::class, 'success'])->name('shop.checkout.onepage.success');

// Source Game routes
Route::get('source-game', [LamGamePageController::class, 'sourceGame'])->name('lamgame.source-game');
Route::get('source-game/{slug}', [LamGamePageController::class, 'sourceGameDetail'])->name('lamgame.source-game.detail');

// Seller public profile
Route::get('seller/{slug}', [App\Http\Controllers\SellerProfileController::class, 'show'])->name('seller.profile');

// Contact routes
Route::get('lien-he', [LamGamePageController::class, 'lienHe'])->name('lamgame.lien-he');
Route::post('lien-he', [LamGamePageController::class, 'submitContact'])->name('lamgame.lien-he.submit');

// Blog routes
Route::get('blog', [LamGamePageController::class, 'blog'])->name('lamgame.blog');
Route::get('blog/{slug}', [LamGamePageController::class, 'blogShow'])->name('blog.show');

// Landing Page routes
Route::get('p/{slug}', [\App\Http\Controllers\LandingPageController::class, 'show'])->name('landing-page.show');

// M7 Mini Game
Route::post('m7/predict', [\App\Http\Controllers\M7PredictionController::class, 'store'])->name('m7.predict');
Route::get('m7/leaderboard', [\App\Http\Controllers\M7PredictionController::class, 'leaderboard'])->name('m7.leaderboard');

// Seller routes
Route::prefix('seller')->name('seller.')->middleware('theme')->group(function () {
    Route::get('register', [App\Http\Controllers\SellerController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [App\Http\Controllers\SellerController::class, 'register'])->name('register.submit');
    Route::get('pending', [App\Http\Controllers\SellerController::class, 'pending'])->name('pending');
    
    Route::middleware('seller')->group(function () {
        Route::get('dashboard', [App\Http\Controllers\SellerController::class, 'dashboard'])->name('dashboard');
        Route::get('analytics', [App\Http\Controllers\SellerController::class, 'analytics'])->name('analytics');
        
        // Products
        Route::resource('products', App\Http\Controllers\SellerProductController::class);
        
        // Orders
        Route::get('orders', [App\Http\Controllers\SellerController::class, 'orders'])->name('orders.index');
        Route::get('orders/{id}', [App\Http\Controllers\SellerController::class, 'orderShow'])->name('orders.show');
        
        // Earnings
        Route::get('earnings', [App\Http\Controllers\SellerEarningController::class, 'index'])->name('earnings.index');
        
        // Withdrawals
        Route::get('withdrawals', [App\Http\Controllers\SellerWithdrawalController::class, 'index'])->name('withdrawals.index');
        Route::get('withdrawals/create', [App\Http\Controllers\SellerWithdrawalController::class, 'create'])->name('withdrawals.create');
        Route::post('withdrawals', [App\Http\Controllers\SellerWithdrawalController::class, 'store'])->name('withdrawals.store');
    });
});

// Admin Seller Management routes
Route::prefix('admin')->middleware('admin')->name('admin.')->group(function () {
    Route::prefix('sellers')->name('sellers.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AdminSellerController::class, 'index'])->name('index');
        Route::get('pending', [App\Http\Controllers\Admin\AdminSellerController::class, 'pending'])->name('pending');
        Route::get('{id}', [App\Http\Controllers\Admin\AdminSellerController::class, 'show'])->name('show');
        Route::post('{id}/approve', [App\Http\Controllers\Admin\AdminSellerController::class, 'approve'])->name('approve');
        Route::post('{id}/reject', [App\Http\Controllers\Admin\AdminSellerController::class, 'reject'])->name('reject');
        Route::post('{id}/suspend', [App\Http\Controllers\Admin\AdminSellerController::class, 'suspend'])->name('suspend');
        Route::post('{id}/activate', [App\Http\Controllers\Admin\AdminSellerController::class, 'activate'])->name('activate');
    });

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('sellers', [App\Http\Controllers\Admin\AdminProductController::class, 'sellers'])->name('sellers');
        Route::get('pending', [App\Http\Controllers\Admin\AdminProductController::class, 'pending'])->name('pending');
        Route::get('{id}/review', [App\Http\Controllers\Admin\AdminProductController::class, 'review'])->name('review');
        Route::post('{id}/approve', [App\Http\Controllers\Admin\AdminProductController::class, 'approve'])->name('approve');
        Route::post('{id}/reject', [App\Http\Controllers\Admin\AdminProductController::class, 'reject'])->name('reject');
    });
});


// Company logo route
Route::get('storage/company-logos/{filename}', [App\Http\Controllers\LogoController::class, 'show'])
    ->where('filename', '[A-Za-z0-9\-_\.]+')
    ->name('company.logo');

// Job routes
Route::get('viec-lam-game', [LamGamePageController::class, 'jobs'])->name('lamgame.viec-lam-game');
Route::get('viec-lam/{slug}', [LamGamePageController::class, 'jobDetail'])->name('lamgame.job.detail');

// Forum routes
Route::prefix('forum')->name('forum.')->group(function () {
    // Main forum pages (public)
    Route::get('/', [ForumController::class, 'index'])->name('index');
    Route::get('/search', [ForumController::class, 'search'])->name('search');
    Route::get('/posts/{post}', [ForumController::class, 'show'])->name('posts.show');

    // Categories and tags (public)
    Route::get('/category/{category}', [ForumController::class, 'category'])->name('category');
    Route::get('/tag/{tag}', [ForumController::class, 'tag'])->name('tag');

    // Protected routes - authentication handled in controller
    // Post management
    Route::get('/create', [ForumController::class, 'create'])->name('posts.create');
    Route::post('/posts', [ForumController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [ForumController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [ForumController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [ForumController::class, 'destroy'])->name('posts.destroy');

    // Comments
    Route::post('/posts/{post}/comments', [ForumController::class, 'storeComment'])->name('comments.store');

    // Voting (AJAX)
    Route::post('/vote', [ForumController::class, 'vote'])->name('vote');

    // Reporting
    Route::post('/report', [ForumController::class, 'report'])->name('report');
});

// Admin forum routes - handled by packages/Webkul/Admin/src/Routes/forum-routes.php

// User Profile routes
Route::prefix('profile')->name('forum.profile.')->group(function () {
    Route::get('/{user}', [UserProfileController::class, 'show'])->name('show');
    Route::get('/{user}/posts', [UserProfileController::class, 'posts'])->name('posts');
    Route::get('/{user}/comments', [UserProfileController::class, 'comments'])->name('comments');

    // Protected profile management routes
    Route::middleware('customer')->group(function () {
        Route::get('/edit', [UserProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [UserProfileController::class, 'update'])->name('update');
    });
});





// AI Thumbnail Test Interface
Route::get('ai-thumbnail-test', function () {
    return view('ai.thumbnail-test');
})->name('ai.thumbnail-test');

// Secure route to serve product files from private storage
Route::get('storage/product/{productId}/{filename}', [App\Http\Controllers\ProductFileController::class, 'serve'])
    ->name('product.file')
    ->where(['productId' => '[0-9]+', 'filename' => '[A-Za-z0-9\-_\.]+']);

// AI Image serving route with proper security
Route::get('ai-images/{path}', function($path) {
    // Security: Only allow images from magic-ai directory
    if (!str_starts_with($path, 'magic-ai/')) {
        abort(404);
    }

    $fullPath = storage_path('app/public/' . $path);

    if (!file_exists($fullPath)) {
        abort(404, 'Image not found');
    }

    $mimeType = mime_content_type($fullPath);

    // Only serve image files
    if (!str_starts_with($mimeType, 'image/')) {
        abort(403, 'Only image files are allowed');
    }

    return response()->file($fullPath, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=86400', // Cache for 1 day
        'Expires' => gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT',
    ]);
})->where('path', '.*')->name('ai.images');


Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_url')], function () {

    // Landing Pages Management
    Route::prefix('landing-pages')->name('admin.landing-pages.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\LandingPageController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\LandingPageController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\LandingPageController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [\App\Http\Controllers\Admin\LandingPageController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\Admin\LandingPageController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\LandingPageController::class, 'destroy'])->name('destroy');
    });

    // Blog Edit Route Override - Intercept before package routes
    Route::get('blog/edit/{id}', [App\Http\Controllers\Admin\BlogController::class, 'edit'])
        ->defaults('_config', ['view' => 'blog::admin.blogs.edit'])
        ->name('admin.blog.edit');

    // Blog Update Route Override - Intercept before package routes
    Route::post('blog/update/{id}', [App\Http\Controllers\Admin\BlogController::class, 'update'])
        ->defaults('_config', ['redirect' => 'admin.blog.index'])
        ->name('admin.blog.update');

    // Also override store for consistency
    Route::post('blog/store', [App\Http\Controllers\Admin\BlogController::class, 'store'])
        ->defaults('_config', ['redirect' => 'admin.blog.index'])
        ->name('admin.blog.store');
});

// Customer Authentication Routes
Route::prefix('auth')->name('auth.')->group(function () {
    // Guest middleware - only accessible when not logged in
    Route::middleware('guest:customer')->group(function () {
        // Login routes
        Route::get('/login', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'login']);
        
        // Register routes
        Route::get('/register', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'register']);
        
        // Email verification route
        Route::get('/verify/{id}/{hash}', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'verifyEmail'])->name('verify');
        
        // Forgot password routes
        Route::get('/forgot-password', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
        Route::post('/forgot-password', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'sendPasswordResetLink']);
        
        // Password reset routes
        Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'showPasswordResetForm'])->name('password.reset');
        Route::post('/reset-password', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'resetPassword'])->name('password.update');
    });
    
    // Protected routes - only accessible when logged in
    Route::middleware('customer')->group(function () {
        // Logout route
        Route::post('/logout', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'logout'])->name('logout');
        
        // Profile routes
        Route::get('/profile', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'profile'])->name('profile');
        Route::put('/profile', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'updateProfile'])->name('profile.update');
    });
});

// Login aliases for middleware compatibility
Route::get('/customer/login', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'showLoginForm'])->name('shop.customer.session.index');
Route::get('/login', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'showLoginForm'])->name('login');

// Admin Job Management Routes
require __DIR__.'/admin.php';

// Legacy Redirects
require __DIR__.'/redirects.php';
