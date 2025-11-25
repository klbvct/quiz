<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Получаем язык из сессии, если нет - используем украинский по умолчанию
        $locale = Session::get('locale', config('app.locale', 'uk'));
        
        // Проверяем, что язык поддерживается
        if (!in_array($locale, ['uk', 'ru'])) {
            $locale = 'uk';
        }
        
        // Устанавливаем язык приложения
        App::setLocale($locale);
        
        return $next($request);
    }
}
