<?php
$servername = "localhost";  // Имя сервера базы данных
$username = "root"; // Имя пользователя базы данных
$password = "";       // Пароль для доступа к базе данных
$dbname = "aptekadb";       // Имя базы данных

// Создание подключения
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Переменные для хранения данных формы
$id_product = "";
$name_product = "";
$cost = "";

// Обработка отправки формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных из формы
    $id_product = $_POST["id_product"];
    $name_product = $_POST["name_product"];
    $cost = $_POST["cost"];

    // Подготовка и выполнение SQL-запроса для вставки данных
    $sql = "INSERT INTO Товары (id_product, name_product, cost) VALUES ('$id_product', '$name_product', '$cost')";

    if ($conn->query($sql) === TRUE) {
        echo "Данные успешно добавлены.";
        // Очистка полей формы после успешного добавления данных
        $id_product = "";
        $name_product = "";
        $cost = "";
    } else {
        echo "Ошибка: " . $sql . "<br>" . $conn->error;
    }
}

// Закрытие подключения к базе данных
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Аптека - Добавить товар</title>
</head>
<body>
    <h1>Аптека - Добавить товар</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="id_product">ID товара:</label>
        <input type="text" id="id_product" name="id_product" value="<?php echo $id_product; ?>"><br><br>
        <label for="name_product">Название товара:</label>
        <input type="text" id="name_product" name="name_product" value="<?php echo $name_product; ?>"><br><br>
        <label for="cost">Цена:</label>
        <input type="text" id="cost" name="cost" value="<?php echo $cost; ?>"><br><br>
        <input type="submit" value="Добавить товар">
    </form>
</body>
</html>
