<?php

use App\Http\Controllers\Admin\AdminDashController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\Blog\BlogController;
use App\Http\Controllers\Admin\Blog\CategoriesController as BlogCategoriesController;
use App\Http\Controllers\Admin\Blog\TagsController as BlogTagsController;
use App\Http\Controllers\Admin\BulkActionController;
use App\Http\Controllers\Admin\IcalController;
use App\Http\Controllers\Admin\Locations\CityController;
use App\Http\Controllers\Admin\Locations\CountryController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\News\CategoriesController as NewsCategoriesController;
use App\Http\Controllers\Admin\News\NewsController;
use App\Http\Controllers\Admin\News\TagsController as NewsTagsController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PopupController;
use App\Http\Controllers\Admin\RecoveryController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\SiteSettingsController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\Tour\AttributesController;
use App\Http\Controllers\Admin\Tour\AvailabilityController;
use App\Http\Controllers\Admin\Tour\BookingController;
use App\Http\Controllers\Admin\Tour\CategoriesController as TourCategoriesController;
use App\Http\Controllers\Admin\Tour\ReviewController;
use App\Http\Controllers\Admin\Tour\TourController;
use Illuminate\Support\Facades\Route;

Route::get('/admins', function () {
    return redirect()->route('admin.login');
})->name('admin.admin');

Route::middleware('guest')->prefix('admin')->namespace('Admin')->group(function () {
    Route::get('/auth', [AdminLoginController::class, 'login'])->name('admin.login');
    Route::post('/perform-login', [AdminLoginController::class, 'performLogin'])->name('admin.performLogin')->middleware('throttle:5,1');
});

Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashController::class, 'dashboard'])->name('dashboard');
    Route::get('/showLogo', [SiteSettingsController::class, 'showLogo'])->name('showLogo');
    Route::post('/saveLogo', [SiteSettingsController::class, 'saveLogo'])->name('saveLogo');
    Route::get('/logout', [AdminLoginController::class, 'logout'])->name('logout');

    Route::post('bulk-actions/{resource}', [BulkActionController::class, 'handle'])->name('bulk-actions');
    Route::get('recovery/{resource}', [RecoveryController::class, 'index'])->name('recovery.index');

    Route::resource('blogs', BlogController::class);
    Route::get('media/{media}', [BlogController::class, 'deleteMedia'])->name('media.delete');
    Route::resource('blogs-categories', BlogCategoriesController::class);
    Route::resource('blogs-tags', BlogTagsController::class);

    Route::resource('news', NewsController::class);
    Route::resource('news-tags', NewsTagsController::class);
    Route::resource('news-categories', NewsCategoriesController::class);

    Route::resource('tours', TourController::class);
    Route::get('tour-media/{media}', [TourController::class, 'deleteMedia'])->name('tour-media.delete');
    Route::resource('tour-attributes', AttributesController::class);
    Route::get('delete/attribute-item/{id}', [AttributesController::class, 'deleteItem'])->name('tour-attribute-item.delete');
    Route::resource('tour-categories', TourCategoriesController::class);
    Route::prefix('tour-reviews')->name('tour-reviews.')->group(function () {
        Route::resource('/', ReviewController::class);
        Route::get('/approved', [ReviewController::class, 'approved'])->name('approved');
        Route::get('/rejected', [ReviewController::class, 'rejected'])->name('rejected');
    });

    Route::resource('tour-availability', AvailabilityController::class);
    Route::resource('tour-bookings', BookingController::class);

    Route::resource('pages', PageController::class);
    Route::resource('sections', SectionController::class);
    Route::get('pages/{page}/page-builder', [PageController::class, 'editTemplate'])->name('pages.page-builder');
    Route::post('pages/{page}/page-builder', [PageController::class, 'storeTemplate'])->name('pages.page-builder.store');
    Route::post('pages/{page}/page-builder/sections/{section?}', [PageController::class, 'saveSectionDetails'])->name('pages.page-builder.sections.save');
    Route::get('pages/{page}/page-builder/section-template', [PageController::class, 'getSectionTemplate'])->name('pages.page-builder.section-template');

    Route::get('export-ical', IcalController::class)->name('ical.export');

    Route::resource('countries', CountryController::class);
    Route::resource('cities', CityController::class);
    Route::resource('testimonials', TestimonialController::class);
    Route::resource('popups', PopupController::class);

    Route::get('media/{id}/destroy', [MediaController::class, 'destroy'])->name('media.destroy');
});
