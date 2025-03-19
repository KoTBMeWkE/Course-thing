<?php

// Функция для проверки доступа
function checkAccess($requiredRole, $teacherId = null, $idContest = null, $conn) {
    // Получаем роль пользователя из сессии
    $role = $_SESSION['role'];
    $userTeacherId = $_SESSION['teacher_id'];

    if ($teacherId !== null) {
        $stmt = $conn->prepare("SELECT * FROM public.contests WHERE teacher_id = :teacher_id AND id_contest = :id_contest");
        $stmt->execute(['teacher_id' => $teacherId, 'id_contest' => $idContest]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Если нет результатов, значит, доступ запрещен
        if (empty($results)) {
            header("Location: ../index.php");
            exit();
        }
    }

    // Проверка доступа
    if ($role !== $requiredRole && $userTeacherId !== $teacherId) {
        // Если доступ запрещен, перенаправляем на главную страницу
        header("Location: ../index.php");
        exit();
    }
}
function checkGroupAccess($conn, $group_id) {
    // Получаем роль пользователя из сессии
    $role = $_SESSION['role'];
    $userTeacherId = $_SESSION['teacher_id'];
    $studentId = $_SESSION['student_id'];

    // Проверка, является ли пользователь администратором
    if ($role === 'admin') {
        return true;
    }

    // Проверка, является ли пользователь руководителем группы
    $stmt = $conn->prepare("SELECT * 
                            FROM public.teachers 
                            WHERE group_id = :group_id AND id_prep = :teacher_id");
    $stmt->execute(['group_id' => $group_id, 'teacher_id' => $userTeacherId]);
    if ($stmt->fetch()) {
        return true;
    }

    // Проверка, является ли пользователь учеником группы
    $stmt = $conn->prepare("SELECT * 
                            FROM public.students 
                            WHERE group_id = :group_id AND id_student = :student_id");
    $stmt->execute(['group_id' => $group_id, 'student_id' => $studentId]);
    if ($stmt->fetch()) {
        return true;
    }

    // Если ни одно из условий не выполнено, доступ запрещен
    header("Location: index.php");
    exit();
}
?>