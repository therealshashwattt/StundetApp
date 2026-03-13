<?php
include "../config/dbs.php";
$dbs = getAllSlaveDatabases();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>School Portal - Login</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#1152d4",
                        "accent-gold": "#FFD700",
                        "off-white": "#F8FAFC",
                        "charcoal": "#1E293B",
                    },
                    fontFamily: { "display": ["Lexend"] },
                    borderRadius: { "DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px" },
                },
            },
        }
    </script>
    <style type="text/tailwindcss">
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { font-family: 'Lexend', sans-serif; min-height: max(884px, 100dvh); }
    </style>
</head>
<body class="bg-off-white min-h-screen flex flex-col text-charcoal">

    <div class="flex items-center bg-transparent p-6 justify-between">
        <button onclick="history.back()" class="flex size-10 items-center justify-center rounded-full bg-white border border-slate-100 shadow-sm">
            <span class="material-symbols-outlined text-slate-600 text-[20px]">arrow_back_ios_new</span>
        </button>
        <div class="flex items-center gap-2.5">
            <div class="h-1.5 w-6 rounded-full bg-primary"></div>
            <div class="size-1.5 rounded-full bg-slate-200"></div>
        </div>
    </div>

    <div class="px-8 pt-6 flex flex-col items-center text-center">
        <div class="mb-8 size-28 bg-white rounded-3xl flex items-center justify-center relative shadow-sm border border-slate-50">
            <span class="material-symbols-outlined text-primary text-5xl">school</span>
            <div class="absolute -top-2 -right-2 size-9 bg-accent-gold rounded-full border-4 border-off-white flex items-center justify-center shadow-sm">
                <span class="material-symbols-outlined text-charcoal text-base font-bold">star</span>
            </div>
        </div>
        <h1 class="text-3xl font-bold tracking-tight mb-3 text-charcoal">Welcome back!</h1>
        <p class="text-slate-500 text-base leading-relaxed max-w-[280px]">
            Please select your institution and enter your mobile number.
        </p>
    </div>

    <form method="post" action="check_student.php" class="flex-1 px-8 pt-12 pb-8 flex flex-col">
        <div class="space-y-8">
            
            <div class="flex flex-col gap-2.5">
                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider ml-1">Select School</label>
                <div class="relative group">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">search</span>
                    <select name="school_db" required class="form-select w-full pl-12 pr-10 py-4.5 rounded-2xl border-slate-200 bg-white/80 focus:border-primary focus:ring-4 focus:ring-primary/5 appearance-none text-base text-charcoal transition-all shadow-sm">
                        <option value="" disabled selected>Search for your school...</option>
                        <?php foreach($dbs as $d){ ?>
                            <option value="<?= $d['db'] ?>">
                                <?= strtoupper(str_replace("u355567025_", "", $d['db'])) ?>
                            </option>
                        <?php } ?>
                    </select>
                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none">unfold_more</span>
                </div>
            </div>

            <div class="flex flex-col gap-2.5">
                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider ml-1">Mobile Number</label>
                <div class="flex gap-3">
                    <div class="flex items-center gap-1.5 px-4 py-4.5 rounded-2xl border border-slate-200 bg-white/80 min-w-[85px] shadow-sm">
                        <span class="text-base font-medium text-charcoal">+91</span>
                        <span class="material-symbols-outlined text-slate-300 text-sm">expand_more</span>
                    </div>
                    <div class="relative flex-1 group">
                        <input name="mobile" required type="tel" class="form-input w-full px-5 py-4.5 rounded-2xl border-slate-200 bg-white/80 focus:border-primary focus:ring-4 focus:ring-primary/5 text-base text-charcoal transition-all shadow-sm tracking-[0.05em] placeholder:tracking-normal placeholder:text-slate-300" placeholder="000 000 0000"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-1"></div>

        <div class="space-y-8 pt-8">
            <button type="submit" class="w-full bg-primary hover:brightness-110 active:scale-[0.98] text-white font-semibold py-5 rounded-2xl transition-all flex items-center justify-center gap-3 shadow-lg shadow-primary/20">
                <span class="text-lg">Send OTP</span>
                <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
            </button>
            
            <div class="flex justify-center items-center gap-8">
                <button type="button" class="text-sm font-medium text-slate-400 hover:text-primary transition-colors flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">help_outline</span> Need help?
                </button>
                <div class="w-1 h-1 rounded-full bg-slate-300"></div>
                <button type="button" class="text-sm font-medium text-slate-400 hover:text-primary transition-colors flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">shield</span> Privacy
                </button>
            </div>
        </div>
    </form>
    <div class="flex justify-center pb-3">
        <div class="w-32 h-1.5 bg-slate-200 rounded-full"></div>
    </div>

</body>
</html>