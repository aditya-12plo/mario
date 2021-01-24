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

$router->group(['prefix' => 'root-system'], function () use ($router) {
    Route::get('/', 'IndexController@index');
    Route::get('/pdf', 'IndexController@pdf');
    Route::get('/excel', 'IndexController@excel');
});


Route::get('/', 'Auth\LoginController@showLoginForm');
Auth::routes();
Auth::routes(['register' => false]);
Route::get('/home', 'HomeController@index')->name('home');
Route::post('/home', 'HomeController@mapsMatrix')->name('homePost');
Route::get('/profile', 'HomeController@profile')->name('profile');
Route::post('/profile', 'HomeController@profilePost')->name('profilePost');
Route::get('/users', 'HomeController@users')->name('users');
Route::get('/user/{encrpt}', 'HomeController@userDetail')->name('userDetail');
Route::post('/user/{encrpt}', 'HomeController@userDetailPost')->name('userDetailPost');
