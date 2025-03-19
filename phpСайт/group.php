<?php
session_start();
require 'sidebar.php';
require 'access_check.php';
$group_id = (int)filter_input(INPUT_GET, 'group_id', FILTER_SANITIZE_NUMBER_INT);
$role = $_SESSION['role'];

checkGroupAccess($conn, $group_id);

$Squery = "SELECT g.id_group, s.name, s.surname, s.lastname, s.birthdate, s.live_place, s.parent_contact
FROM public.groups g
left join students s on s.group_id = g.id_group
where id_group = :group_id";
$Tquery = "SELECT g.id_group, t.name, t.surname, t.lastname  
FROM public.groups g
left join teachers t on t.group_id = g.id_group
where id_group = :group_id";
$Sstmt = $conn->prepare($Squery);
$Tstmt = $conn->prepare($Tquery);
$Sstmt->execute(['group_id' => $group_id]);
$Tstmt->execute(['group_id' => $group_id]);
$Sresults = $Sstmt->fetchAll(PDO::FETCH_ASSOC);
$Tresults = $Tstmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Группа</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="sidebar.js"></script>
</head>
<body>
    <div class="container-fluid p-2" id="upbar" style="position: fixed; background-color: rgba(141, 141, 141, 1); z-index: 2;">       
        <div class="col">
            <div class="row align-items-start">
                <a href="index.php" class="btn">❮</a>
                <a class="btn" id="btn-menu">☰</a>
                <div class="col px-2 text-center"><h4><?php echo htmlspecialchars($siderow['group_name']); ?></h4></div>
            </div>
        </div>
    </div>
    </div>
    <div class="container-fluid pt-5">   
        <h3>Классный руководитель</h3>
        <?php foreach ($Tresults as $row2): ?>
            <div class="col p-2" style="background-color: rgb(221, 221, 221); border-radius: .25rem; margin: 10px">
                <div class="row m-2 align-items-start" style="margin-bottom: 0 !important;">
                    <div class="col-9"><h3> <?php echo htmlspecialchars($row2['name'] . ' ' . $row2['surname'] . ' ' . $row2['lastname']);?></h3></div>
                </div>
            </div>
        <?php endforeach; ?>
        <h3>Ученики</h3>
        <?php foreach ($Sresults as $row): ?>
            <div class="col p-2" style="background-color: rgb(221, 221, 221); border-radius: .25rem; margin: 10px">
                <div class="row m-2 align-items-start" style="margin-bottom: 0 !important;">
                    <div class="col-9"><h3> <?php echo htmlspecialchars($row['name'] . ' ' . $row['surname'] . ' ' . $row['lastname']);?></h3></div>
                    <?php $dateString = $row['birthdate'];
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
                    <div class="col-3 align-self-end text-right">День рождения <?php echo $formattedDate; ?></div>
                </div>
                <?php if ($role === 'admin' || $role === 'teacher'): ?>
                <div class="body m-2" style="padding-right: 15px; padding-left: 15px; margin-top: 0 !important">
                    <h6>Место жительства: <?php echo htmlspecialchars($row['live_place']);?></h6>
                    <h6>Контакты родителей: <?php echo htmlspecialchars($row['parent_contact']);?></h6>
                </div>         
                <?php endif;?> 
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>