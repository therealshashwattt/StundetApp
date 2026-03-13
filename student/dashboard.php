<?php
// Error Reporting & Config
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

include "../middleware/auth_guard.php";
include "../config/db.php";

$software_direct = "../" . SCHOOL_ROOT . "/";
require_once $software_direct . "newadmin/functions.php";

// Session Variables
$stu_id     = $_SESSION['student_id'] ?? 0;
$section = $_SESSION['section_id'] ?? 1;
$session_id = $_SESSION['session_id'] ?? 0;
$class_id   = $_SESSION['class_id'] ?? 0;

// Dynamic Data Variables
$student_name = SelectStudentHistory('name', $stu_id, $class_id, $section, $session_id);
$class_name = SelectStudentHistory('class_name', $stu_id, $class_id, $section, $session_id);
$school_name = GetSiteOptions('schoolNameInter');
$student_photo = SelectStudentImage($con, $stu_id);

// Attendance Percentage Logic (Example: Fetching from your tbl_attendance)
$att_sql = "SELECT 
            (SUM(CASE WHEN attend = 'P' THEN 1 ELSE 0 END) * 100 / COUNT(*)) as percentage 
            FROM tbl_attendance WHERE stu_id = '$stu_id' AND session_id = '$session_id'";
$att_res = mysqli_query($con, $att_sql);
$att_data = mysqli_fetch_assoc($att_res);

$attendance_percent = student_attendance_percentage($session_id, $class_id, $stu_id);
//$attendance_percent = round($att_data['percentage'] ?? 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= $student_name ?> - Dashboard</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet"/>
    <script>
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              primary: "#F59E0B", 
              "background-light": "#F3F4F6",
              "background-dark": "#111827",
              "card-light": "#FFFFFF",
              "card-dark": "#1F2937",
            },
            borderRadius: { DEFAULT: "1rem" },
          },
        },
      };
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; -webkit-tap-highlight-color: transparent; min-height: 100dvh; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .safe-bottom { padding-bottom: env(safe-area-inset-bottom); }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display">

<div class="mx-auto w-full max-w-md">

<div class="px-6 pt-12 pb-6 bg-white dark:bg-card-dark rounded-b-[2.5rem] shadow-sm">
    <div class="flex items-center justify-between mb-6 gap-4">
        <div class="flex items-center gap-3 min-w-0">
            <div class="p-2 bg-blue-900 rounded-xl">
                <span class="material-icons-round text-white text-xl">school</span>
            </div>
            <div class="min-w-0">
                <h1 class="text-sm font-bold text-blue-900 dark:text-blue-400 leading-tight uppercase truncate"><?= htmlspecialchars($school_name ?? '') ?></h1>
                <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-wider truncate">Prayagraj, Uttar Pradesh</p>
            </div>
        </div>
        <button onclick="window.location='notification.php'"  class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center relative">
            <span class="material-icons-round text-slate-600 dark:text-slate-300">notifications</span>
            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
        </button>
    </div>

    <div class="flex items-center gap-4">
        <div class="relative">
            <div class="w-20 h-20 rounded-2xl overflow-hidden border-2 border-primary/20 p-0.5">
                <img alt="Student Profile" class="w-full h-full object-cover rounded-xl" src="<?= SCHOOL_BASE ?>/admin/student_image/<?= $student_photo ?>"/>
            </div>
            <div class="absolute -bottom-1 -right-1 bg-green-500 w-4 h-4 rounded-full border-2 border-white dark:border-card-dark"></div>
        </div>
        <div class="flex-1">
            <p class="text-slate-500 dark:text-slate-400 text-sm">Welcome back,</p>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white uppercase"><?= explode(' ', $student_name)[0] ?> <span class="text-primary">👋</span></h2>
            <div class="flex items-center gap-2 mt-1">
                <span class="px-2 py-0.5 bg-primary/10 text-primary text-[10px] font-bold rounded-md uppercase">Class <?= $class_name ?></span>
                <span class="text-xs text-slate-400">ID: <?= $stu_id ?></span>
            </div>
        </div>
    </div>
</div>



<main class="px-6 py-8 space-y-6 pb-32">
    
    <?php
// SQL Query wahi rahegi
$latest_sql = "
    SELECT title, message 
    FROM student_notifications
    WHERE 
        (stu_id = '$stu_id')
        OR (class_id = '$class_id')
        OR (stu_id IS NULL AND class_id IS NULL)
    ORDER BY id DESC
    LIMIT 1
";

$latest_res = mysqli_query($con, $latest_sql);
$latest = mysqli_fetch_assoc($latest_res);

// Check karein ki kya notification mili hai?
if ($latest): 
?>

<div class="space-y-4">
    <div class="flex items-center justify-between px-1">
        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Latest Notification</h3>
        <a href="notification.php" class="text-xs font-bold text-primary">View All</a>
    </div>

    <div class="bg-white p-4 rounded-3xl shadow-sm border border-slate-100 flex gap-4">
        <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center text-red-600">
            <span class="material-icons-round">campaign</span>
        </div>

        <div>
            <h4 class="font-bold text-sm">
                <?= htmlspecialchars($latest['title']) ?>
            </h4>

            <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                <?= htmlspecialchars($latest['message']) ?>
            </p>
        </div>
    </div>
</div>

<?php endif; // Yaha condition khatam hoti hai ?>
    
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-card-light dark:bg-card-dark p-4 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-xl text-blue-600 dark:text-blue-400">
                    <span class="material-icons-round">how_to_reg</span>
                </div>
            </div>
            <h3 class="text-slate-500 dark:text-slate-400 text-xs font-medium">Attendance</h3>
            <p class="text-2xl font-bold text-slate-800 dark:text-white mt-1"><?= $attendance_percent ?></p>
            <div class="w-full bg-slate-100 dark:bg-slate-700 h-1.5 rounded-full mt-3 overflow-hidden">
                <div class="bg-blue-500 h-full rounded-full" style="width: <?= $attendance_percent ?>"></div>
            </div>
        </div>

        <div class="bg-card-light dark:bg-card-dark p-4 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-orange-50 dark:bg-orange-900/30 rounded-xl text-orange-600 dark:text-orange-400">
                    <span class="material-icons-round">edit_note</span>
                </div>
                <span class="text-[10px] font-bold text-white bg-red-500 px-2 py-1 rounded-full">New</span>
            </div>
            <h3 class="text-slate-500 dark:text-slate-400 text-xs font-medium">Homework</h3>
            <p class="text-2xl font-bold text-slate-800 dark:text-white mt-1">Active</p>
            <p class="text-[10px] text-slate-400 mt-2">Check details below</p>
        </div>
    </div>

    <div class="space-y-4">
        <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 px-1 uppercase tracking-wider">DashBoard</h3>
        <div class="grid grid-cols-4 gap-4">
            <button onclick="window.location='notification.php'" class="flex flex-col items-center gap-2 group">
                <div class="w-14 h-14 bg-card-light dark:bg-card-dark rounded-2xl flex items-center justify-center shadow-sm border border-slate-100 dark:border-slate-800 group-active:scale-95 transition-transform">
                    <span class="material-icons-round text-indigo-500">mail</span>
                </div>
                <span class="text-[11px] font-medium text-slate-600 dark:text-slate-400">Inbox</span>
            </button>
            <button onclick="window.location='timetable.php'" class="flex flex-col items-center gap-2 group">
                <div class="w-14 h-14 bg-card-light dark:bg-card-dark rounded-2xl flex items-center justify-center shadow-sm border border-slate-100 dark:border-slate-800 group-active:scale-95 transition-transform">
                    <span class="material-icons-round text-emerald-500">calendar_month</span>
                </div>
                <span class="text-[11px] font-medium text-slate-600 dark:text-slate-400 text-center leading-tight">Time Table</span>
            </button>
            <button onclick="window.location='fees.php'" class="flex flex-col items-center gap-2 group">
                <div class="w-14 h-14 bg-card-light dark:bg-card-dark rounded-2xl flex items-center justify-center shadow-sm border border-slate-100 dark:border-slate-800 group-active:scale-95 transition-transform">
                    <span class="material-icons-round text-amber-500">payments</span>
                </div>
                <span class="text-[11px] font-medium text-slate-600 dark:text-slate-400">Fees</span>
            </button>
            <button onclick="window.location='result.php'" class="flex flex-col items-center gap-2 group">
                <div class="w-14 h-14 bg-card-light dark:bg-card-dark rounded-2xl flex items-center justify-center shadow-sm border border-slate-100 dark:border-slate-800 group-active:scale-95 transition-transform">
                    <span class="material-icons-round text-rose-500">assignment_turned_in</span>
                </div>
                <span class="text-[11px] font-medium text-slate-600 dark:text-slate-400 text-center leading-tight">Results</span>
            </button>
        </div>
    </div>

 
</main>


</div>

<nav class="fixed bottom-6 left-1/2 -translate-x-1/2 w-[calc(100%-3rem)] max-w-md safe-bottom">
    <div class="bg-primary shadow-2xl shadow-primary/30 rounded-[2rem] px-6 py-4 flex justify-between items-center text-white">
        <button onclick="window.location='dashboard.php'" class="flex flex-col items-center gap-1">
            <span class="material-icons-round text-2xl">account_circle</span>
            <span class="text-[10px] font-bold uppercase tracking-tight">Profile</span>
        </button>
        <button onclick="window.location='../auth/switch_student.php'" class="flex flex-col items-center gap-1 opacity-70">
            <span class="material-icons-round text-2xl">swap_horiz</span>
            <span class="text-[10px] font-bold uppercase tracking-tight">Switch</span>
        </button>
        <button onclick="window.location='social.php'" class="flex flex-col items-center gap-1 opacity-70">
            <span class="material-icons-round text-2xl">grid_view</span>
            <span class="text-[10px] font-bold uppercase tracking-tight">Social</span>
        </button>
        <button onclick="window.location='../auth/logout.php'" class="flex flex-col items-center gap-1 opacity-70">
            <span class="material-icons-round text-2xl">power_settings_new</span>
            <span class="text-[10px] font-bold uppercase tracking-tight">Logout</span>
        </button>
    </div>
</nav>


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
