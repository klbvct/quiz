@extends('layouts.app')

@section('title', 'Відновлення пароля - Quiz Education')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="back-to-site-wrapper">
        <a href="{{ url('/') }}" class="back-to-site">← Повернутися на сайт</a>
    </div>
    <div class="form-wrapper">
        <div class="form-header">
            <h1>Відновлення пароля</h1>
            <p>Введіть ваш email для отримання посилання відновлення</p>
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
            
            <button type="submit" class="btn btn-primary">Надіслати посилання</button>
            
            <div class="form-footer">
                <p>Згадали пароль? <a href="{{ route('login.form') }}">Увійти</a></p>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/auth.js') }}"></script>
@endpush
