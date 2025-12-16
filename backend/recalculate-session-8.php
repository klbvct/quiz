<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Находим сессию 8
$session = \App\Models\QuizSession::find(8);
if (!$session) {
    echo "Session 8 not found\n";
    exit(1);
}

echo "Found session 8 for user: {$session->user->name}\n";

// Находим результат
$result = \App\Models\QuizResult::where('session_id', 8)->first();
if ($result) {
    echo "\nCurrent module1 data:\n";
    print_r($result->module_scores['module1'] ?? 'NOT FOUND');
    echo "\n\nTotal module1 sum: " . (isset($result->module_scores['module1']) ? array_sum($result->module_scores['module1']) : 0) . "\n";
    
    // Проверяем структуру
    if (isset($result->module_scores['module1'])) {
        echo "\nModule1 keys: " . implode(', ', array_keys($result->module_scores['module1'])) . "\n";
    }
} else {
    echo "No result found for session 8\n";
}

// Пересчитываем результаты
echo "\n\n=== RECALCULATING RESULTS ===\n";
$controller = new \App\Http\Controllers\QuizController();
$reflection = new ReflectionClass($controller);
$method = $reflection->getMethod('calculateResults');
$method->setAccessible(true);

try {
    $newResult = $method->invoke($controller, $session);
    echo "Results recalculated successfully!\n";
    echo "\nNew module1 data:\n";
    print_r($newResult->module_scores['module1'] ?? 'NOT FOUND');
    echo "\n\nNew total module1 sum: " . (isset($newResult->module_scores['module1']) ? array_sum($newResult->module_scores['module1']) : 0) . "\n";
    
    if (isset($newResult->module_scores['module1'])) {
        echo "\nNew Module1 keys: " . implode(', ', array_keys($newResult->module_scores['module1'])) . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
