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
        header("Location: /index.php");
    } else {
        echo "Ошибка при обновлении данных о поставщике: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Аптека - Редактирование поставщика</title>
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
<h1>Редактирование поставщика</h1>
    <form method="POST" action="">
        <input type="hidden" name="id_provider" value="<?php echo $idProvider; ?>">
        <div class="mb-3">
            <label for="organization" class="form-label">Организация:</label>
            <input type="text" name="organization" value="<?php echo $organization; ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="supervisor" class="form-label">Руководитель:</label>
            <input type="text" name="supervisor" value="<?php echo $supervisor; ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="number" class="form-label">Номер телефона:</label>
            <input type="text" name="number" value="<?php echo $number; ?>" class="form-control">
        </div>
        <input type="submit" value="Сохранить изменения" class="btn btn-primary">
    </form>
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
            echo "<tr><td colspan='6'>Нет доступных заказов</td></tr>";
        }
        ?>
        </tbody>
    </table>
</body>
</html>

<?php
// Закрытие подключения к базе данных
$conn->close();
?>