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
try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Не удалось подключиться к базе данных: " . $e->getMessage();
    header("Location: list.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_user = $_POST['id_user'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $student_id = $_POST['student_id'];
    $teacher_id = $_POST['teacher_id'];

    $query = "INSERT INTO public.users (id_user, username, password, role, student_id, teacher_id) VALUES (:id_user, :username, crypt(:password, gen_salt('bf')), :role, :student_id, :teacher_id)";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute([
            'id_user' => $id_user,
            'username' => $username,
            'password' => $password,
            'role' => $role,
            'student_id' => $student_id,
            'teacher_id' => $teacher_id,
        ]);
        $_SESSION['success_message'] = "Пользователь успешно добавлен";
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
    <title>Пользователи</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container-fluid p-2" id="upbar" style="position: fixed; background-color: rgba(141, 141, 141, 1); z-index: 2;">       
        <div class="col">
            <div class="row align-items-start">
                <a href="list.php" class="btn" style="z-index: 2;">❮</a>
                <!-- <a class="btn" id="btn-menu">☰</a> -->
                <div class="col px-2 text-center"><h4>Добавить пользователя</h4></div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-5">
        <form method="POST" action="">
            <div class="form-group">
            <h2></h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="id_user">Код:</label>
                <input type="number" class="form-control" id="id_user" name="id_user" min=0 required>
                <label for="username">Логин:</label>
                <input type="text" class="form-control" id="username" name="username" required>
                <label for="password">Пароль:</label>
                <input type="text" class="form-control" id="password" name="password" required>
                <label for="role">Роль:</label>
                <input type="text" class="form-control" id="role" name="role" required>
                <label for="student_id">Ученик:</label>
                <select class="form-control" id="student_id" name="student_id">
                    <option value="">Ученик</option>
                    <?php foreach ($students as $student): ?>
                        <option value="<?php echo htmlspecialchars($student['id_student']); ?>"><?php echo htmlspecialchars($student['name']. ' ' . $student['surname']. ' ' . $student['lastname']); ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="student_id">Учитель</label>
                <select class="form-control" id="teacher_id" name="teacher_id">
                    <option value="">Учитель</option>
                    <?php foreach ($teachers as $teacher): ?>
                        <option value="<?php echo htmlspecialchars($teacher['id_prep']); ?>"><?php echo htmlspecialchars($teacher['name']. ' ' . $teacher['surname']. ' ' . $teacher['lastname']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Добавить</button>
        </form>
    </div>
</body>
</html>