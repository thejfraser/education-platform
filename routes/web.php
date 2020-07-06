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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::name('post.')->prefix('posts')->group( function(){
    Route::get('/', 'PostController@index')->name('index');
    Route::get('/{post:slug}', 'PostController@show')->name('show');
});

Route::get('/home', 'HomeController@index')->name('home');
