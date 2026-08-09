<?php
// Mencegah akses langsung ke file
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    die('Direct access not permitted');
}

// Konfigurasi URL Dasar (Sesuaikan dengan folder Laragon Anda)
define('BASE_URL', 'http://localhost/arkayasa');

// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'rental_mobil');

// Nama Aplikasi
define('APP_NAME', 'Arkayasa Rent Car');

// Zona Waktu
date_default_timezone_set('Asia/Jakarta');
