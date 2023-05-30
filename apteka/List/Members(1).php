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

// Запрос для получения данных о сотрудниках
$sql = "SELECT id_employee, job_title, first_name, last_name, surname, number FROM Сотрудники";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Аптека - Сотрудники</title>
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
    <h1>Аптека - Сотрудники</h1>
    <table>
        <tr>
            <th>ID сотрудника</th>
            <th>Должность</th>
            <th>Фамилия</th>
            <th>Имя</th>
            <th>Отчество</th>
            <th>Номер телефона</th>
            <th>Действия</th>
        </tr>
        <?php
        // Вывод данных из базы данных
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id_employee"] . "</td>";
                echo "<td>" . $row["job_title"] . "</td>";
                echo "<td>" . $row["last_name"] . "</td>";
                echo "<td>" . $row["first_name"] . "</td>";
                echo "<td>" . $row["surname"] . "</td>";
                echo "<td>" . $row["number"] . "</td>";
                echo "<td><button onclick='editRecord(" . $row["id_employee"] . ")'>Редактировать</button></td>"; // Добавленная кнопка "Редактировать"
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>Нет данных о сотрудниках</td></tr>";
        }
        ?>
    </table>
    <button onclick="addRecord()">Добавить новую запись</button> <!-- Добавленная кнопка "Добавить новую запись" -->
    <script>
                function addRecord() {
            window.open("../edit/edit_member.php", "_blank");
        }
        function editRecord(employeeId) {
            window.open("edit/edit_employee.php?id=" + employeeId, "_blank");
        }
    </script>
</body>
</html>

<?php
// Закрытие подключения к базе данных
$conn->close();
?>
