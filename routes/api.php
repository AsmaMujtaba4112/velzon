<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\ProductController;

Route::middleware('auth')->group(function () {

    // Category APIs
    Route::get('/categories', [CategoryController::class, 'index'])->name('api.categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('api.categories.store');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('api.categories.show');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('api.categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('api.categories.destroy');

    // SubCategory APIs
    Route::get('/sub-categories', [SubCategoryController::class, 'index'])->name('api.subcategories.index');
    Route::post('/sub-categories', [SubCategoryController::class, 'store'])->name('api.subcategories.store');
    Route::get('/sub-categories/{subCategory}', [SubCategoryController::class, 'show'])->name('api.subcategories.show');
    Route::put('/sub-categories/{subCategory}', [SubCategoryController::class, 'update'])->name('api.subcategories.update');
    Route::delete('/sub-categories/{subCategory}', [SubCategoryController::class, 'destroy'])->name('api.subcategories.destroy');

    // Product APIs
    Route::get('/products', [ProductController::class, 'index'])->name('api.products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('api.products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('api.products.show');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('api.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('api.products.destroy');
});
