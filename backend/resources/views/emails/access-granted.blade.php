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
            background: linear-gradient(135deg, #0c68f5 0%, #764ba2 100%);
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
            border-left: 4px solid #0c68f5;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #0c68f5;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 20px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Доброго дня!</h1>
        </div>
        <div class="content">
            
            <p>Дякуємо за оплату профорієнтаційного тестування. Для вас створено акаунт на платформі "Дизайн Освіти".</p>
            
            <div class="credentials">
                <p><strong>Ваші дані для входу:</strong></p>
                <p><strong>Email:</strong> {{ $email }}</p>
                <p><strong>Пароль:</strong> {{ $password }}</p>
            </div>
            
            <p>Ви можете увійти в особистий кабінет і почати тестування прямо зараз:</p>
            
            <a href="{{ url('/login') }}" class="button">Увійти в особистий кабінет</a>
            
            <p style="margin-top: 30px; font-size: 14px; color: #6b7280;">
                <strong>Важливо:</strong> Рекомендуємо змінити пароль після першого входу в систему.
            </p>
        </div>
    </div>
</body>
</html>
