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

// Проверяем, был ли отправлен идентификатор заказа для редактирования
if (isset($_GET["id"])) {
    $orderId = $_GET["id"];

    // Запрос для получения данных о заказе
    $sql = "SELECT id_order, id_application, id_client, id_product, qyantity FROM Заказы WHERE id_order = '$orderId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $idOrder = $row["id_order"];
        $idApplication = $row["id_application"];
        $idClient = $row["id_client"];
        $idProduct = $row["id_product"];
        $quantity = $row["qyantity"];
    } else {
        echo "Заказ не найден.";
        exit;
    }
} else {
    echo "Идентификатор заказа не указан.";
    exit;
}

// Проверяем, была ли отправлена форма с обновленными данными заказа
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idOrder = $_POST["id_order"];
    $idApplication = $_POST["id_application"];
    $idClient = $_POST["id_client"];
    $idProduct = $_POST["id_product"];
    $quantity = $_POST["quantity"];

    // Обновляем данные заказа в базе данных
    $updateSql = "UPDATE Заказы SET id_application = '$idApplication', id_client = '$idClient', id_product = '$idProduct', qyantity = '$quantity' WHERE id_order = '$idOrder'";
    if ($conn->query($updateSql) === TRUE) {
        echo "Данные о заказе успешно обновлены.";
    } else {
        echo "Ошибка при обновлении данных о заказе: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Аптека - Редактирование заказа</title>
</head>
<body>
    <h1>Редактирование заказа</h1>
    <form method="POST" action="">
        <input type="hidden" name="id_order" value="<?php echo $idOrder; ?>">
        <label for="id_application">Код заявки:</label>
        <input type="text" name="id_application" value="<?php echo $idApplication; ?>"><br><br>
        <label for="id_client">Код клиента:</label>
        <input type="text" name="id_client" value="<?php echo $idClient; ?>"><br><br>
        <label for="id_product">Код товара:</label>
        <input type="text" name="id_product" value="<?php echo $idProduct; ?>"><br><br>
        <label for="quantity">Количество:</label>
        <input type="text" name="quantity" value="<?php echo $quantity; ?>"><br><br>
        <input type="submit" value="Сохранить изменения">
    </form>
</body>
</html>

<?php
// Закрытие подключения к базе данных
$conn->close();
?>
