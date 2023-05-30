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
    $id_client = $_POST["id_client"];
    $id_product = $_POST["id_product"];
    $quantity = $_POST["quantity"];

    // Подготовленный запрос для добавления новой записи
    $stmt = $conn->prepare("INSERT INTO Заказы (id_client, id_product, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $id_client, $id_product, $quantity);

    // Выполнение подготовленного запроса
    if ($stmt->execute()) {
        $last_insert_id = $stmt->insert_id; // Получаем последний автоматически сгенерированный ID
        echo "Новая запись успешно добавлена. Код заявки: " . $last_insert_id;
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
    <title>Аптека - Добавление нового заказа</title>
</head>
<body>
    <h1>Аптека - Добавление нового заказа</h1>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="id_client">Код клиента:</label>
        <input type="text" name="id_client" id="id_client" required><br><br>

        <label for="id_product">Код товара:</label>
        <input type="text" name="id_product" id="id_product" required><br><br>

        <label for="quantity">Количество:</label>
        <input type="text" name="quantity" id="quantity" required><br><br>

        <input type="submit" value="Добавить">
    </form>
</body>
</html>
