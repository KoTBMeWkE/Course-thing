<?php
session_start();
require '../db.php';
// require 'sidebar.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}
$role = $_SESSION['role'];
if ($role !== 'admin') {
    header("Location: ../index.php");
    exit();
}
try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Не удалось подключиться к базе данных: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_prep = $_POST['id_prep'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $lastname = $_POST['lastname'];
    $group_id = $_POST['group_id'];
    $phone_num = $_POST['phone_num'];
    $email = $_POST['email'];

    $query = "INSERT INTO public.teachers (id_prep,name,surname,lastname,group_id,phone_num,email) VALUES (:id_prep,:name,:surname,:lastname,:group_id,:phone_num,:email)";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute([
            'id_prep' => $id_prep,
            'name' => $name,
            'surname' => $surname,
            'lastname' => $lastname,
            'group_id' => $group_id,
            'phone_num' => $phone_num,
            'email' => $email
        ]);
        $_SESSION['success_message'] = "Учитель успешно добавлен";
        header("Location: list.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Ошибка: " . $e->getMessage();
        header("Location: list.php");
        exit();
    }
}

$stmt_groups = $pdo->query("SELECT * FROM public.groups");
$groups = $stmt_groups->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Учителя</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- <script src="sidebar.js"></script> -->
</head>
<body>
<div class="container-fluid p-2" id="upbar" style="position: fixed; background-color: rgba(141, 141, 141, 1); z-index: 2;">       
        <div class="col">
            <div class="row align-items-start">
                <a href="list.php" class="btn" style="z-index: 2;">❮</a>
                <!-- <a class="btn" id="btn-menu">☰</a> -->
                <div class="col px-2 text-center"><h4>Добавить Учителя</h4></div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-5">
        <form method="POST" action="">
            <div class="form-group">
            <h2></h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="id_prep">Код:</label>
                <input type="number" class="form-control" id="id_prep" name="id_prep" min=0 required>
                <label for="name">Имя:</label>
                <input type="text" class="form-control" id="name" name="name" required>
                <label for="surname">Фамилия:</label>
                <input type="text" class="form-control" id="surname" name="surname" required>
                <label for="lastname">Отчество:</label>
                <input type="text" class="form-control" id="lastname" name="lastname" required>
                <label for="group_id">Класное руководство группы:</label>
                <select class="form-control" id="group_id" name="group_id" required>
                    <option value="">Группа</option>
                    <?php foreach ($groups as $group): ?>
                        <option value="<?php echo htmlspecialchars($group['id_group']); ?>"><?php echo htmlspecialchars($group['group_name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="phone_num">Номер телефона:</label>
                <input type="text" class="form-control" id="phone_num" name="phone_num" required>
                <label for="email">Почта:</label>
                <input type="text" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary">Добавить</button>
        </form>
    </div>
</body>
</html>