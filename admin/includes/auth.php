<?php
// 1. Memastikan Session Sudah Berjalan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Cek Apakah User Sudah Login dan Memiliki Akses Admin/Operator (role_id 1 atau 2)
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role_id'], [1, 2])) {
    $_SESSION['error'] = "Silakan login terlebih dahulu untuk mengakses panel admin.";
    header("Location: " . base_url('admin/login'));
    exit;
}

// 3. FITUR KEAMANAN: Auto-Logout Jika Inaktif (Idle Timeout)
// Batas waktu inaktif: 2 Jam (7200 detik). Anda bisa mengubahnya sesuai kebutuhan (misal 30 menit = 1800)
$max_idle_time = 7200; 

if (isset($_SESSION['last_activity'])) {
    $elapsed_time = time() - $_SESSION['last_activity'];

    // Jika durasi diam (inaktif) melebihi batas waktu 2 jam
    if ($elapsed_time > $max_idle_time) {
        // Hapus seluruh session
        session_unset();
        session_destroy();

        // Mulai session baru khusus untuk membawa pesan error
        session_start();
        $_SESSION['error'] = "Sesi Anda telah berakhir karena tidak ada aktivitas selama 2 jam. Silakan login kembali.";
        header("Location: " . base_url('admin/login'));
        exit;
    }
}

// 4. Perbarui Waktu Aktivitas Terakhir Setiap Kali Halaman Di-refresh / Diakses
$_SESSION['last_activity'] = time();

// 5. Mencegah Session Hijacking: Regenerasi ID Session Secara Berkala Setiap 30 Menit
if (!isset($_SESSION['created_at'])) {
    $_SESSION['created_at'] = time();
} else if (time() - $_SESSION['created_at'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['created_at'] = time();
}
