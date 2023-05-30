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

// Проверка, если форма была отправлена
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных из формы
    $applicationId = $_POST["application_id"];
    $employeeId = $_POST["employee_id"];
    $clientId = $_POST["client_id"];
    $dateApplication = $_POST["date_application"];
    $status = isset($_POST["status"]) ? 1 : 0;

    // Обновление записи в базе данных
    $sql = "UPDATE Заявки SET id_employee = '$employeeId', id_client = '$clientId', date_application = '$dateApplication', status = '$status' WHERE id_application = '$applicationId'";

    if ($conn->query($sql) === TRUE) {
        echo "Запись успешно обновлена";
        header("Location: /index.php");
    } else {
        echo "Ошибка при обновлении записи: " . $conn->error;
    }
}

// Получение ID заявки из параметра URL
$applicationId = $_GET["id"];

// Запрос для получения данных о заявке
$sql = "SELECT id_application, id_employee, id_client, date_application, status FROM заявки WHERE id_application = '$applicationId'";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Редактирование заявки</title>
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
    <?php
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <input type="hidden" name="application_id" value="<?php echo $row["id_application"]; ?>">
            <div class="mb-3">
                <label for="employee_id" class="form-label">Код сотрудника:</label>
                <input type="text" name="employee_id" value="<?php echo $row["id_employee"]; ?>" class="form-control">
            </div>
            <div class="mb-3">
                <label for="client_id" class="form-label">Код клиента:</label>
                <input type="text" name="client_id" value="<?php echo $row["id_client"]; ?>" class="form-control">
            </div>
            <div class="mb-3">
                <label for="date_application" class="form-label">Дата заявки:</label>
                <input type="text" name="date_application" value="<?php echo $row["date_application"]; ?>" class="form-control">
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Статус заявки:</label>
                <div class="form-check">
                    <input type="checkbox" id="status" name="status" <?php echo $row["status"] == 1 ? "checked" : ""; ?> class="form-check-input">
                    <label class="form-check-label" for="status">Активный</label>
                </div>
            </div>
            <input type="submit" value="Сохранить" class="btn btn-primary">
        </form>
        <?php
    } else {
        echo "Заявка не найдена";
    }
    ?>

    <h2>Информация о заказах клиента</h2>
    <table class="table">
        <thead>
        <tr>
            <th>Код заказа</th>
            <th>Код заявки</th>
            <th>Код товара</th>
            <th>Количество</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Запрос для получения данных о заказах клиента
        $clientOrdersSql = "SELECT * FROM `заказы` WHERE `id_client` = '" . $row['id_client'] . "'";
        $clientOrdersResult = $conn->query($clientOrdersSql);

        if ($clientOrdersResult && $clientOrdersResult->num_rows > 0) {
            while ($orderRow = $clientOrdersResult->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $orderRow["id_order"]; ?></td>
                    <td><?php echo $orderRow["id_application"]; ?></td>
                    <td><?php echo $orderRow["id_product"]; ?></td>
                    <td><?php echo $orderRow["qyantity"]; ?></td>
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
