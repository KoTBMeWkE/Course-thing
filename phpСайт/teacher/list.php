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

$query = "SELECT t.id_prep, t.name, t.surname, t.lastname, t.email, t.phone_num, t.surname, g.group_name
FROM public.teachers t
left join groups g on g.id_group = t.group_id";
$stmt = $conn->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                <a href="../index.php" class="btn">❮</a>
                <!-- <a class="btn" id="btn-menu">☰</a> -->
                <div class="col px-2 text-center"><h4>Список Учителей</h4></div>
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
                                <div class="col-9"><h3> <?php echo htmlspecialchars($row['name'] . ' ' . $row['surname'] . ' ' . $row['lastname']);?></h3></div>
                            </div>
                            <div class="body m-2" style="padding-right: 15px; padding-left: 15px; margin-top: 0 !important">
                                Руководитель группы: <?php echo htmlspecialchars($row['group_name']); ?>
                                <br>
                                Телефон: <?php echo htmlspecialchars($row['phone_num']); ?>
                                <br>
                                Почта: <?php echo htmlspecialchars($row['email']); ?>
                                <br>
                                <br>
                                <?php if ($role === 'admin' || $teacher_id === $row['teacher_id']): ?>
                                    <a href="edit.php?id_prep=<?php echo $row['id_prep']; ?>" class="btn btn-warning">Редактировать</a>
                                    <a href="del.php?id_prep=<?php echo $row['id_prep']; ?>" class="btn btn-danger">Удалить</a>
                                <?php endif; ?>  
                            </div>          
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
</body>
</html>