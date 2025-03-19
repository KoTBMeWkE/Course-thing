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
$id_user = (int)filter_input(INPUT_GET, 'id_user', FILTER_SANITIZE_NUMBER_INT);
if (isset($id_user)) {
    $stmt = $conn->prepare("SELECT * FROM public.users WHERE id_user = :id_user");
    $stmt->execute(['id_user' => $id_user]);
    $contests = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt_students = $conn->query("SELECT * FROM public.students");
    $students = $stmt_students->fetchAll(PDO::FETCH_ASSOC);

    $stmt_teachers = $conn->query("SELECT * FROM public.teachers");
    $teachers = $stmt_teachers->fetchAll(PDO::FETCH_ASSOC);
} else {
    $_SESSION['error_message'] = "Неверный запрос";
    header("Location: list.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_POST['id_user'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $student_id = $_POST['student_id'];
    $teacher_id = $_POST['teacher_id'];

    $stmt_update = $conn->prepare("
        UPDATE public.users 
        SET username = :username, password = crypt(:password, gen_salt('bf')), role = :role, student_id = :student_id, 
            teacher_id = :teacher_id
        WHERE id_user = :id_user
    ");

    $stmt_update->execute([
        'id_user' => $id_user,
        'username' => $username,
        'password' => $password,
        'role' => $role,
        'student_id' => $student_id,
        'teacher_id' => $teacher_id,
    ]);

    header("Location: list.php");
    exit();
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
                <div class="col px-2 text-center"><h4>Изменить Пользователя</h4></div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-5">
        <form method="POST" action="">
            <input type="hidden" name="id_user" value="<?php echo htmlspecialchars($id_user); ?>"> 
            <div class="form-group">
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
            <button type="submit" class="btn btn-primary">Изменить</button>
        </form>
    </div>
</body>
</html>