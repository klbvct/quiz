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
            <h1>Сброс пароля</h1>
        </div>
        <div class="content">
            <p>Здравствуйте!</p>
            
            <p>Вы получили это письмо, потому что мы получили запрос на сброс пароля для вашего аккаунта.</p>
            
            <a href="{{ $resetUrl }}" class="button">Сбросить пароль</a>
            
            <div class="warning">
                <p style="margin: 0;"><strong>⏰ Внимание:</strong> Ссылка для сброса пароля действительна в течение {{ $expiration }} минут.</p>
            </div>
            
            <p style="font-size: 14px; color: #6b7280;">
                Если вы не запрашивали сброс пароля, просто проигнорируйте это письмо. Ваш пароль останется без изменений.
            </p>
            
            <p style="margin-top: 30px; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 20px;">
                Если у вас возникли проблемы с нажатием кнопки "Сбросить пароль", скопируйте и вставьте URL-адрес ниже в свой браузер:<br>
                <a href="{{ $resetUrl }}" style="color: #667eea; word-break: break-all;">{{ $resetUrl }}</a>
            </p>
        </div>
    </div>
</body>
</html>
