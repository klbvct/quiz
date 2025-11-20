<?php
session_start();

// Проверка, авторизован ли пользователь как администратор
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');  // Перенаправление на страницу входа
    exit();
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
<body>

<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Дизайн Освіти</a>
    
    <div class="navbar-nav">
        <div class="nav-item text-nowrap">
            <a class="nav-link px-3" href="./logout.php">Вихід</a>
        </div>
    </div>
</header>
<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3" style="height: 100vh; overflow-y: auto;">
                <ul class="nav flex-column">
                    <?php 
                      include_once './database_functions.php'; 
                      
                      $users = getUserList();
                      $users = array_reverse($users); // Последний пользователь становится первым
                      $activeUserId = $_GET['id'] ?? $users[0]['id'];

                      foreach ($users as $user) {
                          $isActive = ($user['id'] == $activeUserId) ? ' active' : '';
                          echo "<li class='nav-item'><a class='nav-link{$isActive}' href='answers.php?id=" . htmlspecialchars($user['id']) . "'>" . htmlspecialchars($user['user_name'] . " " . $user['user_surname']) . "</a></li>";
                      }
                    ?>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <?php
                $user = getUserById($activeUserId);
                echo "<h1>" . ($user ? htmlspecialchars($user['user_name'] . " " . $user['user_surname']) : "Користувача не знайдено") . "</h1>";
                echo "<h4>" . ($user ? htmlspecialchars('Email: ' . $user['user_email']) : "Користувача не знайдено") . "</h4>";
                echo "<h4>" . ($user ? htmlspecialchars('День народження: ' . date("d-m-Y", strtotime($user['user_birthday']))) : "Користувача не знайдено") . "</h4>";
                ?>
            </div>

            <div class="d-grid gap-2 d-md-block">
            <?php
              $answersData = getUserAnswers($activeUserId);
              $modules = array_column($answersData[0] ?? [], 'modul_name');
                      
              // Определяем активный модуль: если он не установлен, используем первый модуль из списка
              $activeModule = !empty($modules) ? $modules[0] : null;

              echo '<nav class="nav nav-pills flex-column flex-sm-row" id="moduleNav">';
              if (!empty($modules)) {
                  foreach ($modules as $index => $moduleName) {
                      // Если модуль активен, добавляем класс "active"
                      $isActive = ($moduleName === $activeModule) ? ' active' : '';
                      echo '<a class="flex-sm-fill text-sm-center nav-module' . $isActive . '" href="#" onclick="filterAnswers(\'' . htmlspecialchars($moduleName) . '\', this)">' . htmlspecialchars($moduleName) . '</a>';
                  }
              } else {
                  echo '<a class="flex-sm-fill text-sm-center nav-link active" href="#">Модули не найдены</a>';
              }
              echo '</nav>';
            ?>
            </div>
            <div class="row" style="margin:1rem 0;">
                <div class="col-md-6">
                    <!-- Дата складання тесту -->
                    <?php
                        $user = getUserById($activeUserId);
                        echo "<h4>" . ($user ? htmlspecialchars('Дата складання тесту: ' . date("d-m-Y", strtotime($user['submission_time']))) : "Користувача не знайдено") . "</h4>";
                    ?>
                </div>
                <div class="col-md-6 d-md-flex justify-content-md-end">
                    <a class="btn btn-outline-dark" href="export_csv.php?id=<?php echo htmlspecialchars($activeUserId); ?>" role="button">Завантажити .csv</a>
                </div>
            </div>
          
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                    <tr><th scope="col">Питання</th><th scope="col">Відповідь</th></tr>
                    </thead>
                    <tbody id="answersTable">
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<script>
    // // Сохраняем все ответы для каждого модуля в JavaScript
    const allUsers = <?php echo json_encode($answersData); ?>;

    // Функция для фильтрации ответов по модулю
    function filterAnswers(moduleName, element) {
        document.querySelectorAll('.nav-module').forEach(link => link.classList.remove('active'));
        element.classList.add('active');

        const answersTable = document.getElementById('answersTable');
        answersTable.innerHTML = ''; // Очищаем таблицу

        const moduleData = allUsers[0].find(module => module.modul_name === moduleName);
        if (moduleData && moduleData.answers) {
            for (const [question, answer] of Object.entries(moduleData.answers)) {
                const row = `<tr><td>${question}</td><td>${answer}</td></tr>`;
                answersTable.insertAdjacentHTML('beforeend', row);
            }
        } else {
            answersTable.innerHTML = '<tr><td colspan="2">Ответы не найдены для этого модуля.</td></tr>';
        }
    }

    // Показать ответы для первого модуля по умолчанию
    if (allUsers[0] && allUsers[0][0]) {
        filterAnswers(allUsers[0][0].modul_name, document.querySelector('.nav-module'));
    }

</script>

<script src="scripts.js"></script>
<script src="../bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
