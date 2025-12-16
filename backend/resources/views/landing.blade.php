@extends('layouts.app')

@section('title', app()->getLocale() == 'uk' ? 'Дизайн Освіти - Профорієнтаційне тестування' : 'Дизайн Образования - Профориентационное тестирование')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
@endsection

@section('content')
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <img src="{{ asset('images/logo_education_design.svg') }}" alt="Education Design" class="logo-image">
                <span class="logo-text">{{ app()->getLocale() == 'uk' ? 'Дизайн Освіти' : 'Дизайн Образования' }}</span>
            </div>
            <div class="header-buttons">
                <!-- Language Switcher -->
                <div class="language-switcher">
                    <a href="{{ route('locale.set', 'uk') }}" class="lang-btn {{ app()->getLocale() == 'uk' ? 'active' : '' }}">🇺🇦</a>
                    <a href="{{ route('locale.set', 'ru') }}" class="lang-btn {{ app()->getLocale() == 'ru' ? 'active' : '' }}">🇷🇺</a>
                </div>
                <a href="{{ route('login.form') }}" class="btn btn-outline">{{ app()->getLocale() == 'uk' ? 'Увійти' : 'Войти' }}</a>
                <a href="{{ route('register.form') }}" class="btn btn-primary">{{ app()->getLocale() == 'uk' ? 'Реєстрація' : 'Регистрация' }}</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>{{ __('landing.hero_title') }}</h1>
            <p>{{ __('landing.hero_subtitle') }}</p>
            <div class="hero-buttons">
                <button id="startTestBtn" class="btn btn-primary" style="background: white; color: #0c68f5;">{{ __('landing.hero_cta') }}</button>
                <a href="#about" class="btn btn-outline" style="border-color: white; color: white;">{{ app()->getLocale() == 'uk' ? 'Дізнатися більше' : 'Узнать больше' }}</a>
            </div>
        </div>
    </section>

    <!-- Payment Modal -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>{{ app()->getLocale() == 'uk' ? 'Профорієнтаційне тестування' : 'Профориентационное тестирование' }}</h2>
            <p class="modal-description">{{ app()->getLocale() == 'uk' ? 'Після оплати на вказану адресу електронної пошти буде надіслано доступ до тестування' : 'После оплаты на указанный адрес электронной почты будет отправлен доступ к тестированию' }}</p>
            
            <form id="paymentForm" class="payment-form">
                @csrf
                <div class="form-group">
                    <label for="paymentEmail">Email</label>
                    <input type="email" id="paymentEmail" name="email" placeholder="example@mail.com" required>
                </div>
                
                <div class="price-block">
                    <span class="price-label">{{ app()->getLocale() == 'uk' ? 'Вартість:' : 'Стоимость:' }}</span>
                    <span class="price-amount">{{ app()->getLocale() == 'uk' ? '1 грн' : '1 грн' }}</span>
                </div>
                
                <button type="submit" class="btn btn-primary btn-pay">{{ app()->getLocale() == 'uk' ? 'Оплатити' : 'Оплатить' }}</button>
            </form>
        </div>
    </div>

    <!-- Features Section -->
    <section class="section">
        <h2 class="section-title">{{ __('landing.features_title') }}</h2>
        <p class="section-subtitle">{{ app()->getLocale() == 'uk' ? 'Сучасні методики профорієнтації для успішного майбутнього' : 'Современные методики профориентации для успешного будущего' }}</p>
        <div class="features">
            <div class="feature-card">
                <div class="feature-icon">🎯</div>
                <h3>{{ __('landing.feature1_title') }}</h3>
                <p>{{ __('landing.feature1_desc') }}</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>{{ __('landing.feature2_title') }}</h3>
                <p>{{ __('landing.feature2_desc') }}</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💼</div>
                <h3>{{ __('landing.feature3_title') }}</h3>
                <p>{{ __('landing.feature3_desc') }}</p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section about-section" id="about">
        <h2 class="section-title">{{ __('landing.footer_about') }}</h2>
        <p class="section-subtitle">{{ __('landing.footer_about_text') }}</p>
        <div style="max-width: 800px; margin: 0 auto; text-align: center;">
            <p style="font-size: 18px; color: #6b7280; margin-bottom: 20px;">
                {{ app()->getLocale() == 'uk' ? 'Ми допомагаємо студентам, абітурієнтам і людям, які планують зміну кар\'єри, знайти своє істинне покликання через комплексне профорієнтаційне тестування.' : 'Мы помогаем студентам, абитуриентам и людям, планирующим смену карьеры, найти свое истинное призвание через комплексное профориентационное тестирование.' }}
            </p>
            <p style="font-size: 18px; color: #6b7280;">
                {{ app()->getLocale() == 'uk' ? 'Наша платформа використовує перевірені психологічні методики і сучасні технології для максимально точного визначення ваших професійних схильностей.' : 'Наша платформа использует проверенные психологические методики и современные технологии для максимально точного определения ваших профессиональных склонностей.' }}
            </p>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <h2>{{ app()->getLocale() == 'uk' ? 'Готові дізнатися своє покликання?' : 'Готовы узнать свое призвание?' }}</h2>
        <p>{{ app()->getLocale() == 'uk' ? 'Пройдіть тестування прямо зараз і отримайте персональні рекомендації' : 'Пройдите тестирование прямо сейчас и получите персональные рекомендации' }}</p>
        <button id="startTestBtn2" class="btn btn-primary" style="background: white; color: #0c68f5;">{{ __('landing.hero_cta') }}</button>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 {{ app()->getLocale() == 'uk' ? 'Дизайн Освіти' : 'Дизайн Образования' }}. {{ __('landing.footer_rights') }}</p>
        <p><a href="https://education-design.com.ua" target="_blank">education-design.com.ua</a></p>
    </footer>
@endsection

@push('scripts')
    <script src="{{ asset('js/landing.js') }}"></script>
@endpush
