<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('index');

Route::prefix('admin')->group(function (){
    Route::get('login', [AdminController::class, 'login'])->name('login');
    Route::post('login', [AdminController::class, 'loginAction']);
    Route::get('register', [AdminController::class, 'register'])->name('admin.register');
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
});

Route::get('{slug}', [PageController::class, 'index']);
