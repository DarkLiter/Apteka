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
    <title>Аптека - Создание новой заявки</title>
</head>
<body>
    <h1>Аптека - Создание новой заявки</h1>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="id_employee">Код сотрудника:</label>
        <input type="text" name="id_employee" id="id_employee" required><br><br>

        <label for="id_client">Код клиента:</label>
        <input type="text" name="id_client" id="id_client" required><br><br>

        <label for="date_application">Дата заявки:</label>
        <input type="date" name="date_application" id="date_application" required><br><br>

        <label for="status">Статус заявки:</label>
        <input type="checkbox" name="status" id="status"><br><br>

        <input type="submit" value="Добавить">
    </form>
</body>
</html>
