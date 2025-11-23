@extends('layouts.app')

@section('title', 'Вход - Quiz Education')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="form-wrapper">
        <div class="form-header">
            <h1>Вход</h1>
            <p>Войдите в свою учетную запись</p>
        </div>
        
        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf
            <div class="form-group">
                <label for="loginEmail">Email</label>
                <input type="email" id="loginEmail" name="email" value="{{ old('email') }}" placeholder="example@mail.com" required>
                @error('email')
                    <small class="error-message">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="loginPassword">Пароль</label>
                <input type="password" id="loginPassword" name="password" placeholder="••••••••" required>
                @error('password')
                    <small class="error-message">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-options">
                <label class="checkbox-label">
                    <input type="checkbox" id="rememberMe" name="remember">
                    <span>Запомнить меня</span>
                </label>
                <a href="{{ route('password.request') }}" class="forgot-password">Забыли пароль?</a>
            </div>
            
            <button type="submit" class="btn btn-primary">Войти</button>
            
            <div class="form-footer">
                <p>Нет учетной записи? <a href="{{ url('/register') }}">Зарегистрироваться</a></p>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/auth.js') }}"></script>
@endpush

