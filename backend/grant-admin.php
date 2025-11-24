<?php

/**
 * Скрипт для выдачи прав администратора пользователю
 * 
 * Использование:
 * php grant-admin.php user@example.com
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

if ($argc < 2) {
    echo "Использование: php grant-admin.php user@example.com\n";
    exit(1);
}

$email = $argv[1];

try {
    $user = \App\Models\User::where('email', $email)->first();
    
    if (!$user) {
        echo "❌ Пользователь с email {$email} не найден\n";
        exit(1);
    }
    
    if ($user->is_admin) {
        echo "ℹ️  Пользователь {$user->name} ({$email}) уже является администратором\n";
        exit(0);
    }
    
    $user->is_admin = true;
    $user->save();
    
    echo "✅ Права администратора успешно выданы пользователю {$user->name} ({$email})\n";
    
} catch (\Exception $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n";
    exit(1);
}
