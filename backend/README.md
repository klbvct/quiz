# Laravel Backend для Quiz Education

Backend API на Laravel с аутентификацией через Laravel Sanctum.

## Требования

- PHP >= 8.1
- Composer
- MySQL или PostgreSQL
- Laravel 10.x

## Установка

### 1. Установка зависимостей

Если у вас не установлен Composer, скачайте его с [getcomposer.org](https://getcomposer.org/)

Затем установите зависимости Laravel:

```bash
cd backend
composer install
```

### 2. Настройка окружения

Скопируйте файл `.env.example` в `.env`:

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

### Публичные маршруты

- `POST /api/register` - Регистрация нового пользователя
  ```json
  {
    "name": "Иван Иванов",
    "email": "ivan@example.com",
    "password": "password123"
  }
  ```

- `POST /api/login` - Вход в систему
  ```json
  {
    "email": "ivan@example.com",
    "password": "password123"
  }
  ```

### Защищенные маршруты (требуют токен)

Добавьте заголовок: `Authorization: Bearer {your_token}`

- `GET /api/user` - Получить данные текущего пользователя
- `POST /api/logout` - Выход из системы

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
