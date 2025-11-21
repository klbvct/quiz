<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Создание платежа и генерация данных для LiqPay
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;
        $amount = 999;

        // Создаем запись о платеже
        $payment = Payment::create([
            'email' => $email,
            'amount' => $amount,
            'status' => 'pending'
        ]);

        // Настройки LiqPay (добавьте в .env)
        $public_key = env('LIQPAY_PUBLIC_KEY');
        $private_key = env('LIQPAY_PRIVATE_KEY');

        // Формируем данные для LiqPay
        $data = [
            'version' => 3,
            'public_key' => $public_key,
            'action' => 'pay',
            'amount' => $amount,
            'currency' => 'UAH',
            'description' => 'Профориентационное тестирование',
            'order_id' => $payment->id,
            'result_url' => url('/'),
            'server_url' => route('payment.callback'),
        ];

        $data_encoded = base64_encode(json_encode($data));
        $signature = base64_encode(sha1($private_key . $data_encoded . $private_key, true));

        return response()->json([
            'data' => $data_encoded,
            'signature' => $signature,
            'action_url' => 'https://www.liqpay.ua/api/3/checkout'
        ]);
    }

    /**
     * Обработка callback от LiqPay
     */
    public function callback(Request $request)
    {
        $private_key = env('LIQPAY_PRIVATE_KEY');
        
        $data = $request->data;
        $signature = $request->signature;

        // Проверяем подпись
        $expected_signature = base64_encode(sha1($private_key . $data . $private_key, true));
        
        if ($signature !== $expected_signature) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Декодируем данные
        $payment_data = json_decode(base64_decode($data), true);
        
        $order_id = $payment_data['order_id'];
        $status = $payment_data['status'];

        $payment = Payment::find($order_id);

        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        // Сохраняем ID транзакции и данные
        $payment->update([
            'payment_id' => $payment_data['payment_id'] ?? null,
            'payment_data' => $payment_data
        ]);

        // Если платеж успешен
        if ($status === 'success') {
            $payment->update(['status' => 'completed']);
            
            $email = $payment->email;
            $user = User::where('email', $email)->first();

            if (!$user) {
                // Создаем нового пользователя
                $password = Str::random(12);
                $user = User::create([
                    'name' => explode('@', $email)[0],
                    'email' => $email,
                    'password' => Hash::make($password),
                    'has_access' => true
                ]);

                // Отправить письмо с паролем
                Mail::to($email)->send(new \App\Mail\AccessGrantedMail($email, $password));
                
            } else {
                // Даем доступ существующему пользователю
                $user->update(['has_access' => true]);

                // Отправить письмо об активации доступа
                Mail::to($email)->send(new \App\Mail\AccessActivatedMail($user));
            }
        } elseif ($status === 'failure' || $status === 'error') {
            $payment->update(['status' => 'failed']);
        }

        return response()->json(['status' => 'ok']);
    }
}
