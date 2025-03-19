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
    // Получаем данные из формы
    $animator_id = $_POST['animator_id'];
    $celebration_date = $_POST['celebration_date'];
    $start_time = $_POST['start_time'];
    $garland = $_POST['garland'];
    $fireworks = $_POST['fireworks'];
    $popper = $_POST['firecrackers'];
    $event_type_id = $_POST['event_type_id'];

    // Подготовка SQL-запроса без agency_id
    $query = "INSERT INTO public.celebrations (animator_id, celebration_date, start_time, garland, fireworks, firecrackers, event_type_id) 
              VALUES (:animator_id, :celebration_date, :start_time, :garland, :fireworks, :firecrackers, :event_type_id)";
    
    $stmt = $pdo->prepare($query);
    
    // Выполнение запроса
    try {
        $stmt->execute([
            'animator_id' => $animator_id,
            'celebration_date' => $celebration_date,
            'start_time' => $start_time,
            'garland' => $garland,
            'fireworks' => $fireworks,
            'firecrackers' => $firecrackers,
            'event_type_id' => $event_type_id
        ]);
        echo "<div class='alert alert-success'>Праздник добавлен успешно!</div>";
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Ошибка: " . $e->getMessage() . "</div>";
    }
}

// Получение списка агентств
$stmt_agencies = $pdo->query("SELECT * FROM public.agencies");
$agencies = $stmt_agencies->fetchAll(PDO::FETCH_ASSOC);

// Получение списка аниматоров
$stmt_animators = $pdo->query("SELECT * FROM public.animators");
$animators = $stmt_animators->fetchAll(PDO::FETCH_ASSOC);

// Получение списка мероприятий
$stmt_eventtypes = $pdo->query("SELECT * FROM public.eventtypes");
$eventtypes = $stmt_eventtypes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить праздник</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Добавить праздник</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="agency_id">Выберите агентство:</label>
                <select class="form-control" id="agency_id" name="agency_id" required>
                    <option value="">Выберите агентство</option>
                    <?php foreach ($agencies as $agency): ?>
                        <option value="<?php echo htmlspecialchars($agency['agency_id']); ?>"><?php echo htmlspecialchars($agency['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="animator_id">Выберите аниматора:</label>
                <select class="form-control" id="animator_id" name="animator_id" required>
                    <option value="">Выберите аниматора</option>
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
                <label for="garland">Количество гирлянд:</label>
                <input type="number" class="form-control" id="garland" name="garland" min="0" required>
            </div>

            <div class="form-group">
                <label for="fireworks">Количество фейерверков:</label>
                <input type="number" class="form-control" id="fireworks" name="fireworks" min="0" required>
            </div>

            <div class="form-group">
                <label for="popper">Количество петард:</label>
                <input type="number" class="form-control" id="popper" name="popper" min="0" required>
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

            <button type="submit" class="btn btn-primary">Добавить праздник</button>
        </form>
        <a href="index.php" class="btn btn-secondary mt-3">Назад</a>
    </div>
</body>
</html>
