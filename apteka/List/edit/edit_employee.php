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

// Проверка, был ли отправлен идентификатор сотрудника для редактирования
if (isset($_GET["id"])) {
    $employeeId = $_GET["id"];
} else {
    echo "Идентификатор сотрудника не указан.";
    exit;
}

// Запрос для получения информации о выбранном сотруднике
$sql = "SELECT id_employee, job_title, first_name, last_name, surname, number FROM сотрудники WHERE id_employee = " . $employeeId;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Проверка, была ли отправлена форма с обновленными данными
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $jobTitle = $_POST["job_title"];
        $firstName = $_POST["first_name"];
        $lastName = $_POST["last_name"];
        $surname = $_POST["surname"];
        $number = $_POST["number"];

        // Обновление информации о сотруднике в базе данных
        $updateSql = "UPDATE Сотрудники SET job_title = '$jobTitle', first_name = '$firstName', last_name = '$lastName', surname = '$surname', number = '$number' WHERE id_employee = " . $employeeId;

        if ($conn->query($updateSql) === TRUE) {
            echo "Информация о сотруднике успешно обновлена.";
            header("Location: /index.php");
        } else {
            echo "Ошибка при обновлении информации о сотруднике: " . $conn->error;
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Редактирование информации о сотруднике</title>
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
<h1>Редактирование информации о сотруднике</h1>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="job_title" class="form-label">Должность:</label>
            <input type="text" id="job_title" name="job_title" value="<?php echo $row["job_title"]; ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="first_name" class="form-label">Имя:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo $row["first_name"]; ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="last_name" class="form-label">Фамилия:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo $row["last_name"]; ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="surname" class="form-label">Отчество:</label>
            <input type="text" id="surname" name="surname" value="<?php echo $row["surname"]; ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="number" class="form-label">Номер телефона:</label>
            <input type="text" id="number" name="number" value="<?php echo $row["number"]; ?>" class="form-control">
        </div>
        <input type="submit" value="Сохранить изменения" class="btn btn-primary">
    </form>
</div>
</body>
</html>

<?php
} else {
    echo "Сотрудник с указанным идентификатором не найден.";
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
    $clientOrdersSql = "SELECT * FROM `Заявки` WHERE `id_employee` = '" . $row['id_employee'] . "'";
    $clientOrdersResult = $conn->query($clientOrdersSql);

    if ($clientOrdersResult && $clientOrdersResult->num_rows > 0) {
        while ($orderRow = $clientOrdersResult->fetch_assoc()) {
            ?>
            <tr>
                <td><?php echo $orderRow["id_application"]; ?></td>
                <td><?php echo $orderRow["id_employee"]; ?></td>
                <td><?php echo $orderRow["id_client"]; ?></td>
                <td><?php echo $orderRow["date_application"]; ?></td>
                <?php if ($orderRow['status'] == "1") {
                    echo "<td><input type='checkbox' id='scales' name='scales' checked onclick='return false;'></td>";
                } else {
                    echo "<td><input type='checkbox' id='scales' name='scales' onclick='return false;'></td>";
                } ?>
            </tr>
            <?php
        }
    } else {
        echo "<tr><td colspan='5'>Нет доступных заказов</td></tr>";
    }
    ?>
        </tbody>
</table>
</html>
<?php
// Закрытие подключения к базе данных
$conn->close();
?>
