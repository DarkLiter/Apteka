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
    $organization = $_POST["organization"];
    $supervisor = $_POST["supervisor"];
    $number = $_POST["number"];

    // Проверка подключения
    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }

    // Подготовленный запрос для добавления новой записи
    $stmt = $conn->prepare("INSERT INTO поставщики (organization, supervisor, number) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $organization, $supervisor, $number);

    // Выполнение подготовленного запроса
    if ($stmt->execute()) {
        $last_insert_id = $stmt->insert_id; // Получаем последний автоматически сгенерированный ID
        echo "Новая запись успешно добавлена. Код поставщика: " . $last_insert_id;
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
    <title>Аптека - Добавление нового поставщика</title>
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
    <h1>Аптека - Добавление нового поставщика</h1>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <div class="mb-3">
            <label for="organization" class="form-label">Организация:</label>
            <input type="text" name="organization" id="organization" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="supervisor" class="form-label">Руководитель:</label>
            <input type="text" name="supervisor" id="supervisor" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="number" class="form-label">Номер телефона:</label>
            <input type="text" name="number" id="number" class="form-control" required>
        </div>
        <input type="submit" value="Добавить" class="btn btn-primary">
    </form>
</div>
</body>
</html>
