<?php

use Illuminate\Support\Facades\Route;
use Webkul\Menu\Http\Controllers\Shop\MenuController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency'], 'prefix' => 'menu'], function () {
    Route::get('', [MenuController::class, 'index'])->name('shop.menu.index');
});