<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LamGamePageController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

// Homepage route - Must be first to override any default routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/test-home', [HomeController::class, 'index'])->name('test-home');
Route::get('/debug-homepage', [HomeController::class, 'index'])->name('debug-homepage');

// Source Game routes
Route::get('source-game', [LamGamePageController::class, 'sourceGame'])->name('lamgame.source-game');
Route::get('source-game/{slug}', [LamGamePageController::class, 'sourceGameDetail'])->name('lamgame.source-game.detail');

// Blog routes
Route::get('blog', [LamGamePageController::class, 'blog'])->name('lamgame.blog');
Route::get('blog/{slug}', [LamGamePageController::class, 'blogShow'])->name('blog.show');

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

// Test route for AI Thumbnails API (bypass namespace conflicts)
Route::post('test-ai-api/blog', function(\Illuminate\Http\Request $request) {
    try {
        $controller = new \App\Http\Controllers\AI\PublicThumbnailController();
        return $controller->generateBlogThumbnail($request);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});


// Secure route to serve product files from private storage
Route::get('storage/product/{productId}/{filename}', [App\Http\Controllers\ProductFileController::class, 'serve'])
    ->name('product.file')
    ->where(['productId' => '[0-9]+', 'filename' => '[A-Za-z0-9\-_\.]+']);

// Temporary test route for AI storage debugging
Route::get('test-storage/{path}', function($path) {
    $fullPath = storage_path('app/public/' . $path);

    if (!file_exists($fullPath)) {
        abort(404, 'File not found: ' . $fullPath);
    }

    $mimeType = mime_content_type($fullPath);

    return response()->file($fullPath, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=3600'
    ]);
})->where('path', '.*')->name('test.storage');

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

// Login alias for middleware compatibility
Route::get('/customer/login', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'showLoginForm'])->name('customer.session.create');
Route::get('/login', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'showLoginForm'])->name('login');

// Test auth route
Route::get('/test-auth', function() {
    return 'Auth routes working!';
});

Route::get('/test-controller', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'test']);

// Admin Job Management Routes
require __DIR__.'/admin.php';

// Legacy Redirects
require __DIR__.'/redirects.php';

Route::get('/test-companies', function() { return 'Companies route works!'; });

// Admin dashboard routes
Route::get('/admin/dashboard', [\Webkul\Admin\Http\Controllers\DashboardController::class, 'index'])
    ->name('admin.dashboard.index');
Route::redirect('/admin', '/admin/dashboard')->name('admin.dashboard');

// Admin applications route
Route::get('/admin/applications', function() { return view('admin.applications.index'); })->name('admin.applications.index');

// Admin settings route
Route::get('/admin/settings', function() { return view('admin.settings.index'); })->name('admin.settings');
