<?php

use App\Http\Controllers\Books\Get\GetBookIdHandler;
use App\Http\Controllers\Books\Get\GetBooksHandler;
use App\Http\Controllers\Books\Post\PostCreateBooksHandler;
use App\Http\Controllers\Store\Get\GetStoreBooksHandler;
use Illuminate\Support\Facades\Route;

Route::prefix('store')->group(
    function () {
        Route::get('/loadProduts', GetStoreBooksHandler::class);
        
        Route::get('/detailsId', GetBookIdHandler::class);
    }
);
