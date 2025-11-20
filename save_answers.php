<?php
// Подключение к базе данных
$servername = "localhost"; 
$username = "euzp_quiz"; 
$password = "Xexy)3S8(6"; 
$dbname = "euzp_quiz"; 

$dbc = mysqli_connect($servername, $username, $password, $dbname);

if (!$dbc) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Ошибка подключения к базе данных"]);
    exit;
}

// Получаем данные из запроса
$data = json_decode(file_get_contents('php://input'), true);

if (empty($data) || !isset($data['name']) || !isset($data['surname']) || !isset($data['email']) || !isset($data['birthday']) || !isset($data['answer'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Нет данных для записи"]);
    exit;
}

$name = mysqli_real_escape_string($dbc, $data['name']);
$surname = mysqli_real_escape_string($dbc, $data['surname']);
$email = mysqli_real_escape_string($dbc, $data['email']);
$birthday = mysqli_real_escape_string($dbc, $data['birthday']);
$answer = mysqli_real_escape_string($dbc, $data['answer']);


$query = "INSERT INTO answers (user_name, user_surname, user_email, user_birthday, user_answer) VALUES ('$name', '$surname', '$email', '$birthday', '$answer')";

if (mysqli_query($dbc, $query)) {
    http_response_code(201);
    echo json_encode(["status" => "success", "message" => "Feedback has been sent"]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Ошибка при записи данных"]);
}

mysqli_close($dbc);
?>