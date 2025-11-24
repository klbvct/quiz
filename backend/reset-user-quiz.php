<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\QuizSession;

$user = User::where('email', 'kalabukhov87@gmail.com')->first();

if ($user) {
    // Удаляем все сессии (каскадом удалятся ответы и результаты)
    $count = QuizSession::where('user_id', $user->id)->count();
    QuizSession::where('user_id', $user->id)->delete();
    
    echo "Удалено сессий: {$count}\n";
    echo "Пользователь {$user->email} теперь без истории тестирования\n";
} else {
    echo "Пользователь не найден\n";
}
