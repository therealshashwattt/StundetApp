<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['student_id'])) {
    die("Not logged in");
}

$stu_id = $_SESSION['student_id'];
$token  = $_POST['token'] ?? '';

if (!$token) {
    die("No token received");
}

$stmt = $con->prepare("UPDATE student_all SET fcm_token=? WHERE stu_id=?");
$stmt->bind_param("si", $token, $stu_id);
$stmt->execute();

echo "Token saved successfully";
