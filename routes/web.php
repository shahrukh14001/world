<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('documents',                'Web\DocumentController')->except(['create', 'edit'])->middleware(['auth']);
Route::resource('users',                    'Web\UserController')->except(['create', 'edit'])->middleware(['auth']);