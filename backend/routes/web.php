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

// Страница оплаты для зарегистрированных пользователей
Route::get('/payment', function () {
    return view('payment');
})->middleware('auth')->name('payment.page');

// Маршруты для платежей
Route::post('/payment/create', [\App\Http\Controllers\PaymentController::class, 'create'])->name('payment.create');
Route::post('/payment/callback', [\App\Http\Controllers\PaymentController::class, 'callback'])->name('payment.callback');
Route::get('/payment/success', function () {
    return view('payment-success');
})->name('payment.success');

// Маршруты для квиза
Route::middleware('auth')->group(function () {
    Route::get('/quiz/start', [\App\Http\Controllers\QuizController::class, 'start'])->name('quiz.start');
    Route::get('/quiz/module/{module}', [\App\Http\Controllers\QuizController::class, 'showModule'])->name('quiz.module');
    Route::post('/quiz/module/{module}', [\App\Http\Controllers\QuizController::class, 'saveAnswers'])->name('quiz.save');
    Route::get('/quiz/results', [\App\Http\Controllers\QuizController::class, 'results'])->name('quiz.results');
    
    // Маршруты для профиля
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
});

require __DIR__.'/auth.php';
