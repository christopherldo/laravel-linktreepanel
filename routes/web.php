<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('index');

Route::prefix('admin')->group(function (){
    Route::get('login', [AdminController::class, 'login'])->name('login');
    Route::post('login', [AdminController::class, 'loginAction']);

    Route::get('register', [AdminController::class, 'register'])->name('register');
    Route::post('register', [AdminController::class, 'registerAction']);

    Route::get('logout', [AdminController::class, 'logout'])->name('logout');

    Route::get('{slug}/links', [AdminController::class, 'pageLinks'])->name('admin.links');
    Route::get('{slug}/design', [AdminController::class, 'pageDesign'])->name('admin.design');
    Route::get('{slug}/stats', [AdminController::class, 'pageStats'])->name('admin.stats');

    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
});

Route::get('{slug}', [PageController::class, 'index']);
