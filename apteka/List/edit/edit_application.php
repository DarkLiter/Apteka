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

// Проверка подключения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Проверка, если форма была отправлена
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных из формы
    $applicationId = $_POST["application_id"];
    $employeeId = $_POST["employee_id"];
    $clientId = $_POST["client_id"];
    $dateApplication = $_POST["date_application"];
    $status = isset($_POST["status"]) ? 1 : 0;

    // Обновление записи в базе данных
    $sql = "UPDATE Заявки SET id_employee = '$employeeId', id_client = '$clientId', date_application = '$dateApplication', status = '$status' WHERE id_application = '$applicationId'";

    if ($conn->query($sql) === TRUE) {
        echo "Запись успешно обновлена";
    } else {
        echo "Ошибка при обновлении записи: " . $conn->error;
    }
}

// Получение ID заявки из параметра URL
$applicationId = $_GET["id"];

// Запрос для получения данных о заявке
$sql = "SELECT id_application, id_employee, id_client, date_application, status FROM Заявки WHERE id_application = '$applicationId'";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Редактирование заявки</title>
</head>
<body>
    <h1>Редактирование заявки</h1>
    <?php
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <input type="hidden" name="application_id" value="<?php echo $row["id_application"]; ?>">
            <label for="employee_id">Код сотрудника:</label>
            <input type="text" name="employee_id" value="<?php echo $row["id_employee"]; ?>"><br>
            <label for="client_id">Код клиента:</label>
            <input type="text" name="client_id" value="<?php echo $row["id_client"]; ?>"><br>
            <label for="date_application">Дата заявки:</label>
            <input type="text" name="date_application" value="<?php echo $row["date_application"]; ?>"><br>
            <label for="status">Статус заявки:</label>
            <input type="checkbox" id="status" name="status" <?php echo $row["status"] == 1 ? "checked" : ""; ?>><br>
            <input type="submit" value="Сохранить">
        </form>
        <?php
    } else {
        echo "Заявка не найдена";
    }
    ?>

    <h2>Информация о заказах клиента</h2>
    <table>
        <tr>
            <th>Код заказа</th>
            <th>Код заявки</th>
            <th>Код товара</th>
            <th>Количество</th>
        </tr>
        <?php
        // Запрос для получения данных о заказах клиента
        $clientOrdersSql = "SELECT * FROM `заказы` WHERE `id_client` = '" . $row['id_client'] . "'";
        $clientOrdersResult = $conn->query($clientOrdersSql);

        if ($clientOrdersResult && $clientOrdersResult->num_rows > 0) {
            while ($orderRow = $clientOrdersResult->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $orderRow["id_order"]; ?></td>
                    <td><?php echo $orderRow["id_application"]; ?></td>
                    <td><?php echo $orderRow["id_product"]; ?></td>
                    <td><?php echo $orderRow["qyantity"]; ?></td>
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
