<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\QuizSession;
use App\Models\QuizResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Отобразить главную панель администратора
     */
    public function dashboard()
    {
        // Статистика
        $stats = [
            'total_users' => User::count(),
            'users_with_access' => User::where('has_access', true)->count(),
            'completed_tests' => QuizSession::where('completed_at', '!=', null)->count(),
            'in_progress_tests' => QuizSession::whereNull('completed_at')->count(),
            'total_revenue' => DB::table('payments')
                ->where('status', 'completed')
                ->sum('amount'),
        ];

        // Последние зарегистрированные пользователи
        $recent_users = User::orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Последние завершенные тесты
        $recent_completions = QuizSession::with('user')
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'recent_completions'));
    }
}
