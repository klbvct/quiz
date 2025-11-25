@extends('layouts.app')

@section('title', 'Оплата успішна')

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

        <h1>Оплата успішна!</h1>
        
        <p class="success-message">
            Дякуємо за придбання профорієнтаційного тестування. 
            Ваш платіж успішно оброблено.
        </p>

        <div class="email-notice">
            <p>
                📧 На вказаний email надіслано лист з даними для входу в систему. 
                Перевірте папку "Вхідні" або "Спам".
            </p>
        </div>

        <div style="margin-top: 40px;">
            <a href="{{ route('login.form') }}" class="btn">Увійти в особистий кабінет</a>
            <a href="{{ url('/') }}" class="btn btn-secondary">На головну</a>
        </div>

        <p class="info-text">
            Якщо лист не надійшов протягом 5 хвилин, зв'яжіться з нами: admin@education-design.com.ua
        </p>
    </div>
@endsection
