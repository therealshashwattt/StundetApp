<?php
session_start();

$current = basename($_SERVER['PHP_SELF']);

// अगर school ही set नहीं है → school login पर भेजो
if (!isset($_SESSION['school_db'])) {
    header("Location: /StudentAppDatalaysSoftware/auth/login.php");
    exit;
}

// ये pages student के बिना भी खुल सकते हैं
$allowed_without_student = [
    'login.php',
    'switch_student.php'
];

// अगर student login नहीं है और page allowed नहीं → student login पर भेजो
if (!isset($_SESSION['student_id']) && !in_array($current, $allowed_without_student)) {
    header("Location: /StudentAppDatalaysSoftware/student/login.php");
    exit;
}
