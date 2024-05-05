<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DishController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return "kekw";
});

Route::post('/auth/register', [UserController::class, 'createUser']);
Route::post('/auth/login', [UserController::class, 'loginUser']);

Route::get('/dishes', [DishController::class, 'index'])->name('dishes.index');
Route::post('/dishes', [DishController::class, 'store'])->name('dishes.store');
Route::get('/dishes/{dishe}', [DishController::class, 'show'])->name('dishes.show');
Route::put('dishes/{dishe}', [DishController::class, 'update'])->name('dishes.update');
Route::delete('dishes/{dishe}', [DishController::class, 'destroy'])->name('dishes.destroy');

Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])->name('announcements.show');
Route::put('announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
Route::delete('announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');
Route::put('reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
