@extends('layouts.app')

@section('title', 'Сброс пароля - Quiz Education')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
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
                <div class="password-input-wrapper">
                    <input type="password" id="password" name="password" placeholder="••••••••" required minlength="8">
                    <button type="button" class="toggle-password" data-target="password">
                        <svg class="eye-icon eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg class="eye-icon eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    </button>
                </div>
                <small class="form-hint">Минимум 8 символов</small>
                @error('password')
                    <small class="error-message">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password_confirmation">Подтвердите пароль</label>
                <div class="password-input-wrapper">
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                    <button type="button" class="toggle-password" data-target="password_confirmation">
                        <svg class="eye-icon eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg class="eye-icon eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    </button>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Сбросить пароль</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/auth.js') }}"></script>
@endpush
