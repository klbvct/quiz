<?php

/**
 * Простой скрипт для пересчета результатов последней сессии
 * Запуск: php recalculate-last-session.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\QuizSession;
use App\Http\Controllers\QuizController;

// Находим последнюю завершенную сессию текущего пользователя
echo "🔍 Поиск последней завершенной сессии...\n";

$session = QuizSession::where('status', 'completed')
    ->whereNotNull('completed_at')
    ->latest('completed_at')
    ->first();

if (!$session) {
    echo "❌ Не найдено ни одной завершенной сессии\n";
    exit(1);
}

echo "✅ Найдена сессия #{$session->id} (пользователь: {$session->user->name})\n";
echo "📅 Завершена: {$session->completed_at}\n\n";

echo "🔄 Пересчет результатов...\n";

try {
    // Создаем экземпляр контроллера
    $careerService = app(\App\Services\CareerRecommendationService::class);
    $controller = new QuizController($careerService);
    
    // Вызываем приватный метод через Reflection
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('calculateResults');
    $method->setAccessible(true);
    
    $result = $method->invoke($controller, $session);
    
    echo "✅ Результаты успешно пересчитаны!\n";
    echo "📊 ID результата: {$result->id}\n";
    echo "\n🔗 Проверьте результаты: http://127.0.0.1:8000/quiz/results\n";
    
} catch (Exception $e) {
    echo "❌ Ошибка при пересчете: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
