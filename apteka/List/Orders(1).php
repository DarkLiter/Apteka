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

// Запрос для получения данных о заказах
$sql = "SELECT id_order, id_application, id_client, id_product, qyantity FROM Заказы";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Аптека - Заказы</title>
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
    <h1>Аптека - Заказы</h1>
    <table>
        <tr>
            <th>ID заказа</th>
            <th>Код заявки</th>
            <th>Код клиента</th>
            <th>Код товара</th>
            <th>Количество</th>
            <th>Действия</th>
        </tr>
        <?php
        // Вывод данных из базы данных
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id_order"] . "</td>";
                echo "<td>" . $row["id_application"] . "</td>";
                echo "<td>" . $row["id_client"] . "</td>";
                echo "<td>" . $row["id_product"] . "</td>";
                echo "<td>" . $row["qyantity"] . "</td>";
                echo "<td><button onclick='editRecord(" . $row["id_order"] . ")'>Редактировать</button></td>"; // Добавленная кнопка "Редактировать"
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Нет данных о заказах</td></tr>";
        }
        ?>
    </table>
    <button onclick="addRecord()">Добавить новую запись</button> <!-- Добавленная кнопка "Добавить новую запись" -->
    <script>
                function addRecord() {
            window.open("../edit/edit_order.php", "_blank");
        }
        function editRecord(employeeId) {
            window.open("edit/edit_order.php?id=" + employeeId, "_blank");
        }
    </script>
</body>
</html>

<?php
// Закрытие подключения к базе данных
$conn->close();
?>
