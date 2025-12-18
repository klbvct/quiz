<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\QuizSession;
use App\Models\QuizAnswer;
use App\Http\Controllers\QuizController;

echo "Finding all completed sessions with Module 8 answers...\n\n";

$sessions = QuizSession::where('status', 'completed')
    ->get()
    ->filter(function($session) {
        $module8Count = QuizAnswer::where('session_id', $session->id)
            ->where('module_number', 8)
            ->count();
        return $module8Count > 0;
    });

echo "Found " . $sessions->count() . " sessions to recalculate\n\n";

if ($sessions->isEmpty()) {
    echo "No sessions found\n";
    exit;
}

$controller = new QuizController();
$reflection = new ReflectionClass($controller);
$method = $reflection->getMethod('calculateResults');
$method->setAccessible(true);

$success = 0;
$failed = 0;

foreach ($sessions as $session) {
    echo "Processing Session #{$session->id} (User ID: {$session->user_id})... ";
    
    try {
        $result = $method->invoke($controller, $session);
        
        $scores = is_array($result->module_scores) ? $result->module_scores : json_decode($result->module_scores, true);
        
        if (isset($scores['module8'])) {
            echo "✅ Success! Perception types:\n";
            arsort($scores['module8']);
            foreach ($scores['module8'] as $type => $score) {
                echo "    $type: $score\n";
            }
            $success++;
        } else {
            echo "⚠️ No Module 8 scores\n";
            $failed++;
        }
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
        $failed++;
    }
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "Summary:\n";
echo "  ✅ Successfully recalculated: $success\n";
echo "  ❌ Failed: $failed\n";
echo "  📊 Total: " . $sessions->count() . "\n";
echo str_repeat("=", 50) . "\n";
