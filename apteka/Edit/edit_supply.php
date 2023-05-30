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
    <title>Аптека - Добавление новой поставки</title>
</head>
<body>
    <h1>Аптека - Добавление новой поставки</h1>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="id_provider">Код поставщика:</label>
        <input type="text" name="id_provider" id="id_provider" required><br><br>

        <label for="id_product">Код товара:</label>
        <input type="text" name="id_product" id="id_product" required><br><br>

        <label for="id_request">Код запроса:</label>
        <input type="text" name="id_request" id="id_request" required><br><br>

        <label for="date_supply">Дата поставки:</label>
        <input type="text" name="date_supply" id="date_supply" required><br><br>

        <label for="quantity">Количество:</label>
        <input type="text" name="quantity" id="quantity" required><br><br>

        <input type="submit" value="Добавить">
    </form>
</body>
</html>
