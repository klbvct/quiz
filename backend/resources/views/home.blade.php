@extends('layouts.app')

@section('title', 'Главная - Quiz Education')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}?v=2025112301">
@endsection

@section('content')
<div class="home-wrapper">
    <div class="header">
        <div class="logo">
            <svg width="50" height="50" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
                <circle cx="25" cy="25" r="25" fill="url(#gradient)"/>
                <text x="25" y="32" font-family="Arial, sans-serif" font-size="20" font-weight="bold" fill="white" text-anchor="middle">Q</text>
                <defs>
                    <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#667eea;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" />
                    </linearGradient>
                </defs>
            </svg>
            <span class="logo-text">Дизайн Образования</span>
        </div>
        <div class="user-info">
            <span>{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-secondary">Выйти</button>
            </form>
        </div>
    </div>
    
    <div class="content">
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="dashboard">
            @if(Auth::user()->has_access)
                @php
                    $completedSession = \App\Models\QuizSession::where('user_id', Auth::id())
                        ->where('status', 'completed')
                        ->latest()
                        ->first();
                    
                    $inProgressSession = \App\Models\QuizSession::where('user_id', Auth::id())
                        ->where('status', 'in_progress')
                        ->first();
                @endphp
                
                <div class="card">
                    <h3>📝 Профориентационное тестирование</h3>
                    <p>Узнайте свои профессиональные склонности и получите рекомендации по выбору карьеры</p>
                    <p style="margin-top: 20px;">
                        <a href="{{ route('quiz.start') }}" class="btn-start">
                            @if($inProgressSession)
                                Продолжить тестирование
                            @elseif($completedSession)
                                Пройти тест заново
                            @else
                                Начать тестирование
                            @endif
                        </a>
                    </p>
                </div>
                
                <div class="card {{ !$completedSession ? 'card-disabled' : '' }}">
                    <h3>📊 Результаты тестирования</h3>
                    <p>
                        @if($completedSession)
                            Посмотрите результаты вашего последнего завершенного тестирования
                        @else
                            Результаты будут доступны после завершения тестирования
                        @endif
                    </p>
                    <p style="margin-top: 20px;">
                        @if($completedSession)
                            <a href="{{ route('quiz.results') }}" class="btn-start" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">Посмотреть результаты</a>
                        @else
                            <span class="btn-start btn-disabled" style="background: #9ca3af; cursor: not-allowed;">Недоступно</span>
                        @endif
                    </p>
                </div>
                
                <div class="card">
                    <h3>👤 Профиль</h3>
                    <p>Измените свои личные данные, email или пароль</p>
                    <p style="margin-top: 20px;">
                        <a href="{{ route('profile.edit') }}" class="btn-start" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">Редактировать профиль</a>
                    </p>
                </div>
            @else
                <div class="card" style="border-left: 4px solid #ef4444;">
                    <h3>🔒 Доступ ограничен</h3>
                    <p>Для прохождения профориентационного тестирования необходимо оплатить доступ</p>
                    <p style="margin-top: 20px;">
                        <a href="{{ route('payment.page') }}" class="btn-start">Перейти к оплате</a>
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/home.js') }}"></script>
@endpush
