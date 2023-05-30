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
    $id_provider = $_POST["id_provider"];
    $id_product = $_POST["id_product"];
    $id_request = $_POST["id_request"];
    $date_supply = $_POST["date_supply"];
    $quantity = $_POST["quantity"];

    // Подготовленный запрос для добавления новой записи
    $stmt = $conn->prepare("INSERT INTO поставки (id_provider, id_product, id_request, date_supply, quantity) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiisi", $id_provider, $id_product, $id_request, $date_supply, $quantity);

    // Выполнение подготовленного запроса
    if ($stmt->execute()) {
        $last_insert_id = $stmt->insert_id; // Получаем последний автоматически сгенерированный ID
        echo "Новая запись успешно добавлена. Код поставки: " . $last_insert_id;
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
    <title>Аптека - Добавление новой поставки</title>
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
    <h1>Аптека - Добавление новой поставки</h1>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <div class="mb-3">
            <label for="id_provider" class="form-label">Код поставщика:</label>
            <input type="text" name="id_provider" id="id_provider" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="id_product" class="form-label">Код товара:</label>
            <input type="text" name="id_product" id="id_product" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="id_request" class="form-label">Код запроса:</label>
            <input type="text" name="id_request" id="id_request" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="date_supply" class="form-label">Дата поставки:</label>
            <input type="text" name="date_supply" id="date_supply" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Количество:</label>
            <input type="text" name="quantity" id="quantity" class="form-control" required>
        </div>
        <input type="submit" value="Добавить" class="btn btn-primary">
    </form>
</div>
</body>
</html>
