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
    <title>Аптека - Добавление нового поставщика</title>
</head>
<body>
    <h1>Аптека - Добавление нового поставщика</h1>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="organization">Организация:</label>
        <input type="text" name="organization" id="organization" required><br><br>

        <label for="supervisor">Руководитель:</label>
        <input type="text" name="supervisor" id="supervisor" required><br><br>

        <label for="number">Номер телефона:</label>
        <input type="text" name="number" id="number" required><br><br>

        <input type="submit" value="Добавить">
    </form>
</body>
</html>
