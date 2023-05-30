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
    
} else {
    // Пользователь не авторизован, перенаправляем на страницу входа
    header("Location: ../Login/Login.php");
    exit();
}

// Запрос для получения данных о сотрудниках
$sql = "SELECT id_employee, job_title, first_name, last_name, surname, number FROM сотрудники";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
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
    <nav class="navbar navbar-expand navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand"><?php echo "Вы вошли под логином - " . $currentUser['username'];?></a>
        <a class="navbar-brand" href="#">Аптечная база</a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Главная</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Как работать с базой</a>
            </li>
        </ul>
<a class="btn btn-outline-light" href=".." role="button">
            <i class="bi bi-arrow-left"></i> Назад
        </a>
    </div>
</nav>
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
                echo "<td><button onclick='editRecord(" . $row["id_employee"] . ")' class='btn btn-primary'>Редактировать</button></td>"; // Добавленная кнопка "Редактировать"
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>Нет данных о сотрудниках</td></tr>";
        }
        ?>
    </table>
    <button onclick="addRecord()" class='btn btn-primary'>Добавить новую запись</button> <!-- Добавленная кнопка "Добавить новую запись" -->
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
