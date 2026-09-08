<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/mypage', [ProfileController::class, 'show'])
        ->name('mypage');

    Route::get('/mypage/profile', [ProfileController::class, 'edit'])
        ->name('mypage.profile.edit');

    Route::patch('/mypage/profile', [ProfileController::class, 'update'])
        ->name('mypage.profile.update');

    Route::post('/item/{itemId}/like', [LikeController::class, 'store'])
        ->name('likes.store');

    Route::delete('/item/{itemId}/like', [LikeController::class, 'destroy'])
        ->name('likes.destroy');

    Route::post('/item/{itemId}/comment', [CommentController::class, 'store'])
        ->name('comments.store');

    Route::get('/purchase/{itemId}', [PurchaseController::class, 'create'])
        ->name('purchases.create');

    Route::post('/purchase/{itemId}', [PurchaseController::class, 'store'])
        ->name('purchases.store');

    Route::get('/purchase/address/{itemId}', [PurchaseController::class, 'edit'])
        ->name('purchases.address.edit');

    Route::post('/purchase/address/{itemId}', [PurchaseController::class, 'updateAddress'])
        ->name('purchases.address.update');

    Route::get('/sell', [ItemController::class, 'create'])
        ->name('items.create');

    Route::post('/sell', [ItemController::class, 'store'])
        ->name('items.store');
});

Route::get('/item/{itemId}', [ItemController::class, 'show'])
    ->name('items.show');

Route::get('/', [ItemController::class, 'index'])
    ->name('items.index');