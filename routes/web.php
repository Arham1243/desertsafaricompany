<?php

use App\Http\Controllers\Frontend\FetchReviewController;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\Locations\CityController;
use App\Http\Controllers\Frontend\Locations\CountryController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\SearchSuggestionController;
use App\Http\Controllers\Frontend\Tour\CategoryController;
use App\Http\Controllers\Frontend\Tour\TourController;
use Illuminate\Support\Facades\Route;

Route::get('/blog-details', [IndexController::class, 'blog_details'])->name('blog-details');
Route::get('/blog', [IndexController::class, 'blog'])->name('blog');
Route::get('/cart', [IndexController::class, 'cart'])->name('cart');
Route::get('/checkout', [IndexController::class, 'checkout'])->name('checkout');
Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/wishlist', [IndexController::class, 'wishlist'])->name('wishlist');
Route::get('/terms-conditions', [IndexController::class, 'terms_conditions'])->name('terms_conditions');
Route::get('/privacy-policy', [IndexController::class, 'privacy_policy'])->name('privacy_policy');
Route::get('/make-slug', [IndexController::class, 'make_slug']);

Route::post('/save-newsletter', [IndexController::class, 'save_newsletter'])->name('save-newsletter');
Route::get('/search/suggestions', [SearchSuggestionController::class, 'suggest'])->name('search.suggestions');
Route::get('/reviews/fetch', [FetchReviewController::class, 'fetchReview']);

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
    Route::get('/search', [TourController::class, 'search'])->name('search');
    Route::get('/{slug}', [TourController::class, 'details'])->name('details');

    Route::prefix('category')->name('category.')->group(function () {
        Route::get('{slug}', [CategoryController::class, 'details'])->name('details');
    });
});

Route::post('/save-review', [IndexController::class, 'save_review'])->name('save_review');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
