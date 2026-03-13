<?php
session_start();
if(isset($_SESSION['student_id'])){
    header("Location: student/dashboard.php");
} else {
    header("Location: auth/login.php");
}
exit;
