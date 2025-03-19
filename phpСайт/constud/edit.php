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
if (isset($student_id)) {
    $stmt = $conn->prepare("SELECT * FROM public.student_contests WHERE student_id = :student_id AND contest_id = :contest_id");
    $stmt->execute(['student_id' => $student_id,'contest_id' => $contest_id]);
    $contests = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt_students = $conn->query("SELECT * FROM public.students");
    $students = $stmt_students->fetchAll(PDO::FETCH_ASSOC);

    $stmt_contests = $conn->query("SELECT * FROM public.contests");
    $contests = $stmt_contests->fetchAll(PDO::FETCH_ASSOC);
} else {
    $_SESSION['error_message'] = "Неверный запрос";
    header("Location: list.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contest_id = $_POST['contest_id'];
    $student_id = $_POST['student_id'];

    $stmt_update = $conn->prepare("
        UPDATE public.student_contests
        SET contest_id = :contest_id, student_id = :student_id
    ");

    $stmt_update->execute([
        'contest_id' => $contest_id,
        'student_id' => $student_id,
    ]);
    $_SESSION['success_message'] = "Данные успешно изменены";
    header("Location: list.php");
    exit();
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
                <div class="col px-2 text-center"><h4>Изменить Конкурс</h4></div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-5">
        <form method="POST" action="">
            <input type="hidden" name="id_prep" value="<?php echo htmlspecialchars($id_prep); ?>"> 
            <div class="form-group">
            <label for="student_id">Ученик:</label>
                <select class="form-control" id="student_id" name="student_id">
                    <option value="">Ученик</option>
                    <?php foreach ($students as $student): ?>
                        <option value="<?php echo htmlspecialchars($student['id_student']); ?>"><?php echo htmlspecialchars($student['name']. ' ' . $student['surname']. ' ' . $student['lastname']); ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="contest_id">Конкурс:</label>
                <select class="form-control" id="contest_id" name="contest_id">
                    <option value="">Конкурс</option>
                    <?php foreach ($contests as $contest): ?>
                        <option value="<?php echo htmlspecialchars($contest['id_contest']); ?>"><?php echo htmlspecialchars($contest['title']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Изменить</button>
        </form>
    </div>
</body>
</html>