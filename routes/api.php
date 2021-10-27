<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//AKo odime preku resource CRUD operaciite are set by default: php artisan route:list
//Route::resource('products', ProductController::class);


//Public routes - user
Route::post('register', ('App\Http\Controllers\AuthController@register'));
Route::post('login', ('App\Http\Controllers\AuthController@login'));

//Public routes
Route::get('products', ('App\Http\Controllers\ProductController@index'));
Route::post('product/{id}', ('App\Http\Controllers\ProductController@show'));
Route::get('search/{key}', ('App\Http\Controllers\ProductController@search'));



//Protected with auth
Route::middleware('auth:sanctum')->group(function () {

   Route::post('create', ('App\Http\Controllers\ProductController@store'));
   Route::post('update/{id}', ('App\Http\Controllers\ProductController@update'));
   Route::post('delete/{id}', ('App\Http\Controllers\ProductController@delete'));


   Route::post('logout', ('App\Http\Controllers\AuthController@logout'));
});
