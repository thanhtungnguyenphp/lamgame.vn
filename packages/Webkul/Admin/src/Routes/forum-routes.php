<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Forum\ForumModerationController;

/**
 * Forum moderation routes.
 */
Route::prefix('forum')->group(function () {
    Route::controller(ForumModerationController::class)->group(function () {
        // Forum Posts Management
        Route::prefix('posts')->group(function () {
            Route::get('', 'posts')->name('admin.forum.posts.index');
            
            Route::get('{id}', 'showPost')->name('admin.forum.posts.show');
            
            Route::get('edit/{id}', 'showPost')->name('admin.forum.posts.edit');
            
            Route::put('edit/{id}', 'updatePost')->name('admin.forum.posts.update');
            
            Route::delete('{id}', 'destroyPost')->name('admin.forum.posts.delete');
            
            Route::post('mass-delete', 'massDestroyPosts')->name('admin.forum.posts.mass_delete');
            
            Route::post('mass-update', 'massUpdatePosts')->name('admin.forum.posts.mass_update');
        });

        // Forum Comments Management
        Route::prefix('comments')->group(function () {
            Route::get('', 'comments')->name('admin.forum.comments.index');
            
            Route::get('{id}', 'showComment')->name('admin.forum.comments.show');
            
            Route::get('edit/{id}', 'showComment')->name('admin.forum.comments.edit');
            
            Route::put('edit/{id}', 'updateComment')->name('admin.forum.comments.update');
            
            Route::delete('{id}', 'destroyComment')->name('admin.forum.comments.delete');
            
            Route::post('mass-delete', 'massDestroyComments')->name('admin.forum.comments.mass_delete');
            
            Route::post('mass-update', 'massUpdateComments')->name('admin.forum.comments.mass_update');
        });

        // Forum Reports Management
        Route::prefix('reports')->group(function () {
            Route::get('', 'reports')->name('admin.forum.reports.index');
            
            Route::get('{id}', 'showReport')->name('admin.forum.reports.show');
            
            Route::get('edit/{id}', 'showReport')->name('admin.forum.reports.edit');
            
            Route::put('edit/{id}', 'updateReport')->name('admin.forum.reports.update');
            
            Route::delete('{id}', 'destroyReport')->name('admin.forum.reports.delete');
            
            Route::post('mass-delete', 'massDestroyReports')->name('admin.forum.reports.mass_delete');
            
            Route::post('mass-update', 'massUpdateReports')->name('admin.forum.reports.mass_update');
        });

        // Forum Statistics
        Route::get('stats', 'stats')->name('admin.forum.stats');
    });
});
