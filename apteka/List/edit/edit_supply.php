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
        header("Location: /index.php");
    } else {
        echo "Ошибка при обновлении данных о поставке: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Аптека - Редактирование поставки</title>
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
    <h1>Редактирование поставки</h1>
    <form method="POST" action="">
        <input type="hidden" name="id_supply" value="<?php echo $idSupply; ?>">
        <div class="mb-3">
            <label for="id_provider" class="form-label">Код поставщика:</label>
            <input type="text" name="id_provider" value="<?php echo $idProvider; ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="id_product" class="form-label">Код товара:</label>
            <input type="text" name="id_product" value="<?php echo $idProduct; ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="id_request" class="form-label">Код запроса:</label>
            <input type="text" name="id_request" value="<?php echo $idRequest; ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="date_supply" class="form-label">Дата поставки:</label>
            <input type="text" name="date_supply" value="<?php echo $dateSupply; ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Количество:</label>
            <input type="text" name="quantity" value="<?php echo $quantity; ?>" class="form-control">
        </div>
        <input type="submit" value="Сохранить изменения" class="btn btn-primary">
    </form>
</div>
</body>
</html>

<?php
// Закрытие подключения к базе данных
$conn->close();
?>
