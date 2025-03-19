<?php
session_start();
require 'db.php';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Не удалось подключиться к базе данных: " . $e->getMessage());
}

$role = $_SESSION['role'];
if ($role !== 'student') {
    header("Location: ../index.php");
    exit();
}
$studentId = $_SESSION['student_id'];
$id_contest = (int)filter_input(INPUT_GET, 'id_contest', FILTER_SANITIZE_NUMBER_INT);

$query = "INSERT INTO public.student_contests (student_id,contest_id) VALUES (:student_id,:contest_id)";
$stmt = $pdo->prepare($query);
try {
    $stmt->execute([
        'student_id' => $studentId,
        'contest_id' => $id_contest,
    ]);
    $_SESSION['success_message'] = "Вы успешно записаны на конкурс!";
    header("Location: contest/list.php");
    exit();
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Ошибка записи на конкурс: " . $e->getMessage();
    header("Location: contest/list.php");
    exit();
}
?>