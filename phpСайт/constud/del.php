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
$student_id = (int)filter_input(INPUT_GET, 'student_id', FILTER_SANITIZE_NUMBER_INT);
$contest_id = (int)filter_input(INPUT_GET, 'contest_id', FILTER_SANITIZE_NUMBER_INT);
try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Не удалось подключиться к базе данных: " . $e->getMessage());
}
if (!isset($student_id)) {
    die("ID праздника не указан.");
}

$query = "DELETE FROM public.student_contests WHERE student_id = :student_id AND contest_id = :contest_id";
$stmt = $pdo->prepare($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt->execute(['student_id' => $student_id,'contest_id' => $contest_id]);
        $_SESSION['success_message'] = "Запись успешно удалена";
        header("Location: list.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Ошибка: " . $e->getMessage();
        header("Location: list.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Учителя</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container-fluid p-2" id="upbar" style="position: fixed; background-color: rgba(141, 141, 141, 1); z-index: 2;">       
        <div class="col">
            <div class="row align-items-start">
                <a href="list.php" class="btn" style="z-index: 2;">❮</a>
                <!-- <a class="btn" id="btn-menu">☰</a> -->
                <div class="col px-2 text-center"><h4>Удалить Конкурс</h4></div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-5">
        <!-- Можно добавить табличку удаляемых данных -->
        <div class="modal-body">
            Вы уверены, что хотите удалить этот праздник?
        </div>
        <div class="modal-footer">
            <a href="list.php" class="btn btn-secondary" >Отменить</a>
            <form method="POST" action="">
                <button type="submit" class="btn btn-danger">Удалить</button>
            </form>
        </div>
    </div>
</body>
</html>