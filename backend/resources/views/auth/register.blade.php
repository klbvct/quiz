@extends('layouts.app')

@section('title', 'Регистрация - Quiz Education')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
<script>
    document.body.classList.add('auth-page');
</script>
<div class="container">
    <div class="form-wrapper">
        <div class="form-header">
            <h1>Регистрация</h1>
            <p>Создайте новую учетную запись</p>
        </div>
        
        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf
            <div class="form-group">
                <label for="registerName">Имя</label>
                <input type="text" id="registerName" name="name" value="{{ old('name') }}" placeholder="Ваше имя" required>
                @error('name')
                    <small class="error-message">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="registerEmail">Email</label>
                <input type="email" id="registerEmail" name="email" value="{{ old('email') }}" placeholder="example@mail.com" required>
                @error('email')
                    <small class="error-message">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="registerPassword">Пароль</label>
                <input type="password" id="registerPassword" name="password" placeholder="••••••••" required minlength="8">
                <small class="form-hint">Минимум 8 символов</small>
                @error('password')
                    <small class="error-message">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="registerConfirmPassword">Подтвердите пароль</label>
                <input type="password" id="registerConfirmPassword" name="password_confirmation" placeholder="••••••••" required>
            </div>
            
            <div class="form-options">
                <label class="checkbox-label">
                    <input type="checkbox" id="acceptTerms" required>
                    <span>Я согласен с <a href="#">условиями использования</a></span>
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
            
            <div class="form-footer">
                <p>Уже есть учетная запись? <a href="{{ route('login.form') }}">Войти</a></p>
            </div>
        </form>
        
        <div id="registerMessage" class="message"></div>
    </div>
</div>
@endsection


