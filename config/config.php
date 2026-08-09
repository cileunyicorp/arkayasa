<?php
// Mencegah akses langsung ke file
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    die('Direct access not permitted');
}

// Konfigurasi URL Dasar (Sesuaikan dengan domain live/hosting Anda, contoh: https://namadomain.com)
define('BASE_URL', 'https://arkayasa.site.je'); // Ganti dengan URL website Anda di hosting

// Konfigurasi Database (disesuaikan dengan gambar)
define('DB_HOST', 'sql202.infinityfree.com');
define('DB_USER', 'if0_42612548');
define('DB_PASS', '6HWRxSsD5r1qp7I');
define('DB_NAME', 'if0_42612548_arkayasa');

// Nama Aplikasi
define('APP_NAME', 'Arkayasa Rent Car');

// Zona Waktu
date_default_timezone_set('Asia/Jakarta');