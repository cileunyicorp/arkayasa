<?php
// Mencegah akses langsung ke file
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    die('Direct access not permitted');
}

// Fungsi sederhana untuk membaca .env jika belum menggunakan library pihak ketiga
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}


// Helper function untuk ambil env
function env($key, $default = null) {
    return $_ENV[$key] ?? $default;
}

// Konfigurasi menggunakan .env
define('BASE_URL', env('BASE_URL', 'http://localhost/arkayasa'));
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));
define('DB_NAME', env('DB_NAME', 'rental_mobil'));
define('APP_NAME', env('APP_NAME', 'Arkayasa Rent Car'));

// Zona Waktu
date_default_timezone_set('Asia/Jakarta');