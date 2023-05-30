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

// Запрос для получения данных о заявках
$sql = "SELECT id_request, id_employee, id_product, name_product, quantity FROM zapros";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Аптека - Запрос товаров</title>
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
    <h1>Аптека - Запрос Товаров</h1>
    <table>
        <tr>
            <th>Код запроса</th>
            <th>Код сотрудника</th>
            <th>Код товара</th>
            <th>Название товара</th>
            <th>Количество</th>
            <th>Действие</th>
        </tr>
        <?php
        // Вывод данных из базы данных
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id_request"] . "</td>";
                echo "<td>" . $row["id_employee"] . "</td>";
                echo "<td>" . $row["id_product"] . "</td>";
                echo "<td>" . $row["name_product"] . "</td>";
                echo "<td>" . $row["quantity"] . "</td>";
                echo "<td><button onclick='editRecord(" . $row["id_request"] . ")'>Редактировать</button></td>"; // Добавленная кнопка "Редактировать"
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Нет данных о заявках</td></tr>";
        }
        ?>
    </table>
    <button onclick="addRecord()">Добавить новую запись</button> <!-- Добавленная кнопка "Добавить новую запись" -->
    <script>
        function addRecord() {
            window.open("../edit/edit_request.php", "_blank");
        }
        function editRecord(employeeId) {
            window.open("edit/edit_request.php?id=" + employeeId, "_blank");
        }
    </script>
</body>
</html>

<?php
// Закрытие подключения к базе данных
$conn->close();
?>
