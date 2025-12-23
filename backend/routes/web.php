<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Смена языка
Route::get('/locale/{locale}', [\App\Http\Controllers\LocaleController::class, 'setLocale'])->name('locale.set');

// Главная страница - лендинг
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
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
    // Если админ явно хочет попасть в обычный кабинет (параметр force=1), пускаем
    if (Auth::user()->is_admin && !request()->has('force')) {
        return redirect()->route('admin.dashboard');
    }
    return view('home');
})->middleware('auth')->name('home');

// Страница оплаты для зарегистрированных пользователей
Route::get('/payment', function () {
    return view('payment');
})->middleware('auth')->name('payment.page');

// Страница оплаты для повторного прохождения теста
Route::get('/payment/retake', function () {
    return view('payment-retake');
})->middleware('auth')->name('payment.retake');

// Маршруты для платежей
Route::post('/api/check-access', [\App\Http\Controllers\PaymentController::class, 'checkAccess'])->name('payment.check');
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
    Route::get('/quiz/test-history', [\App\Http\Controllers\QuizController::class, 'testHistory'])->name('quiz.test-history');
    Route::get('/quiz/results/{sessionId}', [\App\Http\Controllers\QuizController::class, 'viewSessionResult'])->name('quiz.session-result');
    
    // Маршруты для отчётов
    Route::get('/quiz/report/{sessionId}', [\App\Http\Controllers\QuizController::class, 'viewReport'])->name('quiz.report.view');
    Route::get('/quiz/report/{sessionId}/download', [\App\Http\Controllers\QuizController::class, 'generateReport'])->name('quiz.report.download');
    
    // Діагностичний маршрут для перевірки даних
    Route::get('/quiz/debug/{sessionId}', function($sessionId) {
        $result = \App\Models\QuizResult::where('session_id', $sessionId)->first();
        if (!$result) {
            return response()->json(['error' => 'Result not found']);
        }
        return response()->json([
            'module_scores' => $result->module_scores,
            'recommendations' => $result->recommendations,
            'module1_exists' => isset($result->module_scores['module1']),
            'module1_data' => $result->module_scores['module1'] ?? null,
            'module1_sum' => isset($result->module_scores['module1']) ? array_sum($result->module_scores['module1']) : 0
        ]);
    })->name('quiz.debug');
    
    // Маршруты для профиля
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
});

// Маршруты админ-панели
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    
    // Управление пользователями
    Route::get('/users', [\App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/create', [\App\Http\Controllers\Admin\UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users', [\App\Http\Controllers\Admin\UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [\App\Http\Controllers\Admin\UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [\App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [\App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{id}/toggle-access', [\App\Http\Controllers\Admin\UserManagementController::class, 'toggleAccess'])->name('users.toggle-access');
    Route::post('/users/{id}/enable-retake', [\App\Http\Controllers\Admin\UserManagementController::class, 'enableRetake'])->name('users.enable-retake');
    Route::get('/users/{id}/test-history', [\App\Http\Controllers\Admin\UserManagementController::class, 'testHistory'])->name('users.test-history');
    Route::get('/users/{id}/quiz-results', [\App\Http\Controllers\Admin\UserManagementController::class, 'quizResults'])->name('users.quiz-results');
    Route::get('/users/{id}/quiz-results/export', [\App\Http\Controllers\Admin\UserManagementController::class, 'exportQuizResults'])->name('users.quiz-results.export');
    Route::get('/users/{id}/quiz-results/{sessionId}', [\App\Http\Controllers\Admin\UserManagementController::class, 'viewSessionResult'])->name('users.session-result');
    
    // Управление платежами
    Route::get('/payments', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/statistics', [\App\Http\Controllers\Admin\PaymentController::class, 'statistics'])->name('payments.statistics');
    Route::get('/payments/{id}', [\App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{id}/update-status', [\App\Http\Controllers\Admin\PaymentController::class, 'updateStatus'])->name('payments.update-status');
});

require __DIR__.'/auth.php';
