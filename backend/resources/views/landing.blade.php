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
                <span class="logo-text">Дизайн Образования</span>
            </div>
            <div class="header-buttons">
                <a href="{{ route('login.form') }}" class="btn btn-outline">Увійти</a>
                <a href="{{ route('register.form') }}" class="btn btn-primary">Реєстрація</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Знайдіть свій шлях у світі професій</h1>
            <p>Профорієнтаційне тестування для визначення ваших здібностей, інтересів і підходящої кар'єри</p>
            <div class="hero-buttons">
                <button id="startTestBtn" class="btn btn-primary" style="background: white; color: #667eea;">Почати тестування</button>
                <a href="#about" class="btn btn-outline" style="border-color: white; color: white;">Дізнатися більше</a>
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
        <h2 class="section-title">Чому обирають нас?</h2>
        <p class="section-subtitle">Сучасні методики профорієнтації для успішного майбутнього</p>
        <div class="features">
            <div class="feature-card">
                <div class="feature-icon">🎯</div>
                <h3>Точна діагностика</h3>
                <p>Науково обгрунтовані методики для визначення ваших схильностей і талантів</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Детальна аналітика</h3>
                <p>Отримайте детальний звіт про свої здібності та рекомендації щодо вибору професії</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💼</div>
                <h3>Кар'єрні рекомендації</h3>
                <p>Персональні поради щодо побудови кар'єри на основі ваших результатів</p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section about-section" id="about">
        <h2 class="section-title">Про платформу</h2>
        <p class="section-subtitle">Дизайн Освіти - ваш надійний помічник у виборі професії</h2>
        <div style="max-width: 800px; margin: 0 auto; text-align: center;">
            <p style="font-size: 18px; color: #6b7280; margin-bottom: 20px;">
                Ми допомагаємо студентам, абітурієнтам і людям, які планують зміну кар'єри, 
                знайти своє істинне покликання через комплексне профорієнтаційне тестування.
            </p>
            <p style="font-size: 18px; color: #6b7280;">
                Наша платформа використовує перевірені психологічні методики і сучасні 
                технології для максимально точного визначення ваших професійних схильностей.
            </p>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <h2>Готові дізнатися своє покликання?</h2>
        <p>Пройдіть тестування прямо зараз і отримайте персональні рекомендації</p>
        <button id="startTestBtn2" class="btn btn-primary" style="background: white; color: #667eea;">Почати тестування</button>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Дизайн Освіти. Всі права захищені.</p>
        <p><a href="https://education-design.com.ua" target="_blank">education-design.com.ua</a></p>
    </footer>
@endsection

@push('scripts')
    <script src="{{ asset('js/landing.js') }}"></script>
@endpush
