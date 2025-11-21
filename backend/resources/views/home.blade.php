@extends('layouts.app')

@section('title', 'Главная - Quiz Education')

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
            <div class="card">
                <h3>📝 Мои квизы</h3>
                <p>Создавайте и управляйте своими квизами</p>
                <p style="margin-top: 15px;"><strong>0</strong> квизов создано</p>
            </div>
            <div class="card">
                <h3>📊 Статистика</h3>
                <p>Отслеживайте свои результаты и прогресс</p>
                <p style="margin-top: 15px;"><strong>0</strong> квизов пройдено</p>
            </div>
            <div class="card">
                <h3>👤 Профиль</h3>
                <p>Управляйте своей учетной записью</p>
                <p style="margin-top: 15px; font-size: 13px; color: #9ca3af;">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>
</div>

<script>
    document.body.classList.add('home-page');
</script>
@endsection
