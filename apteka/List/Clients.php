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

// Проверка подключения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Запрос для получения данных о клиентах
$sql = "SELECT id_client, first_name, last_name, surname, number FROM Клиенты";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Аптека - Клиенты</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Аптека - Клиенты</h1>
    <table>
        <tr>
            <th>ID Клиента</th>
            <th>Фамилия</th>
            <th>Имя</th>
            <th>Отчество</th>
            <th>Номер телефона</th>
        </tr>
        <?php
        // Вывод данных из базы данных
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id_client"] . "</td>";
                echo "<td>" . $row["last_name"] . "</td>";
                echo "<td>" . $row["first_name"] . "</td>";
                echo "<td>" . $row["surname"] . "</td>";
                echo "<td>" . $row["number"] . "</td>";
                echo "<td><button onclick='editRecord(" . $row["id_client"] . ")'>Редактировать</button></td>"; // Добавленная кнопка "Редактировать"
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Нет данных о клиентах</td></tr>";
        }
        ?>
    </table>
    <button onclick="addRecord()">Добавить новую запись</button> <!-- Добавленная кнопка "Добавить новую запись" -->
    <script>
                function addRecord() {
            window.open("../edit/edit_client.php", "_blank");
        }
function editRecord(clientId) {
    // Открывает форму редактирования в новом окне или модальном окне
    window.open("edit/edit_client.php?id=" + clientId, "_blank"); // Измените "edit_client.php" на ваш файл редактирования
}
</script>
</body>
</html>

<?php
// Закрытие подключения к базе данных
$conn->close();
?>
