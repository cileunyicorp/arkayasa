<?php
// Menentukan URL aktif untuk highlight menu sidebar
$currentUrl = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$isDashboard = ($currentUrl === 'admin/dashboard' || $currentUrl === 'admin');
$isMobil = (strpos($currentUrl, 'admin/mobil') === 0);
$isBooking = (strpos($currentUrl, 'admin/booking') === 0);
$isDriver = (strpos($currentUrl, 'admin/driver') === 0);
$isPelanggan = (strpos($currentUrl, 'admin/pelanggan') === 0);
$isPerawatan = (strpos($currentUrl, 'admin/perawatan') === 0);
$isKeuangan = (strpos($currentUrl, 'admin/keuangan') === 0);
?>
<!-- Sidebar -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-950 text-white border-r border-slate-900 transition-transform duration-300 ease-in-out md:relative md:translate-x-0 flex flex-col justify-between">
    <div>
        <!-- Logo Branding -->
        <div class="flex items-center justify-between px-6 h-20 border-b border-slate-900">
            <div class="flex-1 flex flex-col items-center justify-center">
                <img src="<?= base_url('assets/images/logo.png') ?>" alt="Arkayasa Rent Car" class="h-10 w-auto max-w-[150px] object-contain">
                <div class="flex items-center gap-2 mt-1"> <span class="h-px w-5 bg-primary-600"></span>
                    <span class="text-[9px] font-semibold uppercase tracking-[0.18em] text-slate-500"> Trans Panel </span>
                    <span class="h-px w-5 bg-primary-600"></span>
                </div>
            </div>
            <!-- Tutup Sidebar (Mobile) -->
            <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="px-4 pt-5">
            <!-- Kembali ke Menu Utama -->
            <a href="<?= base_url() ?>"
                class="flex items-center px-4 py-3 rounded-xl font-medium transition duration-200 text-slate-400 hover:bg-slate-900/50 hover:text-white group">
                <i class="fa-solid fa-house w-5 text-lg group-hover:text-primary-600 transition"></i>
                <span class="mx-3 text-sm">Menu Utama</span>
            </a>
        </div>

        <!-- Link Navigasi -->
        <nav class="mt-6 px-4 space-y-1.5">
            <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center px-4 py-3 rounded-xl font-medium transition duration-200 <?= $isDashboard ? 'bg-primary-600 text-white' : 'text-slate-400 hover:bg-slate-900/50 hover:text-white group' ?>">
                <i class="fa-solid fa-chart-pie w-5 text-lg <?= $isDashboard ? '' : 'group-hover:text-primary-600 transition' ?>"></i>
                <span class="mx-3 text-sm">Dashboard</span>
            </a>

            <a href="<?= base_url('admin/mobil') ?>" class="flex items-center px-4 py-3 rounded-xl font-medium transition duration-200 <?= $isMobil ? 'bg-primary-600 text-white' : 'text-slate-400 hover:bg-slate-900/50 hover:text-white group' ?>">
                <i class="fa-solid fa-car w-5 text-lg <?= $isMobil ? '' : 'group-hover:text-primary-600 transition' ?>"></i>
                <span class="mx-3 text-sm">Data Mobil</span>
            </a>

            <a href="<?= base_url('admin/pelanggan') ?>" class="flex items-center px-4 py-3 <?= $isPelanggan ? 'bg-primary-600 text-white' : 'text-slate-400 hover:bg-slate-900/50 hover:text-white' ?> rounded-xl font-medium transition duration-200 group">
                <i class="fa-solid fa-users w-5 text-lg <?= $isPelanggan ? '' : 'group-hover:text-primary-600 transition' ?>"></i>
                <span class="mx-3 text-sm">Pelanggan</span>
            </a>

            
            <a href="<?= base_url('admin/driver') ?>" class="flex items-center px-4 py-3 <?= $isDriver ? 'bg-primary-600 text-white' : 'text-slate-400 hover:bg-slate-900/50 hover:text-white' ?> rounded-xl font-medium transition duration-200 group">
                <i class="fa-solid fa-user-tie w-5 text-lg <?= $isDriver ? '' : 'group-hover:text-primary-600 transition' ?>"></i>
                <span class="mx-3 text-sm">Sopir</span>
            </a>
            
            <a href="<?= base_url('admin/booking') ?>" class="flex items-center px-4 py-3 rounded-xl font-medium transition duration-200 <?= $isBooking ? 'bg-primary-600 text-white' : 'text-slate-400 hover:bg-slate-900/50 hover:text-white group' ?>">
                <i class="fa-solid fa-calendar-days w-5 text-lg <?= $isBooking ? '' : 'group-hover:text-primary-600 transition' ?>"></i>
                <span class="mx-3 text-sm">Data Booking</span>
            </a>
            
            <a href="<?= base_url('admin/perawatan') ?>" class="flex items-center px-4 py-3 rounded-xl font-medium transition duration-200 <?= $isPerawatan ? 'bg-primary-600 text-white' : 'text-slate-400 hover:bg-slate-900/50 hover:text-white group' ?>">
                <i class="fa-solid fa-screwdriver-wrench w-5 text-lg <?= $isPerawatan ? '' : 'group-hover:text-primary-600 transition' ?>"></i>
                <span class="mx-3 text-sm">Perawatan Mobil</span>
            </a>

            <a href="<?= base_url('admin/keuangan') ?>" class="flex items-center px-4 py-3 rounded-xl font-medium transition duration-200 <?= $isKeuangan ? 'bg-primary-600 text-white' : 'text-slate-400 hover:bg-slate-900/50 hover:text-white group' ?>">
                <i class="fa-solid fa-wallet w-5 text-lg <?= $isKeuangan ? '' : 'group-hover:text-primary-600 transition' ?>"></i>
                <span class="mx-3 text-sm">Data Keuangan</span>
            </a>

        </nav>
    </div>

    <!-- Info User Terbawah -->
    <div class="p-4 border-t border-slate-900 bg-slate-950/50">
        <div class="flex items-center gap-3">
            <img class="h-9 w-9 rounded-xl object-cover border border-slate-800" src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user_name']) ?>&background=B30427&color=fff" alt="Avatar">
            <div class="truncate">
                <h4 class="text-xs font-bold text-white"><?= escape($_SESSION['user_name']) ?></h4>
                <span class="text-[10px] text-slate-500 uppercase font-semibold">Administrator</span>
            </div>
        </div>
    </div>
</aside>
<!-- Overlay Latar Belakang Mobile -->
<div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-sm md:hidden"></div>