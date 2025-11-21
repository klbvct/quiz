<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .credentials {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Добро пожаловать!</h1>
        </div>
        <div class="content">
            <p>Здравствуйте!</p>
            
            <p>Спасибо за оплату профориентационного тестирования. Для вас создан аккаунт на платформе "Дизайн Образования".</p>
            
            <div class="credentials">
                <p><strong>Ваши данные для входа:</strong></p>
                <p><strong>Email:</strong> {{ $email }}</p>
                <p><strong>Пароль:</strong> {{ $password }}</p>
            </div>
            
            <p>Вы можете войти в личный кабинет и начать тестирование прямо сейчас:</p>
            
            <a href="{{ url('/login') }}" class="button">Войти в личный кабинет</a>
            
            <p style="margin-top: 30px; font-size: 14px; color: #6b7280;">
                <strong>Важно:</strong> Рекомендуем изменить пароль после первого входа в систему.
            </p>
        </div>
    </div>
</body>
</html>
