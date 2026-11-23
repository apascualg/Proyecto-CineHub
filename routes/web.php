<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CreditController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Admin\PersonController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\TitleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TmdbImportController;
use App\Http\Controllers\User\TitleInteractionController;
use App\Http\Controllers\User\WatchlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogController::class, 'home'])
    ->name('home');
Route::get('/explore', [CatalogController::class, 'publicIndex'])
    ->name('catalog.explore');
Route::get('/explore/{title}', [CatalogController::class, 'publicShow'])
    ->name('catalog.show');
Route::get('/catalog/suggestions', [CatalogController::class, 'suggestions'])
    ->middleware('auth')
    ->name('catalog.suggestions');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/forgot-password', [PasswordResetController::class, 'create'])
        ->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])
        ->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])
        ->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])
        ->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [CatalogController::class, 'index'])
        ->name('dashboard');
    Route::get('/surprise', [CatalogController::class, 'surprise'])
        ->name('titles.surprise');

    Route::get('/titles/{title}', [TitleInteractionController::class, 'show'])
        ->name('titles.show');
    Route::post('/titles/{title}/rating', [TitleInteractionController::class, 'storeRating'])
        ->name('titles.ratings.store');
    Route::post('/titles/{title}/reviews', [TitleInteractionController::class, 'storeReview'])
        ->name('titles.reviews.store');
    Route::put('/reviews/{review}', [TitleInteractionController::class, 'updateReview'])
        ->name('reviews.update');
    Route::delete('/reviews/{review}', [TitleInteractionController::class, 'destroyReview'])
        ->name('reviews.destroy');
    Route::post('/reviews/{review}/comments', [TitleInteractionController::class, 'storeComment'])
        ->name('reviews.comments.store');
    Route::put('/comments/{comment}', [TitleInteractionController::class, 'updateComment'])
        ->name('comments.update');
    Route::delete('/comments/{comment}', [TitleInteractionController::class, 'destroyComment'])
        ->name('comments.destroy');
    Route::get('/my-lists', [WatchlistController::class, 'index'])
        ->name('lists.index');
    Route::post('/my-lists', [WatchlistController::class, 'storeCustom'])
        ->name('lists.custom.store');
    Route::delete('/my-lists/{watchlist}', [WatchlistController::class, 'destroyList'])
        ->name('lists.custom.destroy');
    Route::post('/titles/{title}/lists', [WatchlistController::class, 'store'])
        ->name('lists.store');
    Route::delete('/titles/{title}/lists', [WatchlistController::class, 'destroy'])
        ->name('lists.destroy');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('titles', TitleController::class)->except('show');
        Route::patch('titles/{title}/toggle-status', [TitleController::class, 'toggleStatus'])
            ->name('titles.toggle-status');
        Route::resource('genres', GenreController::class)->except('show');
        Route::resource('people', PersonController::class)->except('show');
        Route::resource('credits', CreditController::class)->except('show');
        Route::resource('users', UserController::class)->only(['index', 'edit', 'update']);
        Route::patch('reviews/bulk-status', [ReviewController::class, 'bulkStatus'])
            ->name('reviews.bulk-status');
        Route::resource('reviews', ReviewController::class)->only(['index', 'update', 'destroy']);
        Route::patch('reviews/{review}/status', [ReviewController::class, 'updateStatus'])
            ->name('reviews.status');
        Route::get('tmdb', [TmdbImportController::class, 'search'])
            ->name('tmdb.search');
        Route::post('tmdb/import', [TmdbImportController::class, 'import'])
            ->name('tmdb.import');
    });
