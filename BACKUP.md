# Инструкция по резервному копированию БД

## Автоматическое создание backup

### Windows (PowerShell)

```powershell
cd backend

# Создать backup
$timestamp = Get-Date -Format "yyyy-MM-dd_HHmmss"
$backupFile = "backup_quiz_education_$timestamp.sql"
C:\xampp\mysql\bin\mysqldump.exe -u root -h 127.0.0.1 quiz_education > $backupFile

# Проверить размер
(Get-Item $backupFile).Length / 1KB
```

### Linux/Mac

```bash
cd backend

# Создать backup
timestamp=$(date +%Y-%m-%d_%H%M%S)
mysqldump -u root -h 127.0.0.1 quiz_education > backup_quiz_education_$timestamp.sql

# Проверить размер
ls -lh backup_*.sql
```

## Восстановление из backup

### Windows

```powershell
cd backend
C:\xampp\mysql\bin\mysql.exe -u root -h 127.0.0.1 quiz_education < backup_quiz_education_YYYY-MM-DD_HHMMSS.sql
```

### Linux/Mac

```bash
cd backend
mysql -u root -h 127.0.0.1 quiz_education < backup_quiz_education_YYYY-MM-DD_HHMMSS.sql
```

## Рекомендации

1. **Частота backup:**
   - Ежедневно: автоматически (cron/Task Scheduler)
   - Перед обновлениями: вручную
   - После важных изменений: вручную

2. **Хранение:**
   - Локально: последние 7 дней
   - Облако: последние 30 дней
   - Критические версии: бессрочно

3. **Безопасность:**
   - Backup файлы НЕ коммитить в Git (добавлены в .gitignore)
   - Хранить в защищённом месте
   - Периодически проверять целостность

## Настройка автоматического backup

### Windows Task Scheduler

1. Откройте Task Scheduler
2. Создайте новую задачу:
   - **Триггер:** Ежедневно в 02:00
   - **Действие:** Запустить программу
   - **Программа:** `powershell.exe`
   - **Аргументы:** 
     ```
     -File "C:\Users\kalab\Downloads\quiz\backend\create-backup.ps1"
     ```

3. Создайте файл `backend/create-backup.ps1`:

```powershell
$timestamp = Get-Date -Format "yyyy-MM-dd_HHmmss"
$backupDir = "C:\Backups\quiz"
$backupFile = "$backupDir\backup_quiz_education_$timestamp.sql"

# Создать папку если не существует
if (!(Test-Path $backupDir)) {
    New-Item -ItemType Directory -Path $backupDir
}

# Создать backup
C:\xampp\mysql\bin\mysqldump.exe -u root -h 127.0.0.1 quiz_education > $backupFile

# Удалить старые backup (> 7 дней)
Get-ChildItem $backupDir -Filter "backup_*.sql" | 
    Where-Object { $_.LastWriteTime -lt (Get-Date).AddDays(-7) } | 
    Remove-Item

Write-Host "Backup created: $backupFile"
```

### Linux Cron

```bash
# Открыть crontab
crontab -e

# Добавить строку (backup каждый день в 02:00)
0 2 * * * /path/to/quiz/backend/create-backup.sh

# Создать скрипт backend/create-backup.sh:
#!/bin/bash
timestamp=$(date +%Y-%m-%d_%H%M%S)
backup_dir="/backups/quiz"
backup_file="$backup_dir/backup_quiz_education_$timestamp.sql"

mkdir -p $backup_dir
mysqldump -u root -h 127.0.0.1 quiz_education > $backup_file

# Удалить старые backup (> 7 дней)
find $backup_dir -name "backup_*.sql" -mtime +7 -delete

echo "Backup created: $backup_file"
```

## Проверка backup

```powershell
# Проверить список всех backup
Get-ChildItem backup_*.sql | Select-Object Name, Length, LastWriteTime

# Проверить содержимое backup (первые 20 строк)
Get-Content backup_quiz_education_YYYY-MM-DD_HHMMSS.sql -Head 20
```

## Текущий backup

✅ Создан: `backup_quiz_education_2025-12-19_143639.sql` (126.6 KB)
