<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

// Находим последний pending платеж
$payment = Payment::where('status', 'pending')
    ->where('email', 'kalabukhov87@gmail.com')
    ->orderBy('created_at', 'desc')
    ->first();

if (!$payment) {
    echo "Платеж не найден!\n";
    exit;
}

echo "Обрабатываем платеж #" . $payment->id . " для " . $payment->email . "\n";

// Обновляем статус платежа
$payment->update([
    'status' => 'completed',
    'payment_id' => 'test_' . time()
]);

$email = $payment->email;
$user = User::where('email', $email)->first();

if (!$user) {
    echo "Создаем нового пользователя...\n";
    $password = Str::random(12);
    $user = User::create([
        'name' => explode('@', $email)[0],
        'email' => $email,
        'password' => Hash::make($password),
        'has_access' => true
    ]);

    echo "Отправляем письмо с паролем: $password\n";
    Mail::to($email)->send(new \App\Mail\AccessGrantedMail($email, $password));
    echo "Письмо отправлено!\n";
    
} else {
    echo "Пользователь существует, активируем доступ...\n";
    $user->update(['has_access' => true]);

    echo "Отправляем письмо об активации доступа...\n";
    Mail::to($email)->send(new \App\Mail\AccessActivatedMail($user));
    echo "Письмо отправлено!\n";
}

echo "\nГотово! Проверьте почту $email\n";
