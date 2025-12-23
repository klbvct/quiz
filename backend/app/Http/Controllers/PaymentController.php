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
     * Проверка доступа пользователя перед оплатой
     */
    public function checkAccess(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;
        $user = User::where('email', $email)->first();

        // Если пользователя нет - разрешаем оплату
        if (!$user) {
            return response()->json([
                'can_pay' => true,
                'message' => 'Вы можете оплатить доступ к тесту'
            ]);
        }

        // Проверяем, есть ли у пользователя доступ
        if (!$user->has_access) {
            // Нет доступа - разрешаем оплату
            return response()->json([
                'can_pay' => true,
                'message' => 'Вы можете оплатить доступ к тесту'
            ]);
        }

        // Есть доступ - проверяем, пройден ли тест
        $completedSession = $user->quizSessions()
            ->whereNotNull('completed_at')
            ->exists();

        if ($completedSession) {
            // Тест пройден - разрешаем повторную оплату
            return response()->json([
                'can_pay' => true,
                'message' => 'Вы можете пройти тест повторно'
            ]);
        } else {
            // Тест не пройден - блокируем оплату, предлагаем войти
            return response()->json([
                'can_pay' => false,
                'has_access' => true,
                'message' => 'У вас уже есть доступ к тесту. Войдите в систему для прохождения.',
                'login_url' => route('login.form')
            ]);
        }
    }

    /**
     * Создание платежа и генерация данных для LiqPay
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;
        $amount = 1; // Тестовая стоимость (было 999)

        // Проверяем доступ перед созданием платежа
        $user = User::where('email', $email)->first();
        
        if ($user && $user->has_access) {
            $completedSession = $user->quizSessions()
                ->whereNotNull('completed_at')
                ->exists();
            
            if (!$completedSession) {
                // Есть доступ, тест не пройден - не даём создать платёж
                return response()->json([
                    'error' => 'У вас уже есть доступ к тесту',
                    'login_url' => route('login.form')
                ], 400);
            }
            // Если тест пройден - разрешаем создать новый платёж (повторное прохождение)
        }

        // Создаем запись о платеже
        $payment = Payment::create([
            'email' => $email,
            'amount' => $amount,
            'status' => 'pending'
        ]);

        // Настройки LiqPay (добавьте в .env)
        $public_key = env('LIQPAY_PUBLIC_KEY');
        $private_key = env('LIQPAY_PRIVATE_KEY');

        // Определяем URL возврата в зависимости от авторизации
        $result_url = auth()->check() ? route('home') : route('payment.success');

        // Формируем данные для LiqPay
        $data = [
            'version' => 3,
            'public_key' => $public_key,
            'action' => 'pay',
            'amount' => $amount,
            'currency' => 'UAH',
            'description' => 'Профориентационное тестирование',
            'order_id' => $payment->id,
            'result_url' => $result_url,
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
                $completedSession = $user->quizSessions()
                    ->whereNotNull('completed_at')
                    ->exists();

                if ($completedSession) {
                    // Повторное прохождение - устанавливаем флаг can_retake
                    $user->update([
                        'has_access' => true,
                        'can_retake' => true
                    ]);
                    
                    // Отправляем письмо о возможности повторного прохождения
                    Mail::to($email)->send(new \App\Mail\RetakeAccessMail($user));
                } else {
                    // Первое прохождение - просто даём доступ
                    $user->update(['has_access' => true]);
                    
                    // Отправляем письмо об активации
                    Mail::to($email)->send(new \App\Mail\AccessActivatedMail($user));
                }
            }
        } elseif ($status === 'failure' || $status === 'error') {
            $payment->update(['status' => 'failed']);
        }

        return response()->json(['status' => 'ok']);
    }
}
