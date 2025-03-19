<?php
session_start();
require '../db.php';
// require 'sidebar.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}
$role = $_SESSION['role'];
if ($role !== 'admin' && $role !== 'teacher') {
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
    $id_contest = $_POST['id_contest'];
    $title = $_POST['title'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    $subject = $_POST['subject'];
    $winning_student = !empty($_POST['winning_student']) ? (int)$_POST['winning_student'] : null;
    $teacher_id = $_POST['teacher_id'];

    $query = "INSERT INTO public.contests (id_contest,title,date,description,subject,winning_student,teacher_id) VALUES (:id_contest,:title,:date,:description,:subject,:winning_student,:teacher_id)";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute([
            'id_contest' => $id_contest,
            'title' => $title,
            'date' => $date,
            'description' => $description,
            'subject' => $subject,
            'winning_student' => $winning_student,
            'teacher_id' => $teacher_id
        ]);
        $_SESSION['success_message'] = "Конкурс успешно добавлен";
        header("Location: list.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Ошибка: " . $e->getMessage();
        header("Location: list.php");
        exit();
    }
}

$stmt_students = $pdo->query("SELECT * FROM public.students");
$students = $stmt_students->fetchAll(PDO::FETCH_ASSOC);
$stmt_teachers = $pdo->query("SELECT * FROM public.teachers");
$teachers = $stmt_teachers->fetchAll(PDO::FETCH_ASSOC);
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
                <div class="col px-2 text-center"><h4>Добавить Конкурс</h4></div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-5">
        <form method="POST" action="">
            <div class="form-group">
            <h2></h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="id_contest">Код:</label>
                <input type="number" class="form-control" id="id_contest" name="id_contest" min=0 required>
                <label for="title">Название:</label>
                <input type="text" class="form-control" id="title" name="title" required>
                <label for="date">Дата:</label>
                <input type="date" class="form-control" id="date" name="date" min="2023-01-01"  required>
                <label for="description">Описание:</label>
                <input type="text" class="form-control" id="description" name="description" required>
                <label for="subject">Задание:</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
                <label for="winning_student">Победитель:</label>
                <select class="form-control" id="winning_student" name="winning_student">
                    <option value="">Не выбран</option>
                    <?php foreach ($students as $student): ?>
                        <option value="<?php echo htmlspecialchars($student['id_student']); ?>"><?php echo htmlspecialchars($student['name']. ' ' . $student['surname']); ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                <label for="teacher_id">Организатор:</label>
                <select class="form-control" id="teacher_id" name="teacher_id" required>
                    <option value="teacher_id">Не указано</option>
                    <<?php foreach ($teachers as $teacher): ?>
                        <option value="<?php echo htmlspecialchars($teacher['id_prep']); ?>"><?php echo htmlspecialchars($teacher['name']. ' ' . $teacher['surname']); ?></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <input type="hidden" name="teacher_id" value="<?php echo htmlspecialchars($_SESSION['teacher_id']); ?>"> 
                <?php endif; ?>  
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Добавить</button>
        </form>
    </div>
</body>
</html>