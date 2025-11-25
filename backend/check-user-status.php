<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::where('name', 'LIKE', '%Jara%')
    ->orWhere('name', 'LIKE', '%Fesenko%')
    ->first();

if (!$user) {
    echo "User not found\n";
    exit;
}

echo "User: {$user->name}\n";
echo "Email: {$user->email}\n";
echo "can_retake: " . ($user->can_retake ? 'true' : 'false') . "\n";
echo "has_access: " . ($user->has_access ? 'true' : 'false') . "\n\n";

$sessions = App\Models\QuizSession::where('user_id', $user->id)->get();
echo "Total sessions: {$sessions->count()}\n\n";

foreach($sessions as $session) {
    echo "Session ID: {$session->id}\n";
    echo "  Status: {$session->status}\n";
    echo "  Module: {$session->current_module}\n";
    echo "  Created: {$session->created_at}\n";
    echo "  Completed: " . ($session->completed_at ?? 'null') . "\n\n";
}
