<?php

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

//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('products', \App\Http\Controllers\ProductsController::class)->only(['index', 'show']);
Route::resource('categories', \App\Http\Controllers\CategoriesController::class)->only(['index', 'show']);

// localhost/admin/....
// route('admin. ....')
Route::name('admin.')->prefix('admin')->middleware(['role:admin|editor'])->group(function() {
    Route::get('dashboard', \App\Http\Controllers\Admin\DashboardController::class)->name('dashboard');


    Route::resource('products', \App\Http\Controllers\Admin\ProductsController::class)->except(['show']);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoriesController::class)->except(['show']);

});

Route::name('ajax.')->middleware('auth')->prefix('ajax')->group(function() {
    Route::group(['role:admin|editor'], function() {
        Route::delete('images/{image}', \App\Http\Controllers\Ajax\RemoveImageController::class)->name('images.delete');
    });
});
