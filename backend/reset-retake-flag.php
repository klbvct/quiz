<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::where('email', 'j.fesenko@studyway.com.ua')->first();
$user->can_retake = false;
$user->save();

echo "can_retake сброшен для {$user->name}\n";
