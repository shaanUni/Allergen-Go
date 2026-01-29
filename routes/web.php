<?php

use App\Http\Controllers\Admin\DishController;
use App\Http\Controllers\Admin\DishShareController;
use App\Http\Controllers\Admin\StatsPageController;
use App\Http\Controllers\Admin\SuperAdmin\SuperAdminController;
use App\Http\Controllers\Admin\SuperAdmin\SuperAdminDishes;
use App\Http\Controllers\User\SelectedDishesController;
use App\Models\SelectedDishes;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\User\SearchController;
use PhpParser\Node\Expr\FuncCall;
use App\Http\Middleware\AdminSubscribedCheck;

Route::get('/', function () {
    return view('restaurant');
})->name('restaurant');

Route::get('/users', function () {
    return view('welcome');
})->name('users');

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

Route::get('/privacy-policy', function () {
    return view('user.legal.privacy');
})->name('privacy.policy');

Route::get('/terms-of-service', function () {
    return view('user.legal.terms');
})->name('terms.of.service');

Route::get('/email-message', function () {
    return view('emails.generic-email-page');
})->name('generic.email.message');

Route::prefix('user')->name('user.')->group(function () {

    Route::get('search', [SearchController::class, 'search'])->name('search');
    Route::post('searchCode', [SearchController::class, 'searchCode'])->name('searchCode');
    Route::get('qr/{code}', [SearchController::class, 'qrCode'])->name('qr');

    Route::post('individual/{id}/{state}', [SearchController::class, 'showIndividualDish'])->name('individual');

    Route::post('adddish/{id}/{state}', [SelectedDishesController::class, 'add'])->name('adddish');
    Route::post('selected-dishes', [SelectedDishesController::class, 'selected'])->name('selected');
    Route::post('reset', [SelectedDishesController::class, 'reset'])->name('reset');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Registration
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::get('/multi-branch-register', [RegisterController::class, 'showMultiBranchRegisterForm'])->name('multi-form-register');

    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

    Route::get('/agreement', function () {
        return view('admin.legal.agreement');
    })->name('agreement');
    
    Route::get('/admin-terms-of-service', function () {
        return view('admin.legal.terms');
    })->name('terms.of.service');


    Route::get('unsubscribed', function () {
        return view('admin.auth.unsubscribed');
    })->name('unsubscribed');

    // Stripe success callback
    Route::get('/subscription/success', [RegisterController::class, 'subscriptionSuccess'])->name('subscription.success');

    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

    //Once the admin has logged in, they can acsess these pages
    Route::middleware(['auth:admin', 'auth.session'], 'subscribed')->group(function () {
        Route::get('/checkout', [SubscriptionController::class, 'checkout']);
        Route::post('/admin/subscription/cancel', [SubscriptionController::class, 'cancelSubscription'])->name('subscription.cancel');

        Route::post('/admin/subscription/buy', [SubscriptionController::class, 'resubscribe'])->name('subscription.buy');

        Route::post('/admin/payment-methods/{paymentMethod}/default', [SubscriptionController::class, 'makeDefault'])->name('payment-methods.default');
        Route::delete('/admin/payment-methods/{paymentMethod}', [SubscriptionController::class, 'deletePaymentMethod'])->name('payment-methods.delete');

        Route::get('share-dish', [DishShareController::class, 'index'])->name('dish-share.index');
        Route::post('share-dish-sharing', [DishShareController::class, 'initShare'])->name('dish-share.init');
        Route::get('dish-share.accept/{uuid}', [DishShareController::class, 'accept'])->name('dish-share.accept')->middleware('signed');
        Route::get('dish-share.decline/{uuid}', [DishShareController::class, 'decline'])->name('dish-share.decline')->middleware('signed');
        Route::delete('dish-share/{child_admin_id}', [DishShareController::class, 'delete'])->name('dish-share.delete');
        
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('qrcode', [DashboardController::class, 'qrCode'])->name('qrcode');
        Route::get('account', [DashboardController::class, 'account'])->name('account');
        Route::post('/update-card', [SubscriptionController::class, 'updateCard'])->name('payment-methods.update-card');

        Route::get('stats/{child_admin_id?}', [StatsPageController::class, 'index'])->name('stats');
        Route::post('search', [StatsPageController::class, 'search'])->name('search');
        Route::post('individual/{id}/{state}', [StatsPageController::class, 'showIndividualDish'])->name('individual');



        Route::get('dishes/create', [DishController::class, 'create'])->name('dishes.create');
        Route::post('dishes', [DishController::class, 'store'])->name('dishes.store');
        
        Route::get('dishes/{admin_id?}', [DishController::class, 'index'])->name('dishes.index');
        Route::get('dishes/{id}/edit', [DishController::class, 'edit'])->name('dishes.edit');
        Route::put('dishes/{id}', [DishController::class, 'update'])->name('dishes.update');

        Route::delete('dishes/{id}', [DishController::class, 'destroy'])->name('dishes.destroy');

        //super admin
        Route::prefix('super-admin')->name('super-admin.')->group(function () {
            Route::get('dashboard', [SuperAdminController::class, 'index'])->name('dashboard');
            Route::get('new-admin', [SuperAdminController::class, 'newAdminForm'])->name('new-admin-form');
            Route::post('submit', [SuperAdminController::class, 'submit'])->name('submit');
            Route::post('update-share-dish', [SuperAdminController::class, 'updateDishShareSatus'])->name('update-share-dish');

            Route::delete('delete-account/{admin}', [SuperAdminController::class, 'deleteAccount'])->name('delete-account');
            Route::get('overall-stats', [StatsPageController::class, 'superAdminStats'])->name('overall-stats');

        });

    });
});
