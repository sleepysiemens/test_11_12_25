<?php

use App\Http\Controllers\API\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'getProducts'])->name('api.products.get');
