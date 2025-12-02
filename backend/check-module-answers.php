<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\QuizAnswer;

$sessionId = $argv[1] ?? 5;
$moduleNumber = $argv[2] ?? 5;

echo "Checking answers for session #{$sessionId}, module {$moduleNumber}\n";
echo str_repeat('=', 60) . "\n";

$answers = QuizAnswer::where('session_id', $sessionId)
    ->where('module_number', $moduleNumber)
    ->orderBy('question_number')
    ->get(['question_number', 'answer']);

if ($answers->isEmpty()) {
    echo "No answers found!\n";
} else {
    echo "Total answers: " . $answers->count() . "\n\n";
    
    foreach ($answers as $answer) {
        echo "Q{$answer->question_number}: {$answer->answer}\n";
    }
}
