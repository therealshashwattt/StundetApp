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

?>
<?php
function ShowExamSummary($con, $class_id, $stu_id, $session_id) {
    // Exams fetch karne ka query
    $sqlExams = "
        SELECT e.exam_id, e.exam_name, e.date
        FROM all_exam e
        INNER JOIN student_result1 r ON e.exam_id = r.exam_id
        WHERE r.stu_id = $stu_id AND r.class_id = $class_id
        GROUP BY e.exam_id
        ORDER BY e.date DESC
    ";
    $resExams = mysqli_query($con, $sqlExams);

    if (mysqli_num_rows($resExams) > 0) {
        echo '';
        echo '<div class="flex flex-col gap-4 p-4">';

        while ($exam = mysqli_fetch_assoc($resExams)) {
            $exam_id = $exam['exam_id'];
            $exam_name = $exam['exam_name'];
            $date = $exam['date'];

            $date_obj = DateTime::createFromFormat('Y-m-d', $date);
            $formatted_date = $date_obj ? $date_obj->format('d M, Y') : $date;

            // Subject-wise marks fetch karna
            $sqlMarks = "
                SELECT subject_id, t_maximum_mark, t_obtain_mark, present_or_absent, pass_or_fail
                FROM student_result1
                WHERE stu_id = $stu_id AND class_id = $class_id AND exam_id = $exam_id
            ";
            $resMarks = mysqli_query($con, $sqlMarks);

            $totalMax = 0; $totalObt = 0; $subjectRows = "";

            while ($row = mysqli_fetch_assoc($resMarks)) {
                $subjectName = get_subject_name($con, $row['subject_id']);
                $maxMark = $row['t_maximum_mark'];
                $obtMark = $row['present_or_absent'] == "A" ? "AA" : $row['t_obtain_mark'];

                $subjectGrade = "";
                if ($row['present_or_absent'] != "A") {
                    $percent = ($maxMark > 0) ? round(($row['t_obtain_mark'] / $maxMark) * 100, 2) : 0;
                    if ($percent > 0) {
                        $subjectGrade = getGrade($percent, $con, $session_id);
                    }
                    $totalMax += (int)$maxMark;
                    $totalObt += (int)$row['t_obtain_mark'];
                }

                // Har subject ki row (Design ke mutabik)
                $subjectRows .= '
                    <div class="flex items-center justify-between py-2 border-b border-slate-50 dark:border-slate-700 last:border-0">
                        <div class="flex items-center gap-3">
                            <div class="size-8 rounded-lg bg-primary/5 flex items-center justify-center">
                                <span class="material-symbols-outlined text-sm text-primary">book</span>
                            </div>
                            <span class="text-sm font-medium">'.$subjectName.'</span>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-bold">'.$obtMark.'/'.$maxMark.'</div>
                            <div class="text-[10px] text-primary font-bold">Grade: '.$subjectGrade.'</div>
                        </div>
                    </div>';
            }

            $overallPercentage = ($totalMax > 0) ? round(($totalObt / $totalMax) * 100, 2) : 0;
            $overallGrade = ($overallPercentage > 0) ? getGrade($overallPercentage, $con, $session_id) : "N/A";

            // Card Output
            ?>
            <div class="bg-white dark:bg-slate-800 rounded-xl p-5 ios-shadow border border-white/50 flex flex-col gap-4">
                <div class="flex justify-between items-start">
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-2">
                            <span class="flex size-2 rounded-full bg-green-500 animate-pulse"></span>
                            <span class="text-[10px] font-bold text-green-600 dark:text-green-400 uppercase tracking-widest">Result Declared</span>
                        </div>
                        <h4 class="text-lg font-bold"><?php echo htmlspecialchars($exam_name); ?></h4>
                        <p class="text-sm text-[#4c669a]">Completed on <?php echo $formatted_date; ?></p>
                    </div>
                    <div class="bg-primary/5 p-2 rounded-lg">
                        <span class="material-symbols-outlined text-primary">analytics</span>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2 py-3 border-y border-slate-50 dark:border-slate-700">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-[#4c669a] font-bold uppercase">Marks</span>
                        <span class="text-base font-bold text-primary"><?php echo $totalObt.'/'.$totalMax; ?></span>
                    </div>
                    <div class="flex flex-col border-x border-slate-100 dark:border-slate-700 px-2 text-center">
                        <span class="text-[10px] text-[#4c669a] font-bold uppercase">Percentage</span>
                        <span class="text-base font-bold"><?php echo $overallPercentage; ?>%</span>
                    </div>
                    <div class="flex flex-col text-right">
                        <span class="text-[10px] text-[#4c669a] font-bold uppercase">Grade</span>
                        <span class="text-base font-bold text-green-600"><?php echo $overallGrade; ?></span>
                    </div>
                </div>

                <div id="sub-list-<?php echo $exam_id; ?>" class="hidden bg-slate-50 dark:bg-slate-900/40 rounded-xl p-3">
                    <?php echo $subjectRows; ?>
                </div>

                <button onclick="document.getElementById('sub-list-<?php echo $exam_id; ?>').classList.toggle('hidden')" class="w-full bg-background-light dark:bg-slate-700/50 hover:bg-primary hover:text-white transition-all text-primary font-bold py-3 rounded-full flex items-center justify-center gap-2 group">
                    View Subject Details
                    <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">expand_more</span>
                </button>
            </div>
            <?php
        }
        echo '</div>';
    } else {
        echo '<div class="p-8 text-center text-[#4c669a]">No exams found for this student.</div>';
    }
}
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Exam Results - KLJM School</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#1152d4",
                        "gold": "#D4AF37",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                    },
                    fontFamily: { "display": ["Lexend", "sans-serif"] },
                    borderRadius: { "DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px" },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Lexend', sans-serif; -webkit-tap-highlight-color: transparent; }
        .ios-shadow { box-shadow: 0 4px 24px -1px rgba(0, 0, 0, 0.06); }
        body { min-height: 100dvh; }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-[#0d121b] selection:bg-primary/20">

<div class="sticky top-0 z-50 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-md px-4 py-3 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div onclick="window.location='dashboard.php'" class="flex items-center justify-center size-10 rounded-full bg-white dark:bg-slate-800 ios-shadow cursor-pointer">
            <span class="material-symbols-outlined text-primary">arrow_back_ios_new</span>
        </div>
        <div>
            <h1 class="text-lg font-bold leading-tight tracking-tight"><?php echo $school_name; ?></h1>
            <p class="text-xs text-[#4c669a] font-medium uppercase tracking-wider">Academic Portal</p>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <div  onclick="window.location='notification.php'" class="size-10 rounded-full bg-white dark:bg-slate-800 ios-shadow flex items-center justify-center relative cursor-pointer">
            <span class="material-symbols-outlined text-[#0d121b] dark:text-white">notifications</span>
            <span class="absolute top-2.5 right-2.5 size-2 bg-red-500 rounded-full border-2 border-white dark:border-slate-800"></span>
        </div>
    </div>
</div>

<main class="max-w-md mx-auto pb-32">
    <div class="p-4">
        <div class="relative overflow-hidden rounded-xl bg-white dark:bg-slate-800 p-6 ios-shadow border border-white/50">
            <div class="absolute -top-10 -right-10 size-40 bg-primary/5 rounded-full"></div>
            <div class="absolute -bottom-10 -left-10 size-32 bg-gold/5 rounded-full"></div>
            <div class="relative z-10 flex flex-col gap-4">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-4">
                        <div class="size-16 rounded-full border-2 border-primary/20 p-0.5">
                            <div class="w-full h-full rounded-full bg-center bg-cover bg-slate-200" style="background-image: url('https://ui-avatars.com/api/?name=Rahul+Sharma&background=1152d4&color=fff')"></div>
                        </div>
                        <div>
                            <p class="text-primary text-xs font-bold uppercase tracking-widest">Student Profile</p>
                            <h2 class="text-xl font-bold text-[#0d121b] dark:text-white"><?php echo $student_name; ?></h2>
                            <p class="text-sm text-[#4c669a]">Class <?php echo $class_name; ?></p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-slate-700">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-[#4c669a] uppercase font-bold tracking-tighter">Current Session</span>
                        <span class="text-sm font-semibold">2023 - 2024</span>
                    </div>
                    <button class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-full text-xs font-bold ios-shadow active:scale-95 transition-transform">
                        <span class="material-symbols-outlined text-sm">download</span>
                        Report Card
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 flex items-center justify-between">
        <h3 class="text-xl font-bold text-[#0d121b] dark:text-white flex items-center gap-2">
            Exam Results
        </h3>
        <button class="text-primary text-sm font-semibold">Filter</button>
    </div>

    <?php ShowExamSummary($con, $class_id, $stu_id, $session_id); ?>

</main>

 

</body>
</html>