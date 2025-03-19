<?php
require 'db.php';
$role = $_SESSION['role'];
$username = $_SESSION['username']; 
$teacherId = $_SESSION['teacher_id'];
$studentId = $_SESSION['student_id'];

$sidequery = "SELECT g.group_name, t.group_id 
FROM public.teachers t
left join groups g on t.group_id = g.id_group
where id_prep = :teacher_id";
$sidestmt = $conn->prepare($sidequery);
$sidestmt->execute(['teacher_id' => $teacherId]);
$sideresults = $sidestmt->fetchAll(PDO::FETCH_ASSOC);
$sidequery2 = "SELECT g.group_name, s.group_id 
FROM public.students s
left join groups g on s.group_id = g.id_group
where id_student = :student_id";
$sidestmt2 = $conn->prepare($sidequery2);
$sidestmt2->execute(['student_id' => $studentId]);
$sideresults2 = $sidestmt2->fetchAll(PDO::FETCH_ASSOC);
?>
<head>
    <script src="../sidebar.js"></script>
</head>
<body>
<div class="col p-3" style="position: fixed; display: flex; left:-380px; z-index: 4; background-color: white; width:380px; height:100%; transition: left 0.3s" id="menu">
        <div class="col" style="height:100%">
            <h2>Меню</h2>
            <a href="contest/list.php" class="btn">Конкурсы</a>  
            <br> 
            <?php if ($role === 'admin'): ?>
                <a href="teacher/list.php" class="btn">Учителя</a>
                <br>
                <a href="student/list.php" class="btn">Ученики</a>
                <br>
                <a href="group/list.php" class="btn">Группы</a>
                <br>
                <a href="user/list.php" class="btn">Пользователи</a>
                <br>
                <a href="constud/list.php" class="btn">Записавшиеся Ученики</a>        
            <?php elseif($role === 'teacher'): ?>
                <a class="btn" style="pointer-events: none">Группы</a>
                <br>
                <?php foreach ($sideresults as $siderow): ?>
                    <a href="group.php?group_id=<?php echo $siderow['group_id']; ?>" class="btn mx-3"><?php echo htmlspecialchars($siderow['group_name']); ?></a>
                    <br> 
                <?php endforeach; ?>
            <?php elseif($role === 'student'): ?>
                <?php foreach ($sideresults2 as $siderow): ?>
                    <a href="group.php?group_id=<?php echo $siderow['group_id']; ?>" class="btn"><?php echo htmlspecialchars($siderow['group_name']); ?></a>
                    <br> 
                <?php endforeach; ?>
            <?php endif;?>
        </div>
        <div class="col" style="position: absolute; align-self: end; align-content: end;">
            <div clas="row"><?php echo $username;?> | <?php echo $role;?> <a href="logout.php" class="btn" style="align-self: end;">Выход</a> </div >
        </div> 
</div> 
<div id="overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); visibility: hidden; z-index: 3; transition: opacity 0.3s, visibility 0.3s;"></div>
</body>