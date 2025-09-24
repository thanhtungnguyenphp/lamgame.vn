<?php

use App\Http\Controllers\LamGamePageController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

// Source Game routes
Route::get('source-game', [LamGamePageController::class, 'sourceGame'])->name('lamgame.source-game');
Route::get('source-game/{slug}', [LamGamePageController::class, 'sourceGameDetail'])->name('lamgame.source-game.detail');

// Blog routes
Route::get('blog', [LamGamePageController::class, 'blog'])->name('lamgame.blog');
Route::get('blog/{slug}', [LamGamePageController::class, 'blogShow'])->name('blog.show');

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

// Admin forum routes
Route::prefix('admin/forum')->name('admin.forum.')->middleware('admin')->group(function () {

    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Posts management
    Route::get('/posts', [AdminController::class, 'posts'])->name('posts');
    Route::put('/posts/{post}/status', [AdminController::class, 'updatePostStatus'])->name('posts.status');

    // Comments management
    Route::get('/comments', [AdminController::class, 'comments'])->name('comments');
    Route::put('/comments/{comment}/status', [AdminController::class, 'updateCommentStatus'])->name('comments.status');

    // Reports management
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::put('/reports/{report}/review', [AdminController::class, 'reviewReport'])->name('reports.review');

    // Users management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::put('/users/{user}/status', [AdminController::class, 'updateUserStatus'])->name('users.status');
    Route::put('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.role');
});

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

// Test auth route
Route::get('/test-auth', function() {
    return 'Auth routes working!';
});

Route::get('/test-controller', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'test']);

// Debug route for blog testing
Route::get('debug-blog-test', function() {
    \Log::info('TEST: Debug route accessed', [
        'timestamp' => now(),
        'user_agent' => request()->userAgent()
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Debug logging is working',
        'timestamp' => now()
    ]);
});
