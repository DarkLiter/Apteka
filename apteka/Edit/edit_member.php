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
    $job_title = $_POST["job_title"];
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $surname = $_POST["surname"];
    $number = $_POST["number"];

    // Подготовленный запрос для добавления новой записи
    $stmt = $conn->prepare("INSERT INTO Сотрудники (job_title, first_name, last_name, surname, number) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $job_title, $first_name, $last_name, $surname, $number);

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
    <title>Аптека - Добавление нового сотрудника</title>
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
    <h1>Аптека - Добавление нового сотрудника</h1>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <div class="mb-3">
            <label for="job_title" class="form-label">Должность:</label>
            <input type="text" name="job_title" id="job_title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="first_name" class="form-label">Имя:</label>
            <input type="text" name="first_name" id="first_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="last_name" class="form-label">Фамилия:</label>
            <input type="text" name="last_name" id="last_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="surname" class="form-label">Отчество:</label>
            <input type="text" name="surname" id="surname" class="form-control" required>
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
