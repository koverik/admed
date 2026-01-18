<?php

declare(strict_types=1);

use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\StatisticsController;
use Illuminate\Support\Facades\Route;

Route::prefix('books')->group(function () {
    Route::get('/', [BookController::class, 'index']);
    Route::get('/search', [BookController::class, 'search']);
    Route::get('/{id}', [BookController::class, 'show'])->whereNumber('id');
    Route::post('/', [BookController::class, 'store']);
});

Route::prefix('statistics')->group(function () {
    Route::get('/expensive-books', [StatisticsController::class, 'expensiveBooks']);
    Route::get('/popular-categories', [StatisticsController::class, 'popularCategories']);
    Route::get('/top-fantasy-and-sci-fi', [StatisticsController::class, 'topFantasyAndSciFi']);
});

Route::prefix('currency')->group(function () {
    Route::get('/rate', [CurrencyController::class, 'getRate']);
    Route::get('/convert/{amount}', [CurrencyController::class, 'convertAmount'])->where('amount', '[0-9]+(\.[0-9]+)?');
});
