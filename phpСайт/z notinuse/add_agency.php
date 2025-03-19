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
    $agency_name = $_POST['agency_name'];
    $price_day = $_POST['price_day'];
    $price_evening = $_POST['price_evening'];

    $query = "INSERT INTO public.agencies (name,price_day,price_evening) VALUES (:agency_name,:price_day,:price_evening)";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute([
            'agency_name' => $agency_name,
            'price_day' => $price_day,
            'price_evening' => $price_evening
        ]);
        echo "<div class='alert alert-success'>Агенство добавлено успешно!</div>";
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Ошибка: " . $e->getMessage() . "</div>";
    }
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить агентство</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Добавить агентство</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="agency_name">Название агентства:</label>
                <input type="text" class="form-control" id="agency_name" name="agency_name" required>
                <label for="price_day">Дневная цена агентства:</label>
                <input type="text" class="form-control" id="price_day" name="price_day" min="0" required>
                <label for="price_evening">Вечерная цена агентства:</label>
                <input type="text" class="form-control" id="price_evening" name="price_evening" min="0" required>
            </div>
            <button type="submit" class="btn btn-primary">Добавить агентство</button>
        </form>
        <a href="index.php" class="btn btn-secondary mt-3">Назад</a>
    </div>
</body>
</html>