@extends('layouts.admin')

@section('title', 'Статистика платежів')

@section('content')
<div class="page-header">
    <div class="header-with-back">
        <a href="{{ route('admin.payments.index') }}" class="btn-back">← Назад до платежів</a>
        <h1>Статистика платежів</h1>
    </div>
</div>

<!-- Фільтр за періодом -->
<div class="filters-section">
    <form method="GET" action="{{ route('admin.payments.statistics') }}" class="filters-form">
        <div class="filter-group">
            <label for="date_from">Період з:</label>
            <input type="date" 
                   name="date_from" 
                   id="date_from"
                   value="{{ request('date_from', now()->subDays(30)->format('Y-m-d')) }}"
                   class="filter-select">
        </div>

        <div class="filter-group">
            <label for="date_to">по:</label>
            <input type="date" 
                   name="date_to" 
                   id="date_to"
                   value="{{ request('date_to', now()->format('Y-m-d')) }}"
                   class="filter-select">
        </div>

        <button type="submit" class="btn btn-primary">Застосувати</button>
        
        <div class="quick-filters">
            <a href="{{ route('admin.payments.statistics', ['date_from' => now()->subDays(7)->format('Y-m-d'), 'date_to' => now()->format('Y-m-d')]) }}" 
               class="btn btn-secondary btn-sm">7 днів</a>
            <a href="{{ route('admin.payments.statistics', ['date_from' => now()->subDays(30)->format('Y-m-d'), 'date_to' => now()->format('Y-m-d')]) }}" 
               class="btn btn-secondary btn-sm">30 днів</a>
            <a href="{{ route('admin.payments.statistics', ['date_from' => now()->subDays(90)->format('Y-m-d'), 'date_to' => now()->format('Y-m-d')]) }}" 
               class="btn btn-secondary btn-sm">3 місяці</a>
            <a href="{{ route('admin.payments.statistics', ['date_from' => now()->subYear()->format('Y-m-d'), 'date_to' => now()->format('Y-m-d')]) }}" 
               class="btn btn-secondary btn-sm">Рік</a>
        </div>
    </form>
</div>

<!-- Графіки та статистика -->
<div class="statistics-grid">
    <!-- Графік за обраний період -->
    <div class="section-card full-width">
        <h2>Платежі за обраний період</h2>
        <p class="text-muted">
            З {{ request('date_from', now()->subDays(30)->format('Y-m-d')) }} 
            по {{ request('date_to', now()->format('Y-m-d')) }}
        </p>
        
        @if($dailyRevenue->count() > 0)
            <!-- Графік-діаграма -->
            <div class="bar-chart">
                @php
                    $maxAmount = $dailyRevenue->max('total');
                @endphp
                
                @foreach($dailyRevenue as $day)
                    <div class="bar-item">
                        <div class="bar-container">
                            <div class="bar-fill" 
                                 style="height: {{ $maxAmount > 0 ? ($day->total / $maxAmount * 100) : 0 }}%"
                                 title="{{ number_format($day->total, 0, ',', ' ') }} ₴">
                                <span class="bar-value">{{ number_format($day->total, 0, ',', ' ') }}</span>
                            </div>
                        </div>
                        <div class="bar-label">
                            <div class="bar-date">{{ \Carbon\Carbon::parse($day->date)->format('d.m') }}</div>
                            <div class="bar-count">{{ $day->count }} шт</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="chart-container">
                <table class="stats-table">
                    <thead>
                        <tr>
                            <th>Дата</th>
                            <th>Кількість</th>
                            <th>Сума</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dailyRevenue as $day)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($day->date)->format('d.m.Y') }}</td>
                                <td>{{ $day->count }}</td>
                                <td><strong>{{ number_format($day->total, 0, ',', ' ') }} ₴</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><strong>Всього:</strong></td>
                            <td><strong>{{ $dailyRevenue->sum('count') }}</strong></td>
                            <td><strong>{{ number_format($dailyRevenue->sum('total'), 0, ',', ' ') }} ₴</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <p class="text-muted">Немає даних за останні 30 днів</p>
        @endif
    </div>

    <!-- Графік за місяцями -->
    <div class="section-card full-width">
        <h2>Платежі за місяцями (останній рік)</h2>
        
        @if($monthlyRevenue->count() > 0)
            <div class="chart-container">
                <table class="stats-table">
                    <thead>
                        <tr>
                            <th>Місяць</th>
                            <th>Кількість</th>
                            <th>Сума</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthlyRevenue as $month)
                            <tr>
                                <td>{{ \Carbon\Carbon::create($month->year, $month->month)->format('F Y') }}</td>
                                <td>{{ $month->count }}</td>
                                <td><strong>{{ number_format($month->total, 0, ',', ' ') }} ₴</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><strong>Всього:</strong></td>
                            <td><strong>{{ $monthlyRevenue->sum('count') }}</strong></td>
                            <td><strong>{{ number_format($monthlyRevenue->sum('total'), 0, ',', ' ') }} ₴</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <p class="text-muted">Немає даних за останній рік</p>
        @endif
    </div>

    <!-- Топ користувачів -->
    <div class="section-card full-width">
        <h2>Топ-10 користувачів за платежами</h2>
        
        @if($topUsers->count() > 0)
            <div class="chart-container">
                <table class="stats-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Користувач</th>
                            <th>Email</th>
                            <th>Кількість платежів</th>
                            <th>Загальна сума</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topUsers as $index => $payment)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $payment->user_id) }}" class="user-link">
                                        {{ $payment->user->name }}
                                    </a>
                                </td>
                                <td>{{ $payment->user->email }}</td>
                                <td>{{ $payment->payments_count }}</td>
                                <td><strong>{{ number_format($payment->total_spent, 0, ',', ' ') }} ₴</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"><strong>Всього:</strong></td>
                            <td><strong>{{ $topUsers->sum('payments_count') }}</strong></td>
                            <td><strong>{{ number_format($topUsers->sum('total_spent'), 0, ',', ' ') }} ₴</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <p class="text-muted">Немає даних про користувачів</p>
        @endif
    </div>
</div>

<style>
.statistics-grid {
    display: grid;
    gap: 25px;
}

.full-width {
    grid-column: 1 / -1;
}

.chart-container {
    overflow-x: auto;
}

.stats-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.stats-table th,
.stats-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.stats-table thead th {
    background: #f9fafb;
    font-weight: 600;
    color: #374151;
}

.stats-table tbody tr:hover {
    background: #f9fafb;
}

.stats-table tfoot {
    background: #f3f4f6;
    font-weight: 600;
}

.stats-table tfoot td {
    border-top: 2px solid #d1d5db;
}

.filters-section {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-bottom: 25px;
}

.filters-form {
    display: flex;
    align-items: flex-end;
    gap: 15px;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.filter-group label {
    font-size: 14px;
    color: #6b7280;
    font-weight: 500;
}

.filter-select {
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    min-width: 150px;
}

.quick-filters {
    display: flex;
    gap: 8px;
    margin-left: auto;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 13px;
}

.text-muted {
    color: #6b7280;
    font-size: 14px;
    margin-top: 5px;
}

.bar-chart {
    display: flex;
    align-items: flex-end;
    gap: 8px;
    padding: 40px 20px 20px;
    background: linear-gradient(to top, #f9fafb 0%, white 100%);
    border-radius: 8px;
    margin: 20px 0;
    overflow-x: auto;
    min-height: 300px;
}

.bar-item {
    flex: 1;
    min-width: 40px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.bar-container {
    width: 100%;
    height: 250px;
    display: flex;
    align-items: flex-end;
    position: relative;
}

.bar-fill {
    width: 100%;
    background: linear-gradient(to top, #3b82f6, #60a5fa);
    border-radius: 6px 6px 0 0;
    transition: all 0.3s ease;
    position: relative;
    min-height: 5px;
    cursor: pointer;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding-top: 8px;
}

.bar-fill:hover {
    background: linear-gradient(to top, #2563eb, #3b82f6);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
}

.bar-value {
    font-size: 11px;
    font-weight: 600;
    color: white;
    writing-mode: vertical-rl;
    transform: rotate(180deg);
    white-space: nowrap;
}

.bar-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
}

.bar-date {
    font-size: 12px;
    font-weight: 600;
    color: #374151;
}

.bar-count {
    font-size: 11px;
    color: #6b7280;
}
</style>
@endsection
