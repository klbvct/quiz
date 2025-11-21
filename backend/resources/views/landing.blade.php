<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Дизайн Образования - Профориентационное тестирование</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1f2937;
            line-height: 1.6;
        }

        .header {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 20px 40px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo svg {
            width: 50px;
            height: 50px;
        }

        .logo-text {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
        }

        .header-buttons {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 12px 28px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-outline {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-outline:hover {
            background: #667eea;
            color: white;
        }

        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 40px;
            text-align: center;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero p {
            font-size: 20px;
            margin-bottom: 40px;
            opacity: 0.95;
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .section {
            padding: 80px 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            font-size: 36px;
            text-align: center;
            margin-bottom: 20px;
            color: #667eea;
        }

        .section-subtitle {
            text-align: center;
            font-size: 18px;
            color: #6b7280;
            margin-bottom: 60px;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .feature-card h3 {
            font-size: 22px;
            margin-bottom: 15px;
            color: #667eea;
        }

        .feature-card p {
            color: #6b7280;
            font-size: 16px;
        }

        .about-section {
            background: #f9fafb;
        }

        .cta-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 80px 40px;
        }

        .cta-section h2 {
            font-size: 42px;
            margin-bottom: 20px;
        }

        .cta-section p {
            font-size: 20px;
            margin-bottom: 40px;
            opacity: 0.95;
        }

        footer {
            background: #1f2937;
            color: white;
            text-align: center;
            padding: 30px 40px;
        }

        footer a {
            color: #667eea;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .header {
                padding: 15px 20px;
            }

            .logo-text {
                display: none;
            }

            .hero {
                padding: 60px 20px;
            }

            .hero h1 {
                font-size: 32px;
            }

            .hero p {
                font-size: 16px;
            }

            .section {
                padding: 40px 20px;
            }

            .section-title {
                font-size: 28px;
            }

            .cta-section h2 {
                font-size: 28px;
            }

            .cta-section p {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <svg width="50" height="50" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="25" cy="25" r="25" fill="url(#gradient)"/>
                    <text x="25" y="32" font-family="Arial, sans-serif" font-size="20" font-weight="bold" fill="white" text-anchor="middle">Q</text>
                    <defs>
                        <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
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
                <a href="{{ route('register.form') }}" class="btn btn-primary" style="background: white; color: #667eea;">Начать тестирование</a>
                <a href="#about" class="btn btn-outline" style="border-color: white; color: white;">Узнать больше</a>
            </div>
        </div>
    </section>

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
        <a href="{{ route('register.form') }}" class="btn btn-primary" style="background: white; color: #667eea;">Начать бесплатно</a>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Дизайн Образования. Все права защищены.</p>
        <p><a href="https://education-design.com.ua" target="_blank">education-design.com.ua</a></p>
    </footer>
</body>
</html>
