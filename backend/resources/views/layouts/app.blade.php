<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quiz Education')</title>
    
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

        .form-wrapper {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            max-width: 450px;
            margin: 0 auto;
        }

        .home-wrapper {
            min-height: 100vh;
            background: #f9fafb;
            padding: 0;
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-header h1 {
            color: var(--primary-color);
            font-size: 32px;
            margin-bottom: 10px;
        }

        .form-header p {
            color: #6b7280;
            font-size: 14px;
        }

        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            color: var(--text-color);
            font-weight: 600;
            font-size: 14px;
        }

        .form-group input {
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-hint {
            color: #9ca3af;
            font-size: 12px;
        }

        .error-message {
            color: #ef4444;
            font-size: 12px;
            font-weight: 500;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 14px;
            color: var(--text-color);
        }

        .checkbox-label input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: var(--secondary-color);
        }

        .btn {
            padding: 14px 24px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background: #ef4444;
            color: white;
        }

        .btn-secondary:hover {
            background: #dc2626;
        }

        .form-footer {
            text-align: center;
            margin-top: 20px;
        }

        .form-footer p {
            color: #6b7280;
            font-size: 14px;
        }

        .form-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .form-footer a:hover {
            color: var(--secondary-color);
        }

        .message {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 14px;
            margin-top: 20px;
            display: none;
        }

        .message.success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
            display: block;
        }

        .message.error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #ef4444;
            display: block;
        }

        /* Home page styles */
        .header {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header h1 {
            color: var(--primary-color);
            font-size: 28px;
            margin: 0;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo svg {
            width: 50px;
            height: 50px;
            flex-shrink: 0;
        }

        .logo-text {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
            white-space: nowrap;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-info span {
            color: var(--text-color);
            font-weight: 600;
        }

        .content {
            padding: 40px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .content h2 {
            color: var(--text-color);
            margin-bottom: 10px;
            font-size: 32px;
        }

        .content > p {
            color: #6b7280;
            margin-bottom: 30px;
            font-size: 16px;
        }

        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
        }

        .card h3 {
            color: var(--primary-color);
            margin-bottom: 15px;
            font-size: 22px;
        }

        .card p {
            color: #6b7280;
            font-size: 15px;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .form-wrapper {
                padding: 30px 20px;
            }

            .form-header h1 {
                font-size: 24px;
            }

            .header {
                padding: 15px 20px;
            }

            .logo-text {
                display: none;
            }

            .user-info {
                gap: 10px;
            }

            .user-info span {
                font-size: 14px;
            }

            .btn-secondary {
                padding: 10px 16px;
                font-size: 14px;
            }

            .content {
                padding: 20px;
            }

            .content h2 {
                font-size: 24px;
            }

            .dashboard {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }
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
