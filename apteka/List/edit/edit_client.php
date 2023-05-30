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
    $clientId = $_POST["client_id"];
    $lastName = $_POST["last_name"];
    $firstName = $_POST["first_name"];
    $surname = $_POST["surname"];
    $number = $_POST["number"];

    // Обновление записи в базе данных
    $sql = "UPDATE Клиенты SET last_name = '$lastName', first_name = '$firstName', surname = '$surname', number = '$number' WHERE id_client = '$clientId'";

    if ($conn->query($sql) === TRUE) {
        echo "Запись успешно обновлена";
    } else {
        echo "Ошибка при обновлении записи: " . $conn->error;
    }
}

// Получение ID клиента из параметра URL
$clientId = $_GET["id"];

// Запрос для получения данных о клиенте
$sql = "SELECT id_client, last_name, first_name, surname, number FROM Клиенты WHERE id_client = '$clientId'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Редактирование клиента</title>
</head>
<body>
    <h1>Редактирование клиента</h1>
    <?php
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <input type="hidden" name="client_id" value="<?php echo $row["id_client"]; ?>">
            <label for="last_name">Фамилия:</label>
            <input type="text" name="last_name" value="<?php echo $row["last_name"]; ?>"><br>
            <label for="first_name">Имя:</label>
            <input type="text" name="first_name" value="<?php echo $row["first_name"]; ?>"><br>
            <label for="surname">Отчество:</label>
            <input type="text" name="surname" value="<?php echo $row["surname"]; ?>"><br>
            <label for="number">Номер телефона:</label>
            <input type="text" name="number" value="<?php echo $row["number"]; ?>"><br>
            <input type="submit" value="Сохранить">
        </form>
        <?php
    } else {
        echo "Клиент не найден";
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
        $clientOrdersSql = "SELECT * FROM `Заявки` WHERE `id_client` = '" . $row['id_client'] . "'";
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