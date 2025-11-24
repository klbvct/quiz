<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

$email = 'kalabukhov87@gmail.com';

$user = User::where('email', $email)->first();

if ($user) {
    $user->update(['has_access' => true]);
    echo "Доступ предоставлен для пользователя: {$user->email}\n";
    echo "ID: {$user->id}\n";
    echo "Имя: {$user->name}\n";
} else {
    echo "Пользователь с email {$email} не найден\n";
}
