<?php
// ... (Top PHP Logic same rahega) ...
include "../middleware/auth_guard.php";
include "../config/db.php";
$software_direct = "../" . SCHOOL_ROOT . "/";
require_once $software_direct . "newadmin/functions.php";

$stu_id = $_SESSION['student_id'] ?? 0;
$session_id = $_SESSION['session_id'] ?? 0;
$class_id = $_SESSION['class_id'] ?? 0;

function getMonthsTillNow() {
    $allMonths = ['April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'January', 'February', 'March'];
    $currentMonthNum = date('n');
    $monthMap = [1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December'];
    $currentMonthName = $monthMap[$currentMonthNum];
    $finalMonths = [];
    foreach ($allMonths as $month) {
        $finalMonths[] = $month;
        if ($month == $currentMonthName) break;
    }
    return $finalMonths;
}

$months = getMonthsTillNow();
$studentFees = getStudentFeeData($con, $stu_id, $class_id, $session_id, $months, true);

$oldBalanceData = $studentFees['Old_Balance'] ?? null;
unset($studentFees['Old_Balance']);
$studentFees = array_reverse($studentFees, true);

$oldBalVal = (float)($oldBalanceData['amount'] ?? 0);
$grandTotalWithDiscount = $oldBalVal;
foreach ($studentFees as $m => $d) {
    if ($d['status'] != 'Paid' && isset($d['items'])) {
        foreach ($d['items'] as $it) {
            $grandTotalWithDiscount += ((float)$it['amount'] - (float)($it['pre_applied_discount'] ?? 0));
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/><meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Fees Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1" rel="stylesheet" />
    <script>tailwind.config={darkMode:"class",theme:{extend:{colors:{primary:"#F59E0B",secondary:"#1E3A8A","card-dark":"#1F2937"}}}};</script>
    <style>body{font-family:'Inter',sans-serif; -webkit-tap-highlight-color: transparent; } .hide-scrollbar::-webkit-scrollbar{display:none;}</style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 min-h-screen pb-32">

<header class="px-6 pt-12 pb-6 bg-white dark:bg-card-dark rounded-b-[2.5rem] shadow-sm">
    <div class="flex items-center justify-between mb-6">
        <button onclick="window.location='dashboard.php'" class="p-2 bg-slate-100 dark:bg-slate-800 rounded-xl text-blue-900 dark:text-blue-400">
            <span class="material-symbols-outlined block">arrow_back_ios_new</span>
        </button>
        <h1 class="text-lg font-bold text-blue-900 dark:text-blue-400">Fees Portal</h1>
        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold">KL</div>
    </div>
    
    <div class="bg-blue-900 rounded-[2rem] p-6 text-white relative overflow-hidden shadow-xl shadow-blue-900/20">
        <div class="absolute -top-4 -right-4 opacity-10"><span class="material-symbols-outlined text-9xl">payments</span></div>
        <p class="text-[10px] font-bold uppercase tracking-widest opacity-70 mb-1">Total Outstanding</p>
        <h2 class="text-4xl font-black mb-6">₹<?= number_format($grandTotalWithDiscount) ?></h2>
        <button class="w-full bg-primary text-blue-900 font-black py-4 rounded-2xl shadow-lg active:scale-95 transition-transform flex items-center justify-center gap-2">
            <span class="material-symbols-outlined">bolt</span> PAY SECURELY
        </button>
    </div>
</header>

<main class="px-6 mt-8">
    <div class="flex p-1.5 bg-slate-200/50 dark:bg-slate-800/50 rounded-2xl mb-8">
        <button id="tab-pending-btn" onclick="showTab('pending')" class="flex-1 py-3 text-xs font-bold rounded-xl bg-white dark:bg-card-dark shadow-sm text-blue-900 dark:text-white transition-all">DUE FEES</button>
        <button id="tab-history-btn" onclick="showTab('history')" class="flex-1 py-3 text-xs font-bold text-slate-500 transition-all">PAID HISTORY</button>
    </div>

    <div id="section-pending" class="space-y-4">
        <?php foreach ($studentFees as $month => $m_data): 
            if ($m_data['status'] == 'Paid') continue; 
            $mNet = 0; 
        ?>
            <div class="bg-white dark:bg-card-dark p-6 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-800">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 font-bold"><?= substr($month, 0, 1) ?></div>
                        <h4 class="font-bold text-slate-800 dark:text-white"><?= $month ?></h4>
                    </div>
                    <span class="bg-rose-50 text-rose-600 text-[10px] font-black px-3 py-1 rounded-lg uppercase tracking-wider">Unpaid</span>
                </div>
                <div class="space-y-2 mb-4">
                    <?php foreach($m_data['items'] as $it): 
                        $f = (float)$it['amount'] - (float)($it['pre_applied_discount'] ?? 0); $mNet += $f; ?>
                        <div class="flex justify-between text-xs text-slate-500">
                            <span><?= $it['fee_name'] ?></span>
                            <span class="font-bold text-slate-800 dark:text-slate-200">₹<?= number_format($f) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="pt-4 border-t border-dashed border-slate-100 flex justify-between items-center">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Total Due</span>
                    <span class="text-xl font-black text-blue-900 dark:text-blue-400">₹<?= number_format($mNet) ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="section-history" class="hidden space-y-4">
        <?php 
        $rQ = mysqli_query($con, "SELECT * FROM receipt_master WHERE stu_id=$stu_id ORDER BY id DESC");
        while($r = mysqli_fetch_assoc($rQ)): ?>
            <div onclick="openModal(<?= $r['id'] ?>)" class="bg-white dark:bg-card-dark p-5 rounded-[2rem] shadow-sm border border-slate-100 dark:border-slate-800 flex items-center justify-between active:scale-[0.97] transition-all cursor-pointer">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 shadow-inner">
                        <span class="material-symbols-outlined text-3xl font-bold">verified</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-0.5">Payment Successful</p>
                        <h4 class="font-bold text-sm text-slate-800 dark:text-white leading-tight mb-1"><?= $r['months'] ?></h4>
                        <p class="text-[10px] text-slate-400 font-medium"><?= date('d M, Y', strtotime($r['receipt_date'])) ?> • #<?= $r['id'] ?></p>
                    </div>
                </div>
                <div class="text-right flex flex-col items-end gap-2">
                    <p class="text-lg font-black text-blue-900 dark:text-blue-400 leading-none">₹<?= number_format($r['received']) ?></p>
                    <span class="material-symbols-outlined text-slate-300 text-lg">chevron_right</span>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</main>

<div id="receiptModal" class="fixed inset-0 z-[1100] hidden flex items-center justify-center px-6">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-md" onclick="closeModal()"></div>
    <div class="bg-white dark:bg-card-dark w-full max-w-sm rounded-[3rem] p-8 relative z-10 shadow-2xl scale-95 transition-all duration-300">
        <div class="flex justify-between items-center mb-8">
            <h3 class="text-xl font-black text-blue-900 dark:text-blue-400">Receipt Details</h3>
            <button onclick="closeModal()" class="w-10 h-10 flex items-center justify-center bg-slate-100 rounded-full text-slate-400"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div id="modal-body" class="max-h-[60vh] overflow-y-auto hide-scrollbar"></div>
    </div>
</div>

<script>
function showTab(t){
    const pS=document.getElementById('section-pending'), hS=document.getElementById('section-history');
    const pB=document.getElementById('tab-pending-btn'), hB=document.getElementById('tab-history-btn');
    if(t==='pending'){ pS.classList.remove('hidden'); hS.classList.add('hidden'); pB.className="flex-1 py-3 text-xs font-bold rounded-xl bg-white dark:bg-card-dark shadow-sm text-blue-900 dark:text-white"; hB.className="flex-1 py-3 text-xs font-bold text-slate-500"; }
    else { hS.classList.remove('hidden'); pS.classList.add('hidden'); hB.className="flex-1 py-3 text-xs font-bold rounded-xl bg-white dark:bg-card-dark shadow-sm text-blue-900 dark:text-white"; pB.className="flex-1 py-3 text-xs font-bold text-slate-500"; }
}
function openModal(id){
    const m = document.getElementById('receiptModal');
    m.classList.remove('hidden'); setTimeout(() => m.children[1].classList.remove('scale-95'), 10);
    document.getElementById('modal-body').innerHTML = '<div class="flex flex-col items-center py-10"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-900 mb-2"></div><p class="text-[10px] font-bold text-slate-400 uppercase">Fetching Ledger...</p></div>';
    fetch('get_receipt_details.php', { method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:'receipt_id='+id })
    .then(r=>r.text()).then(d=> document.getElementById('modal-body').innerHTML=d);
}
function closeModal(){ document.getElementById('receiptModal').classList.add('hidden'); document.getElementById('receiptModal').children[1].classList.add('scale-95'); }
</script>
</body>
</html>