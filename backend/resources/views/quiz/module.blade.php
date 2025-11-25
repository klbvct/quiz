@extends('layouts.app')

@section('title', $moduleData['title'] . ' - Quiz Education')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
@endsection

@section('content')
<div class="quiz-wrapper">
    <div class="quiz-header">
        <div class="logo">
            <a href="{{ route('home') }}">
                <svg width="40" height="40" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
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
        <div class="progress-bar">
            <div class="progress-fill" style="width: {{ ($module / 8) * 100 }}%"></div>
        </div>
        <div class="module-indicator">Модуль {{ $module }} з 8</div>
        <a href="{{ route('home') }}" class="btn-exit">Зберегти і вийти</a>
    </div>

    <div class="quiz-content">
        <div class="module-title">{{ $moduleData['title'] }}</div>
        
        <div class="instruction">
            {!! nl2br(e($moduleData['instruction'])) !!}
        </div>

        @if(isset($moduleData['subtitle']))
        <div class="subtitle">{{ $moduleData['subtitle'] }}</div>
        @endif

        <form method="POST" action="{{ route('quiz.save', ['module' => $module]) }}" id="quiz-form">
            @csrf

            @if($moduleData['type'] == 'choice_ab')
                @include('quiz.types.choice_ab', ['questions' => $moduleData['questions'], 'savedAnswers' => $savedAnswers])
            @elseif($moduleData['type'] == 'scale_5')
                @include('quiz.types.scale_5', ['questions' => $moduleData['questions'], 'savedAnswers' => $savedAnswers, 'scale' => $moduleData['scale']])
            @elseif($moduleData['type'] == 'choice_abc')
                @include('quiz.types.choice_abc', ['questions' => $moduleData['questions'], 'savedAnswers' => $savedAnswers])
            @elseif($moduleData['type'] == 'ranking')
                @include('quiz.types.ranking', ['values' => $moduleData['values'], 'savedAnswers' => $savedAnswers, 'maxRank' => $moduleData['max_rank']])
            @elseif($moduleData['type'] == 'scale_1_5')
                @include('quiz.types.scale_1_5', ['questions' => $moduleData['questions'], 'savedAnswers' => $savedAnswers])
            @elseif($moduleData['type'] == 'scale_schwartz')
                @include('quiz.types.scale_schwartz', ['values' => $moduleData['values'], 'savedAnswers' => $savedAnswers, 'scale' => $moduleData['scale']])
            @elseif($moduleData['type'] == 'choice_professions')
                @include('quiz.types.choice_professions', ['questions' => $moduleData['questions'], 'savedAnswers' => $savedAnswers])
            @elseif($moduleData['type'] == 'modality')
                @include('quiz.types.modality', ['questions' => $moduleData['questions'], 'savedAnswers' => $savedAnswers])
            @endif

            <div class="form-actions">
                @if($module > 1)
                    <a href="{{ route('quiz.module', ['module' => $module - 1]) }}" class="btn btn-secondary">Назад</a>
                @endif
                <button type="submit" class="btn btn-primary">
                    {{ $module == 8 ? 'Завершити тест' : 'Далі' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/quiz.js') }}"></script>
@endpush
