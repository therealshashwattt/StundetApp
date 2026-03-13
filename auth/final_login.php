<?php
session_start();
$_SESSION['school_db'] = $_SESSION['temp_school'];
$_SESSION['student_id'] = $_POST['stu_id'];
$_SESSION['class_id'] = $_POST['class_id'];
$_SESSION['section_id'] = $_POST['section_id'];
$_SESSION['session_id'] = $_POST['session_id'];
$_SESSION['last_mobile'] = $_POST['mobile'] ?? $_SESSION['last_mobile'] ?? null;

unset($_SESSION['temp_school']);
header("Location: ../student/dashboard.php");
exit;
