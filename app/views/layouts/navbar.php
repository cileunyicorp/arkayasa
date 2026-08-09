<?php
// Deteksi halaman aktif berdasarkan URL
$current_uri = $_SERVER['REQUEST_URI'] ?? '';
$url_param   = $_GET['url'] ?? '';

$isHome    = ($current_uri === '/' || $current_uri === base_url() || $url_param === '' || $url_param === 'home');
$isMobil   = (strpos($current_uri, '/mobil') !== false || strpos($current_uri, '/cars') !== false || str_starts_with($url_param, 'cars') || str_starts_with($url_param, 'mobil'));
$isTentang = (strpos($current_uri, '/tentang-kami') !== false || str_starts_with($url_param, 'tentang-kami'));
$isArtikel = (strpos($current_uri, '/artikel') !== false || strpos($current_uri, '/articles') !== false || str_starts_with($url_param, 'articles') || str_starts_with($url_param, 'artikel'));
$isKontak  = (strpos($current_uri, '/kontak') !== false || strpos($current_uri, '/contact') !== false || str_starts_with($url_param, 'contact') || str_starts_with($url_param, 'kontak'));
?>

<!-- =========================================
     NAVBAR UTAMA (TAILWIND CSS V4)
========================================== -->
<header id="main-navbar" class="fixed top-0 left-0 right-0 z-50 bg-white/95 dark:bg-slate-950/95 backdrop-blur-md border-b border-slate-100 dark:border-slate-800 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-20 flex items-center justify-between">

            <!-- LOGO -->
            <a href="<?= base_url() ?>" class="flex items-center gap-3 group shrink-0">
                <img src="<?= base_url('assets/images/logo.png') ?>" alt="Arkayasa Logo" class="h-10 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
            </a>

            <!-- DESKTOP NAVIGATION -->
            <nav class="hidden lg:flex items-center gap-1">

                <!-- Beranda -->
                <a href="<?= base_url() ?>" class="group relative px-4 py-2.5 text-sm font-semibold transition-all duration-200 <?= $isHome ? 'text-primary-600 dark:text-primary-400' : 'text-slate-600 dark:text-slate-300 hover:text-primary-600 dark:hover:text-primary-400' ?>">
                    <span>Beranda</span>
                    <?php if ($isHome) : ?>
                        <span class="absolute left-4 right-4 -bottom-1 h-0.5 rounded-full bg-primary-600 dark:bg-primary-400"></span>
                    <?php else : ?>
                        <span class="absolute left-4 right-4 -bottom-1 h-0.5 scale-x-0 rounded-full bg-primary-600 dark:bg-primary-400 transition-transform duration-200 group-hover:scale-x-100"></span>
                    <?php endif; ?>
                </a>

                <!-- Armada -->
                <a href="<?= base_url('cars') ?>" class="group relative px-4 py-2.5 text-sm font-semibold transition-all duration-200 <?= $isMobil ? 'text-primary-600 dark:text-primary-400' : 'text-slate-600 dark:text-slate-300 hover:text-primary-600 dark:hover:text-primary-400' ?>">
                    <span>Armada</span>
                    <?php if ($isMobil) : ?>
                        <span class="absolute left-4 right-4 -bottom-1 h-0.5 rounded-full bg-primary-600 dark:bg-primary-400"></span>
                    <?php else : ?>
                        <span class="absolute left-4 right-4 -bottom-1 h-0.5 scale-x-0 rounded-full bg-primary-600 dark:bg-primary-400 transition-transform duration-200 group-hover:scale-x-100"></span>
                    <?php endif; ?>
                </a>

                <!-- Tentang Kami -->
                <a href="<?= base_url('tentang-kami') ?>" class="group relative px-4 py-2.5 text-sm font-semibold transition-all duration-200 <?= $isTentang ? 'text-primary-600 dark:text-primary-400' : 'text-slate-600 dark:text-slate-300 hover:text-primary-600 dark:hover:text-primary-400' ?>">
                    <span>Tentang Kami</span>
                    <?php if ($isTentang) : ?>
                        <span class="absolute left-4 right-4 -bottom-1 h-0.5 rounded-full bg-primary-600 dark:bg-primary-400"></span>
                    <?php else : ?>
                        <span class="absolute left-4 right-4 -bottom-1 h-0.5 scale-x-0 rounded-full bg-primary-600 dark:bg-primary-400 transition-transform duration-200 group-hover:scale-x-100"></span>
                    <?php endif; ?>
                </a>

                <!-- Artikel -->
                <a href="<?= base_url('articles') ?>" class="group relative px-4 py-2.5 text-sm font-semibold transition-all duration-200 <?= $isArtikel ? 'text-primary-600 dark:text-primary-400' : 'text-slate-600 dark:text-slate-300 hover:text-primary-600 dark:hover:text-primary-400' ?>">
                    <span>Artikel</span>
                    <?php if ($isArtikel) : ?>
                        <span class="absolute left-4 right-4 -bottom-1 h-0.5 rounded-full bg-primary-600 dark:bg-primary-400"></span>
                    <?php else : ?>
                        <span class="absolute left-4 right-4 -bottom-1 h-0.5 scale-x-0 rounded-full bg-primary-600 dark:bg-primary-400 transition-transform duration-200 group-hover:scale-x-100"></span>
                    <?php endif; ?>
                </a>

                <!-- Kontak -->
                <a href="<?= base_url('contact') ?>" class="group relative px-4 py-2.5 text-sm font-semibold transition-all duration-200 <?= $isKontak ? 'text-primary-600 dark:text-primary-400' : 'text-slate-600 dark:text-slate-300 hover:text-primary-600 dark:hover:text-primary-400' ?>">
                    <span>Kontak</span>
                    <?php if ($isKontak) : ?>
                        <span class="absolute left-4 right-4 -bottom-1 h-0.5 rounded-full bg-primary-600 dark:bg-primary-400"></span>
                    <?php else : ?>
                        <span class="absolute left-4 right-4 -bottom-1 h-0.5 scale-x-0 rounded-full bg-primary-600 dark:bg-primary-400 transition-transform duration-200 group-hover:scale-x-100"></span>
                    <?php endif; ?>
                </a>

            </nav>

            <!-- RIGHT SIDE DESKTOP -->
            <div class="hidden lg:flex items-center gap-4">

                <!-- Theme Toggle Desktop -->
                <button type="button" class="btn-theme-toggle relative flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 bg-white text-slate-600 hover:border-primary-200 hover:bg-primary-50 hover:text-primary-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-primary-500/40 dark:hover:bg-slate-700 dark:hover:text-primary-400 transition-all duration-200" aria-label="Ganti tema">
                    <i class="icon-sun fa-solid fa-sun text-sm"></i>
                    <i class="icon-moon fa-solid fa-moon text-sm hidden"></i>
                </button>

                <!-- Login Admin -->
                <a href="<?= base_url('admin') ?>" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-primary-600 dark:text-slate-300 dark:hover:text-primary-400 transition duration-200">
                    <i class="fa-regular fa-user text-sm"></i>
                    <span>Masuk</span>
                </a>

                <!-- Booking Button -->
                <a href="<?= base_url('cars') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary-600 text-white text-sm font-bold shadow-lg shadow-primary-600/20 hover:bg-primary-700 hover:-translate-y-0.5 transition-all duration-200">
                    <span>Booking Sekarang</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>

            </div>

            <!-- MOBILE RIGHT -->
            <div class="lg:hidden flex items-center gap-2">

                <!-- Mobile Theme Toggle -->
                <button type="button" class="btn-theme-toggle flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-primary-50 hover:text-primary-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-primary-400 transition-all duration-200" aria-label="Ganti tema">
                    <i class="icon-sun fa-solid fa-sun text-sm"></i>
                    <i class="icon-moon fa-solid fa-moon text-sm hidden"></i>
                </button>

                <!-- Mobile Menu Button -->
                <button type="button" id="mobile-menu-button" class="flex items-center justify-center w-10 h-10 rounded-xl text-slate-700 hover:bg-slate-100 hover:text-primary-600 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-primary-400 transition duration-200" aria-label="Buka menu" aria-expanded="false">
                    <i id="mobile-menu-icon" class="fa-solid fa-bars text-xl"></i>
                </button>

            </div>

        </div>

        <!-- MOBILE NAVIGATION -->
        <div id="mobile-menu" class="lg:hidden hidden border-t border-slate-100 dark:border-slate-800">
            <nav class="py-4 space-y-1">

                <a href="<?= base_url() ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold <?= $isHome ? 'bg-primary-50 text-primary-600 dark:bg-primary-950/40 dark:text-primary-400' : 'text-slate-600 hover:bg-slate-50 hover:text-primary-600 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-primary-400' ?> transition duration-200">
                    <i class="fa-solid fa-house w-5"></i>
                    <span>Beranda</span>
                </a>

                <a href="<?= base_url('cars') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold <?= $isMobil ? 'bg-primary-50 text-primary-600 dark:bg-primary-950/40 dark:text-primary-400' : 'text-slate-600 hover:bg-slate-50 hover:text-primary-600 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-primary-400' ?> transition duration-200">
                    <i class="fa-solid fa-car w-5"></i>
                    <span>Armada</span>
                </a>

                <a href="<?= base_url('tentang-kami') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold <?= $isTentang ? 'bg-primary-50 text-primary-600 dark:bg-primary-950/40 dark:text-primary-400' : 'text-slate-600 hover:bg-slate-50 hover:text-primary-600 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-primary-400' ?> transition duration-200">
                    <i class="fa-solid fa-circle-info w-5"></i>
                    <span>Tentang Kami</span>
                </a>

                <a href="<?= base_url('articles') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold <?= $isArtikel ? 'bg-primary-50 text-primary-600 dark:bg-primary-950/40 dark:text-primary-400' : 'text-slate-600 hover:bg-slate-50 hover:text-primary-600 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-primary-400' ?> transition duration-200">
                    <i class="fa-solid fa-newspaper w-5"></i>
                    <span>Artikel</span>
                </a>

                <a href="<?= base_url('contact') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold <?= $isKontak ? 'bg-primary-50 text-primary-600 dark:bg-primary-950/40 dark:text-primary-400' : 'text-slate-600 hover:bg-slate-50 hover:text-primary-600 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-primary-400' ?> transition duration-200">
                    <i class="fa-solid fa-phone w-5"></i>
                    <span>Kontak</span>
                </a>

                <a href="<?= base_url('admin') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-primary-600 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-primary-400 transition duration-200">
                    <i class="fa-regular fa-user w-5"></i>
                    <span>Masuk</span>
                </a>

                <a href="<?= base_url('cars') ?>" class="mt-3 flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-primary-600 text-white text-sm font-bold hover:bg-primary-700 transition duration-200">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Booking Sekarang</span>
                </a>

            </nav>
        </div>

    </div>
</header>

<!-- SPACER NAVBAR -->
<div class="h-20"></div>
