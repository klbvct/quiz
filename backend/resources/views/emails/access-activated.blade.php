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
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
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
            <h1>Доступ активирован!</h1>
        </div>
        <div class="content">
            <p>Здравствуйте, {{ $user->name }}!</p>
            
            <p>Спасибо за оплату. Ваш доступ к профориентационному тестированию успешно активирован.</p>
            
            <p>Теперь вы можете войти в личный кабинет и начать тестирование:</p>
            
            <a href="{{ url('/login') }}" class="button">Перейти в личный кабинет</a>
            
            <p style="margin-top: 30px; font-size: 14px; color: #6b7280;">
                Если у вас возникнут вопросы, свяжитесь с нами по адресу: admin@education-design.com.ua
            </p>
        </div>
    </div>
</body>
</html>
