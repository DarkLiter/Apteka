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
        header("Location: /index.php");
    } else {
        echo "Ошибка при обновлении данных о заявке: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Аптека - Редактирование заявки</title>
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
    <h1>Редактирование заявки</h1>
    <form method="POST" action="">
        <input type="hidden" name="id_request" value="<?php echo $idRequest; ?>">
        <div class="mb-3">
            <label for="id_employee" class="form-label">Код сотрудника:</label>
            <input type="text" name="id_employee" value="<?php echo $idEmployee; ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="id_product" class="form-label">Код товара:</label>
            <input type="text" name="id_product" value="<?php echo $idProduct; ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="product_name" class="form-label">Название товара:</label>
            <input type="text" name="product_name" value="<?php echo $productName; ?>" class="form-control">
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
