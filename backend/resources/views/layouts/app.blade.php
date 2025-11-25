<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', app()->getLocale() == 'uk' ? 'Дизайн Освіти' : 'Дизайн Образования')</title>
    
    @yield('styles')
    
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #10b981;
            --error-color: #ef4444;
            --text-color: #1f2937;
            --bg-color: #f9fafb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        body.auth-page {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        body.home-page {
            background: #f9fafb;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Стили определяются в отдельных CSS файлах:
           - landing.css (главная страница)
           - auth.css (формы входа/регистрации/сброса пароля)
           - home.css (личный кабинет)
           - payment.css (страница оплаты)
           - payment-success.css (страница успешной оплаты)
        */
    </style>
</head>
<body>
    @yield('content')
    
    <!-- Общий JS файл -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    <!-- Специфичные JS файлы для каждой страницы -->
    @stack('scripts')
</body>
</html>
