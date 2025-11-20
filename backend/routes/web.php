<?php

use Illuminate\Support\Facades\Route;

// Страницы аутентификации
Route::get('/', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
});

// Защищенная домашняя страница
Route::get('/home', function () {
    return view('home');
});

require __DIR__.'/auth.php';
