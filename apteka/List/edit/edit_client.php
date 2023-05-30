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
    $clientId = $_POST["client_id"];
    $lastName = $_POST["last_name"];
    $firstName = $_POST["first_name"];
    $surname = $_POST["surname"];
    $number = $_POST["number"];

    // Обновление записи в базе данных
    $sql = "UPDATE Клиенты SET last_name = '$lastName', first_name = '$firstName', surname = '$surname', number = '$number' WHERE id_client = '$clientId'";

    if ($conn->query($sql) === TRUE) {
        echo "Запись успешно обновлена";
        header("Location: /index.php");
    } else {
        echo "Ошибка при обновлении записи: " . $conn->error;
    }
}

// Получение ID клиента из параметра URL
$clientId = $_GET["id"];

// Запрос для получения данных о клиенте
$sql = "SELECT id_client, last_name, first_name, surname, number FROM клиенты WHERE id_client = '$clientId'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Редактирование клиента</title>
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
    <h1>Редактирование клиента</h1>
    <?php
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <input type="hidden" name="client_id" value="<?php echo $row["id_client"]; ?>">
            <div class="mb-3">
                <label for="last_name" class="form-label">Фамилия:</label>
                <input type="text" name="last_name" value="<?php echo $row["last_name"]; ?>" class="form-control">
            </div>
            <div class="mb-3">
                <label for="first_name" class="form-label">Имя:</label>
                <input type="text" name="first_name" value="<?php echo $row["first_name"]; ?>" class="form-control">
            </div>
            <div class="mb-3">
                <label for="surname" class="form-label">Отчество:</label>
                <input type="text" name="surname" value="<?php echo $row["surname"]; ?>" class="form-control">
            </div>
            <div class="mb-3">
                <label for="number" class="form-label">Номер телефона:</label>
                <input type="text" name="number" value="<?php echo $row["number"]; ?>" class="form-control">
            </div>
            <input type="submit" value="Сохранить" class="btn btn-primary">
        </form>
        <?php
    } else {
        echo "Клиент не найден";
    }
    ?>

    <h2>Информация о заявках</h2>
    <table class="table">
        <thead>
        <tr>
            <th>Код заявки</th>
            <th>Код сотрудника</th>
            <th>Код клиента</th>
            <th>Дата</th>
            <th>Статус</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Запрос для получения данных о заказах клиента
        $clientOrdersSql = "SELECT * FROM `Заявки` WHERE `id_client` = '" . $row['id_client'] . "'";
        $clientOrdersResult = $conn->query($clientOrdersSql);

        if ($clientOrdersResult && $clientOrdersResult->num_rows > 0) {
            while ($orderRow = $clientOrdersResult->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $orderRow["id_application"]; ?></td>
                    <td><?php echo $orderRow["id_employee"]; ?></td>
                    <td><?php echo $orderRow["id_client"]; ?></td>
                    <td><?php echo $orderRow["date_application"]; ?></td>
                    <td>
                        <input type="checkbox" id="scales" name="scales" <?php echo $orderRow['status'] == "1" ? "checked" : ""; ?> onclick="return false;">
                    </td>
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