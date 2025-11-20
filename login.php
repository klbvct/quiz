<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Данные для авторизации
    $adminUsername = 'marianna';  // логин администратора
    $adminPassword = 'password123';  // пароль администратора (лучше хранить хэш)

    // Проверяем введенные данные
    if ($_POST['username'] === $adminUsername && $_POST['password'] === $adminPassword) {
        // Устанавливаем сессию для администратора
        $_SESSION['is_admin'] = true;
        header('Location: answers.php');  // Перенаправление на защищенную страницу
        exit();
    } else {
        echo "Invalid login credentials. Please try again.";
    }
}
?>

<!doctype html>
<html lang="uk">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">

    <link rel="icon" href="/favicon.ico">
    <title>Профорієнтаційна діагностика - Дизайн Освіти</title>

    <!-- Bootstrap core CSS -->
    <link href="../bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../grid.css" rel="stylesheet">
  </head>
  <body class="text-center">
    <h1>Переглянути результати тестування</h1>
    <form action="login.php" method="post" class="form-signin">
        <label for="username">Логін:</label>
        <input type="text" name="username" class="form-control" required>
        <br>
        <label for="password">Пароль:</label>
        <input type="password" name="password" class="form-control" required>
        <br>
        <button type="submit" class="w-100 btn btn-lg btn-primary">Увійти</button>
    </form>
</body>
</html>
