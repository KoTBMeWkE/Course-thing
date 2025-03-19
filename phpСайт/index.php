<?php
session_start();
include 'sidebar.php';

// Проверка аутентификации
if (!isset($_SESSION['id_user']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Творческий поток</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
</head>
<body>
    <div class="container-fluid p-2" id="upbar" style="position: fixed; background-color: rgba(141, 141, 141, 1); z-index: 2;">       
        <div class="col">
            <div class="row align-items-start">
                <a class="btn" id="btn-menu">☰</a>
            </div>
        </div>
    </div>
    </div>
    <div class="container-fluid pt-5">   
    
    </div>
    <div class="body p-2 align-conten-center" style="margin: 10px; height:90%; display: flex; justify-content: center; align-items: center; flex-direction: column;">
            <h1>Добро пожаловать в "Творческий поток"!</h1>
            <p>Для начала работы нажмите кнопку на верхней панели слева.</p>
    </div>
</body>
</html>
