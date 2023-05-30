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

// Проверяем, был ли отправлен идентификатор поставки для редактирования
if (isset($_GET["id"])) {
    $supplyId = $_GET["id"];

    // Запрос для получения данных о поставке
    $sql = "SELECT id_supply, id_provider, id_product, id_request, date_supply, quantity FROM поставки WHERE id_supply = '$supplyId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $idSupply = $row["id_supply"];
        $idProvider = $row["id_provider"];
        $idProduct = $row["id_product"];
        $idRequest = $row["id_request"];
        $dateSupply = $row["date_supply"];
        $quantity = $row["quantity"];
    } else {
        echo "Поставка не найдена.";
        exit;
    }
} else {
    echo "Идентификатор поставки не указан.";
    exit;
}

// Проверяем, была ли отправлена форма с обновленными данными о поставке
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idSupply = $_POST["id_supply"];
    $idProvider = $_POST["id_provider"];
    $idProduct = $_POST["id_product"];
    $idRequest = $_POST["id_request"];
    $dateSupply = $_POST["date_supply"];
    $quantity = $_POST["quantity"];

    // Обновляем данные о поставке в базе данных
    $updateSql = "UPDATE поставки SET id_provider = '$idProvider', id_product = '$idProduct', id_request = '$idRequest', date_supply = '$dateSupply', quantity = '$quantity' WHERE id_supply = '$idSupply'";
    if ($conn->query($updateSql) === TRUE) {
        echo "Данные о поставке успешно обновлены.";
    } else {
        echo "Ошибка при обновлении данных о поставке: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Аптека - Редактирование поставки</title>
</head>
<body>
    <h1>Редактирование поставки</h1>
    <form method="POST" action="">
        <input type="hidden" name="id_supply" value="<?php echo $idSupply; ?>">
        <label for="id_provider">Код поставщика:</label>
        <input type="text" name="id_provider" value="<?php echo $idProvider; ?>"><br><br>
        <label for="id_product">Код товара:</label>
        <input type="text" name="id_product" value="<?php echo $idProduct; ?>"><br><br>
        <label for="id_request">Код запроса:</label>
        <input type="text" name="id_request" value="<?php echo $idRequest; ?>"><br><br>
        <label for="date_supply">Дата поставки:</label>
        <input type="text" name="date_supply" value="<?php echo $dateSupply; ?>"><br><br>
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
