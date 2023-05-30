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
    $id_employee = $_POST["id_employee"];
    $id_client = $_POST["id_client"];
    $date_application = $_POST["date_application"];
    $status = isset($_POST["status"]) ? 1 : 0;

    // Проверка подключения
    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }

    // Подготовленный запрос для добавления новой записи
    $stmt = $conn->prepare("INSERT INTO Заявки (id_employee, id_client, date_application, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisi", $id_employee, $id_client, $date_application, $status);

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
    <title>Аптека - Создание новой заявки</title>
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
<body>
<div class="container">
    <h1>Аптека - Создание новой заявки</h1>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <div class="mb-3">
            <label for="id_employee" class="form-label">Код сотрудника:</label>
            <input type="text" name="id_employee" id="id_employee" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="id_client" class="form-label">Код клиента:</label>
            <input type="text" name="id_client" id="id_client" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="date_application" class="form-label">Дата заявки:</label>
            <input type="date" name="date_application" id="date_application" class="form-control" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="status" id="status" class="form-check-input">
            <label for="status" class="form-check-label">Статус заявки</label>
        </div>
        <input type="submit" value="Добавить" class="btn btn-primary">
    </form>
</div>
</body>
</html>
