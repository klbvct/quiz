<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Админ-панель') - Тестирование</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <div class="admin-container">
        <!-- Боковое меню -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h2>Админ-панель</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="icon">📊</span>
                    <span>Главная</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <span class="icon">👥</span>
                    <span>Пользователи</span>
                </a>
                <a href="{{ route('admin.payments.index') }}" class="nav-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <span class="icon">💳</span>
                    <span>Платежи</span>
                </a>
                <a href="{{ route('home') }}" class="nav-item">
                    <span class="icon">🏠</span>
                    <span>Выйти из админки</span>
                </a>
            </nav>
            <div class="sidebar-footer">
                <div class="admin-info">
                    <strong>{{ auth()->user()->name }}</strong>
                    <small>{{ auth()->user()->email }}</small>
                </div>
            </div>
        </aside>

        <!-- Основной контент -->
        <main class="admin-main">
            <div class="admin-content">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Автоматическое скрытие уведомлений
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });
        });
    </script>
</body>
</html>
