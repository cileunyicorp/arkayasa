<?php
// File: reset_admin.php
require_once 'config/config.php';
require_once 'config/database.php';

try {
    $db = Database::getConnection();
    
    $email = 'admin@arkayasa.com';
    $password = 'admin123';
    
    // Generate hash baru yang dijamin valid oleh server PHP Anda
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Cek apakah user admin sudah ada
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Jika ada, update passwordnya
        $stmt = $db->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$hashedPassword, $email]);
        echo "<h3>Berhasil! Password untuk {$email} telah di-reset.</h3>";
    } else {
        // Jika ternyata belum ada, insert baru
        $stmt = $db->prepare("INSERT INTO users (role_id, name, email, password) VALUES (1, 'Super Admin', ?, ?)");
        $stmt->execute([$email, $hashedPassword]);
        echo "<h3>Berhasil! Akun {$email} berhasil dibuat ulang.</h3>";
    }

    echo "<p>Silakan gunakan kredensial berikut untuk login:</p>";
    echo "<ul>";
    echo "<li>Email: <b>{$email}</b></li>";
    echo "<li>Password: <b>{$password}</b></li>";
    echo "</ul>";
    echo "<a href='" . BASE_URL . "/admin' style='padding:10px; background:blue; color:white; text-decoration:none; border-radius:5px;'>Kembali ke Login</a>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
