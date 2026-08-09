<?php

// Mengembalikan URL lengkap
function base_url(string $path = ''): string {
    return BASE_URL . '/' . ltrim($path, '/');
}

// Mencegah XSS (Cross-Site Scripting)
function escape(?string $string): string {
    if ($string === null) {
        return '';
    }
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Redirect halaman
function redirect(string $path): void {
    header("Location: " . base_url($path));
    exit;
}

// Memuat View (Templating Engine Sederhana)
function view(string $viewPath, array $data = []): void {
    // Mengekstrak array menjadi variabel ($data['title'] menjadi $title)
    extract($data);
    
    $file = __DIR__ . '/../views/' . $viewPath . '.php';
    if (file_exists($file)) {
        require_once $file;
    } else {
        die("Error: View '{$viewPath}' tidak ditemukan di {$file}");
    }
}
