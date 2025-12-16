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
        .warning {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Скидання пароля</h1>
        </div>
        <div class="content">
            <p>Вітаємо!</p>
            
            <p>Ви отримали цей лист, тому що ми отримали запит на скидання пароля для вашого акаунту.
            
            <a href="{{ $resetUrl }}" class="button">Скинути пароль</a>
            
            <div class="warning">
                <p style="margin: 0;"><strong>⏰ Увага:</strong> Посилання для скидання пароля дійсне протягом {{ $expiration }} хвилин.</p>
            </div>
            
            <p style="font-size: 14px; color: #6b7280;">
                Якщо ви не запитували скидання пароля, просто проігноруйте цей лист. Ваш пароль залишиться без змін.
            </p>
            
            <p style="margin-top: 30px; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 20px;">
                Якщо у вас виникли проблеми з натисканням кнопки "Скинути пароль", скопіюйте та вставте URL-адресу нижче в свій браузер:<br>
                <a href="{{ $resetUrl }}" style="color: #0c68f5; word-break: break-all;">{{ $resetUrl }}</a>
            </p>
        </div>
    </div>
</body>
</html>
