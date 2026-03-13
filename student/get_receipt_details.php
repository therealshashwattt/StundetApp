<?php
error_reporting(E_ALL & ~E_DEPRECATED); 
include "../config/db.php";

if(isset($_POST['receipt_id'])) {
    $r_id = mysqli_real_escape_string($con, $_POST['receipt_id']);
    
    // 1. Receipt Master Data
    $master_sql = "SELECT * FROM receipt_master WHERE id = '$r_id' LIMIT 1";
    $master_res = mysqli_query($con, $master_sql);
    $master = mysqli_fetch_assoc($master_res);

    // 2. Items Breakdown
    $sql = "SELECT * FROM student_fee WHERE receipt_id = '$r_id'";
    $res = mysqli_query($con, $sql);
    
    if($master) {
        echo '<div class="space-y-5 px-1">';
        
        // Header: Paid Months Info
        echo '<div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-3xl border border-blue-100 dark:border-blue-800">
                <p class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-1">Fee Paid For</p>
                <h4 class="text-sm font-black text-blue-900 dark:text-blue-200">'.$master['months'].'</h4>
              </div>';

        // Items List
        echo '<div class="space-y-3">';
        while($row = mysqli_fetch_assoc($res)) {
            $amt = (float)$row['amount'];
            $disc = (float)($row['discount_amount'] ?? 0);
            echo '<div class="flex justify-between items-center bg-slate-50 dark:bg-slate-800/50 p-3 rounded-2xl">
                    <div>
                        <p class="text-[12px] font-bold text-slate-800 dark:text-slate-200">'.$row['fee_type'].'</p>
                        <p class="text-[10px] text-slate-400 font-medium italic">Month: '.$row['month'].'</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-black text-slate-900 dark:text-white">₹'.number_format($amt).'</p>
                        '.($disc > 0 ? '<p class="text-[9px] text-emerald-500 font-bold">-₹'.$disc.' Discount</p>' : '').'
                    </div>
                  </div>';
        }
        echo '</div>';

        // Payment Summary Logic
        $total = (float)$master['total'];
        $received = (float)$master['received'];
        $balance = isset($master['remain']) ? (float)$master['remain'] : ($total - $received);

        echo '<div class="mt-6 p-5 bg-secondary dark:bg-blue-900 rounded-[2rem] text-white shadow-xl shadow-blue-900/20">
                <div class="flex justify-between text-[10px] opacity-70 mb-1">
                    <span>Payable Total</span>
                    <span>₹'.number_format($total).'</span>
                </div>
                <div class="flex justify-between text-xs font-bold text-amber-400 mb-3">
                    <span>Amount Received</span>
                    <span>₹'.number_format($received).'</span>
                </div>
                <div class="pt-3 border-t border-white/10 flex justify-between items-center">';
                
        if($balance > 0.5) {
            echo '<span class="text-xs font-bold opacity-80">Pending Balance</span>
                  <span class="text-xl font-black text-rose-400">₹'.number_format($balance).'</span>';
        } else {
            echo '<span class="text-xs font-bold opacity-80">Status</span>
                  <span class="bg-emerald-500/20 text-emerald-400 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">verified</span> Fully Paid
                  </span>';
        }
        echo '</div></div></div>';
    }
}
?>