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
    <title>Авторизация</title>
</head>
<body>
    <h2>Авторизация</h2>
    
    <?php if (isset($loginError)): ?>
        <p style="color: red;"><?php echo $loginError; ?></p>
    <?php endif; ?>
    
    <form method="POST" action="login.php">
        <div>
            <label for="username">Имя пользователя:</label>
            <input type="text" name="username" required>
        </div>
        <div>
            <label for="password">Пароль:</label>
            <input type="password" name="password" required>
        </div>
        <div>
            <button type="submit" name="login">Войти</button>
        </div>
    </form>
</body>
</html>
