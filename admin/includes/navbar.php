<div class="flex-1 flex flex-col overflow-hidden">
    <!-- Navbar Atas -->
    <header class="flex items-center justify-between px-6 py-4 bg-white dark:bg-neutral-950 border-b border-slate-200 dark:border-neutral-800 shadow-sm transition-colors duration-300">
        <div class="flex items-center">
            <!-- Tombol Hamburger Mobile -->
            <button @click="sidebarOpen = true" class="text-slate-500 hover:text-primary dark:text-neutral-400 focus:outline-none md:hidden transition">
                <i class="fa-solid fa-bars-staggered text-xl"></i>
            </button>
        </div>

        <div class="flex items-center gap-4">

            <!-- Theme Toggle -->
            <button type="button" id="theme-toggle" class="flex h-10 w-10 items-center justify-center rounded-xl text-slate-500 hover:bg-slate-100 hover:text-primary-600 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:hover:text-primary-400 focus:outline-none focus:ring-2 focus:ring-primary-600/20 transition-all duration-200" title="Ganti Tema" aria-label="Ganti Tema">
                <i id="theme-toggle-icon" class="fa-solid fa-moon text-lg"></i>
            </button>

            <!-- Dropdown Profil Alpine.js -->
            <div x-data="{ dropdownOpen: false }" class="relative">
                <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-2 text-slate-700 dark:text-neutral-300 focus:outline-none py-1.5 px-3 hover:bg-slate-50 dark:hover:bg-neutral-900 rounded-xl transition">
                    <img class="h-8 w-8 rounded-xl object-cover border border-slate-200 dark:border-neutral-800" src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user_name']) ?>&background=B30427&color=fff" alt="Avatar">
                    <span class="font-semibold text-sm hidden md:block text-slate-700 dark:text-neutral-200"><?= escape($_SESSION['user_name']) ?></span>
                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-200" :class="dropdownOpen ? 'rotate-180' : ''"></i>
                </button>

                <!-- Menu Dropdown -->
                <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition class="absolute right-0 mt-2.5 w-52 bg-white dark:bg-neutral-950 border border-slate-200 dark:border-neutral-800 rounded-2xl shadow-xl py-2 z-50">
                    <div class="px-4 py-2.5 border-b border-slate-100 dark:border-neutral-800/80 mb-1">
                        <p class="text-xs text-slate-400 dark:text-neutral-500">Masuk sebagai</p>
                        <p class="text-xs font-bold text-slate-700 dark:text-neutral-200 truncate"><?= escape($_SESSION['user_name']) ?></p>
                    </div>
                    <a href="#" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-600 dark:text-neutral-300 hover:bg-slate-50 dark:hover:bg-neutral-900/50 transition">
                        <i class="fa-solid fa-gears text-slate-400"></i> Pengaturan
                    </a>
                    <hr class="border-slate-100 dark:border-neutral-800/80 my-1.5">
                    <a href="<?= base_url('admin/logout') ?>" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-primary hover:bg-red-50 dark:hover:bg-red-950/20 font-semibold transition">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Wrapper -->
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 dark:bg-neutral-900 p-6 transition-colors duration-300">
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const themeToggle = document.getElementById('theme-toggle');
                const themeIcon = document.getElementById('theme-toggle-icon');

                if (!themeToggle || !themeIcon) {
                    return;
                }

                function updateThemeIcon() {

                    const isDark = document.documentElement.classList.contains('dark');

                    if (isDark) {

                        // Dark mode → tampilkan matahari
                        themeIcon.classList.remove('fa-moon');
                        themeIcon.classList.add('fa-sun');

                    } else {

                        // Light mode → tampilkan bulan
                        themeIcon.classList.remove('fa-sun');
                        themeIcon.classList.add('fa-moon');

                    }
                }

                // Tentukan icon saat halaman pertama dibuka
                updateThemeIcon();

                // Toggle tema
                themeToggle.addEventListener('click', function() {

                    const isDark =
                        document.documentElement.classList.contains('dark');

                    if (isDark) {

                        document.documentElement.classList.remove('dark');

                        localStorage.setItem(
                            'color-theme',
                            'light'
                        );

                    } else {

                        document.documentElement.classList.add('dark');

                        localStorage.setItem(
                            'color-theme',
                            'dark'
                        );

                    }

                    updateThemeIcon();

                });

            });
        </script>