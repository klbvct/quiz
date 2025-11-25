<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Обработка регистрации
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        // Редирект админов в админ-панель (на случай если админ регистрируется)
        if ($user->is_admin) {
            return redirect()->route('admin.dashboard')->with('success', 'Регистрация успешна!');
        }

        return redirect()->route('home')->with('success', 'Регистрация успешна!');
    }

    /**
     * Обработка входа
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Второй параметр - это значение чекбокса "Запомнить меня"
        // Если true, Laravel создаст долгосрочный cookie (по умолчанию 5 лет)
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Редирект админов в админ-панель
            if (Auth::user()->is_admin) {
                return redirect()->route('admin.dashboard')->with('success', 'Добро пожаловать!');
            }
            
            return redirect()->intended('/home')->with('success', 'Добро пожаловать!');
        }

        return back()->withErrors([
            'email' => 'Неверные учетные данные.',
        ])->onlyInput('email');
    }

    /**
     * Выход из системы
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }

    /**
     * Отправка ссылки для восстановления пароля
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => 'Ссылка для восстановления пароля отправлена на ваш email! Проверьте папку "Спам", если письмо не пришло.'])
            : back()->withErrors(['email' => 'Не удалось отправить ссылку. Проверьте правильность email.']);
    }

    /**
     * Сброс пароля
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
                
                // Автоматически входим после сброса пароля
                Auth::login($user);
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('home')->with('success', 'Пароль успешно изменен!')
            : back()->withErrors(['email' => 'Не удалось сбросить пароль. Попробуйте еще раз.']);
    }
}
