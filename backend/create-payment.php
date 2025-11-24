<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\Payment;
use App\Models\User;

// Получаем email из параметра
$email = $argv[1] ?? null;

if (!$email) {
    echo "Использование: php create-payment.php <email>\n";
    exit(1);
}

// Находим пользователя
$user = User::where('email', $email)->first();

if (!$user) {
    echo "Пользователь с email $email не найден!\n";
    exit(1);
}

// Создаем платеж
$payment = Payment::create([
    'user_id' => $user->id,
    'email' => $user->email,
    'amount' => 999,
    'status' => 'pending'
]);

echo "✅ Платеж успешно создан!\n";
echo "   ID: {$payment->id}\n";
echo "   Пользователь: {$user->name} ({$user->email})\n";
echo "   Сумма: {$payment->amount} UAH\n";
echo "   Статус: {$payment->status}\n\n";
echo "Теперь выполните: php simulate-payment.php {$payment->id}\n";
