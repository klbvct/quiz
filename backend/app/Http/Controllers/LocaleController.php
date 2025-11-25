<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    /**
     * Изменить язык приложения
     */
    public function setLocale(Request $request, $locale)
    {
        // Проверяем, что язык поддерживается
        if (!in_array($locale, ['uk', 'ru'])) {
            abort(400);
        }
        
        // Сохраняем выбранный язык в сессии
        Session::put('locale', $locale);
        
        // Устанавливаем язык для текущего запроса
        App::setLocale($locale);
        
        // Перенаправляем обратно на предыдущую страницу
        return redirect()->back();
    }
}
