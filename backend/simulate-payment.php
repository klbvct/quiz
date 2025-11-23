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

// Получаем ID платежа из параметра командной строки
$paymentId = $argv[1] ?? null;

if (!$paymentId) {
    echo "Использование: php simulate-payment.php <payment_id>\n";
    echo "Доступные платежи со статусом pending:\n\n";
    
    $pendingPayments = Payment::where('status', 'pending')->get();
    foreach ($pendingPayments as $p) {
        echo "ID: {$p->id} | Email: {$p->email} | Сумма: {$p->amount} UAH | Создан: {$p->created_at}\n";
    }
    exit;
}

// Находим платеж по ID
$payment = Payment::find($paymentId);

if (!$payment) {
    echo "Платеж с ID $paymentId не найден!\n";
    exit;
}

if ($payment->status !== 'pending') {
    echo "Платеж #$paymentId уже имеет статус: {$payment->status}\n";
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
