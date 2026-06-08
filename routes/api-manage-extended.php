<?php

use App\Http\Controllers\Api\PromotionManageController;
use App\Http\Controllers\Api\CmsManageController;
use App\Http\Controllers\Api\AttributeManageController;
use App\Http\Controllers\Api\AdminNotificationManageController;
use App\Http\Controllers\Api\SettingsManageController;
use App\Http\Controllers\Api\ConfigurationManageController;
use App\Http\Controllers\Api\MarketingSeoManageController;
use App\Http\Controllers\Api\MarketingCommManageController;

/*
|--------------------------------------------------------------------------
| Extended Management API Routes (api.key auth)
|--------------------------------------------------------------------------
|
| Header: X-Api-Key: {admin_api_token}
| Prefix: /api/manage
|
*/
Route::prefix('manage')->name('api.manage.')->middleware(['api.key', 'throttle:60,1'])->group(function () {

    // === Marketing Promotions ===
    Route::prefix('promotions')->name('promotions.')->group(function () {
        // Catalog Rules
        Route::get('/catalog-rules', [PromotionManageController::class, 'catalogRuleList'])->name('catalog-rules.list');
        Route::get('/catalog-rules/{id}', [PromotionManageController::class, 'catalogRuleDetail'])->name('catalog-rules.detail')->where('id', '[0-9]+');
        Route::post('/catalog-rules', [PromotionManageController::class, 'catalogRuleStore'])->name('catalog-rules.store')->middleware('throttle:10,1');
        Route::put('/catalog-rules/{id}', [PromotionManageController::class, 'catalogRuleUpdate'])->name('catalog-rules.update')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::delete('/catalog-rules/{id}', [PromotionManageController::class, 'catalogRuleDestroy'])->name('catalog-rules.destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');

        // Cart Rules
        Route::get('/cart-rules', [PromotionManageController::class, 'cartRuleList'])->name('cart-rules.list');
        Route::get('/cart-rules/{id}', [PromotionManageController::class, 'cartRuleDetail'])->name('cart-rules.detail')->where('id', '[0-9]+');
        Route::post('/cart-rules', [PromotionManageController::class, 'cartRuleStore'])->name('cart-rules.store')->middleware('throttle:10,1');
        Route::put('/cart-rules/{id}', [PromotionManageController::class, 'cartRuleUpdate'])->name('cart-rules.update')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::delete('/cart-rules/{id}', [PromotionManageController::class, 'cartRuleDestroy'])->name('cart-rules.destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');
    });

    // === CMS Pages ===
    Route::prefix('cms')->name('cms.')->group(function () {
        Route::get('/', [CmsManageController::class, 'list'])->name('list');
        Route::get('/{id}', [CmsManageController::class, 'detail'])->name('detail')->where('id', '[0-9]+');
        Route::post('/', [CmsManageController::class, 'store'])->name('store')->middleware('throttle:10,1');
        Route::put('/{id}', [CmsManageController::class, 'update'])->name('update')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::delete('/{id}', [CmsManageController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::post('/mass-delete', [CmsManageController::class, 'massDestroy'])->name('mass-delete')->middleware('throttle:10,1');
    });

    // === Attributes ===
    Route::prefix('attributes')->name('attributes.')->group(function () {
        Route::get('/', [AttributeManageController::class, 'attributeList'])->name('list');
        Route::get('/{id}', [AttributeManageController::class, 'attributeDetail'])->name('detail')->where('id', '[0-9]+');
        Route::post('/', [AttributeManageController::class, 'attributeStore'])->name('store')->middleware('throttle:10,1');
        Route::put('/{id}', [AttributeManageController::class, 'attributeUpdate'])->name('update')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::delete('/{id}', [AttributeManageController::class, 'attributeDestroy'])->name('destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');
    });

    // === Attribute Families ===
    Route::prefix('attribute-families')->name('attribute-families.')->group(function () {
        Route::get('/', [AttributeManageController::class, 'familyList'])->name('list');
        Route::get('/{id}', [AttributeManageController::class, 'familyDetail'])->name('detail')->where('id', '[0-9]+');
        Route::post('/', [AttributeManageController::class, 'familyStore'])->name('store')->middleware('throttle:10,1');
        Route::put('/{id}', [AttributeManageController::class, 'familyUpdate'])->name('update')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::delete('/{id}', [AttributeManageController::class, 'familyDestroy'])->name('destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');
    });

    // === Admin Notifications ===
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [AdminNotificationManageController::class, 'list'])->name('list');
        Route::get('/unread-count', [AdminNotificationManageController::class, 'unreadCount'])->name('unread-count');
        Route::post('/{id}/read', [AdminNotificationManageController::class, 'markRead'])->name('read')->where('id', '[0-9]+');
        Route::post('/read-all', [AdminNotificationManageController::class, 'markAllRead'])->name('read-all');
    });

    // === Settings ===
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/locales', [SettingsManageController::class, 'localeList'])->name('locales.list');
        Route::post('/locales', [SettingsManageController::class, 'localeStore'])->name('locales.store')->middleware('throttle:10,1');
        Route::put('/locales/{id}', [SettingsManageController::class, 'localeUpdate'])->name('locales.update')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::delete('/locales/{id}', [SettingsManageController::class, 'localeDestroy'])->name('locales.destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');

        Route::get('/currencies', [SettingsManageController::class, 'currencyList'])->name('currencies.list');
        Route::post('/currencies', [SettingsManageController::class, 'currencyStore'])->name('currencies.store')->middleware('throttle:10,1');
        Route::put('/currencies/{id}', [SettingsManageController::class, 'currencyUpdate'])->name('currencies.update')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::delete('/currencies/{id}', [SettingsManageController::class, 'currencyDestroy'])->name('currencies.destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');

        Route::get('/exchange-rates', [SettingsManageController::class, 'exchangeRateList'])->name('exchange-rates.list');
        Route::post('/exchange-rates', [SettingsManageController::class, 'exchangeRateStore'])->name('exchange-rates.store')->middleware('throttle:10,1');
        Route::put('/exchange-rates/{id}', [SettingsManageController::class, 'exchangeRateUpdate'])->name('exchange-rates.update')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::delete('/exchange-rates/{id}', [SettingsManageController::class, 'exchangeRateDestroy'])->name('exchange-rates.destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');

        Route::get('/channels', [SettingsManageController::class, 'channelList'])->name('channels.list');
        Route::get('/channels/{id}', [SettingsManageController::class, 'channelDetail'])->name('channels.detail')->where('id', '[0-9]+');

        Route::get('/inventory-sources', [SettingsManageController::class, 'inventorySourceList'])->name('inventory-sources.list');

        Route::get('/taxes/categories', [SettingsManageController::class, 'taxCategoryList'])->name('taxes.categories.list');
        Route::post('/taxes/categories', [SettingsManageController::class, 'taxCategoryStore'])->name('taxes.categories.store')->middleware('throttle:10,1');
        Route::get('/taxes/rates', [SettingsManageController::class, 'taxRateList'])->name('taxes.rates.list');
        Route::post('/taxes/rates', [SettingsManageController::class, 'taxRateStore'])->name('taxes.rates.store')->middleware('throttle:10,1');

        Route::get('/users', [SettingsManageController::class, 'userList'])->name('users.list');
        Route::get('/users/{id}', [SettingsManageController::class, 'userDetail'])->name('users.detail')->where('id', '[0-9]+');
        Route::get('/roles', [SettingsManageController::class, 'roleList'])->name('roles.list');
        Route::get('/roles/{id}', [SettingsManageController::class, 'roleDetail'])->name('roles.detail')->where('id', '[0-9]+');
        Route::get('/themes', [SettingsManageController::class, 'themeList'])->name('themes.list');
    });

    // === Configuration ===
    Route::prefix('configuration')->name('configuration.')->group(function () {
        Route::get('/', [ConfigurationManageController::class, 'index'])->name('index');
        Route::get('/search', [ConfigurationManageController::class, 'search'])->name('search');
        Route::get('/{slug}', [ConfigurationManageController::class, 'getBySlug'])->name('show')->where('slug', '[a-z0-9._-]+');
        Route::post('/', [ConfigurationManageController::class, 'store'])->name('store')->middleware('throttle:10,1');
    });

    // === Marketing SEO ===
    Route::prefix('marketing/seo')->name('marketing.seo.')->group(function () {
        Route::get('/url-rewrites', [MarketingSeoManageController::class, 'urlRewriteList'])->name('url-rewrites.list');
        Route::post('/url-rewrites', [MarketingSeoManageController::class, 'urlRewriteStore'])->name('url-rewrites.store')->middleware('throttle:10,1');
        Route::put('/url-rewrites/{id}', [MarketingSeoManageController::class, 'urlRewriteUpdate'])->name('url-rewrites.update')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::delete('/url-rewrites/{id}', [MarketingSeoManageController::class, 'urlRewriteDestroy'])->name('url-rewrites.destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');

        Route::get('/search-terms', [MarketingSeoManageController::class, 'searchTermList'])->name('search-terms.list');
        Route::post('/search-terms', [MarketingSeoManageController::class, 'searchTermStore'])->name('search-terms.store')->middleware('throttle:10,1');
        Route::delete('/search-terms/{id}', [MarketingSeoManageController::class, 'searchTermDestroy'])->name('search-terms.destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');

        Route::get('/search-synonyms', [MarketingSeoManageController::class, 'searchSynonymList'])->name('search-synonyms.list');
        Route::post('/search-synonyms', [MarketingSeoManageController::class, 'searchSynonymStore'])->name('search-synonyms.store')->middleware('throttle:10,1');
        Route::delete('/search-synonyms/{id}', [MarketingSeoManageController::class, 'searchSynonymDestroy'])->name('search-synonyms.destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');

        Route::get('/sitemaps', [MarketingSeoManageController::class, 'sitemapList'])->name('sitemaps.list');
        Route::post('/sitemaps', [MarketingSeoManageController::class, 'sitemapStore'])->name('sitemaps.store')->middleware('throttle:10,1');
        Route::delete('/sitemaps/{id}', [MarketingSeoManageController::class, 'sitemapDestroy'])->name('sitemaps.destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');
    });

    // === Marketing Communications ===
    Route::prefix('marketing/communications')->name('marketing.comm.')->group(function () {
        Route::get('/email-templates', [MarketingCommManageController::class, 'templateList'])->name('templates.list');
        Route::get('/email-templates/{id}', [MarketingCommManageController::class, 'templateDetail'])->name('templates.detail')->where('id', '[0-9]+');
        Route::post('/email-templates', [MarketingCommManageController::class, 'templateStore'])->name('templates.store')->middleware('throttle:10,1');
        Route::put('/email-templates/{id}', [MarketingCommManageController::class, 'templateUpdate'])->name('templates.update')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::delete('/email-templates/{id}', [MarketingCommManageController::class, 'templateDestroy'])->name('templates.destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');

        Route::get('/events', [MarketingCommManageController::class, 'eventList'])->name('events.list');
        Route::post('/events', [MarketingCommManageController::class, 'eventStore'])->name('events.store')->middleware('throttle:10,1');
        Route::delete('/events/{id}', [MarketingCommManageController::class, 'eventDestroy'])->name('events.destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');

        Route::get('/campaigns', [MarketingCommManageController::class, 'campaignList'])->name('campaigns.list');
        Route::get('/campaigns/{id}', [MarketingCommManageController::class, 'campaignDetail'])->name('campaigns.detail')->where('id', '[0-9]+');
        Route::post('/campaigns', [MarketingCommManageController::class, 'campaignStore'])->name('campaigns.store')->middleware('throttle:10,1');
        Route::put('/campaigns/{id}', [MarketingCommManageController::class, 'campaignUpdate'])->name('campaigns.update')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::delete('/campaigns/{id}', [MarketingCommManageController::class, 'campaignDestroy'])->name('campaigns.destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');

        Route::get('/subscribers', [MarketingCommManageController::class, 'subscriberList'])->name('subscribers.list');
        Route::delete('/subscribers/{id}', [MarketingCommManageController::class, 'subscriberDestroy'])->name('subscribers.destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');
    });
});
