<?php
// Параметры подключения к базе данных
$host = "localhost";
$db_name = "aptekadb";
$username = "root";
$password = "";

// Подключение к базе данных
$conn = new mysqli($host, $username, $password, $db_name);

// Проверка на ошибку подключения
if ($conn->connect_errno) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}
?>
