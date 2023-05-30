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

// Обработка отправки формы редактирования
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST["product_id"];
    $productName = $_POST["product_name"];
    $productCost = $_POST["product_cost"];

    // Запрос для обновления данных о товаре
    $sql = "UPDATE Товары SET name_product = '$productName', cost = '$productCost' WHERE id_product = '$productId'";

    if ($conn->query($sql) === TRUE) {
        echo "Запись успешно обновлена";
        header("Location: /index.php");
    } else {
        echo "Ошибка при обновлении записи: " . $conn->error;
    }
}

// Получение ID товара из параметра URL
$productId = $_GET["id"];

// Запрос для получения данных о товаре
$sql = "SELECT id_product, name_product, cost FROM товары WHERE id_product = '$productId'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Редактирование товара</title>
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
<!DOCTYPE html>
<html>
<head>
    <title>Редактирование товара</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1>Редактирование товара</h1>
    <?php
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <input type="hidden" name="product_id" value="<?php echo $row["id_product"]; ?>">
            <div class="mb-3">
                <label for="product_name" class="form-label">Название товара:</label>
                <input type="text" name="product_name" value="<?php echo $row["name_product"]; ?>" class="form-control">
            </div>
            <div class="mb-3">
                <label for="product_cost" class="form-label">Цена:</label>
                <input type="text" name="product_cost" value="<?php echo $row["cost"]; ?>" class="form-control">
            </div>
            <input type="submit" value="Сохранить" class="btn btn-primary">
        </form>
        <?php
    } else {
        echo "Товар не найден";
    }
    ?>

    <h2>Информация о поставках</h2>
    <table class="table">
        <thead>
        <tr>
            <th>Код поставки</th>
            <th>Код поставщика</th>
            <th>Код товара</th>
            <th>Код запроса</th>
            <th>Дата</th>
            <th>Количество</th>
        </tr>
        </thead>
        <tbody>
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
        </tbody>
    </table>
</div>
</body>
</html>
<?php
// Закрытие подключения к базе данных
$conn->close();
?>
