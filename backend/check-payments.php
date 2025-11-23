<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Payment;

$payments = Payment::orderBy('created_at', 'desc')->take(5)->get();

echo "Последние 5 платежей:\n";
echo "====================================\n";
foreach ($payments as $payment) {
    echo "ID: {$payment->id}\n";
    echo "Email: {$payment->email}\n";
    echo "Amount: {$payment->amount} UAH\n";
    echo "Status: {$payment->status}\n";
    echo "Created: {$payment->created_at}\n";
    echo "------------------------------------\n";
}
