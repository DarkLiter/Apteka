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
    <title>Аптека - Добавление нового сотрудника</title>
</head>
<body>
    <h1>Аптека - Добавление нового сотрудника</h1>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="job_title">Должность:</label>
        <input type="text" name="job_title" id="job_title" required><br><br>

        <label for="first_name">Имя:</label>
        <input type="text" name="first_name" id="first_name" required><br><br>

        <label for="last_name">Фамилия:</label>
        <input type="text" name="last_name" id="last_name" required><br><br>

        <label for="surname">Отчество:</label>
        <input type="text" name="surname" id="surname" required><br><br>

        <label for="number">Номер телефона:</label>
        <input type="text" name="number" id="number" required><br><br>

        <input type="submit" value="Добавить">
    </form>
</body>
</html>
