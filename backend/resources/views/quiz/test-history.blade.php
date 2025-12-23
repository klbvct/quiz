@extends('layouts.app')

@section('title', 'Історія тестувань')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}?v=2025112301">
    <link rel="stylesheet" href="{{ asset('css/test-history.css') }}?v=2025122201">
@endsection

@section('content')
<div class="home-wrapper">
    <div class="header">
        <div class="logo">
            <img src="{{ asset('images/logo_education_design.svg') }}" alt="Education Design" class="logo-image">
            <span class="logo-text">Дизайн Освіти</span>
        </div>
        <div class="user-info">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="user-avatar" style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: bold; color: white; border: 2px solid rgba(255,255,255,0.3);">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <span>{{ Auth::user()->name }}</span>
            </div>
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
        <div class="history-container">
            <div style="text-align: center;">
                <a href="{{ route('home') }}" class="back-link">← Повернутися до кабінету</a>
            </div>
    
    <div class="page-header">
        <h1>Історія тестувань</h1>
    </div>

    @if($completedSessions->count() > 0)
        <div class="stats-bar">
            <h2>Усі проходження тестування</h2>
            <div class="stats-badge">Всього: {{ $completedSessions->total() }}</div>
        </div>

        <div class="sessions-grid">
            @foreach($completedSessions as $index => $session)
                <div class="session-card">
                    <div class="session-header">
                        <div class="session-number">Тестування #{{ $completedSessions->total() - $completedSessions->firstItem() - $index + 1 }}</div>
                        <!-- <div class="session-id">ID: {{ $session->id }}</div> -->
                    </div>
                    
                    <div class="session-info">
                        <div class="info-item">
                            <div class="info-label">Дата початку</div>
                            <div class="info-value">{{ $session->created_at->format('d.m.Y H:i') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Дата завершення</div>
                            <div class="info-value">{{ $session->completed_at->format('d.m.Y H:i') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Тривалість</div>
                            <div class="info-value">{{ $session->created_at->diffForHumans($session->completed_at, true) }}</div>
                        </div>
                    </div>
                    
                    <div class="session-actions">
                        @if($session->result)
                            <a href="{{ route('quiz.report.download', $session->id) }}" class="btn btn-primary" target="_blank">
                                📄 Переглянути результат / Скачати PDF
                            </a>
                        @else
                            <span style="color: #9ca3af;">Результати недоступні</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($completedSessions->hasPages())
            <div class="pagination-wrapper">
                {{ $completedSessions->links() }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-state-icon">📋</div>
            <h2>Немає завершених тестувань</h2>
            <p>Ви ще не проходили професійне тестування</p>
            <a href="{{ route('quiz.start') }}" class="btn btn-primary">Почати тестування</a>
        </div>
    @endif
        </div>
    </div>
</div>
@endsection
