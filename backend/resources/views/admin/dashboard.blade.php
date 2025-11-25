@extends('layouts.admin')

@section('title', 'Панель керування')

@section('content')
<div class="dashboard-header">
    <h1>Панель керування</h1>
    <p class="subtitle">Загальна статистика та активність</p>
</div>

<!-- Карточки статистики -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-content">
            <div class="stat-value">{{ $stats['total_users'] }}</div>
            <div class="stat-label">Всего пользователей</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-content">
            <div class="stat-value">{{ $stats['users_with_access'] }}</div>
            <div class="stat-label">С доступом</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">📝</div>
        <div class="stat-content">
            <div class="stat-value">{{ $stats['completed_tests'] }}</div>
            <div class="stat-label">Пройдено тестов</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">⏳</div>
        <div class="stat-content">
            <div class="stat-value">{{ $stats['in_progress_tests'] }}</div>
            <div class="stat-label">В процессе</div>
        </div>
    </div>

    <div class="stat-card stat-card-highlight">
        <div class="stat-icon">💰</div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} ₴</div>
            <div class="stat-label">Общая выручка</div>
        </div>
    </div>
</div>

<!-- Два столбца: последние пользователи и завершенные тесты -->
<div class="dashboard-grid">
    <!-- Останні користувачі -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2>Останні реєстрації</h2>
            <a href="{{ route('admin.users.index') }}" class="btn-link">Всі користувачі →</a>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Ім'я</th>
                        <th>Email</th>
                        <th>Доступ</th>
                        <th>Дата реєстрації</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_users as $user)
                        <tr>
                            <td>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="user-link">
                                    {{ $user->name }}
                                </a>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->has_access)
                                    <span class="badge badge-success">Є</span>
                                @else
                                    <span class="badge badge-warning">Немає</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Немає користувачів</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Последние завершенные тесты -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2>Останні завершені тести</h2>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Користувач</th>
                        <th>Email</th>
                        <th>Дата завершення</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_completions as $session)
                        <tr>
                            <td>
                                <a href="{{ route('admin.users.edit', $session->user_id) }}" class="user-link">
                                    {{ $session->user->name }}
                                </a>
                            </td>
                            <td>{{ $session->user->email }}</td>
                            <td>{{ $session->completed_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Немає завершених тестів</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
