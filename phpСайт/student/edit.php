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
$id_student = (int)filter_input(INPUT_GET, 'id_student', FILTER_SANITIZE_NUMBER_INT);;
if (isset($id_student)) {
    $stmt = $conn->prepare("SELECT * FROM public.students WHERE id_student = :id_student");
    $stmt->execute(['id_student' => $id_student]);
    $contests = $stmt->fetch(PDO::FETCH_ASSOC);    
    $stmt_groups = $conn->query("SELECT * FROM public.groups");
    $groups = $stmt_groups->fetchAll(PDO::FETCH_ASSOC);

} else {
    $_SESSION['error_message'] = "Неверный запрос";
    header("Location: list.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_student = $_POST['id_student'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $lastname = $_POST['lastname'];
    $group_id = $_POST['group_id'];
    $birthdate = $_POST['birthdate'];
    $live_place = $_POST['live_place'];
    $parent_contact = $_POST['parent_contact'];

    $stmt_update = $conn->prepare("
        UPDATE public.students 
        SET name = :name, surname = :surname, lastname = :lastname, group_id = :group_id, 
            birthdate = :birthdate, live_place = :live_place, parent_contact = :parent_contact
        WHERE id_student = :id_student
    ");

    $stmt_update->execute([
        'id_student' => $id_student,
        'name' => $name,
        'surname' => $surname,
        'lastname' => $lastname,
        'group_id' => $group_id,
        'birthdate' => $birthdate,
        'live_place' => $live_place,
        'parent_contact' => $parent_contact,
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
    <title>Ученики</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- <script src="../sidebar.js"></script> -->
</head>
<body>
<div class="container-fluid p-2" id="upbar" style="position: fixed; background-color: rgba(141, 141, 141, 1); z-index: 2;">       
        <div class="col">
            <div class="row align-items-start">
                <a href="list.php" class="btn" style="z-index: 2;">❮</a>
                <!-- <a class="btn" id="btn-menu">☰</a> -->
                <div class="col px-2 text-center"><h4>Изменить Ученика</h4></div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-5">
        <form method="POST" action="">
            <input type="hidden" name="id_student" value="<?php echo htmlspecialchars($id_student); ?>"> 
            <div class="form-group">
                <label for="name">Имя:</label>
                <input type="text" class="form-control" id="name" name="name" required>
                <label for="surname">Фамилия:</label>
                <input type="text" class="form-control" id="surname" name="surname" required>
                <label for="lastname">Отчество:</label>
                <input type="text" class="form-control" id="lastname" name="lastname" required>
                <label for="group_id">В группе:</label>
                <select class="form-control" id="group_id" name="group_id" required>
                    <option value="">Группа</option>
                    <?php foreach ($groups as $group): ?>
                        <option value="<?php echo htmlspecialchars($group['id_group']); ?>"><?php echo htmlspecialchars($group['group_name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="birthdate">День рождения:</label>
                <input type="date" class="form-control" id="birthdate" name="birthdate" required>
                <label for="live_place">Место жительства:</label>
                <input type="text" class="form-control" id="live_place" name="live_place" required>
                <label for="parent_contact">Контакты родителей:</label>
                <input type="text" class="form-control" id="parent_contact" name="parent_contact" required>
            </div>
            <button type="submit" class="btn btn-primary">Изменить</button>
        </form>
    </div>
</body>
</html>