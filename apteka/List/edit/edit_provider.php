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

// Проверяем, был ли отправлен идентификатор поставщика для редактирования
if (isset($_GET["id"])) {
    $providerId = $_GET["id"];

    // Запрос для получения данных о поставщике
    $sql = "SELECT id_provider, organization, supervisor, number FROM поставщики WHERE id_provider = '$providerId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $idProvider = $row["id_provider"];
        $organization = $row["organization"];
        $supervisor = $row["supervisor"];
        $number = $row["number"];
    } else {
        echo "Поставщик не найден.";
        exit;
    }
} else {
    echo "Идентификатор поставщика не указан.";
    exit;
}

// Проверяем, была ли отправлена форма с обновленными данными поставщика
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idProvider = $_POST["id_provider"];
    $organization = $_POST["organization"];
    $supervisor = $_POST["supervisor"];
    $number = $_POST["number"];

    // Обновляем данные поставщика в базе данных
    $updateSql = "UPDATE поставщики SET organization = '$organization', supervisor = '$supervisor', number = '$number' WHERE id_provider = '$idProvider'";
    if ($conn->query($updateSql) === TRUE) {
        echo "Данные о поставщике успешно обновлены.";
    } else {
        echo "Ошибка при обновлении данных о поставщике: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Аптека - Редактирование поставщика</title>
</head>
<body>
    <h1>Редактирование поставщика</h1>
    <form method="POST" action="">
        <input type="hidden" name="id_provider" value="<?php echo $idProvider; ?>">
        <label for="organization">Организация:</label>
        <input type="text" name="organization" value="<?php echo $organization; ?>"><br><br>
        <label for="supervisor">Руководитель:</label>
        <input type="text" name="supervisor" value="<?php echo $supervisor; ?>"><br><br>
        <label for="number">Номер телефона:</label>
        <input type="text" name="number" value="<?php echo $number; ?>"><br><br>
        <input type="submit" value="Сохранить изменения">
    </form>
</body>
</html>

   <h2>Информация о поставках</h2>
    <table>
        <tr>
            <th>Код поставки</th>
            <th>Код поставщика</th>
            <th>Код товара</th>
            <th>Код запроса</th>
            <th>Дата</th>
            <th>Количество</th>
        </tr>
        <?php
        // Запрос для получения данных о заказах клиента
        $clientOrdersSql = "SELECT * FROM `поставки` WHERE `id_provider` = '" . $row['id_provider'] . "'";
        $clientOrdersResult = $conn->query($clientOrdersSql);

        if ($clientOrdersResult && $clientOrdersResult->num_rows > 0) {
            while ($orderRow = $clientOrdersResult->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $orderRow["id_supply"]; ?></td>
                    <td><?php echo $orderRow["id_provider"]; ?></td>
                    <td><?php echo $orderRow["id_product"]; ?></td>
                    <td><?php echo $orderRow["id_request"]; ?></td>
                    <td><?php echo $orderRow["date_supply"]; ?></td>
                    <td><?php echo $orderRow["quantity"]; ?></td>
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