<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Mail;

$email = 'kalabukhov87@gmail.com';

echo "Отправка тестового письма для СУЩЕСТВУЮЩЕГО пользователя...\n";
echo "Email: $email\n\n";

// Ищем или создаем временного пользователя для теста
$user = User::where('email', $email)->first();

if (!$user) {
    echo "⚠️ Пользователь не найден, создаем временного для теста...\n";
    $user = new User([
        'name' => 'Viktor Kalabukhov',
        'email' => $email,
    ]);
}

try {
    Mail::to($email)->send(new \App\Mail\AccessActivatedMail($user));
    echo "✅ Письмо успешно отправлено на $email\n\n";
    echo "Проверьте почту - это письмо об активации доступа для существующего пользователя\n";
} catch (\Exception $e) {
    echo "❌ Ошибка отправки: " . $e->getMessage() . "\n";
}
