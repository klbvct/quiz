@extends('layouts.admin')

@section('title', 'Панель управления')

@section('content')
<div class="dashboard-header">
    <h1>Панель управления</h1>
    <p class="subtitle">Общая статистика и активность</p>
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
    <!-- Последние пользователи -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2>Последние регистрации</h2>
            <a href="{{ route('admin.users.index') }}" class="btn-link">Все пользователи →</a>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Доступ</th>
                        <th>Дата регистрации</th>
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
                                    <span class="badge badge-success">Есть</span>
                                @else
                                    <span class="badge badge-warning">Нет</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Нет пользователей</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Последние завершенные тесты -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2>Последние завершенные тесты</h2>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Пользователь</th>
                        <th>Email</th>
                        <th>Дата завершения</th>
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
                            <td colspan="3" class="text-center">Нет завершенных тестов</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
