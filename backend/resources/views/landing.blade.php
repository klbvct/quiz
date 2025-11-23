@extends('layouts.app')

@section('title', 'Дизайн Образования - Профориентационное тестирование')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
@endsection

@section('content')
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <svg width="50" height="50" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="25" cy="25" r="25" fill="url(#gradient-landing)"/>
                    <text x="25" y="32" font-family="Arial, sans-serif" font-size="20" font-weight="bold" fill="white" text-anchor="middle">Q</text>
                    <defs>
                        <linearGradient id="gradient-landing" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#667eea;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                </svg>
                <span class="logo-text">Дизайн Образования</span>
            </div>
            <div class="header-buttons">
                <a href="{{ route('login.form') }}" class="btn btn-outline">Войти</a>
                <a href="{{ route('register.form') }}" class="btn btn-primary">Регистрация</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Найдите свой путь в мире профессий</h1>
            <p>Профориентационное тестирование для определения ваших способностей, интересов и подходящей карьеры</p>
            <div class="hero-buttons">
                <button id="startTestBtn" class="btn btn-primary" style="background: white; color: #667eea;">Начать тестирование</button>
                <a href="#about" class="btn btn-outline" style="border-color: white; color: white;">Узнать больше</a>
            </div>
        </div>
    </section>

    <!-- Payment Modal -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Профориентационное тестирование</h2>
            <p class="modal-description">После оплаты на указанный адрес электронной почты будет отправлен доступ к тестированию</p>
            
            <form id="paymentForm" class="payment-form">
                @csrf
                <div class="form-group">
                    <label for="paymentEmail">Email</label>
                    <input type="email" id="paymentEmail" name="email" placeholder="example@mail.com" required>
                </div>
                
                <div class="price-block">
                    <span class="price-label">Стоимость:</span>
                    <span class="price-amount">1 грн</span>
                </div>
                
                <button type="submit" class="btn btn-primary btn-pay">Оплатить</button>
            </form>
        </div>
    </div>

    <!-- Features Section -->
    <section class="section">
        <h2 class="section-title">Почему выбирают нас?</h2>
        <p class="section-subtitle">Современные методики профориентации для успешного будущего</p>
        <div class="features">
            <div class="feature-card">
                <div class="feature-icon">🎯</div>
                <h3>Точная диагностика</h3>
                <p>Научно обоснованные методики для определения ваших склонностей и талантов</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Подробная аналитика</h3>
                <p>Получите детальный отчет о своих способностях и рекомендации по выбору профессии</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💼</div>
                <h3>Карьерные рекомендации</h3>
                <p>Персональные советы по построению карьеры на основе ваших результатов</p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section about-section" id="about">
        <h2 class="section-title">О платформе</h2>
        <p class="section-subtitle">Дизайн Образования - ваш надежный помощник в выборе профессии</p>
        <div style="max-width: 800px; margin: 0 auto; text-align: center;">
            <p style="font-size: 18px; color: #6b7280; margin-bottom: 20px;">
                Мы помогаем студентам, абитуриентам и людям, планирующим смену карьеры, 
                найти свое истинное призвание через комплексное профориентационное тестирование.
            </p>
            <p style="font-size: 18px; color: #6b7280;">
                Наша платформа использует проверенные психологические методики и современные 
                технологии для максимально точного определения ваших профессиональных склонностей.
            </p>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <h2>Готовы узнать свое призвание?</h2>
        <p>Пройдите тестирование прямо сейчас и получите персональные рекомендации</p>
        <button id="startTestBtn2" class="btn btn-primary" style="background: white; color: #667eea;">Начать тестирование</button>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Дизайн Образования. Все права защищены.</p>
        <p><a href="https://education-design.com.ua" target="_blank">education-design.com.ua</a></p>
    </footer>
@endsection

@push('scripts')
    <script src="{{ asset('js/landing.js') }}"></script>
@endpush
