@extends('layouts.app')

@section('title', 'Дизайн Освіти - Профорієнтаційне тестування')

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
                <span class="logo-text">Quiz Education</span>
            </div>
            <div class="header-buttons">
                <!-- Language Switcher -->
                <div class="language-switcher">
                    <a href="{{ route('locale.set', 'uk') }}" class="lang-btn {{ app()->getLocale() == 'uk' ? 'active' : '' }}">🇺🇦</a>
                    <a href="{{ route('locale.set', 'en') }}" class="lang-btn {{ app()->getLocale() == 'en' ? 'active' : '' }}">🇬🇧</a>
                </div>
                <a href="{{ route('login.form') }}" class="btn btn-outline">{{ app()->getLocale() == 'uk' ? 'Увійти' : 'Login' }}</a>
                <a href="{{ route('register.form') }}" class="btn btn-primary">{{ app()->getLocale() == 'uk' ? 'Реєстрація' : 'Register' }}</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>{{ __('landing.hero_title') }}</h1>
            <p>{{ __('landing.hero_subtitle') }}</p>
            <div class="hero-buttons">
                <button id="startTestBtn" class="btn btn-primary" style="background: white; color: #667eea;">{{ __('landing.hero_cta') }}</button>
                <a href="#about" class="btn btn-outline" style="border-color: white; color: white;">{{ app()->getLocale() == 'uk' ? 'Дізнатися більше' : 'Learn More' }}</a>
            </div>
        </div>
    </section>

    <!-- Payment Modal -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Профорієнтаційне тестування</h2>
            <p class="modal-description">Після оплати на вказану адресу електронної пошти буде надіслано доступ до тестування</p>
            
            <form id="paymentForm" class="payment-form">
                @csrf
                <div class="form-group">
                    <label for="paymentEmail">Email</label>
                    <input type="email" id="paymentEmail" name="email" placeholder="example@mail.com" required>
                </div>
                
                <div class="price-block">
                    <span class="price-label">Вартість:</span>
                    <span class="price-amount">1 грн</span>
                </div>
                
                <button type="submit" class="btn btn-primary btn-pay">Оплатити</button>
            </form>
        </div>
    </div>

    <!-- Features Section -->
    <section class="section">
        <h2 class="section-title">{{ __('landing.features_title') }}</h2>
        <p class="section-subtitle">{{ app()->getLocale() == 'uk' ? 'Сучасні методики профорієнтації для успішного майбутнього' : 'Modern career guidance techniques for a successful future' }}</p>
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
                {{ app()->getLocale() == 'uk' ? 'Ми допомагаємо студентам, абітурієнтам і людям, які планують зміну кар\'єри, знайти своє істинне покликання через комплексне профорієнтаційне тестування.' : 'We help students, applicants, and those planning a career change find their true calling through comprehensive career guidance testing.' }}
            </p>
            <p style="font-size: 18px; color: #6b7280;">
                {{ app()->getLocale() == 'uk' ? 'Наша платформа використовує перевірені психологічні методики і сучасні технології для максимально точного визначення ваших професійних схильностей.' : 'Our platform uses proven psychological techniques and modern technologies for the most accurate determination of your professional inclinations.' }}
            </p>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <h2>{{ app()->getLocale() == 'uk' ? 'Готові дізнатися своє покликання?' : 'Ready to discover your calling?' }}</h2>
        <p>{{ app()->getLocale() == 'uk' ? 'Пройдіть тестування прямо зараз і отримайте персональні рекомендації' : 'Take the test now and get personalized recommendations' }}</p>
        <button id="startTestBtn2" class="btn btn-primary" style="background: white; color: #667eea;">{{ __('landing.hero_cta') }}</button>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Quiz Education. {{ __('landing.footer_rights') }}</p>
        <p><a href="https://education-design.com.ua" target="_blank">education-design.com.ua</a></p>
    </footer>
@endsection

@push('scripts')
    <script src="{{ asset('js/landing.js') }}"></script>
@endpush
