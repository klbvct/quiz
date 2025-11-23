<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

$users = User::all();

echo "Все пользователи:\n";
echo "====================================\n";
foreach ($users as $user) {
    echo "ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Has Access: " . ($user->has_access ? 'YES' : 'NO') . "\n";
    echo "Created: {$user->created_at}\n";
    echo "------------------------------------\n";
}
