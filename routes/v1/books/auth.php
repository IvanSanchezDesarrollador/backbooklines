<?php

use App\Http\Controllers\Books\Get\GetBookIdHandler;
use App\Http\Controllers\Books\Get\GetBooksHandler;
use App\Http\Controllers\Books\Post\PostCreateBooksHandler;
use Illuminate\Support\Facades\Route;

Route::prefix('books')->group(
    function () {

        Route::post('/create', PostCreateBooksHandler::class);
        Route::get('/load', GetBooksHandler::class);
        Route::get('/detailsId', GetBookIdHandler::class);

    }
);
