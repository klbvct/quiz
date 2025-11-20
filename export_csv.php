<?php
session_start();

// Проверка авторизации администратора
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');
    exit();
}

// Подключение к базе данных и функции
include_once './database_functions.php';

// Получаем ID пользователя из URL
$userId = $_GET['id'] ?? null;
if (!$userId) {
    die("Ошибка: Не указан ID пользователя.");
}

// Получаем данные пользователя и его ответы
$user = getUserById($userId);
$answersData = getUserAnswers($userId);

// Проверка наличия данных пользователя
if (!$user) {
    die("Ошибка: Пользователь не найден.");
}

// Заголовки для скачивания CSV
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="user_answers.csv"');

// Добавляем BOM для корректной кодировки UTF-8 в Excel
echo "\xEF\xBB\xBF";

// Открываем "файл" для вывода
$output = fopen('php://output', 'w');

// Добавляем строки с основной информацией о пользователе
fputcsv($output, ['Ім\'я', $user['user_name'] . ' ' . $user['user_surname']]);
fputcsv($output, ['Email', $user['user_email']]);
fputcsv($output, ['День народження', date("d-m-Y", strtotime($user['user_birthday']))]);
fputcsv($output, []); // Пустая строка для разделения

// Добавляем заголовок для раздела с ответами
fputcsv($output, ['Модуль', 'Питання', 'Відповідь']);

// Проверяем наличие ответов и добавляем их в CSV
if (!empty($answersData)) {
    // Убедимся, что мы проходим по каждому модулю
    foreach ($answersData as $moduleArray) {
        foreach ($moduleArray as $module) { // Перебираем каждый модуль в текущем массиве
            // Проверяем, что в модуле есть имя и ответы
            if (isset($module['modul_name']) && isset($module['answers']) && !empty($module['answers'])) {
                $moduleName = $module['modul_name'];
                // Проходим по всем ответам
                foreach ($module['answers'] as $question => $answer) {
                    fputcsv($output, [$moduleName, $question, $answer]);
                }
            }
        }
    }
} else {
    fputcsv($output, ['Дані для відповідей не знайдені']);
}

// Закрываем поток
fclose($output);
exit();
?>
