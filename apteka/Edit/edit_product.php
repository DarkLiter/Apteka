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
    echo "Вы вошли под логином - " . $currentUser['username'];
} else {
    // Пользователь не авторизован, перенаправляем на страницу входа
    header("Location: ../Login/login.php");
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
    <title>Аптека - Добавление нового товара</title>
</head>
<body>
    <h1>Аптека - Добавление нового товара</h1>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="name_product">Название товара:</label>
        <input type="text" name="name_product" id="name_product" required><br><br>

        <label for="cost">Цена:</label>
        <input type="text" name="cost" id="cost" required><br><br>

        <input type="submit" value="Добавить">
    </form>
</body>
</html>
