@extends('layouts.app')

@section('title', 'Результати тестування - Quiz Education')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/results.css') }}">
@endsection

@section('content')
<div class="results-wrapper">
    <div class="results-header">
        <div class="logo">
            <a href="{{ route('home') }}">
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
            </a>
        </div>
        <h1>Результати профорієнтаційного тестування</h1>
    </div>

    <div class="results-content">
        <div style="text-align: center; margin-bottom: 30px;">
            <a href="{{ route('home') }}" style="color: #667eea; text-decoration: none; font-weight: 600; font-size: 16px;">← Вернуться в кабинет</a>
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

        @if(isset($result->module_scores['module1']))
        <div class="results-section">
            <h3>Типи професійної спрямованості (ДДО)</h3>
            <div class="scores-chart">
                @php
                    $ddo = $result->module_scores['module1'];
                    $maxScore = max($ddo);
                    $typeNames = [
                        'nature' => 'Людина-Природа',
                        'technic' => 'Людина-Техніка',
                        'human' => 'Людина-Людина',
                        'sign' => 'Людина-Знакова система',
                        'art' => 'Людина-Художній образ'
                    ];
                @endphp
                @foreach($ddo as $type => $score)
                <div class="score-bar">
                    <div class="score-label">{{ $typeNames[$type] }}</div>
                    <div class="bar-container">
                        <div class="bar-fill" style="width: {{ ($score / 8) * 100 }}%"></div>
                    </div>
                    <div class="score-value">{{ $score }}/8</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

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
            <button onclick="window.print()" class="btn btn-secondary">Роздрукувати результати</button>
        </div>
        @endif
    </div>
</div>
@endsection
