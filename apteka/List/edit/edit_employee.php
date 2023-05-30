<?php
require_once('../../config/db.php');
require_once '../../Login/function.php';

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
    header("Location: ../../Login/login.php");
    exit();
}

// Проверка, был ли отправлен идентификатор сотрудника для редактирования
if (isset($_GET["id"])) {
    $employeeId = $_GET["id"];
} else {
    echo "Идентификатор сотрудника не указан.";
    exit;
}

// Запрос для получения информации о выбранном сотруднике
$sql = "SELECT id_employee, job_title, first_name, last_name, surname, number FROM Сотрудники WHERE id_employee = " . $employeeId;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Проверка, была ли отправлена форма с обновленными данными
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $jobTitle = $_POST["job_title"];
        $firstName = $_POST["first_name"];
        $lastName = $_POST["last_name"];
        $surname = $_POST["surname"];
        $number = $_POST["number"];

        // Обновление информации о сотруднике в базе данных
        $updateSql = "UPDATE Сотрудники SET job_title = '$jobTitle', first_name = '$firstName', last_name = '$lastName', surname = '$surname', number = '$number' WHERE id_employee = " . $employeeId;

        if ($conn->query($updateSql) === TRUE) {
            echo "Информация о сотруднике успешно обновлена.";
        } else {
            echo "Ошибка при обновлении информации о сотруднике: " . $conn->error;
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Редактирование информации о сотруднике</title>
</head>
<body>
    <h1>Редактирование информации о сотруднике</h1>
    <form method="POST" action="">
        <label for="job_title">Должность:</label>
        <input type="text" id="job_title" name="job_title" value="<?php echo $row["job_title"]; ?>"><br>

        <label for="first_name">Имя:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo $row["first_name"]; ?>"><br>

        <label for="last_name">Фамилия:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo $row["last_name"]; ?>"><br>

        <label for="surname">Отчество:</label>
        <input type="text" id="surname" name="surname" value="<?php echo $row["surname"]; ?>"><br>

        <label for="number">Номер телефона:</label>
        <input type="text" id="number" name="number" value="<?php echo $row["number"]; ?>"><br>

        <input type="submit" value="Сохранить изменения">
    </form>
</body>
</html>

<?php
} else {
    echo "Сотрудник с указанным идентификатором не найден.";
}
?>
   <h2>Информация о заявках</h2>
    <table>
        <tr>
            <th>Код заявки</th>
            <th>Код сотрудника</th>
            <th>Код клиента</th>
            <th>Дата</th>
            <th>Статус</th>
        </tr>
        <?php
        // Запрос для получения данных о заказах клиента
        $clientOrdersSql = "SELECT * FROM `Заявки` WHERE `id_employee` = '" . $row['id_employee'] . "'";
        $clientOrdersResult = $conn->query($clientOrdersSql);

        if ($clientOrdersResult && $clientOrdersResult->num_rows > 0) {
            while ($orderRow = $clientOrdersResult->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $orderRow["id_application"]; ?></td>
                    <td><?php echo $orderRow["id_employee"]; ?></td>
                    <td><?php echo $orderRow["id_client"]; ?></td>
                    <td><?php echo $orderRow["date_application"]; ?></td>
                    <?php if($orderRow['status'] == "1") {
                    echo "<td><input type='checkbox' id='scales' name='scales' checked onclick='return false;'></td>";
                }
                else {
                    echo "<td><input type='checkbox' id='scales' name='scales' onclick='return false;'></td>";
                } ?>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='5'>Нет доступных заказов</td></tr>";
        }
        ?>
    </table>
</body>
</html>
<?php
// Закрытие подключения к базе данных
$conn->close();
?>
