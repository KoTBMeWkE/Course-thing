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
$id_group =  (int)filter_input(INPUT_GET, 'id_group', FILTER_SANITIZE_NUMBER_INT);
if (isset($id_group)) {
    $stmt = $conn->prepare("SELECT * FROM public.groups WHERE id_group = :id_group");
    $stmt->execute(['id_group' => $id_group]);
    $contests = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt_groups = $conn->query("SELECT * FROM public.groups");
    $groups = $stmt_groups->fetchAll(PDO::FETCH_ASSOC);

} else {
    $_SESSION['error_message'] = "Неверный запрос";
    header("Location: list.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_group = $_POST['id_group'];
    $group_name = $_POST['group_name'];
    $students_amount = $_POST['students_amount'];
    $study_year_start = $_POST['study_year_start'];

    $stmt_update = $conn->prepare("
        UPDATE public.groups 
        SET group_name = :group_name, students_amount = :students_amount, study_year_start = :study_year_start 
        WHERE id_group = :id_group
    ");

    $stmt_update->execute([
        'id_group' => $id_group,
        'group_name' => $group_name,
        'students_amount' => $students_amount,
        'study_year_start' => $study_year_start,
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
    <title>Группы</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container-fluid p-2" id="upbar" style="position: fixed; background-color: rgba(141, 141, 141, 1); z-index: 2;">       
        <div class="col">
            <div class="row align-items-start">
                <a href="list.php" class="btn" style="z-index: 2;">❮</a>
                <!-- <a class="btn" id="btn-menu">☰</a> -->
                <div class="col px-2 text-center"><h4>Изменить Группу</h4></div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-5">
        <form method="POST" action="">
            <input type="hidden" name="id_group" value="<?php echo htmlspecialchars($id_group); ?>"> 
            <div class="form-group">
                <label for="group_name">Название:</label>
                <input type="text" class="form-control" id="group_name" name="group_name" required>
                <label for="students_amount">Количество учеников:</label>
                <input type="number" class="form-control" id="students_amount" name="students_amount" min=0 required>
                <label for="study_year_start">Год начала обучения:</label>
                <input type="number" class="form-control" id="study_year_start" name="study_year_start" min=0 required>
            </div>
            <button type="submit" class="btn btn-primary">Изменить</button>
        </form>
    </div>
</body>
</html>