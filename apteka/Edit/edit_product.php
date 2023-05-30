<?php
require_once('../config/db.php');
require_once '../Login/function.php';

// Запускаем сессию
session_start();

// Проверяем авторизацию пользователя
if (isLoggedIn()) {
    // Получаем данные текущего пользователя
    $currentUser = getCurrentUser();
    
    // Используем данные текущего пользователя
    
} else {
    // Пользователь не авторизован, перенаправляем на страницу входа
    header("Location: ../Login/Login.php");
    exit();
}
// Проверяем, была ли отправлена форма с данными для добавления новой записи
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем значения полей из формы
    $name_product = $_POST["name_product"];
    $cost = $_POST["cost"];

    // Получение следующего доступного ID товара
    $nextIdQuery = "SELECT MAX(id_product) AS maxId FROM Товары";
    $result = $conn->query($nextIdQuery);
    $row = $result->fetch_assoc();
    $nextId = $row["maxId"] + 1;

    // Подготовленный запрос для добавления новой записи
    $stmt = $conn->prepare("INSERT INTO Товары (id_product, name_product, cost) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $nextId, $name_product, $cost);

    // Выполнение подготовленного запроса
    if ($stmt->execute()) {
        echo "Новая запись успешно добавлена.";
    } else {
        echo "Ошибка при добавлении новой записи: " . $stmt->error;
    }

    // Закрытие подключения к базе данных
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Аптека - Добавление нового товара</title>
</head>
<body>
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
<a class="btn btn-outline-light" href=".." role="button">
            <i class="bi bi-arrow-left"></i> Назад
        </a>
    </div>
</nav>
<div class="container">
    <h1>Аптека - Добавление нового товара</h1>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <div class="mb-3">
            <label for="name_product" class="form-label">Название товара:</label>
            <input type="text" name="name_product" id="name_product" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="cost" class="form-label">Цена:</label>
            <input type="text" name="cost" id="cost" class="form-control" required>
        </div>
        <input type="submit" value="Добавить" class="btn btn-primary">
    </form>
</body>
</html>
