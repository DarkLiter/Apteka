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

// Проверяем, был ли отправлен идентификатор заявки для редактирования
if (isset($_GET["id"])) {
    $requestId = $_GET["id"];

    // Запрос для получения данных о заявке
    $sql = "SELECT id_request, id_employee, id_product, name_product, quantity FROM zapros WHERE id_request = '$requestId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $idRequest = $row["id_request"];
        $idEmployee = $row["id_employee"];
        $idProduct = $row["id_product"];
        $productName = $row["name_product"];
        $quantity = $row["quantity"];
    } else {
        echo "Заявка не найдена.";
        exit;
    }
} else {
    echo "Идентификатор заявки не указан.";
    exit;
}

// Проверяем, была ли отправлена форма с обновленными данными заявки
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idRequest = $_POST["id_request"];
    $idEmployee = $_POST["id_employee"];
    $idProduct = $_POST["id_product"];
    $productName = $_POST["product_name"];
    $quantity = $_POST["quantity"];

    // Обновляем данные заявки в базе данных
    $updateSql = "UPDATE zapros SET id_employee = '$idEmployee', id_product = '$idProduct', name_product = '$productName', quantity = '$quantity' WHERE id_request = '$idRequest'";
    if ($conn->query($updateSql) === TRUE) {
        echo "Данные о заявке успешно обновлены.";
    } else {
        echo "Ошибка при обновлении данных о заявке: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Аптека - Редактирование заявки</title>
</head>
<body>
    <h1>Редактирование заявки</h1>
    <form method="POST" action="">
        <input type="hidden" name="id_request" value="<?php echo $idRequest; ?>">
        <label for="id_employee">Код сотрудника:</label>
        <input type="text" name="id_employee" value="<?php echo $idEmployee; ?>"><br><br>
        <label for="id_product">Код товара:</label>
        <input type="text" name="id_product" value="<?php echo $idProduct; ?>"><br><br>
        <label for="product_name">Название товара:</label>
        <input type="text" name="product_name" value="<?php echo $productName; ?>"><br><br>
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
