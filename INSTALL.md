## Инструкция по установке Laravel Backend

### Требования

Перед началом убедитесь, что у вас установлено:

1. **PHP 8.1 или выше**
   - Скачать: https://windows.php.net/download/
   - Или установить через XAMPP/WAMP

2. **Composer**
   - Скачать: https://getcomposer.org/download/

3. **MySQL или MariaDB**
   - Через XAMPP: https://www.apachefriends.org/
   - Или установить отдельно: https://dev.mysql.com/downloads/installer/

### Шаги установки

#### 1. Установка Composer (если не установлен)

Скачайте и установите Composer с официального сайта:
https://getcomposer.org/Composer-Setup.exe

#### 2. Установка Laravel зависимостей

Откройте PowerShell или CMD в папке проекта:

```bash
cd backend
composer install
```

Если `composer install` не работает, попробуйте установить Laravel глобально:

```bash
composer global require laravel/installer
```

#### 3. Настройка окружения

```bash
# Скопируйте .env.example в .env
copy .env.example .env

# Сгенерируйте ключ приложения
php artisan key:generate
```

#### 4. Настройка базы данных

1. Откройте XAMPP (или другой MySQL сервер) и запустите MySQL
2. Откройте phpMyAdmin (http://localhost/phpmyadmin)
3. Создайте новую базу данных с именем `quiz_education`

Или через командную строку MySQL:
```sql
CREATE DATABASE quiz_education CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

4. Откройте файл `backend/.env` и настройте подключение:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quiz_education
DB_USERNAME=root
DB_PASSWORD=
```

#### 5. Запуск миграций

```bash
php artisan migrate
```

Если возникает ошибка, убедитесь что MySQL запущен и база данных создана.

#### 6. Запуск сервера

```bash
php artisan serve
```

Сервер будет доступен по адресу: http://localhost:8000

### Проверка установки

1. Откройте браузер и перейдите: http://localhost:8000/api/user
2. Вы должны получить ответ с ошибкой 401 (Unauthenticated) - это нормально

### Альтернатива: Использование полного Laravel проекта

Если у вас есть Composer, вы можете создать полный Laravel проект:

```bash
# Создать новый Laravel проект
composer create-project laravel/laravel backend

# Установить Laravel Sanctum
cd backend
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

Затем скопируйте файлы из папки backend в новый проект.

### Решение проблем

#### Ошибка "composer not found"
- Переустановите Composer
- Перезапустите PowerShell/CMD

#### Ошибка "Class 'Illuminate\Foundation\Application' not found"
```bash
composer dump-autoload
composer install
```

#### Ошибка миграции базы данных
- Проверьте, что MySQL запущен
- Проверьте настройки в .env файле
- Попробуйте: `php artisan migrate:fresh`

#### CORS ошибки
Убедитесь, что в `backend/config/cors.php` установлено:
```php
'allowed_origins' => ['*'],
```

### Запуск в production

Для production сервера:

1. Установите зависимости без dev пакетов:
```bash
composer install --optimize-autoloader --no-dev
```

2. Кэшируйте конфигурацию:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. Настройте веб-сервер (Apache/Nginx) для работы с Laravel
