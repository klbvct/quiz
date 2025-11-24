<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\QuizSession;

$user = User::where('email', 'kalabukhov87@gmail.com')->first();

if ($user) {
    echo "Пользователь: {$user->name} ({$user->email})\n";
    echo "ID: {$user->id}\n";
    echo "Has access: " . ($user->has_access ? 'Да' : 'Нет') . "\n\n";
    
    $sessions = QuizSession::where('user_id', $user->id)->get();
    
    echo "Всего сессий: " . $sessions->count() . "\n\n";
    
    foreach ($sessions as $session) {
        echo "Сессия ID: {$session->id}\n";
        echo "  Статус: {$session->status}\n";
        echo "  Текущий модуль: {$session->current_module}\n";
        echo "  Начало: {$session->started_at}\n";
        echo "  Завершение: {$session->completed_at}\n";
        echo "  Ответов: " . $session->answers()->count() . "\n";
        echo "  Результат: " . ($session->result ? 'Да' : 'Нет') . "\n\n";
    }
} else {
    echo "Пользователь не найден\n";
}
