<?php
// Подключаем конфигурацию и функции
require_once '../config/db.php';
require_once 'function.php';

// Запускаем сессию
session_start();

// Проверяем авторизацию пользователя
if (isLoggedIn()) {
    // Пользователь уже авторизован, перенаправляем на защищенную страницу
    header("Location: ../index.php");
    exit();
}

// Обработка формы авторизации
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Попытка авторизации
    if (loginUser($username, $password)) {
        // Авторизация успешна, перенаправляем на защищенную страницу
        header("Location: ../index.php");
        exit();
    } else {
        // Неверное имя пользователя или пароль
        $loginError = "Неверное имя пользователя или пароль.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Авторизация</title>
</head>
<body>
<div class="container">
    <h2>Авторизация</h2>

    <?php if (isset($loginError)): ?>
        <p style="color: red;"><?php echo $loginError; ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <div class="mb-3">
            <label for="username" class="form-label">Имя пользователя:</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Пароль:</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div>
            <button type="submit" name="login" class="btn btn-primary">Войти</button>
        </div>
    </form>
</div>
</body>
</html>
