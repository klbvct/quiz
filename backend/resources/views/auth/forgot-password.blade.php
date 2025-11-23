@extends('layouts.app')

@section('title', 'Восстановление пароля - Quiz Education')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="form-wrapper">
        <div class="form-header">
            <h1>Восстановление пароля</h1>
            <p>Введите ваш email для получения ссылки восстановления</p>
        </div>
        
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('password.email') }}" class="auth-form">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="example@mail.com" required autofocus>
                @error('email')
                    <small class="error-message">{{ $message }}</small>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary">Отправить ссылку</button>
            
            <div class="form-footer">
                <p>Вспомнили пароль? <a href="{{ route('login.form') }}">Войти</a></p>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/auth.js') }}"></script>
@endpush
