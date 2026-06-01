<?php

use Illuminate\Support\Facades\Route;

Route::prefix('solutions')->name('solutions.')->group(function () {
    Route::get('/ai-solutions-automation', function () {
        return view('portfolio.solutions.ai-automation');
    })->name('ai-automation');

    Route::get('/custom-application-development', function () {
        return view('portfolio.solutions.custom-app');
    })->name('custom-app');

    Route::get('/digital-transformation', function () {
        return view('portfolio.solutions.digital-transformation');
    })->name('digital-transformation');

    Route::get('/startup-product-development', function () {
        return view('portfolio.solutions.startup-product');
    })->name('startup-product');

    Route::get('/branding-digital-presence', function () {
        return view('portfolio.solutions.branding-digital');
    })->name('branding-digital');
});
