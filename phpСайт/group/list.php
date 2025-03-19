<?php
session_start();
include '../sidebar.php';
include '../alert.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../index.php");
    exit();
}
$role = $_SESSION['role'];
if ($role !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$role = $_SESSION['role'];
$teacher_id = $_SESSION['teacher_id'];

$query = "SELECT g.id_group, g.group_name, g.students_amount, g.study_year_start, t.name, t.surname, t.lastname
FROM public.groups g 
left join teachers t on t.group_id = g.id_group";
$stmt = $conn->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                <a href="../index.php" class="btn">❮</a>
                <!-- <a class="btn" id="btn-menu">☰</a> -->
                <div class="col px-2 text-center"><h4>Список Групп</h4></div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-5">

            <?php if ($message): ?>
                <div class="alert alert-warning"><?php echo $message; ?></div>
            <?php else: ?>
                <div class="container-fluid">
                    <?php if ($role === 'admin'): ?>
                        <div class="col p-2 align-conten-center" style="background-color: rgb(221, 221, 221); border-radius: .25rem; margin: 10px">
                            <div class="row m-2 justify-content-center">
                                <a href="add.php?" class="btn btn-primary" style="padding: 5px 50px 5px 50px;">+</a>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php foreach ($results as $row): ?>
                        <div class="col p-2" style="background-color: rgb(221, 221, 221); border-radius: .25rem; margin: 10px">
                            <div class="row m-2 align-items-start" style="margin-bottom: 0 !important;">
                                <h3><?php echo htmlspecialchars($row['group_name']); ?></h3>
                            </div>
                            <div class="body m-2" style="padding-right: 15px; padding-left: 15px; margin-top: 0 !important">
                                Руководитель группы: <?php echo htmlspecialchars($row['name'] . ' ' . $row['surname'] . ' ' . $row['lastname']);?>
                                <br>
                                Количество студентов: <?php echo htmlspecialchars($row['students_amount']); ?>
                                <br>
                                Год начала обучения: <?php echo htmlspecialchars($row['study_year_start']); ?>
                                <br>
                                <br>
                                <?php if ($role === 'admin'): ?>
                                    <a href="edit.php?id_group=<?php echo $row['id_group']; ?>" class="btn btn-warning">Редактировать</a>
                                    <a href="del.php?id_group=<?php echo $row['id_group']; ?>" class="btn btn-danger">Удалить</a>
                                <?php endif; ?>  
                            </div>          
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
</body>
</html>