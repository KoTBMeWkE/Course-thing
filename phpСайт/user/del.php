<?php
session_start();
require '../db.php';
// require 'sidebar.php';

// Полная проверка доступа к странице у пользователя
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}
$role = $_SESSION['role'];
if ($role !== 'admin') {
    header("Location: ../index.php");
    exit();
}
$id_user = (int)filter_input(INPUT_GET, 'id_user', FILTER_SANITIZE_NUMBER_INT);
try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Не удалось подключиться к базе данных: " . $e->getMessage());
}
if (!isset($id_user)) {
    $_SESSION['error_message'] = "ID Пользователя не указан";
    header("Location: list.php");
    exit();
}

// Подготовка SQL-запроса с указанием схемы
$query = "DELETE FROM public.users WHERE id_user = :id_user";
$stmt = $pdo->prepare($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt->execute(['id_user' => $id_user]);
        $_SESSION['success_message'] = "Пользователь успешно удалён";
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
    <title>Пользователи</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container-fluid p-2" id="upbar" style="position: fixed; background-color: rgba(141, 141, 141, 1); z-index: 2;">       
        <div class="col">
            <div class="row align-items-start">
                <a href="list.php" class="btn" style="z-index: 2;">❮</a>
                <!-- <a class="btn" id="btn-menu">☰</a> -->
                <div class="col px-2 text-center"><h4>Удалить Пользователя</h4></div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-5">
        <!-- Можно добавить табличку удаляемых данных -->
        <div class="modal-body">
            Вы уверены, что хотите удалить этого пользователя?
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