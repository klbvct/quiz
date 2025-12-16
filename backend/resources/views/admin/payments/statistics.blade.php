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
            <!-- Лінійний графік -->
            <div class="line-chart-wrapper">
                <svg class="line-chart" viewBox="0 0 1000 300" preserveAspectRatio="none">
                    @php
                        $maxCount = $dailyRevenue->max('count') ?: 1;
                        $count = $dailyRevenue->count();
                        $points = [];
                        foreach($dailyRevenue as $index => $day) {
                            $x = ($index / max($count - 1, 1)) * 1000;
                            $y = 300 - (($day->count / $maxCount) * 280);
                            $points[] = "$x,$y";
                        }
                        $pathData = 'M ' . implode(' L ', $points);
                    @endphp
                    
                    <!-- Область під лінією -->
                    <defs>
                        <linearGradient id="lineGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" style="stop-color:#0c68f5;stop-opacity:0.3" />
                            <stop offset="100%" style="stop-color:#0c68f5;stop-opacity:0.05" />
                        </linearGradient>
                    </defs>
                    
                    <path d="{{ $pathData }} L 1000,300 L 0,300 Z" fill="url(#lineGradient)" />
                    <path d="{{ $pathData }}" stroke="#0c68f5" stroke-width="3" fill="none" />
                    
                    <!-- Точки на графіку -->
                    @foreach($dailyRevenue as $index => $day)
                        @php
                            $x = ($index / max($count - 1, 1)) * 1000;
                            $y = 300 - (($day->count / $maxCount) * 280);
                        @endphp
                        <circle cx="{{ $x }}" cy="{{ $y }}" r="5" fill="#0c68f5" class="chart-point">
                            <title>{{ \Carbon\Carbon::parse($day->date)->format('d.m.Y') }}: {{ $day->count }} платежів ({{ number_format($day->total, 0, ',', ' ') }} ₴)</title>
                        </circle>
                    @endforeach
                </svg>
                
                <!-- Мітки дат під графіком -->
                <div class="chart-labels">
                    @foreach($dailyRevenue as $index => $day)
                        @if($index % max(floor($count / 10), 1) == 0 || $index == $count - 1)
                            <div class="chart-label" style="left: {{ ($index / max($count - 1, 1)) * 100 }}%">
                                <div class="label-date">{{ \Carbon\Carbon::parse($day->date)->format('d.m') }}</div>
                                <div class="label-count">{{ $day->count }}</div>
                            </div>
                        @endif
                    @endforeach
                </div>
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
                            @if($payment->user)
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
                            @endif
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

.line-chart-wrapper {
    padding: 30px 20px 60px;
    background: linear-gradient(to bottom, #f9fafb 0%, white 100%);
    border-radius: 8px;
    margin: 20px 0;
    position: relative;
}

.line-chart {
    width: 100%;
    height: 300px;
    display: block;
}

.chart-point {
    cursor: pointer;
    transition: r 0.2s ease;
}

.chart-point:hover {
    r: 8;
    fill: #764ba2;
}

.chart-labels {
    display: flex;
    position: relative;
    height: 40px;
    margin-top: 15px;
}

.chart-label {
    position: absolute;
    transform: translateX(-50%);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.label-date {
    font-size: 12px;
    font-weight: 600;
    color: #374151;
}

.label-count {
    font-size: 11px;
    color: #0c68f5;
    font-weight: 600;
}
</style>
@endsection
