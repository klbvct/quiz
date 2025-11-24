@extends('layouts.app')

@section('title', 'Редактирование профиля - Quiz Education')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v=2025112401">
@endsection

@section('content')
<div class="auth-wrapper">
    <div class="auth-container">
        <div class="auth-header">
            <h2>Редактирование профиля</h2>
            <p>Обновите свои личные данные</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Форма редактирования профиля -->
        <form method="POST" action="{{ route('profile.update') }}" class="auth-form">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="name">Имя</label>
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

            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        </form>

        <!-- Форма смены пароля -->
        <div class="auth-header" style="margin-top: 40px; border-top: 2px solid #e5e7eb; padding-top: 30px;">
            <h3>Изменить пароль</h3>
        </div>

        <form method="POST" action="{{ route('profile.password') }}" class="auth-form">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="current_password">Текущий пароль</label>
                <input id="current_password" type="password" name="current_password" required>
                @error('current_password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Новый пароль</label>
                <input id="password" type="password" name="password" required>
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Подтвердите новый пароль</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-primary">Изменить пароль</button>
        </form>

        <div class="auth-footer">
            <a href="{{ route('home') }}">← Вернуться на главную</a>
        </div>
    </div>
</div>
@endsection
