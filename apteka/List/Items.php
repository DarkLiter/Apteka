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

// Запрос для получения данных о товарах
$sql = "SELECT id_product, name_product, cost FROM Товары";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Аптека - Товары</title>
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
        body {
    font-size: 16px;
    font-family: 'Times New Roman', Times, serif;
   
}
    </style>
</head>
<body>
    <h1>Аптека - Товары</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Название товара</th>
            <th>Цена</th>
            <th>Действия</th>
        </tr>
        <?php
        // Вывод данных из базы данных
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id_product"] . "</td>";
                echo "<td>" . $row["name_product"] . "</td>";
                echo "<td>" . $row["cost"] . "</td>";
                echo "<td><button onclick='editRecord(" . $row["id_product"] . ")'>Редактировать</button></td>"; // Добавленная кнопка "Редактировать"
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Нет данных о товарах</td></tr>";
        }
        ?>
    </table>
    <button onclick="addRecord()">Добавить новую запись</button> <!-- Добавленная кнопка "Добавить новую запись" -->
    <script>
                function addRecord() {
            window.open("../edit/edit_product.php", "_blank");
        }
        function editRecord(productId) {
            window.open("edit/edit_product.php?id=" + productId, "_blank");
        }
    </script>
</body>
</html>

<?php
// Закрытие подключения к базе данных
$conn->close();
?>
