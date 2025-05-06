<?php

use App\Http\Controllers\Admin\DishController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    
    //Once the admin has logged in, they can acsess these pages
    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('dishes', [DishController::class, 'index'])->name('dishes.index');

        Route::get('dishes/create', [DishController::class, 'create'])->name('dishes.create');
        Route::post('dishes', [DishController::class, 'store'])->name('dishes.store');

        Route::get('dishes/{id}/edit', [DishController::class, 'edit'])->name('dishes.edit');
        Route::put('dishes/{id}', [DishController::class, 'update'])->name('dishes.update');
        
        Route::delete('dishes/{id}', [DishController::class, 'destroy'])->name('dishes.destroy');
    });
});
