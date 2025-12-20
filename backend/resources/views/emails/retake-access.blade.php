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
        .info-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 4px solid #10b981;
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
        .footer {
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎯 Повторне проходження тесту</h1>
            <p style="margin: 0; font-size: 16px;">Дизайн Освіти - Профорієнтаційне тестування</p>
        </div>
        <div class="content">
            <p>Вітаємо, <strong>{{ $user->name }}</strong>!</p>
            
            <p>Дякуємо за оплату! Ви успішно оплатили доступ до <strong>повторного проходження</strong> профорієнтаційного тестування.</p>
            
            <div class="info-box">
                <h3 style="margin-top: 0; color: #10b981;">✅ Ваш доступ активовано</h3>
                <p style="margin-bottom: 0;">
                    <strong>Email:</strong> {{ $user->email }}<br>
                    <strong>Статус:</strong> Доступ активний<br>
                    <strong>Тип:</strong> Повторне проходження
                </p>
            </div>

            <p><strong>Що далі?</strong></p>
            <ol>
                <li>Увійдіть у систему, використовуючи ваш email та існуючий пароль</li>
                <li>Ви зможете пройти тест знову та отримати нові результати</li>
                <li>Попередні результати залишаться доступними в особистому кабінеті</li>
            </ol>

            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="button">Увійти та розпочати тест</a>
            </div>

            <p style="margin-top: 30px; color: #6b7280; font-size: 14px;">
                <strong>Примітка:</strong> Якщо ви забули пароль, використовуйте функцію "Забули пароль?" на сторінці входу.
            </p>
        </div>
        <div class="footer">
            <p>З повагою,<br>Команда "Дизайн Освіти"</p>
            <p style="font-size: 12px;">Це автоматичний лист. Будь ласка, не відповідайте на нього.</p>
        </div>
    </div>
</body>
</html>
