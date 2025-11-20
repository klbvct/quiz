<?php
// Получаем данные POST-запроса
$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

if (isset($data['name']) && isset($data['surname']) && isset($data['email'])) {
    $name = $data['name'];
    $surname = $data['surname'];
    $email = $data['email'];
   
    // Формируем текст письма
    $message = "Ім'я: " . $name . " " . $surname . "\n";
    $message .= "Email: " . $email . "\n\n";
    
    // Заголовки письма
    $headers = "From: quiz@education-design.com.ua\r\n";
    $headers .= "Reply-To: quiz@education-design.com.ua\r\n";
    $headers .= "Content-type: text/plain; charset=UTF-8\r\n";

    // Отправка письма
    $subject = "Результати тестування";
    $to = 'info@studyway.com.ua';

    if (mail($to, $subject, $message, $headers)) {
        echo json_encode(['status' => 'success', 'message' => 'Email успешно отправлен!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Ошибка при отправке email']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Недостаточно данных для отправки']);
}
?>
