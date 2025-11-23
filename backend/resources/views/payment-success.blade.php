@extends('layouts.app')

@section('title', 'Оплата успешна')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/payment-success.css') }}">
@endsection

@section('content')
    <div class="success-container">
        <div class="success-icon">
            <svg viewBox="0 0 24 24">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>

        <h1>Оплата успешна!</h1>
        
        <p class="success-message">
            Спасибо за покупку профориентационного тестирования. 
            Ваш платеж успешно обработан.
        </p>

        <div class="email-notice">
            <p>
                📧 На указанный email отправлено письмо с данными для входа в систему. 
                Проверьте папку "Входящие" или "Спам".
            </p>
        </div>

        <div style="margin-top: 40px;">
            <a href="{{ route('login.form') }}" class="btn">Войти в личный кабинет</a>
            <a href="{{ url('/') }}" class="btn btn-secondary">На главную</a>
        </div>

        <p class="info-text">
            Если письмо не пришло в течение 5 минут, свяжитесь с нами: admin@education-design.com.ua
        </p>
    </div>
@endsection
