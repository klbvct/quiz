@extends('layouts.app')

@section('title', 'Кабінет')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}?v=2025112301">
@endsection

@section('content')
<div class="home-wrapper">
    <div class="header">
        <div class="logo">
                <img src="{{ asset('images/logo_education_design.svg') }}" alt="Education Design" class="logo-image">
            <span class="logo-text">Дизайн Освіти</span>
        </div>
        <div class="user-info">
            <span>{{ Auth::user()->name }}</span>
            @if(Auth::user()->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="btn btn-admin">Адмін-панель</a>
            @endif
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-secondary">Вийти</button>
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
                    
                    $allCompletedSessions = \App\Models\QuizSession::where('user_id', Auth::id())
                        ->where('status', 'completed')
                        ->orderBy('completed_at', 'desc')
                        ->get();
                @endphp
                
                <div class="card {{ $completedSession && !Auth::user()->can_retake ? 'card-completed' : 'card-active' }}">
                    <div class="card-content">
                        <h3>
                            📝 Тестування
                            @if($completedSession && !Auth::user()->can_retake)
                                <span class="badge-completed">✓</span>
                            @endif
                        </h3>
                        <p>Дізнайтеся про свої професійні схильності та отримайте рекомендації щодо вибору кар'єри</p>
                        @if($completedSession && !Auth::user()->can_retake)
                            <p class="completion-info">
                                <small>Тест пройдено: {{ $completedSession->completed_at->format('d.m.Y о H:i') }}</small>
                            </p>
                        @endif
                    </div>
                    @if(!$completedSession || Auth::user()->can_retake)
                        <div class="card-actions">
                            <a href="{{ route('quiz.start') }}" class="btn-start">
                                @if($inProgressSession)
                                    Продовжити тестування
                                @else
                                    Почати тестування
                                @endif
                            </a>
                        </div>
                    @endif
                </div>
                
                <div class="card {{ (!$completedSession || Auth::user()->can_retake) ? 'card-disabled' : '' }}">
                    <div class="card-content">
                        <h3>📊 Результати тестування</h3>
                        <p>
                            @if($completedSession && !Auth::user()->can_retake)
                                Переглянь результати вашого останнього завершеного тестування
                                @if($allCompletedSessions->count() > 1)
                                    <br><small style="color: #6b7280;">Усього пройдено тестувань: {{ $allCompletedSessions->count() }}</small>
                                @endif
                            @else
                                Результати будуть доступні після завершення тестування
                            @endif
                        </p>
                    </div>
                    <div class="card-actions">
                        @if($completedSession && !Auth::user()->can_retake)
                            <a href="{{ route('quiz.results') }}" class="btn-start" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">Останні результати</a>
                            @if($allCompletedSessions->count() > 1)
                                <a href="{{ route('quiz.test-history') }}" class="btn-start" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); margin-top: 10px;">📋 Історія всіх тестувань</a>
                            @endif
                        @else
                            <span class="btn-start btn-disabled" style="background: #9ca3af; cursor: not-allowed;">Недоступно</span>
                        @endif
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-content">
                        <h3>👤 Профіль</h3>
                        <p>Змініть свої особисті дані, email або пароль</p>
                    </div>
                    <div class="card-actions">
                        <a href="{{ route('profile.edit') }}" class="btn-start" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">Редагувати профіль</a>
                    </div>
                </div>
            @else
                <div class="card" style="border-left: 4px solid #ef4444;">
                    <div class="card-content">
                        <h3>🔒 Доступ обмежено</h3>
                        <p>Для проходження профорієнтаційного тестування необхідно оплатити доступ</p>
                    </div>
                    <div class="card-actions">
                        <a href="{{ route('payment.page') }}" class="btn-start">Перейти до оплати</a>
                    </div>
                </div>
                
                <div class="card card-disabled">
                    <div class="card-content">
                        <h3>📊 Результати тестування</h3>
                        <p>Результати будуть доступні після завершення тестування</p>
                    </div>
                    <div class="card-actions">
                        <span class="btn-start btn-disabled" style="background: #9ca3af; cursor: not-allowed;">Недоступно</span>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-content">
                        <h3>👤 Профіль</h3>
                        <p>Змініть свої особисті дані, email або пароль</p>
                    </div>
                    <div class="card-actions">
                        <a href="{{ route('profile.edit') }}" class="btn-start" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">Редагувати профіль</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/home.js') }}"></script>
@endpush
