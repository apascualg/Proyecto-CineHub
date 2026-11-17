<?php

use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\User\TitleInteractionController;
use App\Http\Controllers\User\WatchlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [CatalogController::class, 'index'])
        ->name('dashboard');

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
    Route::delete('/comments/{comment}', [TitleInteractionController::class, 'destroyComment'])
        ->name('comments.destroy');
    Route::get('/my-lists', [WatchlistController::class, 'index'])
        ->name('lists.index');
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
    ->group(function () {
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
        Route::get('/reviews', [AdminReviewController::class, 'index'])
            ->name('admin.reviews.index');
        Route::patch('/reviews/{review}/status', [AdminReviewController::class, 'updateStatus'])
            ->name('admin.reviews.status');
        Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])
            ->name('admin.reviews.destroy');
    });
