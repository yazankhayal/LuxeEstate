<?php

use Illuminate\Support\Facades\Route;

// Admin controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PropertyController     as AdminPropertyController;
use App\Http\Controllers\Admin\BlogController         as AdminBlogController;
use App\Http\Controllers\Admin\ServiceController      as AdminServiceController;
use App\Http\Controllers\Admin\ContactController      as AdminContactController;
use App\Http\Controllers\Admin\UserController         as AdminUserController;
use App\Http\Controllers\Admin\SettingController      as AdminSettingController;
use App\Http\Controllers\Admin\LanguageController     as AdminLanguageController;
use App\Http\Controllers\Admin\CategoryController     as AdminCategoryController;
use App\Http\Controllers\Admin\TagController          as AdminTagController;
use App\Http\Controllers\Admin\ArtisanCallController          as ArtisanCallController;

// Frontend controllers
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PropertyController    as PublicPropertyController;
use App\Http\Controllers\Frontend\BlogController        as PublicBlogController;
use App\Http\Controllers\Frontend\ServiceController     as PublicServiceController;
use App\Http\Controllers\Frontend\ContactController     as PublicContactController;
use App\Http\Controllers\Frontend\PageController;

/*
|--------------------------------------------------------------------------
| Language Switcher
|--------------------------------------------------------------------------
*/
Route::get('/lang/{code}', function (string $code) {
    session(['locale' => $code]);
    return redirect()->back();
})->name('lang.switch');

/*
|--------------------------------------------------------------------------
| Admin Routes — must be registered BEFORE public routes to avoid conflicts
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/artisan/{call?}', [ArtisanCallController::class, 'artisanCall'])->name('artisan.call');

    // Properties — use numeric {id} to avoid slug collision with public routes
    Route::prefix('properties')->name('properties.')->group(function () {
        Route::get('/',                  [AdminPropertyController::class, 'index'])->name('index');
        Route::get('/create',            [AdminPropertyController::class, 'create'])->name('create');
        Route::post('/',                 [AdminPropertyController::class, 'store'])->name('store');
        Route::get('/{id}/edit',         [AdminPropertyController::class, 'edit'])->name('edit');
        Route::put('/{id}',              [AdminPropertyController::class, 'update'])->name('update');
        Route::delete('/{id}',           [AdminPropertyController::class, 'destroy'])->name('destroy');
        Route::delete('/images/{imageId}', [AdminPropertyController::class, 'destroyImage'])->name('images.destroy');
        Route::post('/images/reorder',     [AdminPropertyController::class, 'reorderImages'])->name('images.reorder');
    });

    // Blog
    Route::prefix('blog')->name('blog.')->group(function () {
        Route::get('/',            [AdminBlogController::class, 'index'])->name('index');
        Route::get('/create',      [AdminBlogController::class, 'create'])->name('create');
        Route::post('/',           [AdminBlogController::class, 'store'])->name('store');
        Route::get('/{id}/edit',   [AdminBlogController::class, 'edit'])->name('edit');
        Route::put('/{id}',        [AdminBlogController::class, 'update'])->name('update');
        Route::patch('/{id}',      [AdminBlogController::class, 'update']);
        Route::delete('/{id}',     [AdminBlogController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/toggle',  [AdminBlogController::class, 'togglePublished'])->name('toggle-publish');
    });

    // Services
    Route::prefix('services')->name('services.')->group(function () {
        Route::get('/',              [AdminServiceController::class, 'index'])->name('index');
        Route::get('/create',        [AdminServiceController::class, 'create'])->name('create');
        Route::post('/',             [AdminServiceController::class, 'store'])->name('store');
        Route::get('/{id}/edit',     [AdminServiceController::class, 'edit'])->name('edit');
        Route::put('/{id}',          [AdminServiceController::class, 'update'])->name('update');
        Route::patch('/{id}',        [AdminServiceController::class, 'update']);
        Route::delete('/{id}',       [AdminServiceController::class, 'destroy'])->name('destroy');
        Route::post('/reorder',      [AdminServiceController::class, 'reorder'])->name('reorder');
    });

    // Contacts
    Route::prefix('contacts')->name('contacts.')->group(function () {
        Route::get('/',                   [AdminContactController::class, 'index'])->name('index');
        Route::get('/{id}',               [AdminContactController::class, 'show'])->name('show');
        Route::post('/{id}/replied',      [AdminContactController::class, 'markReplied'])->name('replied');
        Route::delete('/{id}',            [AdminContactController::class, 'destroy'])->name('destroy');
    });

    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/',              [AdminUserController::class, 'index'])->name('index');
        Route::get('/create',        [AdminUserController::class, 'create'])->name('create');
        Route::post('/',             [AdminUserController::class, 'store'])->name('store');
        Route::get('/{id}/edit',     [AdminUserController::class, 'editUser'])->name('edit');
        Route::put('/{id}',          [AdminUserController::class, 'updateUser'])->name('update');
        Route::delete('/{id}',       [AdminUserController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/role',   [AdminUserController::class, 'updateRole'])->name('role');
    });

    // Settings
    Route::get('/settings',  [AdminSettingController::class, 'index'])->name('settings.index');
    Route::put('/settings',  [AdminSettingController::class, 'update'])->name('settings.update');

    // Languages
    Route::prefix('languages')->name('languages.')->group(function () {
        Route::get('/',                    [AdminLanguageController::class, 'index'])->name('index');
        Route::get('/create',              [AdminLanguageController::class, 'create'])->name('create');
        Route::post('/',                   [AdminLanguageController::class, 'store'])->name('store');
        Route::get('/{id}/edit',           [AdminLanguageController::class, 'edit'])->name('edit');
        Route::put('/{id}',                [AdminLanguageController::class, 'update'])->name('update');
        Route::patch('/{id}',              [AdminLanguageController::class, 'update']);
        Route::delete('/{id}',             [AdminLanguageController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/default',       [AdminLanguageController::class, 'setDefault'])->name('default');
        Route::post('/{id}/toggle',        [AdminLanguageController::class, 'toggleActive'])->name('toggle');
    });

    // Categories & Tags (JSON API)
    Route::apiResource('categories', AdminCategoryController::class)->except(['show']);
    Route::apiResource('tags',       AdminTagController::class)->except(['show']);
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::middleware([\App\Http\Middleware\SetLocale::class])->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/about',          [PageController::class, 'about'])->name('about');
    Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');
    Route::get('/terms',          [PageController::class, 'terms'])->name('terms');

    Route::prefix('properties')->name('properties.')->group(function () {
        Route::get('/',                [PublicPropertyController::class, 'index'])->name('index');
        Route::get('/{property:slug}', [PublicPropertyController::class, 'show'])->name('show');
    });

    Route::prefix('blog')->name('blog.')->group(function () {
        Route::get('/',                [PublicBlogController::class, 'index'])->name('index');
        Route::get('/{post:slug}',     [PublicBlogController::class, 'show'])->name('show');
    });

    Route::get('/services', [PublicServiceController::class, 'index'])->name('services.index');

    Route::prefix('contact')->name('contact.')->group(function () {
        Route::get('/',  [PublicContactController::class, 'index'])->name('index');
        Route::post('/', [PublicContactController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('store');
    });
});

require __DIR__.'/auth.php';
