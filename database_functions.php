<?php

// Функция подключения к базе данных
function connectToDatabase() {
    $servername = "localhost"; 
    $username = "euzp_quiz"; 
    $password = "Xexy)3S8(6"; 
    $dbname = "euzp_quiz"; 

    $dbc = mysqli_connect($servername, $username, $password, $dbname);

    if (!$dbc) {
        die("Ошибка подключения к базе данных: " . mysqli_connect_error());
    }

    return $dbc;
}

// Функция для получения списка пользователей
function getUserList() {
    $dbc = connectToDatabase();
    // Извлечение id, user_name и user_surname
    $query = "SELECT id, user_name, user_surname FROM answers";
    $result = mysqli_query($dbc, $query);
    $users = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
    }

    mysqli_close($dbc);
    return $users;
}

// Функция для получения информации о пользователе по ID
function getUserById($userId) {
    $dbc = connectToDatabase();
    $query = "SELECT user_name, user_surname, user_email, user_birthday, submission_time FROM answers WHERE id = ?";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $user = null;
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    }

    mysqli_close($dbc);
    return $user;
}

// Функция для получения ответов пользователя по его ID
function getUserAnswers($userId) {
    $dbc = connectToDatabase();
    $query = "SELECT user_answer FROM answers WHERE id = ?"; // Используем столбец id
    $stmt = mysqli_prepare($dbc, $query);

    if (!$stmt) {
        die("Ошибка подготовки запроса: " . mysqli_error($dbc));
    }

    // Привязываем параметр $userId к запросу
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    if (!mysqli_stmt_execute($stmt)) {
        die("Ошибка выполнения запроса: " . mysqli_stmt_error($stmt));
    }

    $result = mysqli_stmt_get_result($stmt);
    $userAnswers = [];
    
    // Извлечение ответов из результата
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Декодируем JSON и добавляем в массив
            $decodedAnswer = json_decode($row['user_answer'], true);
            if (json_last_error() === JSON_ERROR_NONE) { // Проверяем, произошла ли ошибка
                $userAnswers[] = $decodedAnswer;
            } else {
                die("Ошибка декодирования JSON: " . json_last_error_msg());
            }
        }
    } else {
        echo "Ответы не найдены для пользователя с ID: $userId";
    }

    mysqli_close($dbc);
    return $userAnswers; // Возвращаем массив ответов
}




?>
