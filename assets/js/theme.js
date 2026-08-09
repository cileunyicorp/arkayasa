document.addEventListener('DOMContentLoaded', function () {
    const html = document.documentElement;
    const themeButtons = document.querySelectorAll('.btn-theme-toggle');
    const sunIcons = document.querySelectorAll('.icon-sun');
    const moonIcons = document.querySelectorAll('.icon-moon');

    const updateIcons = () => {
        const isDark = html.classList.contains('dark');
        
        sunIcons.forEach(icon => {
            if (isDark) {
                icon.style.display = 'none'; // Sembunyikan matahari saat dark mode
            } else {
                icon.style.display = 'inline-block'; // Tampilkan matahari saat light mode
            }
        });

        moonIcons.forEach(icon => {
            if (isDark) {
                icon.style.display = 'inline-block'; // Tampilkan bulan saat dark mode
            } else {
                icon.style.display = 'none'; // Sembunyikan bulan saat light mode
            }
        });
    };

    // 1. Set Tema Awal dari LocalStorage atau Preferences
    const savedTheme = localStorage.getItem('arkayasa-theme');
    if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        html.classList.add('dark');
    } else {
        html.classList.remove('dark');
    }
    
    // Sinkronisasi ikon saat halaman dimuat
    updateIcons();

    // 2. Pasang Listener Klik pada Semua Tombol Toggle
    themeButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            html.classList.toggle('dark');
            const isDark = html.classList.contains('dark');
            localStorage.setItem('arkayasa-theme', isDark ? 'dark' : 'light');
            updateIcons();
        });
    });
});