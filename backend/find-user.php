<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\QuizSession;

$email = $argv[1] ?? 'j.fesenko@studyway.com.ua';

echo "Searching for user: {$email}\n";
echo str_repeat('=', 60) . "\n";

$user = User::where('email', $email)->first();

if (!$user) {
    echo "User not found!\n";
    exit(1);
}

echo "User ID: {$user->id}\n";
echo "Name: {$user->name}\n";
echo "Email: {$user->email}\n";
echo "Has access: " . ($user->has_access ? 'Yes' : 'No') . "\n";
echo str_repeat('-', 60) . "\n";

$sessions = QuizSession::where('user_id', $user->id)
    ->orderBy('id', 'desc')
    ->get();

if ($sessions->isEmpty()) {
    echo "No quiz sessions found for this user.\n";
    exit(0);
}

echo "Quiz sessions:\n";
foreach ($sessions as $session) {
    echo "  Session #{$session->id}\n";
    echo "    Status: {$session->status}\n";
    echo "    Current module: {$session->current_module}\n";
    echo "    Started: {$session->created_at}\n";
    
    $answersCount = \App\Models\QuizAnswer::where('session_id', $session->id)->count();
    echo "    Total answers: {$answersCount}\n";
    
    if ($session->status === 'completed') {
        echo "    ✓ Completed\n";
    }
    echo "\n";
}

echo str_repeat('=', 60) . "\n";
echo "To analyze this user's results, run:\n";
echo "php artisan quiz:test-calculation {$sessions->first()->id}\n";
