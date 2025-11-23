<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;
use App\Mail\AccessGrantedMail;

// Генерируем тестовые данные
$testEmail = 'kalabukhov87@gmail.com';
$testPassword = 'TestPass123!';

echo "Отправка тестового письма для НОВОГО пользователя...\n";
echo "Email: $testEmail\n";
echo "Пароль: $testPassword\n\n";

try {
    Mail::to($testEmail)->send(new AccessGrantedMail($testEmail, $testPassword));
    echo "✅ Письмо успешно отправлено на $testEmail\n";
    echo "\nПроверьте почту - в письме будут:\n";
    echo "- Email для входа: $testEmail\n";
    echo "- Пароль: $testPassword\n";
} catch (Exception $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n";
}
