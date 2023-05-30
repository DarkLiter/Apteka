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
    
} else {
    // Пользователь не авторизован, перенаправляем на страницу входа
    header("Location: ../../Login/Login.php");
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
    $sql = "SELECT id_order, id_application, id_client, id_product, qyantity FROM заказы WHERE id_order = '$orderId'";
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
        header("Location: /index.php");
    } else {
        echo "Ошибка при обновлении данных о заказе: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Аптека - Редактирование заказа</title>
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
<div class="container">
    <h1>Редактирование заказа</h1>
    <form method="POST" action="">
        <input type="hidden" name="id_order" value="<?php echo $idOrder; ?>">
        <div class="mb-3">
            <label for="id_application" class="form-label">Код заявки:</label>
            <input type="text" name="id_application" value="<?php echo $idApplication; ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="id_client" class="form-label">Код клиента:</label>
            <input type="text" name="id_client" value="<?php echo $idClient; ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="id_product" class="form-label">Код товара:</label>
            <input type="text" name="id_product" value="<?php echo $idProduct; ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Количество:</label>
            <input type="text" name="quantity" value="<?php echo $quantity; ?>" class="form-control">
        </div>
        <input type="submit" value="Сохранить изменения" class="btn btn-primary">
    </form>
</body>
</html>

<?php
// Закрытие подключения к базе данных
$conn->close();
?>
