<?php
// Error reporting (testing ke baad band kar sakte hain)
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include "../config/dbs.php";

$school = $_POST['school_db'] ?? $_SESSION['temp_school'] ?? '';
$mobile = $_POST['mobile'] ?? $_SESSION['last_mobile'] ?? '';


if (empty($school) || empty($mobile)) {
    die("Session expired. Please go back and login again.");
}

$dbs = getAllSlaveDatabases();
$found = null;
$ROOT = '';
$BASE = '';
foreach ($dbs as $d) {
    if ($d['db'] === $school) {
        $found = $d;

        // Root path
        $ROOT = rtrim($d['root'] ?? '', '/');

        // Base URL
        $BASE = rtrim($d['base'] ?? '', '/');
        break;
    }
}
if (!$found) die("School database not found.");

// Global constants
define("SCHOOL_ROOT", $ROOT);
define("SCHOOL_BASE", $BASE);

$con = new mysqli("localhost", $found['user'], $found['pass'], $found['db']);
if ($con->connect_error) die("Connection failed: " . $con->connect_error);

// FIXED QUERY: student_class.roll_no ko hata diya gaya hai
// Query with Class Table Join
$sql = "SELECT 
            student_all.stu_id,
            student_all.name,
            student_class.class_id,
            student_class.section_id,
            student_class.session_id,
            class.class_name,
            student_image.image_name
        FROM student_all
        JOIN student_class 
            ON student_all.stu_id = student_class.stu_id
        INNER JOIN class 
            ON student_class.class_id = class.class_id
        LEFT JOIN student_image 
            ON student_all.stu_id = student_image.stu_id
        WHERE (student_all.mobile = '$mobile' 
               OR student_all.whatsapp = '$mobile')
        AND student_class.active = '1'";


$res = $con->query($sql);
if (!$res) die("Query Error: " . $con->error);

$_SESSION['temp_school'] = $school;
$_SESSION['last_mobile'] = $mobile;
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Select Student</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: { "primary": "#1152d4", "background-light": "#f6f6f8", "background-dark": "#101622" },
                    fontFamily: { "display": ["Lexend", "sans-serif"] },
                    borderRadius: { "DEFAULT": "1rem", "lg": "2rem", "full": "9999px" },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Lexend', sans-serif; -webkit-tap-highlight-color: transparent; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark min-h-screen flex flex-col">

<header class="sticky top-0 z-10 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-md px-6 pt-12 pb-4">
    <div class="flex items-center justify-between max-w-md mx-auto">
        <button onclick="window.history.back()" class="text-[#0d121b] dark:text-white p-2 -ml-2">
           </button>
        <h1 class="text-[#0d121b] dark:text-white text-xl font-bold tracking-tight">Select Student</h1>
        <div class="w-10"></div>
    </div>
</header>

<main class="flex-1 px-6 pt-4 pb-32 max-w-md mx-auto w-full overflow-y-auto">
    <p class="text-[#4c669a] dark:text-gray-400 text-sm mb-6 px-1">Multiple profiles found for <b><?= htmlspecialchars($mobile) ?></b>.</p>

    <div class="space-y-4">
        <?php while($row = $res->fetch_assoc()): 
            $student_name = htmlspecialchars($row['name']);
            $avatar_url = "https://ui-avatars.com/api/?name=".urlencode($student_name)."&background=1152d4&color=fff";
        ?>
            <form method="post" action="final_login.php">
                <input type="hidden" name="stu_id" value="<?= $row['stu_id'] ?>">
                <input type="hidden" name="mobile" value="<?= $mobile ?>">
                <input type="hidden" name="class_id" value="<?= $row['class_id'] ?>">
                <input type="hidden" name="section_id" value="<?= $row['section_id'] ?>">
                <input type="hidden" name="session_id" value="<?= $row['session_id'] ?>">
                
                <?php  $student_photo = $row['image_name']; ?>
                <div class="bg-white dark:bg-[#1a2133] p-4 rounded-lg border border-gray-100 dark:border-gray-800 shadow-sm transition-all active:scale-[0.98]">
                    <div class="flex items-center gap-4">
                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full h-16 w-16" 
                             style='background-image: url("<?= SCHOOL_BASE ?>/admin/student_image/<?= $student_photo ?>");'>
                        </div>
                        <div class="flex flex-col flex-1">
                            <p class="text-[#0d121b] dark:text-white text-sm font-bold leading-tight"><?= $student_name ?></p>
                            <p class="text-[#4c669a] dark:text-gray-400 text-sm font-medium">Class: <?= $row['class_name'] ?></p>
                        </div>
                        <div class="shrink-0">
                            <button type="submit" class="bg-primary text-white px-5 py-2 rounded-full text-sm font-semibold shadow-md active:bg-blue-800">
                                Switch
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        <?php endwhile; ?>

        <button onclick="window.location.href='index.php'" class="w-full mt-4 flex items-center justify-center gap-2 p-4 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-700 text-[#4c669a] dark:text-gray-400">
            <span class="material-symbols-outlined">add_circle</span>
            <span class="font-medium">Use different number</span>
        </button>
    </div>
</main>

<div class="fixed bottom-6 left-1/2 -translate-x-1/2 w-[90%] max-w-sm z-50">
 
</div>

</body>
</html>
