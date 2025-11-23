<?php

use App\Http\Controllers\Admin\AdminDashController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\Blog\BlogController;
use App\Http\Controllers\Admin\Blog\CategoriesController as BlogCategoriesController;
use App\Http\Controllers\Admin\Blog\TagsController as BlogTagsController;
use App\Http\Controllers\Admin\BulkActionController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DBConsoleController;
use App\Http\Controllers\Admin\EnvEditorController;
use App\Http\Controllers\Admin\IcalController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\Locations\CityController;
use App\Http\Controllers\Admin\Locations\CountryController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\News\CategoriesController as NewsCategoriesController;
use App\Http\Controllers\Admin\News\NewsController;
use App\Http\Controllers\Admin\News\TagsController as NewsTagsController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PopupController;
use App\Http\Controllers\Admin\RecoveryController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SchemaController;
use App\Http\Controllers\Admin\TerminalController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\Tour\AttributesController;
use App\Http\Controllers\Admin\Tour\AuthorController as TourAuthorController;
use App\Http\Controllers\Admin\Tour\AvailabilityController;
use App\Http\Controllers\Admin\Tour\BookingController;
use App\Http\Controllers\Admin\Tour\CategoryController as TourCategoryController;
use App\Http\Controllers\Admin\Tour\DetailPopupController as TourDetailPopupController;
use App\Http\Controllers\Admin\Tour\OrderController;
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
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

    Route::get('/terminal', [TerminalController::class, 'index']);
    Route::post('/terminal/run', [TerminalController::class, 'run']);

    Route::get('/db-console', [DBConsoleController::class, 'index']);
    Route::post('/db-console', [DBConsoleController::class, 'run'])->name('db.console.run');

    Route::get('/env-editor', [EnvEditorController::class, 'index'])->name('env');
    Route::post('/env-editor', [EnvEditorController::class, 'save'])->name('env.save');

    Route::get('logs', [LogController::class, 'read']);
    Route::get('logs/delete', [LogController::class, 'delete']);

    Route::post('bulk-actions/{resource}', [BulkActionController::class, 'handle'])->name('bulk-actions');
    Route::get('recovery/{resource}', [RecoveryController::class, 'index'])->name('recovery.index');

    Route::resource('blogs', BlogController::class);
    Route::get('blogs/duplicate/{id}', [BlogController::class, 'duplicate'])->name('blogs.duplicate');
    Route::get('media/{media}', [BlogController::class, 'deleteMedia'])->name('media.delete');
    Route::resource('blogs-categories', BlogCategoriesController::class);
    Route::resource('blogs-tags', BlogTagsController::class);

    Route::resource('news', NewsController::class);
    Route::resource('news-tags', NewsTagsController::class);
    Route::resource('news-categories', NewsCategoriesController::class);

    Route::resource('tours', TourController::class);
    Route::get('tours/duplicate/{id}', [TourController::class, 'duplicate'])->name('tours.duplicate');
    Route::get('{entity}/schema/{id}', [SchemaController::class, 'index'])->name('schema.index');
    Route::post('{entity}/schema/{id}', [SchemaController::class, 'save'])->name('schema.save');

    Route::get('tour-media/{media}', [TourController::class, 'deleteMedia'])->name('tour-media.delete');

    Route::resource('tour-attributes', AttributesController::class);
    Route::get('delete/attribute-item/{id}', [AttributesController::class, 'deleteItem'])->name('tour-attribute-item.delete');

    Route::resource('tour-categories', TourCategoryController::class);
    Route::get('tour-categories/duplicate/{id}', [TourCategoryController::class, 'duplicate'])->name('tour-categories.duplicate');
    Route::post('/tour-categories/city/{city?}', [TourCategoryController::class, 'getByCity']);

    Route::resource('tour-authors', TourAuthorController::class);

    Route::resource('tour-popups', TourDetailPopupController::class);
    Route::resource('inquiries', InquiryController::class);

    Route::prefix('tour-reviews')->name('tour-reviews.')->group(function () {
        Route::controller(ReviewController::class)->group(function () {
            Route::get('/approved', 'approved')->name('approved');
            Route::get('/rejected', 'rejected')->name('rejected');
        });

        Route::resource('/', ReviewController::class)->parameters(['' => 'review'])->except(['show']);
    });

    Route::resource('tour-availability', AvailabilityController::class);

    Route::resource('tour-bookings', BookingController::class);

    Route::resource('pages', PageController::class);
    Route::get('pages/{page}/page-builder', [PageController::class, 'editTemplate'])->name('pages.page-builder');
    Route::post('pages/{page}/page-builder', [PageController::class, 'storeTemplate'])->name('pages.page-builder.store');
    Route::post('pages/{page}/page-builder/sections/{section?}', [PageController::class, 'saveSectionDetails'])->name('pages.page-builder.sections.save');
    Route::get('pages/{page}/page-builder/section-template', [PageController::class, 'getSectionTemplate'])->name('pages.page-builder.section-template');
    Route::resource('sections', SectionController::class);

    Route::get('export-ical', IcalController::class)->name('ical.export');

    Route::resource('countries', CountryController::class);
    Route::get('countries/duplicate/{id}', [CountryController::class, 'duplicate'])->name('countries.duplicate');

    Route::resource('cities', CityController::class);
    Route::get('cities/duplicate/{id}', [CityController::class, 'duplicate'])->name('cities.duplicate');
    Route::get('/countries/{country}/cities', [CityController::class, 'getByCountry']);

    Route::resource('testimonials', TestimonialController::class);

    Route::resource('popups', PopupController::class);

    Route::resource('coupons', CouponController::class);
    Route::resource('bookings', OrderController::class);

    Route::get('media/{id}/destroy', [MediaController::class, 'destroy'])->name('media.destroy');

    Route::get('settings/{resource}/edit', [SettingController::class, 'edit'])->name('settings.edit');
    Route::post('settings/{resource}/update', [SettingController::class, 'update'])->name('settings.update');

    Route::get('/check-upload-filename', [AdminDashController::class, 'checkFilename']);
});
