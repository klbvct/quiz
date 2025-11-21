# Установка проекта на новом компьютере

## Быстрый старт

### 1. Клонирование репозитория
```bash
git clone https://github.com/klbvct/quiz.git
cd quiz/backend
```

### 2. Установка зависимостей
```bash
composer install
```

### 3. Настройка окружения
```bash
# Скопируйте файл настроек
copy .env.example .env

# Сгенерируйте ключ приложения
php artisan key:generate
```

### 4. Настройка базы данных

Откройте файл `.env` и настройте подключение к БД:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quiz_education
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Создание базы данных

В MySQL создайте базу данных:
```sql
CREATE DATABASE quiz_education CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Или через phpMyAdmin:
- Откройте http://localhost/phpmyadmin
- Создайте новую БД с именем `quiz_education`

### 6. Запуск миграций
```bash
php artisan migrate
```

### 7. Создание необходимых директорий (если нужно)
```bash
# Эти директории должны автоматически создаться из git,
# но если возникли проблемы:
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache/data
mkdir -p storage/logs
```

### 8. Установка прав (Windows - обычно не требуется)
Если на Linux/Mac:
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 9. Запуск сервера
```bash
php artisan serve
```

Приложение будет доступно по адресу: http://localhost:8000

## Требования

- PHP >= 8.1
- Composer
- MySQL или MariaDB
- Расширения PHP: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON

## Установка требований на Windows

### XAMPP (PHP + MySQL + Apache)
1. Скачайте: https://www.apachefriends.org/download.html
2. Установите XAMPP 8.2+
3. Добавьте `C:\xampp\php` в PATH

### Composer
1. Скачайте: https://getcomposer.org/Composer-Setup.exe
2. Установите Composer
3. Перезапустите PowerShell

## Возможные проблемы

### Ошибка "No such file or directory" в storage
```bash
php artisan storage:link
mkdir storage/framework/sessions
mkdir storage/framework/views
mkdir storage/framework/cache/data
```

### Ошибка "Please provide a valid cache path"
```bash
mkdir bootstrap/cache
```

### Ошибка подключения к БД
- Проверьте, что MySQL запущен в XAMPP
- Проверьте настройки в файле `.env`
- Убедитесь, что база данных создана

## Структура проекта

```
quiz/
├── backend/              # Laravel приложение
│   ├── app/             # Код приложения
│   ├── database/        # Миграции
│   ├── resources/       # Views (Blade шаблоны)
│   ├── routes/          # Маршруты
│   ├── public/          # Публичная папка
│   └── storage/         # Хранилище файлов
├── README.md            # Главная документация
└── INSTALL.md           # Детальная инструкция по установке
```

## Дополнительно

Подробная инструкция по установке находится в файле `INSTALL.md`
