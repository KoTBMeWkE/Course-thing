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
$id_group = $_GET['id_group'];
try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Не удалось подключиться к базе данных: " . $e->getMessage();
    header("Location: list.php");
    exit();
}
if (!isset($id_group)) {
    $_SESSION['error_message'] = "ID Группы не указан";
    header("Location: list.php");
    exit();
}



// Подготовка SQL-запроса с указанием схемы
$query = "DELETE FROM public.groups WHERE id_group = :id_group";
$stmt = $pdo->prepare($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt->execute(['id_group' => $id_group]);
        header("Location: list.php");
        echo "<div class='alert alert-success'>Группа удален успешно!</div>";       
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
    <title>Группы</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container-fluid p-2" id="upbar" style="position: fixed; background-color: rgba(141, 141, 141, 1); z-index: 2;">       
        <div class="col">
            <div class="row align-items-start">
                <a href="list.php" class="btn" style="z-index: 2;">❮</a>
                <!-- <a class="btn" id="btn-menu">☰</a> -->
                <div class="col px-2 text-center"><h4>Удалить Группу</h4></div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-5">
        <!-- Можно добавить табличку удаляемых данных -->
        <div class="modal-body">
            Вы уверены, что хотите удалить эту группу?
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