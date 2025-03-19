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

if (!isset($_GET['celebration_id'])) {
    die("ID праздника не указан.");
}

$celebration_id = $_GET['celebration_id'];

// Подготовка SQL-запроса с указанием схемы
$query = "DELETE FROM public.celebrations WHERE celebration_id = :celebration_id";
$stmt = $pdo->prepare($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt->execute(['celebration_id' => $celebration_id]);
        echo "<div class='alert alert-success'>Праздник удален успешно!</div>";
        // Перенаправление обратно на главную страницу после удаления
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Ошибка: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Удалить праздник</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Удаление праздника</h2>

        <!-- Кнопка для открытия модального окна подтверждения -->
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal">
            Удалить праздник
        </button>
        <a href="index.php" class="btn btn-secondary">Назад</a>
        <!-- Модальное окно подтверждения удаления -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Подтвердите удаление</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Вы уверены, что хотите удалить этот праздник?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                        <!-- Форма для подтверждения удаления -->
                        <form method="POST" action="">
                            <button type="submit" class="btn btn-danger">Удалить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
