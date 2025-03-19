<?php
session_start();
require_once '../db.php';
// require 'sidebar.php';
require '../access_check.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}
$id_contest = (int)filter_input(INPUT_GET, 'id_contest', FILTER_SANITIZE_NUMBER_INT);
$teacherId = (int)filter_input(INPUT_GET, 'teacher_id', FILTER_SANITIZE_NUMBER_INT);
checkAccess('admin', $teacherId, $id_contest, $conn);
try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Не удалось подключиться к базе данных: " . $e->getMessage();
    header("Location: list.php");
    exit();
}
if (!isset($id_contest)) {
    $_SESSION['error_message'] = "ID Конкурса не указан";
    header("Location: list.php");
    exit();
}

$query1 = "DELETE FROM public.student_contests WHERE contest_id = :id_contest";
$stmt1 = $pdo->prepare($query1);

$query2 = "DELETE FROM public.contests WHERE id_contest = :id_contest";
$stmt2 = $pdo->prepare($query2);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt1->execute(['id_contest' => $id_contest]);
        $stmt2->execute(['id_contest' => $id_contest]);
        $_SESSION['success_message'] = "Конкурс и связанные данные успешно удалены";
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
    <title>Конкурсы</title>
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
            При удалении, исчезнут и записи на этот конкурс <br>
            Вы уверены, что хотите удалить этот конкурс?
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