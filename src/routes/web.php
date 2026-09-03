<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CommentController;

Route::middleware('auth')->group(function () {
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::post('/item/{item_id}/like', [LikeController::class, 'store'])
        ->name('likes.store');

    Route::delete('/item/{item_id}/like', [LikeController::class, 'destroy'])
        ->name('likes.destroy');

    Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])
        ->name('comments.store');

    Route::get('/purchase/{item_id}', [PurchaseController::class, 'create'])
        ->name('purchases.create');

    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])
        ->name('purchases.store');

    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'edit'])
        ->name('purchases.address.edit');

    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])
        ->name('purchases.address.update');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/item/{item_id}', [ItemController::class, 'show'])
    ->name('items.show');

Route::get('/', [ItemController::class, 'index'])
    ->name('items.index');