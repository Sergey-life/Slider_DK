<?php

use App\Http\Controllers\Articles\ArticleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SliderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', [SliderController::class, 'index'])->name('index');

Route::get('category/{id}', [SliderController::class, 'show'])->name('category.show');

Route::middleware(['basicAuth'])->group(function () {
    Route::resource('products', ProductController::class)->only([
        'index', 'store', 'destroy', 'update'
    ]);
});

Route::get('articles', [ArticleController::class, 'index'])->name('articles');
