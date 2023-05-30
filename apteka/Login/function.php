<?php
// Функция регистрации нового пользователя
function registerUser($username, $password) {
    global $conn;
    
    // Хэшируем пароль
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Подготавливаем запрос на вставку новой записи в таблицу пользователей
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashedPassword);
    $stmt->execute();
    
    // Возвращаем идентификатор нового пользователя
    return $stmt->insert_id;
}

// Функция авторизации пользователя
function loginUser($username, $password) {
    global $conn;
    
    // Подготавливаем запрос на выборку пользователя по имени
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    // Проверяем пароль
    if ($user && password_verify($password, $user['password'])) {
        // Авторизация успешна
        // Записываем идентификатор пользователя в сессию
        $_SESSION['user_id'] = $user['id'];
        return true;
    } else {
        // Неверное имя пользователя или пароль
        return false;
    }
}

// Функция проверки авторизации пользователя
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Функция получения данных о текущем пользователе
function getCurrentUser() {
    global $conn;
    
    if (isLoggedIn()) {
        // Получаем данные пользователя по его идентификатору в сессии
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    } else {
        return null;
    }
}
?>
