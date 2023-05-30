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

// Обработка отправки формы редактирования
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST["product_id"];
    $productName = $_POST["product_name"];
    $productCost = $_POST["product_cost"];

    // Запрос для обновления данных о товаре
    $sql = "UPDATE Товары SET name_product = '$productName', cost = '$productCost' WHERE id_product = '$productId'";

    if ($conn->query($sql) === TRUE) {
        echo "Запись успешно обновлена";
    } else {
        echo "Ошибка при обновлении записи: " . $conn->error;
    }
}

// Получение ID товара из параметра URL
$productId = $_GET["id"];

// Запрос для получения данных о товаре
$sql = "SELECT id_product, name_product, cost FROM Товары WHERE id_product = '$productId'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Редактирование товара</title>
</head>
<body>
    <h1>Редактирование товара</h1>
    <?php
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <input type="hidden" name="product_id" value="<?php echo $row["id_product"]; ?>">
            <label for="product_name">Название товара:</label>
            <input type="text" name="product_name" value="<?php echo $row["name_product"]; ?>"><br>
            <label for="product_cost">Цена:</label>
            <input type="text" name="product_cost" value="<?php echo $row["cost"]; ?>"><br>
            <input type="submit" value="Сохранить">
        </form>
        <?php
    } else {
        echo "Товар не найден";
    }
    ?>
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
        $clientOrdersSql = "SELECT * FROM `поставки` WHERE `id_product` = '" . $row['id_product'] . "'";
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
