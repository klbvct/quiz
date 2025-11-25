@extends('layouts.app')

@section('title', 'Редагування профілю - Quiz Education')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}?v=2025112401">
    <style>
        .profile-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .profile-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
            margin-bottom: 30px;
        }
        
        .profile-card h2 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 24px;
        }
        
        .profile-card h3 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 20px;
            padding-top: 30px;
            border-top: 2px solid #e5e7eb;
            margin-top: 40px;
        }
        
        .profile-card p {
            color: #6b7280;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            color: #374151;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.2s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .error {
            color: #ef4444;
            font-size: 13px;
            margin-top: 5px;
            display: block;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
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
                <button type="submit" class="btn btn-secondary">Вийти</button>
            </form>
        </div>
    </div>
    
    <div class="profile-content">
        <div style="text-align: center; margin-bottom: 20px;">
            <a href="{{ route('home') }}" style="color: #667eea; text-decoration: none; font-weight: 600;">← Повернутися до кабінету</a>
        </div>
        
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="profile-card">
            <h2>Редагування профілю</h2>
            <p>Оновіть свої особисті дані</p>

            <!-- Форма редактирования профиля -->
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="name">Ім'я та прізвище</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus>
                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="birthdate">Дата народження</label>
                    <input id="birthdate" type="date" name="birthdate" value="{{ old('birthdate', $user->birthdate) }}">
                    @error('birthdate')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Зберегти зміни</button>
            </form>

            <!-- Форма смены пароля -->
            <h3>Змінити пароль</h3>

            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="current_password">Поточний пароль</label>
                    <input id="current_password" type="password" name="current_password" required>
                    @error('current_password')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Новий пароль</label>
                    <input id="password" type="password" name="password" required>
                    @error('password')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Підтвердіть новий пароль</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn btn-primary">Змінити пароль</button>
            </form>
        </div>
    </div>
</div>
@endsection
