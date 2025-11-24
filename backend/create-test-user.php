<?php

/**
 * Скрипт для создания тестового пользователя
 * 
 * Использование:
 * php create-test-user.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Проверяем, существует ли уже пользователь с таким email
    $email = 'test@example.com';
    $existingUser = \App\Models\User::where('email', $email)->first();
    
    if ($existingUser) {
        echo "ℹ️  Пользователь с email {$email} уже существует\n";
        echo "   ID: {$existingUser->id}\n";
        echo "   Имя: {$existingUser->name}\n";
        echo "   Доступ: " . ($existingUser->has_access ? 'Да' : 'Нет') . "\n";
        echo "   Админ: " . ($existingUser->is_admin ? 'Да' : 'Нет') . "\n";
        exit(0);
    }
    
    // Создаем нового пользователя
    $user = new \App\Models\User();
    $user->name = 'Test User';
    $user->email = $email;
    $user->password = \Illuminate\Support\Facades\Hash::make('password123');
    $user->has_access = true;
    $user->birthdate = '1995-05-15';
    $user->is_admin = false;
    $user->save();
    
    echo "✅ Тестовый пользователь успешно создан!\n\n";
    echo "   ID: {$user->id}\n";
    echo "   Имя: {$user->name}\n";
    echo "   Email: {$user->email}\n";
    echo "   Пароль: password123\n";
    echo "   Дата рождения: {$user->birthdate}\n";
    echo "   Доступ: " . ($user->has_access ? 'Да' : 'Нет') . "\n";
    echo "   Админ: " . ($user->is_admin ? 'Да' : 'Нет') . "\n";
    
} catch (\Exception $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n";
    exit(1);
}
