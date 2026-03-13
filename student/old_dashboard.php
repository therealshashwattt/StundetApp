<?php
// Error Reporting & Config
ini_set('display_errors', 1);
error_reporting(E_ALL);

include "../middleware/auth_guard.php";
include "../config/db.php";

$software_direct = "../" . SCHOOL_ROOT . "/";
require_once $software_direct . "newadmin/functions.php";

// Session Variables
$stu_id     = $_SESSION['student_id'] ?? 0;
$section_id = $_SESSION['section_id'] ?? 0;
$session_id = $_SESSION['session_id'] ?? 0;
$class_id   = $_SESSION['class_id'] ?? 0;

// Student Details Fetching
$student_name  = SelectStudentHistory('name', $stu_id, $class_id, $section_id, $session_id);
$class_name    = SelectStudentHistory('class_name', $stu_id, $class_id, $section_id, $session_id);
$school_name   = GetSiteOptions('schoolNameInter');
$student_photo = SelectStudentImage($con, $stu_id);

// Additional Stats (Purane mapping ke hisab se)
// $attendance = getStudentAttendancePercentage($stu_id, $session_id); // Function example
// $due_fee = calculateDueFee($stu_id, $class_id, $session_id); // As per your logic
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - <?= $school_name ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .dashboard-header { background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); color: white; padding: 30px 20px; border-radius: 0 0 25px 25px; }
        .profile-card { background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin-top: -50px; text-align: center; }
        .profile-image img { width: 100px; height: 100px; border-radius: 50%; border: 4px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.2); object-fit: cover; }
        .stat-card { background: white; border-radius: 15px; padding: 15px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: 0.3s; }
        .stat-card:active { transform: scale(0.95); }
        .menu-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; padding: 20px; }
        .btn-action { margin: 5px; border-radius: 10px; }
    </style>
</head>
<body>

<div class="dashboard-header text-center">
    <h4><?= $school_name ?></h4>
    <p class="small opacity-75">Student Portal 2026</p>
</div>

<div class="container">
    <div class="profile-card mx-auto" style="max-width: 400px;">
        <div class="profile-image mb-2">
            <img src="<?= SCHOOL_BASE ?>/admin/student_image/<?= $student_photo ?>" alt="Student Photo">
        </div>
        <h5 class="mb-0"><?= $student_name ?></h5>
        <p class="text-muted small">Class: <?= $class_name ?> | ID: <?= $stu_id ?></p>
        
        <div class="d-flex justify-content-center">
            <a href="../auth/switch_student.php" class="btn btn-sm btn-outline-primary btn-action">Switch Student</a>
            <a href="../auth/logout.php" class="btn btn-sm btn-danger btn-action">Logout</a>
        </div>
    </div>

    <div class="row mt-4 px-2">
        <div class="col-4">
            <div class="stat-card">
                <i class="fa fa-calendar-check text-success mb-2"></i>
                <h6 class="mb-0">85%</h6>
                <small class="text-muted" style="font-size: 10px;">Attendance</small>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-card">
                <i class="fa fa-wallet text-warning mb-2"></i>
                <h6 class="mb-0">₹2500</h6>
                <small class="text-muted" style="font-size: 10px;">Due Fee</small>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-card">
                <i class="fa fa-star text-primary mb-2"></i>
                <h6 class="mb-0">A+</h6>
                <small class="text-muted" style="font-size: 10px;">Grade</small>
            </div>
        </div>
    </div>

    <h6 class="mt-4 px-3">Quick Menu</h6>
    <div class="menu-grid">
        <div class="stat-card">
            <i class="fa fa-user-graduate fa-2x text-info"></i><br>
            <small>Profile</small>
        </div>
        <div class="stat-card" onclick="window.location='attendance.php'">
            <i class="fa fa-clock fa-2x text-secondary"></i><br>
            <small>Attendance</small>
        </div>
        <div class="stat-card">
            <i class="fa fa-file-invoice-dollar fa-2x text-success"></i><br>
            <small>Fees</small>
        </div>
        <div class="stat-card">
            <i class="fa fa-book fa-2x text-warning"></i><br>
            <small>Exams</small>
        </div>
        <div class="stat-card">
            <i class="fa fa-table fa-2x text-danger"></i><br>
            <small>Timetable</small>
        </div>
        <div class="stat-card">
            <i class="fa fa-bell fa-2x text-primary"></i><br>
            <small>Notices</small>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 


<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging.js";

const firebaseConfig = {
  apiKey: "AIzaSyCKDVLLW20WPWxKlRXLgnBk0P-6kM87P_s",
  authDomain: "studentappdatalayssoftware.firebaseapp.com",
  projectId: "studentappdatalayssoftware",
  messagingSenderId: "239801368091",
  appId: "1:239801368091:web:e9a190ed486d8d0c0a42be"
};

const vapidKey = "BD-JZEBjnv-4y0Zy0O98KT-EoaEMBiw5FT0F8H4PrLQySaxPAX3EY3uBgc7SEeuDCspREHjWnNAfnfwvMwhATxw";

const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

// 🔥 FORCE register service worker
async function initFCM() {
  try {
    const reg = await navigator.serviceWorker.register("/firebase-messaging-sw.js");
    console.log("SW registered:", reg.scope);

    const permission = await Notification.requestPermission();
    console.log("Permission:", permission);

    if (permission !== "granted") {
      alert("Notification allow karo");
      return;
    }

    const token = await getToken(messaging, {
      vapidKey,
      serviceWorkerRegistration: reg
    });

    console.log("TOKEN:", token);

    if (!token) {
      console.error("Token null");
      return;
    }

    const res = await fetch("/StudentAppDatalaysSoftware/api/save_token.php", {
      method: "POST",
      headers: {"Content-Type": "application/x-www-form-urlencoded"},
      body: "token=" + encodeURIComponent(token)
    });

    console.log("Server:", await res.text());

  } catch (e) {
    console.error("FCM error:", e);
  }
}

initFCM();
</script>

</body>
</html>
