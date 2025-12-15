@extends('layouts.app')

@section('title', 'Результати тестування - Дизайн Освіти')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/results.css') }}">
@endsection

@section('content')
<div class="results-wrapper">
    <div class="results-header">
        <div class="logo">
            <img src="{{ asset('images/logo_education_design.svg') }}" alt="Education Design" class="logo-image">
        </div>
        <h1>Результати профорієнтаційного тестування</h1>
    </div>

    <div class="results-content">
        <div style="text-align: center; margin-bottom: 30px;">
            <a href="{{ route('home') }}" style="color: #667eea; text-decoration: none; font-weight: 600; font-size: 16px;">← Повернутися до кабінету</a>
        </div>
        
        <div class="completion-badge">
            <div class="badge-icon">✓</div>
            <h2>Вітаємо! Ви завершили тестування</h2>
            <p>Дата завершення: {{ $session->completed_at->format('d.m.Y H:i') }}</p>
        </div>

        @if($result)
        <div class="results-section">
            <h3>Ваш професійний профіль</h3>
            <div class="summary-text">
                {{ $result->summary }}
            </div>
        </div>

        @if(isset($result->recommendations['professional_types']))
        <div class="results-section recommendations">
            <h3>Рекомендовані професійні напрямки</h3>
            <div class="recommendation-list">
                @foreach($result->recommendations['professional_types'] as $type)
                <div class="recommendation-item">
                    <span class="recommendation-icon">🎯</span>
                    <span>{{ $type }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="results-actions">
            <a href="{{ route('home') }}" class="btn btn-primary">Повернутися до кабінету</a>
            <a href="{{ route('quiz.report.view', ['sessionId' => $session->id]) }}" class="btn btn-info">Переглянути повний звіт</a>
            <a href="{{ route('quiz.report.download', ['sessionId' => $session->id]) }}" class="btn btn-success">Завантажити PDF</a>
        </div>
        @endif
    </div>
</div>
@endsection
