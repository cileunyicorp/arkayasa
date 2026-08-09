<?php
require_once __DIR__ . '/../config/config.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator - <?= escape(APP_NAME) ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/favicon.png') ?>">
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/output.css') ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white transition-colors duration-300">

    <!-- HEADER -->
    <header class="w-full bg-white dark:bg-slate-950 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="h-20 flex items-center justify-between">
                <!-- Logo -->
                <a href="<?= base_url() ?>" class="flex items-center group">
                    <img src="<?= base_url('assets/images/logo.png') ?>" alt="Arkayasa" class="h-10 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                </a>

                <!-- Theme Toggle -->
                <button type="button" id="theme-toggle" class="flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-primary-50 dark:hover:bg-slate-800 hover:text-primary-600 dark:hover:text-primary-400 transition-all duration-200">
                    <i id="theme-icon" class="fa-solid fa-sun text-sm"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- MAIN LOGIN -->
    <main class="min-h-[calc(100vh-5rem)] flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-md">

            <!-- Login Card -->
            <div class="relative overflow-hidden rounded-3xl bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-xl shadow-slate-900/5 dark:shadow-black/20 p-8 sm:p-9 transition-colors duration-300">

                <!-- Top Accent -->
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary-700 via-primary-600 to-primary-700"></div>

                <!-- CARD HEADER -->
                <div class="text-center mb-8 pt-2">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary-50 dark:bg-primary-950/50 text-primary-600 dark:text-primary-400 border border-primary-100 dark:border-primary-900/50 shadow-sm mb-5 transition-colors duration-300">
                        <i class="fa-solid fa-user-shield text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                        Panel Administrator
                    </h1>
                    <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                        Masuk untuk mengelola <?= escape(APP_NAME) ?>
                    </p>
                </div>

                <!-- ALERT ERROR -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="mb-6 flex items-start gap-3 p-4 rounded-2xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/50 text-red-700 dark:text-red-400 text-sm leading-relaxed">
                        <div class="flex items-center justify-center shrink-0 w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/40">
                            <i class="fa-solid fa-circle-exclamation"></i>
                        </div>
                        <div class="pt-1">
                            <?= escape($_SESSION['error']) ?>
                        </div>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- ALERT SUCCESS -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="mb-6 flex items-start gap-3 p-4 rounded-2xl bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900/50 text-green-700 dark:text-green-400 text-sm leading-relaxed">
                        <div class="flex items-center justify-center shrink-0 w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/40">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <div class="pt-1">
                            <?= escape($_SESSION['success']) ?>
                        </div>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <!-- LOGIN FORM -->
                <form action="<?= base_url('admin/login/process') ?>" method="POST" class="space-y-5">

                    <!-- EMAIL -->
                    <div>
                        <label for="email" class="block mb-2 text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Email
                        </label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <input id="email" type="email" name="email" required autocomplete="email" placeholder="admin@arkayasa.com" class="w-full pl-11 pr-4 py-3.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-600 outline-none transition-all duration-200 hover:border-slate-300 dark:hover:border-slate-700 focus:border-primary-600 dark:focus:border-primary-500 focus:ring-4 focus:ring-primary-600/10 dark:focus:ring-primary-500/10">
                        </div>
                    </div>

                    <!-- PASSWORD -->
                    <div>
                        <label for="password" class="block mb-2 text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Password
                        </label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password" class="w-full pl-11 pr-12 py-3.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-600 outline-none transition-all duration-200 hover:border-slate-300 dark:hover:border-slate-700 focus:border-primary-600 dark:focus:border-primary-500 focus:ring-4 focus:ring-primary-600/10 dark:focus:ring-primary-500/10">

                            <!-- Show Password Button -->
                            <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 flex items-center justify-center w-12 text-slate-400 dark:text-slate-500 hover:text-primary-600 dark:hover:text-primary-400 transition-colors" aria-label="Tampilkan password">
                                <i id="password-icon" class="fa-solid fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- LOGIN BUTTON -->
                    <button type="submit" class="group w-full inline-flex items-center justify-center gap-2 py-3.5 px-5 rounded-xl bg-primary-600 hover:bg-primary-700 active:bg-primary-800 text-white font-bold shadow-lg shadow-primary-600/20 hover:shadow-xl hover:shadow-primary-600/25 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">
                        <i class="fa-solid fa-right-to-bracket text-sm transition-transform duration-200 group-hover:translate-x-0.5"></i>
                        <span>Masuk ke Panel</span>
                    </button>

                </form>

                <!-- FOOTER -->
                <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800 text-center">
                    <p class="text-xs text-slate-400 dark:text-slate-500">
                        &copy; <?= date('Y') ?> <?= escape(APP_NAME) ?>. All rights reserved.
                    </p>
                </div>

            </div>

            <!-- Security Text -->
            <div class="mt-4 flex items-center justify-center gap-2 text-xs text-slate-400 dark:text-slate-600">
                <i class="fa-solid fa-shield-halved"></i>
                <span>Secure Administrator Access</span>
            </div>

        </div>
    </main>

    <!-- SCRIPTS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password Toggle Logic
            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('toggle-password');
            const passwordIcon = document.getElementById('password-icon');

            if (passwordInput && togglePassword && passwordIcon) {
                togglePassword.addEventListener('click', function() {
                    const isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';
                    passwordIcon.className = isPassword ? 'fa-solid fa-eye-slash text-sm' : 'fa-solid fa-eye text-sm';
                    togglePassword.setAttribute('aria-label', isPassword ? 'Sembunyikan password' : 'Tampilkan password');
                });
            }

            // Theme Toggle Logic
            const html = document.documentElement;
            const toggle = document.getElementById('theme-toggle');
            const icon = document.getElementById('theme-icon');

            function updateIcon() {
                const isDark = html.classList.contains('dark');
                if (icon) {
                    icon.className = isDark ? 'fa-solid fa-moon text-sm' : 'fa-solid fa-sun text-sm';
                }
            }

            const savedTheme = localStorage.getItem('arkayasa-theme');
            if (savedTheme === 'dark') {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }

            updateIcon();

            if (toggle) {
                toggle.addEventListener('click', function() {
                    const isDark = html.classList.toggle('dark');
                    localStorage.setItem('arkayasa-theme', isDark ? 'dark' : 'light');
                    updateIcon();
                });
            }
        });
    </script>
</body>

</html>