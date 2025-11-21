# Скрипт автоматической установки Quiz Education
# Запускайте от имени Администратора

Write-Host "=== Quiz Education - Установка ===" -ForegroundColor Green
Write-Host ""

# Переход в папку backend
Set-Location -Path "backend"

# Проверка наличия Composer
Write-Host "Проверка Composer..." -ForegroundColor Yellow
try {
    $composerVersion = composer --version
    Write-Host "✓ Composer установлен: $composerVersion" -ForegroundColor Green
} catch {
    Write-Host "✗ Composer не найден! Установите Composer: https://getcomposer.org" -ForegroundColor Red
    exit
}

# Проверка наличия PHP
Write-Host "Проверка PHP..." -ForegroundColor Yellow
try {
    $phpVersion = php --version
    Write-Host "✓ PHP установлен" -ForegroundColor Green
} catch {
    Write-Host "✗ PHP не найден! Установите XAMPP: https://www.apachefriends.org" -ForegroundColor Red
    exit
}

# Установка зависимостей
Write-Host ""
Write-Host "Установка зависимостей..." -ForegroundColor Yellow
composer install

# Копирование .env файла
Write-Host ""
Write-Host "Создание .env файла..." -ForegroundColor Yellow
if (!(Test-Path ".env")) {
    Copy-Item ".env.example" ".env"
    Write-Host "✓ Файл .env создан" -ForegroundColor Green
} else {
    Write-Host "! Файл .env уже существует" -ForegroundColor Yellow
}

# Генерация ключа приложения
Write-Host ""
Write-Host "Генерация ключа приложения..." -ForegroundColor Yellow
php artisan key:generate

# Создание необходимых директорий
Write-Host ""
Write-Host "Создание необходимых директорий..." -ForegroundColor Yellow
$directories = @(
    "storage/framework/sessions",
    "storage/framework/views",
    "storage/framework/cache/data",
    "storage/logs"
)

foreach ($dir in $directories) {
    if (!(Test-Path $dir)) {
        New-Item -Path $dir -ItemType Directory -Force | Out-Null
        Write-Host "✓ Создана директория: $dir" -ForegroundColor Green
    } else {
        Write-Host "! Директория уже существует: $dir" -ForegroundColor Yellow
    }
}

# Информация о базе данных
Write-Host ""
Write-Host "=== ВАЖНО: Настройка базы данных ===" -ForegroundColor Cyan
Write-Host "1. Запустите MySQL в XAMPP" -ForegroundColor White
Write-Host "2. Откройте phpMyAdmin: http://localhost/phpmyadmin" -ForegroundColor White
Write-Host "3. Создайте базу данных: quiz_education" -ForegroundColor White
Write-Host "4. Или выполните команду:" -ForegroundColor White
Write-Host "   CREATE DATABASE quiz_education CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" -ForegroundColor Gray
Write-Host ""
Write-Host "После создания БД выполните: php artisan migrate" -ForegroundColor Yellow
Write-Host ""

# Вопрос о запуске миграций
$runMigrations = Read-Host "Хотите запустить миграции сейчас? (y/n)"
if ($runMigrations -eq "y" -or $runMigrations -eq "Y") {
    Write-Host ""
    Write-Host "Запуск миграций..." -ForegroundColor Yellow
    php artisan migrate
}

Write-Host ""
Write-Host "=== Установка завершена! ===" -ForegroundColor Green
Write-Host ""
Write-Host "Для запуска сервера выполните:" -ForegroundColor Cyan
Write-Host "  php artisan serve" -ForegroundColor White
Write-Host ""
Write-Host "Приложение будет доступно по адресу: http://localhost:8000" -ForegroundColor Cyan
Write-Host ""
