@extends('layouts.app')

@section('title', 'Сброс пароля - Quiz Education')

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
            <h1>Сброс пароля</h1>
            <p>Введите новый пароль для вашей учетной записи</p>
        </div>
        
        <form method="POST" action="{{ route('password.update') }}" class="auth-form">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" placeholder="example@mail.com" required autofocus>
                @error('email')
                    <small class="error-message">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password">Новый пароль</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required minlength="8">
                <small class="form-hint">Минимум 8 символов</small>
                @error('password')
                    <small class="error-message">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password_confirmation">Подтвердите пароль</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Сбросить пароль</button>
        </form>
    </div>
</div>
@endsection
