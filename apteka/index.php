<?php
// Подключаем конфигурацию и функции
require_once 'config/db.php';
require_once 'Login/function.php';

// Запускаем сессию
session_start();

// Проверяем авторизацию пользователя
if (isLoggedIn()) {
    // Получаем данные текущего пользователя
    $currentUser = getCurrentUser();
    
    // Используем данные текущего пользователя
    echo "Вы вошли под логином - " . $currentUser['username'];
} else {
    // Пользователь не авторизован, перенаправляем на страницу входа
    header("Location: Login/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Админ-Панель</title>
    <link rel="stylesheet" href="style.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>
<style>
    body {
        font-size: 16px;
        font-family: 'Times New Roman', Times, serif;
        
    }
    a {
        padding: 8px 10px;
        border: solid 2px;
        margin: 10px auto;
        text-decoration: none;
        text-align: center;
    }
    a:hover {
        color: red;
    }
    h2 {
        text-align: center;
    }
</style>
<h2>Таблицы</h2>
<div class="container">
<a href="List/Items.php">Товары</a>

<a href="List/Clients.php">Клиенты</a>

<a href="List/Members.php">Сотрудники</a>

<a href="List/Orders.php">Заказы</a>

<a href="List/Applications.php">Заявки</a>

<a href="List/R_Items.php">Запрос товаров</a>

<a href="List/Supplies.php">Поставки</a>

<a href="List/Suppliers.php">Поставщики</a>
</div>
<hr>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>