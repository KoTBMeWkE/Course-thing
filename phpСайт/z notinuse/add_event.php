<?php
session_start();
require 'db.php';
// Полная проверка доступа к странице у пользователя
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$role = $_SESSION['role'];
if ($role !== 'admin') {
    echo "У вас нет доступа к этой странице.";
    exit();
}

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Не удалось подключиться к базе данных: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_naming = $_POST['event_naming'];

    $query = "INSERT INTO public.eventtypes (event_naming) VALUES (:event_naming)";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute(['event_naming' => $event_naming]);
        echo "<div class='alert alert-success'>Мероприятие добавлено успешно!</div>";
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Ошибка: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить мероприятие</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Добавить мероприятие</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="event_naming">Название мероприятия:</label>
                <input type="text" class="form-control" id="event_naming" name="event_naming" required>
            </div>
            <button type="submit" class="btn btn-primary">Добавить мероприятие</button>
        </form>
        <a href="index.php" class="btn btn-secondary mt-3">Назад</a>
    </div>
</body>
</html>