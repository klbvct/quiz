<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sessionId = 8;

echo "=== Ответы пользователя (сессия $sessionId, модуль 2) ===\n\n";

$answers = \App\Models\QuizAnswer::where('session_id', $sessionId)
    ->where('module_number', 2)
    ->orderBy('question_number')
    ->get(['question_number', 'answer']);

foreach($answers as $answer) {
    echo sprintf("Q%02d: %s\n", $answer->question_number, $answer->answer);
}

echo "\n=== Текущие результаты модуля 2 ===\n\n";

$result = \App\Models\QuizResult::where('session_id', $sessionId)->first();
if ($result && $result->detailed_scores) {
    $scores = json_decode($result->detailed_scores, true);
    if (isset($scores['module2'])) {
        foreach($scores['module2'] as $key => $score) {
            echo "$key: $score\n";
        }
    }
}
