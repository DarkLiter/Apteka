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
$sql = "SELECT id_provider, organization, supervisor, number FROM поставщики";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Аптека - Поставщики</title>
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
    <h1>Аптека - Поставщики</h1>
    <table>
        <tr>
            <th>Код поставщика</th>
            <th>Организация</th>
            <th>Руководитель</th>
            <th>Номер телефона</th>
            <th>Действие</th>
        </tr>
        <?php
        // Вывод данных из базы данных
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id_provider"] . "</td>";
                echo "<td>" . $row["organization"] . "</td>";
                echo "<td>" . $row["supervisor"] . "</td>";
                echo "<td>" . $row["number"] . "</td>";
                echo "<td><button onclick='editRecord(" . $row["id_provider"] . ")'>Редактировать</button></td>";
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
            window.open("../edit/edit_provide.php", "_blank");
        }
        function editRecord(employeeId) {
            window.open("edit/edit_provider.php?id=" + employeeId, "_blank");
        }
    </script>
</body>
</html>

<?php
// Закрытие подключения к базе данных
$conn->close();
?>
