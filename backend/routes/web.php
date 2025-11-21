<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Главная страница - лендинг
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/home');
    }
    return view('landing');
})->name('landing');

// Страницы аутентификации
Route::get('/register', function () {
    if (Auth::check()) {
        return redirect('/home');
    }
    return view('auth.register');
})->name('register.form');

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect('/home');
    }
    return view('auth.login');
})->name('login.form');



// Защищенная домашняя страница
Route::get('/home', function () {
    return view('home');
})->middleware('auth')->name('home');

// Маршруты для платежей
Route::post('/payment/create', [\App\Http\Controllers\PaymentController::class, 'create'])->name('payment.create');
Route::post('/payment/callback', [\App\Http\Controllers\PaymentController::class, 'callback'])->name('payment.callback');

require __DIR__.'/auth.php';
