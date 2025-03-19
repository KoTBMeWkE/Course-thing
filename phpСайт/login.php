<?php
session_start();
require 'db.php'; // Подключаем БД

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id_user, username, password, role, teacher_id, student_id
                            FROM public.users 
                            WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['teacher_id'] = $user['teacher_id'];
        $_SESSION['student_id'] = $user['student_id'];
        header("Location: index.php");
        
    } else {
        $error = "Неверный логин или пароль";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container-fluid p-4">
        <h2>Вход в систему</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Логин</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Пароль</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-secondary">Войти</button>
        </form>
    </div>
</body>
</html>
