<?php

use App\Http\Controllers\Frontend\FetchReviewController;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\Locations\CountryController;
use App\Http\Controllers\Frontend\Locations\LocationController;
use App\Http\Controllers\Frontend\SearchSuggestionController;
use App\Http\Controllers\Frontend\Tour\CartController;
use App\Http\Controllers\Frontend\Tour\CategoryController;
use App\Http\Controllers\Frontend\Tour\CheckoutController;
use App\Http\Controllers\Frontend\Tour\FavoriteController;
use App\Http\Controllers\Frontend\Tour\TourController;
use Illuminate\Support\Facades\Route;

Route::get('/#', [IndexController::class, 'index'])->name('login');
Route::name('frontend.')->group(function () {
    Route::get('/', [IndexController::class, 'index'])->name('index');
    Route::get('/terms-conditions', [IndexController::class, 'terms_conditions'])->name('terms_conditions');
    Route::get('/privacy-policy', [IndexController::class, 'privacy_policy'])->name('privacy_policy');
});

Route::get('/blog-details', [IndexController::class, 'blog_details'])->name('blog-details');
Route::get('/blog', [IndexController::class, 'blog'])->name('blog');
Route::post('/save-newsletter', [IndexController::class, 'save_newsletter'])->name('save-newsletter');
Route::get('/search/suggestions', [SearchSuggestionController::class, 'suggest'])->name('search.suggestions');
Route::get('/reviews/fetch', [FetchReviewController::class, 'fetchReview']);
Route::post('/save-review', [IndexController::class, 'save_review'])->name('save_review');

Route::prefix('tours')->name('tours.')->group(function () {
    Route::get('/', [TourController::class, 'index'])->name('index');
    Route::post('/api/promo-prices-by-day', [TourController::class, 'getTourPromoPricesByDay'])->name('promo-prices-by-day');
    Route::get('/search', [TourController::class, 'search'])->name('search');
});

Route::prefix('favorites')->name('tours.favorites.')->group(function () {
    Route::get('/', [FavoriteController::class, 'index'])->name('index');
    Route::post('/add/{tour}', [FavoriteController::class, 'add'])->name('add');
    Route::post('/remove/{tour}', [FavoriteController::class, 'remove'])->name('remove');
});

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('add/{tour}', [CartController::class, 'add'])->name('add');
    Route::get('remove/{tour}', [CartController::class, 'remove'])->name('remove');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/sync', [CartController::class, 'sync'])->name('sync');
    Route::post('/flush', [CartController::class, 'flush'])->name('flush');
});

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/', [CheckoutController::class, 'store'])->name('store');
    Route::get('/success', [CheckoutController::class, 'success'])->name('success');
    Route::get('/cancel', [CheckoutController::class, 'cancel'])->name('cancel');
    Route::get('/error', [CheckoutController::class, 'error'])->name('error');
    Route::post('/apply-code', [CheckoutController::class, 'applyCode'])->name('applyCode');
});

Route::name('locations.')->group(function () {
    Route::get('/{country}', [CountryController::class, 'show'])
        ->name('country');

    Route::get('/{country}/{slug}', [LocationController::class, 'resolveSlug'])
        ->where([
            'country' => '[a-zA-Z]{2}',
            'slug' => '[a-z0-9-]+',
        ])
        ->name('city');
});

Route::name('tours.')->group(function () {
    Route::get('/{country}/{city?}/{category?}', [CategoryController::class, 'details'])
        ->where([
            'country' => '[a-zA-Z]{2}',
            'city' => '[a-z0-9-]+',
            'category' => '[a-z0-9-]+',
        ])
        ->name('category.details');

    Route::get('/{country}/{city}/{category}/{slug}', [TourController::class, 'resolveSlug'])
        ->where([
            'country' => '[a-zA-Z]{2}',
            'city' => '[a-z0-9-]+',
            'category' => '[a-z0-9-]+',
            'slug' => '[a-z0-9-]+',
        ])
        ->name('details');
});
