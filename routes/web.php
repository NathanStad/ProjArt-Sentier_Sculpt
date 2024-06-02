<?php

use App\Http\Controllers\EtapeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SentierController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/data-sentiers', [SentierController::class, 'index']);
Route::get("/data-sentier-{id}", [SentierController::class, 'show']);
Route::get("/data-step-{id}", [EtapeController::class, 'show']);
Route::get("/data-sentier/prefere-incr-{id}", [SentierController::class, 'incrCompteur']);
Route::get('/data-sentiers/prefere', [SentierController::class, 'topClickedSentiers']);
Route::get('/data-theme', [ThemeController::class, 'index']);
Route::get('/checkdata-user', [UserController::class, 'authenticate']);
Route::get('/insertdata-user', [UserController::class, 'store']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
