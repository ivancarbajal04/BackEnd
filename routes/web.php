<?php

use Illuminate\Support\Facades\Route;

// Route::get('/hola', function () {
//     return view('welcome');
// });
Route::get('/products', [ProductController::class, 'index']);