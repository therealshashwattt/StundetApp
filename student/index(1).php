<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>School Dashboard Redesign V2</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet"/>
<script>
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              primary: "#F59E0B", // Modern Amber/Yellow from screenshot
              "background-light": "#F3F4F6", // Soft light grey
              "background-dark": "#111827",
              "card-light": "#FFFFFF",
              "card-dark": "#1F2937",
            },
            fontFamily: {
              display: ["Inter", "sans-serif"],
            },
            borderRadius: {
              DEFAULT: "1rem",
            },
          },
        },
      };
    </script>
<style>
        body {
            font-family: 'Inter', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .safe-bottom {
            padding-bottom: env(safe-area-inset-bottom);
        }
    </style>
<style>
    body {
      min-height: max(884px, 100dvh);
    }
  </style>
  </head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 min-h-screen font-display">
<div class="px-6 pt-12 pb-6 bg-white dark:bg-card-dark rounded-b-[2.5rem] shadow-sm">
<div class="flex items-center justify-between mb-6">
<div class="flex items-center gap-3">
<div class="p-2 bg-blue-900 rounded-xl">
<span class="material-icons-round text-white text-xl">school</span>
</div>
<div>
<h1 class="text-sm font-bold text-blue-900 dark:text-blue-400 leading-tight">K.L.J.M. PUBLIC SCHOOL</h1>
<p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-wider">Prayagraj, Uttar Pradesh</p>
</div>
</div>
<button class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
<span class="material-icons-round text-slate-600 dark:text-slate-300">notifications</span>
</button>
</div>
<div class="flex items-center gap-4">
<div class="relative">
<div class="w-20 h-20 rounded-2xl overflow-hidden border-2 border-primary/20 p-0.5">
<img alt="Student Profile" class="w-full h-full object-cover rounded-xl" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA3JwD0GzMfVEdN8QxsAmZMbKl6BckVRufXoyPjCGzTvOVVtxAN5NFGbuS02TTlt8N1OxBhV6rRAxcwkrM6mrp2VDHFLwnWZLz9YfUIYWzWDyXVZE3suBVfvDjHN4oymMX0UorM4nK1kjotoDfB9pQPbXrQfSdjfrmSrQ6nDXlkDTeEy4uH5QyVKmm7q9Qqv_zzvFY8sA-rkEM5EpXKzVBZWH_f1H5KVVs9BqQcJZGQbNZoT6KnawP_Z689mq209gXLwp1HsOe_TTg"/>
</div>
<div class="absolute -bottom-1 -right-1 bg-green-500 w-4 h-4 rounded-full border-2 border-white dark:border-card-dark"></div>
</div>
<div class="flex-1">
<p class="text-slate-500 dark:text-slate-400 text-sm">Welcome back,</p>
<h2 class="text-2xl font-bold text-slate-800 dark:text-white">NISHI <span class="text-primary">👋</span></h2>
<div class="flex items-center gap-2 mt-1">
<span class="px-2 py-0.5 bg-primary/10 text-primary text-[10px] font-bold rounded-md uppercase">Class 5th</span>
<span class="text-xs text-slate-400">ID: 2025-26</span>
</div>
</div>
</div>
</div>
<main class="px-6 py-8 space-y-6 pb-32">
<div class="grid grid-cols-2 gap-4">
<div class="bg-card-light dark:bg-card-dark p-4 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
<div class="flex justify-between items-start mb-4">
<div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-xl text-blue-600 dark:text-blue-400">
<span class="material-icons-round">how_to_reg</span>
</div>
<span class="text-[10px] font-bold text-green-500 bg-green-50 dark:bg-green-900/30 px-2 py-1 rounded-full">+2%</span>
</div>
<h3 class="text-slate-500 dark:text-slate-400 text-xs font-medium">Attendance</h3>
<p class="text-2xl font-bold text-slate-800 dark:text-white mt-1">94%</p>
<div class="w-full bg-slate-100 dark:bg-slate-700 h-1.5 rounded-full mt-3 overflow-hidden">
<div class="bg-blue-500 h-full rounded-full" style="width: 94%"></div>
</div>
</div>
<div class="bg-card-light dark:bg-card-dark p-4 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800">
<div class="flex justify-between items-start mb-4">
<div class="p-2 bg-orange-50 dark:bg-orange-900/30 rounded-xl text-orange-600 dark:text-orange-400">
<span class="material-icons-round">edit_note</span>
</div>
<span class="text-[10px] font-bold text-white bg-red-500 px-2 py-1 rounded-full">2 Pending</span>
</div>
<h3 class="text-slate-500 dark:text-slate-400 text-xs font-medium">Homework</h3>
<p class="text-2xl font-bold text-slate-800 dark:text-white mt-1">08</p>
<p class="text-[10px] text-slate-400 mt-2">6 completed this week</p>
</div>
</div>
<div class="space-y-4">
<h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 px-1 uppercase tracking-wider">DashBoard</h3>
<div class="grid grid-cols-4 gap-4">
<button class="flex flex-col items-center gap-2 group">
<div class="w-14 h-14 bg-card-light dark:bg-card-dark rounded-2xl flex items-center justify-center shadow-sm border border-slate-100 dark:border-slate-800 group-active:scale-95 transition-transform">
<span class="material-icons-round text-indigo-500">mail</span>
</div>
<span class="text-[11px] font-medium text-slate-600 dark:text-slate-400">Inbox</span>
</button>
<button class="flex flex-col items-center gap-2 group">
<div class="w-14 h-14 bg-card-light dark:bg-card-dark rounded-2xl flex items-center justify-center shadow-sm border border-slate-100 dark:border-slate-800 group-active:scale-95 transition-transform">
<span class="material-icons-round text-emerald-500">calendar_month</span>
</div>
<span class="text-[11px] font-medium text-slate-600 dark:text-slate-400 text-center leading-tight">Time Table</span>
</button>
<button class="flex flex-col items-center gap-2 group">
<div class="w-14 h-14 bg-card-light dark:bg-card-dark rounded-2xl flex items-center justify-center shadow-sm border border-slate-100 dark:border-slate-800 group-active:scale-95 transition-transform">
<span class="material-icons-round text-amber-500">payments</span>
</div>
<span class="text-[11px] font-medium text-slate-600 dark:text-slate-400">Fees</span>
</button>
<button class="flex flex-col items-center gap-2 group">
<div class="w-14 h-14 bg-card-light dark:bg-card-dark rounded-2xl flex items-center justify-center shadow-sm border border-slate-100 dark:border-slate-800 group-active:scale-95 transition-transform">
<span class="material-icons-round text-rose-500">assignment_turned_in</span>
</div>
<span class="text-[11px] font-medium text-slate-600 dark:text-slate-400 text-center leading-tight">Report</span>
</button>
<button class="flex flex-col items-center gap-2 group">
<div class="w-14 h-14 bg-card-light dark:bg-card-dark rounded-2xl flex items-center justify-center shadow-sm border border-slate-100 dark:border-slate-800 group-active:scale-95 transition-transform">
<span class="material-icons-round text-slate-700 dark:text-slate-300">qr_code_2</span>
</div>
<span class="text-[11px] font-medium text-slate-600 dark:text-slate-400">QR Code</span>
</button>
<button class="flex flex-col items-center gap-2 group">
<div class="w-14 h-14 bg-card-light dark:bg-card-dark rounded-2xl flex items-center justify-center shadow-sm border border-slate-100 dark:border-slate-800 group-active:scale-95 transition-transform">
<span class="material-icons-round text-purple-500">local_library</span>
</div>
<span class="text-[11px] font-medium text-slate-600 dark:text-slate-400 text-center leading-tight">Library</span>
</button>
<button class="flex flex-col items-center gap-2 group">
<div class="w-14 h-14 bg-card-light dark:bg-card-dark rounded-2xl flex items-center justify-center shadow-sm border border-slate-100 dark:border-slate-800 group-active:scale-95 transition-transform">
<span class="material-icons-round text-cyan-500">history_edu</span>
</div>
<span class="text-[11px] font-medium text-slate-600 dark:text-slate-400">Exams</span>
</button>
<button class="flex flex-col items-center gap-2 group">
<div class="w-14 h-14 bg-card-light dark:bg-card-dark rounded-2xl flex items-center justify-center shadow-sm border border-slate-100 dark:border-slate-800 group-active:scale-95 transition-transform">
<span class="material-icons-round text-slate-400">add_circle_outline</span>
</div>
<span class="text-[11px] font-medium text-slate-400">More</span>
</button>
</div>
</div>
<div class="space-y-4">
<div class="flex items-center justify-between px-1">
<h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Announcements</h3>
<button class="text-xs font-bold text-primary">View All</button>
</div>
<div class="bg-white dark:bg-card-dark p-4 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 flex gap-4">
<div class="flex-shrink-0 w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-2xl flex items-center justify-center text-red-600 dark:text-red-400">
<span class="material-icons-round">campaign</span>
</div>
<div>
<h4 class="font-bold text-sm dark:text-white">Summer Vacation 2025</h4>
<p class="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-2 leading-relaxed">School will remain closed from May 20th to June 30th for summer holidays. Enjoy your break!</p>
</div>
</div>
</div>
</main>
<nav class="fixed bottom-6 left-6 right-6 safe-bottom">
<div class="bg-primary shadow-2xl shadow-primary/30 rounded-[2rem] px-6 py-4 flex justify-between items-center text-white">
<button class="flex flex-col items-center gap-1 opacity-100">
<span class="material-icons-round text-2xl">account_circle</span>
<span class="text-[10px] font-bold uppercase tracking-tight">Profile</span>
</button>
<button class="flex flex-col items-center gap-1 opacity-70 hover:opacity-100 transition-opacity">
<span class="material-icons-round text-2xl">swap_horiz</span>
<span class="text-[10px] font-bold uppercase tracking-tight">Switch</span>
</button>
<button class="flex flex-col items-center gap-1 opacity-70 hover:opacity-100 transition-opacity">
<span class="material-icons-round text-2xl">grid_view</span>
<span class="text-[10px] font-bold uppercase tracking-tight">Social</span>
</button>
<button class="flex flex-col items-center gap-1 opacity-70 hover:opacity-100 transition-opacity">
<span class="material-icons-round text-2xl">power_settings_new</span>
<span class="text-[10px] font-bold uppercase tracking-tight">Logout</span>
</button>
</div>
</nav>

</body></html>