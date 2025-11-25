<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Отобразить список всех платежей
     */
    public function index(Request $request)
    {
        $query = Payment::with('user');

        // Поиск по имени или email пользователя
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Фильтр по статусу
        if ($request->has('status_filter') && $request->status_filter !== '') {
            $query->where('status', $request->status_filter);
        }

        // Фильтр по дате
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Статистика с учетом фильтров
        $statsQuery = Payment::query();
        
        // Применяем те же фильтры к статистике
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $statsQuery->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('date_from') && $request->date_from) {
            $statsQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $statsQuery->whereDate('created_at', '<=', $request->date_to);
        }

        $stats = [
            'total_payments' => (clone $statsQuery)->count(),
            'completed_payments' => (clone $statsQuery)->where('status', 'completed')->count(),
            'pending_payments' => (clone $statsQuery)->where('status', 'pending')->count(),
            'failed_payments' => (clone $statsQuery)->where('status', 'failed')->count(),
            'total_revenue' => (clone $statsQuery)->where('status', 'completed')->sum('amount'),
            'revenue_today' => Payment::where('status', 'completed')
                ->whereDate('created_at', today())
                ->sum('amount'),
            'revenue_month' => Payment::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    /**
     * Показать детали платежа
     */
    public function show($id)
    {
        $payment = Payment::with('user')->findOrFail($id);
        
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Обновить статус платежа вручную
     */
    public function updateStatus(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        
        $validated = $request->validate([
            'status' => ['required', 'in:pending,completed,failed'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);
        
        $oldStatus = $payment->status;
        $payment->status = $validated['status'];
        
        // Если платеж завершен, выдаем доступ
        if ($validated['status'] === 'completed' && $oldStatus !== 'completed') {
            $user = User::find($payment->user_id);
            if ($user) {
                $user->has_access = true;
                $user->save();
            }
        }
        
        $payment->save();
        
        return redirect()->route('admin.payments.show', $id)
            ->with('success', 'Статус платежа обновлен');
    }

    /**
     * Статистика платежей
     */
    public function statistics(Request $request)
    {
        // Получение дат из запроса или установка значений по умолчанию
        $dateFrom = $request->input('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));

        // Платежи по дням за выбранный период
        $dailyRevenue = Payment::where('status', 'completed')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Платежи по месяцам за последний год
        $monthlyRevenue = Payment::where('status', 'completed')
            ->where('created_at', '>=', now()->subYear())
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Топ пользователей по платежам
        $topUsers = Payment::with('user')
            ->where('status', 'completed')
            ->select('user_id', DB::raw('COUNT(*) as payments_count'), DB::raw('SUM(amount) as total_spent'))
            ->groupBy('user_id')
            ->orderByDesc('total_spent')
            ->take(10)
            ->get();

        return view('admin.payments.statistics', compact('dailyRevenue', 'monthlyRevenue', 'topUsers'));
    }
}
