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
    Route::get('/create', 'PostController@create')->name('new')->middleware('can:create,App\Post');
    Route::post('/create', 'PostController@store')->name('create')->middleware('can:create,App\Post');
    Route::get('/edit/{post}', 'PostController@edit')->name('edit')->middleware('can:update,post');
    Route::put('/edit/{post}', 'PostController@update')->name('update')->middleware('can:update,post');
    Route::delete('/{post}', 'PostController@destroy')->name('delete')->middleware('can:delete,post');
    Route::get('/{page}', 'PostController@index')->name('indexpage')->where('page', '^[0-9]+$');
    Route::get('/{post:slug}', 'PostController@show')->name('show');
});

Route::name('tag.')->prefix('tags')->group( function(){
    Route::get('/', 'TagController@index')->name('index');
    Route::get('/{tag:slug}', 'TagController@show')->name('show');
});

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();
