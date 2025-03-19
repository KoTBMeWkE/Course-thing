<?php
session_start();
include '../sidebar.php';
include '../alert.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../index.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$role = $_SESSION['role'];
$teacher_id = $_SESSION['teacher_id'];

$query = "SELECT c.id_contest, c.title, c.date, c.description, c.subject, c.winning_student, c.teacher_id, t.name as teacher_name, t.surname as teacher_surname, t.lastname, s.name as student_name, s.surname as student_surname, g.group_name
FROM public.contests c 
JOIN teachers t on t.id_prep = c.teacher_id
left join students s on c.winning_student = s.id_student
left JOIN groups g on g.id_group = s.group_id
ORDER by c.date";
$stmt = $conn->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                <a href="../index.php" class="btn">❮</a>
                <!-- <a class="btn" id="btn-menu">☰</a> -->
                <div class="col px-2 text-center"><h4>Список Конкурсов</h4></div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid pt-5">

            <?php if ($message): ?>
                <div class="alert alert-warning"><?php echo $message; ?></div>
            <?php else: ?>
                <div class="container-fluid">
                    <?php if ($role === 'admin' || $role === 'teacher'): ?>
                        <div class="col p-2 align-conten-center" style="background-color: rgb(221, 221, 221); border-radius: .25rem; margin: 10px">
                            <div class="row m-2 justify-content-center">
                                <a href="add.php" class="btn btn-primary" style="padding: 5px 50px 5px 50px;">+</a>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php foreach ($results as $row): ?>
                        <div class="col p-2" style="background-color: rgb(221, 221, 221); border-radius: .25rem; margin: 10px">
                            <div class="row m-2 align-items-start" style="margin-bottom: 0 !important;">
                                <div class="col-9"><h3><?php echo htmlspecialchars($row['title']); ?></h3></div>
                                <?php $dateString = $row['date'];
                                    $date = new DateTime($dateString);
                                    $currentYear = date('Y');
                                    $formatter = new IntlDateFormatter(
                                        'ru_RU',
                                        IntlDateFormatter::LONG,
                                        IntlDateFormatter::NONE
                                    );
                                    if ($date->format('Y') == $currentYear) {
                                        $formattedDate = $formatter->format($date->setTime(0, 0));               
                                    } else {
                                        $formattedDate = $formatter->format($date);   
                                    }?>
                                <div class="col-3 align-self-end text-right"><?php echo $formattedDate; ?></div>
                            </div>
                            <div class="body m-2" style="padding-right: 15px; padding-left: 15px; margin-top: 0 !important">
                                <h6>Организатор: <?php echo htmlspecialchars($row['teacher_name'] . ' ' . $row['teacher_surname'] . ' ' . $row['lastname']); ?></h6>
                                Описание: <?php echo htmlspecialchars($row['description']); ?>
                                <br>
                                Задание: <?php echo htmlspecialchars($row['subject']); ?>
                                <br>
                                Победитель: <?php if (!empty($row['winning_student'])) {
                                        echo htmlspecialchars($row['student_name'] . ' ' . $row['student_surname'] . ' из группы ' . $row['group_name']);
                                    } else {
                                        echo "Ещё не объявлено";
                                    }
                                ?>
                                <br>
                                <br>
                                
                                <?php if ($role === 'admin' || $_SESSION['teacher_id'] === $row['teacher_id']): ?>
                                    <a href="edit.php?id_contest=<?php echo $row['id_contest']; ?>&teacher_id=<?php echo $row['teacher_id']; ?>" class="btn btn-warning">Редактировать</a>
                                    <a href="del.php?id_contest=<?php echo $row['id_contest']; ?>&teacher_id=<?php echo $row['teacher_id']; ?>" class="btn btn-danger">Удалить</a>
                                <?php elseif($role == 'student' && is_null($row['winning_student'])): ?>
                                    <a href="../signup.php?id_contest=<?php echo $row['id_contest']; ?>" class="btn btn-primary">Записаться</a>
                                <?php endif; ?>  
                            </div>          
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
</body>
</html>