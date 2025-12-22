# Laravel Backend для Quiz Education

Backend API на Laravel з автентифікацією через Laravel Sanctum та AI-генерацією професійних рекомендацій.

## ✨ Нові можливості

### 🤖 AI-генерація професійних рекомендацій

Система використовує **Google Gemini AI** для генерації персоналізованих професійних напрямків на основі комплексного аналізу 8 модулів тестування.

**Швидкий старт AI:**
1. Отримайте безкоштовний API ключ: https://aistudio.google.com/app/apikey
2. Додайте у `.env`: `GEMINI_API_KEY=ваш_ключ`
3. Протестуйте: `php artisan ai:test-recommendations`

📖 **Детальна документація:**
- [QUICKSTART_AI.md](./QUICKSTART_AI.md) - швидкий старт
- [AI_SETUP.md](./AI_SETUP.md) - повна документація

---

## Вимоги

- PHP >= 8.1
- Composer
- MySQL або PostgreSQL
- Laravel 10.x

## Установка

### 1. Установка залежностей

Якщо у вас не встановлено Composer, завантажте його з [getcomposer.org](https://getcomposer.org/)

Потім встановіть залежності Laravel:

```bash
cd backend
composer install
```

### 2. Налаштування середовища

Скопіюйте файл `.env.example` у `.env`:

```bash
copy .env.example .env
```

Сгенерируйте ключ приложения:

```bash
php artisan key:generate
```

### 3. Настройка базы данных

Отредактируйте файл `.env` и настройте подключение к базе данных:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quiz_education
DB_USERNAME=root
DB_PASSWORD=ваш_пароль
```

Создайте базу данных:

```sql
CREATE DATABASE quiz_education;
```

### 4. Запуск миграций

```bash
php artisan migrate
```

### 5. Запуск сервера

```bash
php artisan serve
```

Сервер будет доступен по адресу: `http://localhost:8000`

## API Endpoints

### Публічні маршруты (routes/auth.php)

- `POST /api/register` - Реєстрація користувача
  ```json
  {
    "name": "Іван Іванов",
    "email": "ivan@example.com",
    "password": "password123",
    "birthdate": "2000-01-01"
  }
  ```

- `POST /api/login` - Вхід
  ```json
  {
    "email": "ivan@example.com",
    "password": "password123"
  }
  ```

- `POST /api/password/reset-request` - Запит на скидання паролю
- `POST /api/password/reset` - Скидання паролю

### Захищені маршрути (routes/api.php)

Додайте заголовок: `Authorization: Bearer {your_token}`

**Користувач:**
- `GET /api/user` - Дані поточного користувача
- `POST /api/logout` - Вихід

**Тестування:**
- `GET /api/quiz/modules` - Список модулів
- `GET /api/quiz/module/{id}` - Питання модуля
- `POST /api/quiz/start` - Початок тесту
- `POST /api/quiz/answer` - Відповідь на питання
- `POST /api/quiz/complete` - Завершення тесту
- `GET /api/quiz/sessions` - Історія сесій
- `GET /api/quiz/report/{session}` - PDF звіт

**Платежі:**
- `POST /api/payments/create` - Створити платіж Fondy
- `POST /api/payments/callback` - Callback від Fondy
- `GET /api/payments/history` - Історія платежів

### Адмін маршрути (routes/web.php)

Потрібен `is_admin = true`

- `GET /admin/dashboard` - Панель адміністратора
- `GET /admin/users` - Список користувачів (з фільтрами)
- `GET /admin/users/{id}/edit` - Редагування користувача
- `PUT /admin/users/{id}` - Оновлення користувача
- `POST /admin/users/{id}/toggle-access` - Перемикання доступу
- `DELETE /admin/users/{id}` - Видалення користувача

## Структура проекта

```
backend/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Api/
│   │           └── AuthController.php
│   └── Models/
│       └── User.php
├── config/
│   ├── cors.php
│   └── sanctum.php
├── database/
│   └── migrations/
│       ├── 2025_11_20_000001_create_users_table.php
│       └── 2025_11_20_000002_create_personal_access_tokens_table.php
├── routes/
│   └── api.php
├── .env.example
└── composer.json
```

## Технологии

- **Laravel 10** - PHP фреймворк
- **Laravel Sanctum** - API аутентификация
- **MySQL** - База данных

## Безопасность

- Пароли хешируются с использованием bcrypt
- API защищен через токены Laravel Sanctum
- Настроен CORS для работы с frontend
- Валидация всех входящих данных

## CORS настройка

CORS настроен для работы с frontend на любом origin. Для production окружения рекомендуется ограничить allowed_origins в `config/cors.php`.

## Разработка

### Очистка кеша

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Просмотр маршрутов

```bash
php artisan route:list
```

## Troubleshooting

### Ошибка "Class not found"

```bash
composer dump-autoload
```

### Ошибка миграции

```bash
php artisan migrate:fresh
```

### CORS ошибки

Убедитесь, что в `.env` файле правильно настроен `APP_URL` и `SANCTUM_STATEFUL_DOMAINS`
