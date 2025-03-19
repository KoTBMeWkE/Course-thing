<?php
include 'db.php';
session_start();
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

// Проверка наличия celebration_id в URL
if (isset($_GET['celebration_id'])) {
    $celebration_id = $_GET['celebration_id'];

    // Получение информации о празднике для редактирования
    $stmt = $conn->prepare("SELECT * FROM public.celebrations WHERE celebration_id = :celebration_id");
    $stmt->execute(['celebration_id' => $celebration_id]);
    $celebrations = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$celebrations) {
        // Если праздник не найден
        die('Праздник не найден');
    }

    // Получение списка агентств
    $stmt_agencies = $conn->query("SELECT * FROM public.agencies");
    $agencies = $stmt_agencies->fetchAll(PDO::FETCH_ASSOC);

    // Получение списка аниматоров
    $stmt_animators = $conn->query("SELECT * FROM public.animators");
    $animators = $stmt_animators->fetchAll(PDO::FETCH_ASSOC);

    // Получение списка мероприятий
    $stmt_eventtypes = $conn->query("SELECT * FROM public.eventtypes");
    $eventtypes = $stmt_eventtypes->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Если нет celebration_id в URL
    die('Неверный запрос');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование праздника</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Редактировать праздник</h2>
        <form method="POST" action="update_holiday.php">
            <input type="hidden" name="celebration_id" value="<?php echo htmlspecialchars($celebration_id); ?>">  
            <div class="form-group">
                <label for="animator_id">Аниматора:</label>
                <select class="form-control" id="animator_id" name="animator_id" required>
                    <option value="">Аниматора</option>
                    <?php foreach ($animators as $animator): ?>
                        <option value="<?php echo htmlspecialchars($animator['animator_id']); ?>">
                            <?php echo htmlspecialchars($animator['first_name'] . ' ' . $animator['last_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="celebration_date">Дата праздника:</label>
                <input type="date" class="form-control" id="celebration_date" name="celebration_date" required>
            </div>

            <div class="form-group">
                <label for="start_time">Время начала:</label>
                <input type="time" class="form-control" id="start_time" name="start_time" required>
            </div>
            <div class="form-group">
                <label for="event_id">Выберите мероприятие:</label>
                <select class="form-control" id="event_type_id" name="event_type_id" required>
                    <option value="">Выберите мероприятие</option>
                    <?php foreach ($eventtypes as $events): ?>
                        <option value="<?php echo htmlspecialchars($events['event_type_id']); ?>"><?php echo htmlspecialchars($events['event_naming']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        <button type="submit" class="btn btn-primary">Подтвердить</button>
        </form>
        <a href="index.php" class="btn btn-secondary mt-3">Назад</a>
    </div>
</body>
</html>
