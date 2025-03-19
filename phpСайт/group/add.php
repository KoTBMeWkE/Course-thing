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
    $_SESSION['error_message'] = "Не удалось подключиться к базе данных: " . $e->getMessage();
    header("Location: list.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_group = $_POST['id_group'];
    $group_name = $_POST['group_name'];
    $students_amount = $_POST['students_amount'];
    $study_year_start = $_POST['study_year_start'];


    $query = "INSERT INTO public.groups (id_group,group_name,students_amount,study_year_start) VALUES (:id_group,:group_name,:students_amount,:study_year_start)";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute([
            'id_group' => $id_group,
            'group_name' => $group_name,
            'students_amount' => $students_amount,
            'study_year_start' => $study_year_start,
        ]);
        $_SESSION['success_message'] = "Группа успешно добавлена";
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
    <title>Группы</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container-fluid p-2" id="upbar" style="position: fixed; background-color: rgba(141, 141, 141, 1); z-index: 2;">       
        <div class="col">
            <div class="row align-items-start">
                <a href="list.php" class="btn" style="z-index: 2;">❮</a>
                <!-- <a class="btn" id="btn-menu">☰</a> -->
                <div class="col px-2 text-center"><h4>Добавить Группу</h4></div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-5">
        <form method="POST" action="">
            <div class="form-group">
            <h2></h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="id_group">Код:</label>
                <input type="number" class="form-control" id="id_group" name="id_group" min=0 required>
                <label for="group_name">Название:</label>
                <input type="text" class="form-control" id="group_name" name="group_name" required>
                <label for="students_amount">Количество учеников:</label>
                <input type="number" class="form-control" id="students_amount" name="students_amount" min=0 required>
                <label for="study_year_start">Год начала обучения:</label>
                <input type="number" class="form-control" id="study_year_start" name="study_year_start" min=0 required>
            </div>
            <button type="submit" class="btn btn-primary">Добавить</button>
        </form>
    </div>
</body>
</html>