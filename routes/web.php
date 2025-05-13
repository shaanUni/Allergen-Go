<?php

use App\Http\Controllers\Admin\DishController;
use App\Http\Controllers\Admin\StatsPageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\User\SearchController;
use PhpParser\Node\Expr\FuncCall;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

Route::prefix('user')->name('user.')->group(function(){

    Route::get('search', [SearchController::class, 'search'])->name('search');
    Route::post('searchCode', [SearchController::class, 'searchCode'])->name('searchCode');
    Route::get('qr/{code}', [SearchController::class, 'qrCode'])->name('qr');

    Route::post('individual/{id}/{state}', [SearchController::class, 'showIndividualDish'])->name('individual');


});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    
    //Once the admin has logged in, they can acsess these pages
    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('generate', [DashboardController::class, 'generate'])->name('generate');
        Route::get('qrcode', [DashboardController::class, 'qrCode'])->name('qrcode');

        Route::get('stats', [StatsPageController::class, 'index'])->name('stats');
        Route::post('search', [StatsPageController::class, 'search'])->name('search');


        Route::get('dishes', [DishController::class, 'index'])->name('dishes.index');

        Route::get('dishes/create', [DishController::class, 'create'])->name('dishes.create');
        Route::post('dishes', [DishController::class, 'store'])->name('dishes.store');

        Route::get('dishes/{id}/edit', [DishController::class, 'edit'])->name('dishes.edit');
        Route::put('dishes/{id}', [DishController::class, 'update'])->name('dishes.update');
        
        Route::delete('dishes/{id}', [DishController::class, 'destroy'])->name('dishes.destroy');

    });
});
