<?php

use App\Http\Controllers\Frontend\FetchReviewController;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\Locations\CityController;
use App\Http\Controllers\Frontend\Locations\CountryController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\SearchSuggestionController;
use App\Http\Controllers\Frontend\Tour\CartController;
use App\Http\Controllers\Frontend\Tour\CategoryController;
use App\Http\Controllers\Frontend\Tour\CheckoutController;
use App\Http\Controllers\Frontend\Tour\FavoriteController;
use App\Http\Controllers\Frontend\Tour\TourController;
use App\Http\Controllers\Frontend\Tour\TourTimeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/home', [IndexController::class, 'index']);
Route::get('/welcome', [IndexController::class, 'index']);
Route::get('/index', [IndexController::class, 'index']);
Route::get('/blog-details', [IndexController::class, 'blog_details'])->name('blog-details');
Route::get('/blog', [IndexController::class, 'blog'])->name('blog');
Route::get('/terms-conditions', [IndexController::class, 'terms_conditions'])->name('terms_conditions');
Route::get('/privacy-policy', [IndexController::class, 'privacy_policy'])->name('privacy_policy');
Route::post('/save-newsletter', [IndexController::class, 'save_newsletter'])->name('save-newsletter');
Route::get('/search/suggestions', [SearchSuggestionController::class, 'suggest'])->name('search.suggestions');
Route::get('/reviews/fetch', [FetchReviewController::class, 'fetchReview']);
Route::post('/save-review', [IndexController::class, 'save_review'])->name('save_review');

Route::prefix('city')->name('city.')->group(function () {
    Route::get('{slug}', [CityController::class, 'show'])->name('details');
});

Route::prefix('country')->name('country.')->group(function () {
    Route::get('{slug}', [CountryController::class, 'show'])->name('details');
});

Route::prefix('page')->name('page.')->group(function () {
    Route::get('{slug}', [PageController::class, 'show'])->name('show');
});

Route::prefix('tours')->name('tours.')->group(function () {
    Route::get('/', [TourController::class, 'index'])->name('index');
    Route::post('/api/promo-prices-by-day', [TourController::class, 'getTourPromoPricesByDay'])->name('promo-prices-by-day');
    Route::get('/search', [TourController::class, 'search'])->name('search');
    Route::get('/{city}/{category}/{time}', [TourTimeController::class, 'details'])->name('time.details');
    Route::get('/{slug}', [TourController::class, 'details'])->name('details');
    Route::get('/{city}/{category}', [CategoryController::class, 'details'])->name('category.details');
    Route::prefix('favorites')->name('favorites.')->group(function () {
        Route::get('/index', [FavoriteController::class, 'index'])->name('index');
        Route::post('/add/{tour}', [FavoriteController::class, 'add'])->name('add');
        Route::post('/remove/{tour}', [FavoriteController::class, 'remove'])->name('remove');
    });
});

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('add/{tour}', [CartController::class, 'add'])->name('add');
    Route::get('remove/{tour}', [CartController::class, 'remove'])->name('remove');
    Route::post('/update', [CartController::class, 'update'])->name('update');
});

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/', [CheckoutController::class, 'store'])->name('store');
    Route::get('/success', [CheckoutController::class, 'success'])->name('success');
    Route::get('/cancel', [CheckoutController::class, 'cancel'])->name('cancel');
    Route::get('/error', [CheckoutController::class, 'error'])->name('error');
    Route::post('/apply-code', [CheckoutController::class, 'applyCode'])->name('applyCode');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
