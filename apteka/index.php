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
    
} else {
    // Пользователь не авторизован, перенаправляем на страницу входа
    header("Location: Login/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
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
    .table {
        margin-top: 20px;
    }

    .table th {
        background-color: #f8f9fa;
        text-align: center;
        font-weight: bold;
    }

    .table td {
        text-align: center;
    }

    .table a {
        color: #007bff;
        text-decoration: none;
    }

    .table a:hover {
        text-decoration: underline;
    }

    ul.navbar {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #f8f9fa;
        }

        ul.navbar li {
            float: left;
        }

        ul.navbar li a {
            display: block;
            color: #333;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        ul.navbar li a:hover {
            background-color: #ddd;
        }

        ul.navbar li.active a {
            background-color: #007bff;
            color: white;
        }
</style>
<nav class="navbar navbar-expand navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand"><?php echo "Вы вошли под логином - " . $currentUser['username'];?></a>
        <a class="navbar-brand" href="#">Аптечная база</a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Главная</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Как работать с базой</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container">
    <table class="table">
        <thead>
        <tr>
            <th>Таблица</th>
            <th>Ссылка</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Товары</td>
            <td><a href="List/Items.php">Перейти</a></td>
        </tr>
        <tr>
            <td>Клиенты</td>
            <td><a href="List/Clients.php">Перейти</a></td>
        </tr>
        <tr>
            <td>Сотрудники</td>
            <td><a href="List/Members.php">Перейти</a></td>
        </tr>
        <tr>
            <td>Заказы</td>
            <td><a href="List/Orders.php">Перейти</a></td>
        </tr>
        <tr>
            <td>Заявки</td>
            <td><a href="List/Applications.php">Перейти</a></td>
        </tr>
        <tr>
            <td>Запрос товаров</td>
            <td><a href="List/R_Items.php">Перейти</a></td>
        </tr>
        <tr>
            <td>Поставки</td>
            <td><a href="List/Supplies.php">Перейти</a></td>
        </tr>
        <tr>
            <td>Поставщики</td>
            <td><a href="List/Suppliers.php">Перейти</a></td>
        </tr>
        </tbody>
    </table>
</div>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>