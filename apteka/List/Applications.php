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
$sql = "SELECT id_application, id_employee, id_client, date_application, status FROM Заявки";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Аптека - Заявки</title>
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
    <h1>Аптека - Заявки</h1>
    <table>
        <tr>
            <th>Код заявки</th>
            <th>Код сотрудника</th>
            <th>Код клиента</th>
            <th>Дата заявки</th>
            <th>Статус заявки</th>
        </tr>
        <?php
        // Вывод данных из базы данных
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id_application"] . "</td>";
                echo "<td>" . $row["id_employee"] . "</td>";
                echo "<td>" . $row["id_client"] . "</td>";
                echo "<td>" . $row["date_application"] . "</td>";
                if($row['status'] == "1") {
                    echo "<td><input type='checkbox' id='scales' name='scales' checked onclick='return false;'></td>";
                }
                else {
                    echo "<td><input type='checkbox' id='scales' name='scales' onclick='return false;'></td>";
                }
                echo "<td><button onclick='editRecord(" . $row["id_application"] . ")'>Редактировать</button></td>";
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
            window.open("../edit/edit_application.php", "_blank");
        }
        
        function editRecord(applicationId) {
            window.open("edit/edit_application.php?id=" + applicationId, "_blank");
        }
    </script>
</body>
</html>

<?php
// Закрытие подключения к базе данных
$conn->close();
?>
