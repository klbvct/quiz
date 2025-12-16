<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== RECALCULATING ALL QUIZ RESULTS ===\n\n";

// Находим все сессии с результатами
$results = \App\Models\QuizResult::with('session')->get();

echo "Found {$results->count()} results to recalculate\n\n";

$controller = new \App\Http\Controllers\QuizController();
$reflection = new ReflectionClass($controller);
$method = $reflection->getMethod('calculateResults');
$method->setAccessible(true);

$success = 0;
$errors = 0;

foreach ($results as $oldResult) {
    $sessionId = $oldResult->session_id;
    $session = $oldResult->session;
    
    if (!$session) {
        echo "❌ Session {$sessionId} not found\n";
        $errors++;
        continue;
    }
    
    echo "Processing session {$sessionId} (User: {$session->user->name})...\n";
    
    // Проверяем старый формат
    $hasOldFormat = isset($oldResult->module_scores['module1']['nature']) 
                    || isset($oldResult->module_scores['module1']['technic']);
    
    if ($hasOldFormat) {
        echo "  └─ Old format detected: " . implode(', ', array_keys($oldResult->module_scores['module1'])) . "\n";
        
        try {
            $newResult = $method->invoke($controller, $session);
            echo "  └─ ✅ Recalculated successfully\n";
            echo "  └─ New format: " . implode(', ', array_keys($newResult->module_scores['module1'])) . "\n";
            $success++;
        } catch (Exception $e) {
            echo "  └─ ❌ Error: " . $e->getMessage() . "\n";
            $errors++;
        }
    } else {
        echo "  └─ ⏩ Already in new format, skipping\n";
    }
    
    echo "\n";
}

echo "\n=== SUMMARY ===\n";
echo "Total processed: {$results->count()}\n";
echo "Successfully recalculated: {$success}\n";
echo "Errors: {$errors}\n";
echo "Skipped (already new format): " . ($results->count() - $success - $errors) . "\n";
