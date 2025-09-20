<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\VehicleCategoryController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehiclePartController;
use Illuminate\Support\Facades\Route;




// lockscreen
Route::get('/', function () {
    return view('lockscreen');
})->name('lockscreen');

// Signup / Register
Route::get('signup', [AuthController::class, 'showSignupForm'])->name('auth.signup');
Route::post('signup', [AuthController::class, 'signup'])->name('signup.post');

// Login
Route::get('login', [AuthController::class, 'showLoginForm'])->name('auth.login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');

// Logout
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');

// Email Verification
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail'])->name('verify.email');


// Profile
Route::get('profile', [AuthController::class, 'profile'])->name('profile')->middleware('auth');
Route::post('profile/update', [AuthController::class, 'updateProfile'])->name('profile.update')->middleware('auth');
Route::post('/profile/change-password', [AuthController::class, 'changePassword'])->name('profile.change-password')->middleware('auth');


// Forgot Password
Route::get('forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
Route::post('forgot-password', [AuthController::class, 'sendResetToken'])->name('password.email');

// Reset Password
Route::get('reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Location
Route::resource('locations', LocationController::class)->middleware('auth');

// vehicle categories
Route::resource('vehicle-categories', VehicleCategoryController::class)->middleware('auth');

// vehicles
Route::resource('vehicles', VehicleController::class)->middleware('auth');

// Categories for dropdown (AJAX)
Route::get('/ajax/categories', [VehicleCategoryController::class, 'ajaxCategories'])
    ->name('ajax.categories')->middleware('auth');

// Towns for dropdown (AJAX)
Route::get('/ajax/towns', [LocationController::class, 'ajaxTowns'])
    ->name('ajax.towns')->middleware('auth');

Route::get('/categories', function () {
    return view('categories.index');
})->name('categories.index');

// Sub-Category Page
Route::get('/sub_categories', function () {
    return view('sub_categories.index');
})->name('sub_categories.index');

// Product Page
Route::get('/products', function () {
    return view('products.index');
})->name('products.index');
