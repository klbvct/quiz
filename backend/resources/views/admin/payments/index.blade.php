@extends('layouts.admin')

@section('title', 'Управление платежами')

@section('content')
<div class="page-header">
    <div class="header-with-actions">
        <h1>Управление платежами</h1>
        <a href="{{ route('admin.payments.statistics') }}" class="btn btn-secondary">📊 Статистика</a>
    </div>
</div>

<!-- Статистика -->
<div class="stats-grid stats-grid-small">
    <div class="stat-card">
        <div class="stat-icon">💳</div>
        <div class="stat-content">
            <div class="stat-value">{{ $stats['total_payments'] }}</div>
            <div class="stat-label">Всего платежей</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-content">
            <div class="stat-value">{{ $stats['completed_payments'] }}</div>
            <div class="stat-label">Завершено</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">⏳</div>
        <div class="stat-content">
            <div class="stat-value">{{ $stats['pending_payments'] }}</div>
            <div class="stat-label">В ожидании</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">❌</div>
        <div class="stat-content">
            <div class="stat-value">{{ $stats['failed_payments'] }}</div>
            <div class="stat-label">Отклонено</div>
        </div>
    </div>

    <div class="stat-card stat-card-highlight">
        <div class="stat-icon">💰</div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} ₴</div>
            <div class="stat-label">Общая выручка</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">📅</div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($stats['revenue_today'], 0, ',', ' ') }} ₴</div>
            <div class="stat-label">Сегодня</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">📆</div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($stats['revenue_month'], 0, ',', ' ') }} ₴</div>
            <div class="stat-label">За месяц</div>
        </div>
    </div>
</div>

<!-- Фільтри -->
<div class="filters-section">
    <form method="GET" action="{{ route('admin.payments.index') }}" class="filters-form">
        <div class="filter-group">
            <input type="text" 
                   name="search" 
                   placeholder="Пошук за користувачем" 
                   value="{{ request('search') }}"
                   class="search-input">
        </div>

        <div class="filter-group">
            <select name="status_filter" class="filter-select">
                <option value="">Всі статуси</option>
                <option value="pending" {{ request('status_filter') === 'pending' ? 'selected' : '' }}>В очікуванні</option>
                <option value="completed" {{ request('status_filter') === 'completed' ? 'selected' : '' }}>Завершено</option>
                <option value="failed" {{ request('status_filter') === 'failed' ? 'selected' : '' }}>Відхилено</option>
            </select>
        </div>

        <div class="filter-group">
            <input type="date" 
                   name="date_from" 
                   value="{{ request('date_from') }}"
                   class="filter-select"
                   placeholder="Дата від">
        </div>

        <div class="filter-group">
            <input type="date" 
                   name="date_to" 
                   value="{{ request('date_to') }}"
                   class="filter-select"
                   placeholder="Дата до">
        </div>

        <button type="submit" class="btn btn-primary">Застосувати</button>
        @if(request()->hasAny(['search', 'status_filter', 'date_from', 'date_to']))
            <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">Скинути</a>
        @endif
    </form>
</div>

<!-- Таблиця платежів -->
<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Користувач</th>
                <th>Email</th>
                <th>Сума</th>
                <th>Статус</th>
                <th>Провайдер</th>
                <th>Дата створення</th>
                <th>Дії</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
                <tr>
                    <td>{{ $payment->id }}</td>
                    <td>
                        @if($payment->user)
                            <a href="{{ route('admin.users.edit', $payment->user_id) }}" class="user-link">
                                {{ $payment->user->name }}
                            </a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>{{ $payment->user ? $payment->user->email : $payment->email }}</td>
                    <td><strong>{{ number_format($payment->amount, 0, ',', ' ') }} ₴</strong></td>
                    <td>
                        @if($payment->status === 'completed')
                            <span class="badge badge-success">Завершено</span>
                        @elseif($payment->status === 'pending')
                            <span class="badge badge-warning">В очікуванні</span>
                        @else
                            <span class="badge badge-error">Відхилено</span>
                        @endif
                    </td>
                    <td>{{ $payment->payment_provider ?? 'LiqPay' }}</td>
                    <td>{{ $payment->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn-action btn-view" title="Детальніше">
                            👁️
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Платежів не знайдено</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Пагінація -->
@if($payments->hasPages())
    <div class="pagination-container">
        {{ $payments->links('pagination::bootstrap-4') }}
    </div>
@endif
@endsection
