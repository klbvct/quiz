<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\User;

$email = 'j.fesenko@studyway.com.ua';

$user = User::where('email', $email)->first();

if (!$user) {
    echo "❌ Пользователь с email $email не найден!\n";
    exit;
}

echo "Текущий статус:\n";
echo "Имя: {$user->name}\n";
echo "Email: {$user->email}\n";
echo "Доступ: " . ($user->has_access ? 'ДА' : 'НЕТ') . "\n\n";

$user->has_access = false;
$user->save();

echo "✅ Доступ снят для {$user->name} ({$user->email})\n";
echo "Новый статус: НЕТ ДОСТУПА\n";
