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
$section    = $_SESSION['section_id'] ?? 1;
$session_id = $_SESSION['session_id'] ?? 0;
$class_id   = $_SESSION['class_id'] ?? 0;

// Fetch notifications for student
$notifications = [];

$sql = "
    SELECT id, stu_id, class_id, title, message, created_at
    FROM student_notifications
    WHERE 
        (stu_id = '$stu_id')
        OR (class_id = '$class_id')
        OR (stu_id IS NULL AND class_id IS NULL)
    ORDER BY id DESC
";

$result = mysqli_query($con, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $notifications[] = $row;
    }
}
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>School Notifications</title>

<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "primary": "#1152d4",
                    "nav-dark": "#0A1E45",
                    "background-light": "#F8FAFC",
                },
                fontFamily: {
                    "display": ["Lexend", "sans-serif"]
                },
                borderRadius: {
                    "DEFAULT": "1rem",
                    "lg": "1.5rem",
                    "xl": "2rem",
                    "full": "9999px"
                },
            },
        },
    }
</script>

<style type="text/tailwindcss">
    body {
        font-family: 'Lexend', sans-serif;
        -webkit-tap-highlight-color: transparent;
        background-color: #F8FAFC;
    }
    .card-shadow {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.04), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
    }
    .glass-header {
        backdrop-filter: blur(20px);
        background-color: rgba(255, 255, 255, 0.85);
    }
</style>

<style>
    body { min-height: max(884px, 100dvh); }
</style>
</head>

<body class="text-[#1e293b] min-h-screen">

<header class="sticky top-0 z-40 glass-header border-b border-gray-100">
    <div class="flex items-center gap-4 px-6 py-5">

        <!-- Back Button -->
        <a href="dashboard.php" class="flex items-center justify-center size-10 rounded-full hover:bg-gray-100 transition">
            <span class="material-symbols-outlined text-gray-700">arrow_back</span>
        </a>

        <!-- Title -->
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Notifications</h1>

    </div>
</header>

<main class="max-w-md mx-auto px-5 pt-6 pb-36 space-y-5">

<?php if(count($notifications) > 0): ?>

    <?php foreach($notifications as $n): ?>

        <div class="bg-white rounded-xl card-shadow overflow-hidden border border-gray-50">
            <div class="p-5">

                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="size-11 rounded-full bg-blue-50 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-2xl">notifications</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 leading-tight">
                                <?= htmlspecialchars($n['title']) ?>
                            </h3>
                            <p class="text-[11px] font-medium text-gray-400 uppercase tracking-wider">
                                <?= date("d M Y, h:i A", strtotime($n['created_at'])) ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <p class="text-[15px] text-gray-700 leading-relaxed">
                        <?= nl2br(htmlspecialchars($n['message'])) ?>
                    </p>
                </div>

            </div>
        </div>

    <?php endforeach; ?>

<?php else: ?>

    <div class="py-12 flex flex-col items-center justify-center text-center">
        <div class="size-16 rounded-full bg-green-50 text-green-500 flex items-center justify-center mb-4">
            <span class="material-symbols-outlined text-3xl">done_all</span>
        </div>
        <p class="text-gray-400 font-medium text-sm">No notifications yet.</p>
    </div>

<?php endif; ?>

</main>

 

<div class="fixed bottom-2 left-1/2 -translate-x-1/2 w-32 h-1.5 bg-gray-200 rounded-full z-[60]"></div>

</body>
</html>
