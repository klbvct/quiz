<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;

$email = 'kalabukhov87@gmail.com';
$token = 'test-token-' . time();
$resetUrl = url(route('password.reset', [
    'token' => $token,
    'email' => $email,
], false));

echo "Отправка тестового письма для сброса пароля...\n";
echo "Email: $email\n";
echo "Reset URL: $resetUrl\n\n";

try {
    Mail::to($email)->send(new \App\Mail\ResetPasswordMail($resetUrl, 60));
    echo "✅ Письмо успешно отправлено на $email\n\n";
    echo "Проверьте почту - в письме будет кнопка для сброса пароля\n";
} catch (\Exception $e) {
    echo "❌ Ошибка отправки: " . $e->getMessage() . "\n";
}
