<?php

use Illuminate\Support\Facades\Route;
use Webkul\Menu\Http\Controllers\Admin\MenuController;
use Webkul\Menu\Http\Controllers\Admin\MenuItemController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_url') . '/settings/menu/'], function () {
    Route::controller(MenuController::class)->group(function () {
        Route::get('', 'index')->name('admin.menu.index');
        Route::get('create', 'create')->name('admin.menu.create');
        Route::post('', 'store')->name('admin.menu.store');
        Route::get('{id}/edit', 'edit')->name('admin.menu.edit');
        Route::put('{id}', 'update')->name('admin.menu.update');
        Route::delete('{id}', 'destroy')->name('admin.menu.destroy');
    });
    
    // Menu Items routes
    Route::controller(MenuItemController::class)->group(function () {
        Route::get('{menuId}/items', 'index')->name('admin.menu.items.index');
        Route::get('items/create', 'create')->name('admin.menu.items.create');
        Route::post('items', 'store')->name('admin.menu.items.store');
        Route::get('items/{id}/edit', 'edit')->name('admin.menu.items.edit');
        Route::put('items/{id}', 'update')->name('admin.menu.items.update');
        Route::delete('items/{id}', 'destroy')->name('admin.menu.items.destroy');
        Route::post('items/update-order', 'updateOrder')->name('admin.menu.items.update-order');
    });
});
