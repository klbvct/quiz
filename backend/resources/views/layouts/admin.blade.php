<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Адмін-панель') - Тестування</title>
    
    <!-- Meta tags -->
    <meta name="theme-color" content="#0c68f5">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo_education_design.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <div class="admin-container">
        <!-- Бокове меню -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h2>Адмін-панель</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="icon">📊</span>
                    <span>Головна</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <span class="icon">👥</span>
                    <span>Користувачі</span>
                </a>
                <a href="{{ route('admin.payments.index') }}" class="nav-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <span class="icon">💳</span>
                    <span>Платежі</span>
                </a>
                <a href="{{ route('home', ['force' => 1]) }}" class="nav-item">
                    <span class="icon">🏠</span>
                    <span>Мій кабінет</span>
                </a>
            </nav>
            <div class="sidebar-footer">
                <div class="admin-info">
                    <strong>{{ auth()->user()->name }}</strong>
                    <small>{{ auth()->user()->email }}</small>
                </div>
            </div>
        </aside>

        <!-- Основний контент -->
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
        // Автоматичне приховування сповіщень
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
