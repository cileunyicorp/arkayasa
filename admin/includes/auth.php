<?php
// Memastikan session sudah berjalan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah belum login atau bukan admin/operator (role_id 1 atau 2)
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role_id'], [1, 2])) {
    // Redirect kembali ke login
    header("Location: " . base_url('admin/login'));
    exit;
}
