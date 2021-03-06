<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('index');

Route::prefix('admin')->group(function (){
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');

    Route::get('login', [AdminController::class, 'login'])->name('login');
    Route::post('login', [AdminController::class, 'loginAction']);

    Route::get('register', [AdminController::class, 'register'])->name('register');
    Route::post('register', [AdminController::class, 'registerAction']);

    Route::get('logout', [AdminController::class, 'logout'])->name('logout');

    Route::get('account', [AdminController::class, 'accountConfig'])->name('admin.account');
    Route::post('account', [AdminController::class, 'accountConfigAction']);

    Route::get('{slug}/links', [AdminController::class, 'pageLinks'])->name('admin.links');

    Route::get('{slug}/design', [AdminController::class, 'pageDesign'])->name('admin.design');
    Route::post('{slug}/design', [AdminController::class, 'pageDesignAction']);

    Route::get('{slug}/stats', [AdminController::class, 'pageStats'])->name('admin.stats');
    Route::get('{slug}/delete', [AdminController::class, 'pageDelete'])->name('admin.delete');

    Route::get('newpage', [AdminController::class, 'newPage'])->name('admin.newpage');
    Route::post('newpage', [AdminController::class, 'newPageAction']);

    Route::get('linkorder/{linkid}/{pos}', [AdminController::class, 'linkOrderUpdate']);

    Route::get('{slug}/newlink', [AdminController::class, 'newLink'])->name('admin.newlink');
    Route::post('{slug}/newlink', [AdminController::class, 'newLinkAction']);

    Route::get('{slug}/editlink/{linkid}', [AdminController::class, 'editLink'])->name('admin.editlink');
    Route::post('{slug}/editlink/{linkid}', [AdminController::class, 'editLinkAction']);

    Route::get('{slug}/dellink/{linkid}', [AdminController::class, 'dellink'])->name('admin.dellink');
});

Route::get('link/{linkId}', [PageController::class, 'linkAction'])->name('link');

Route::get('{slug}', [PageController::class, 'index'])->name('page');
