<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
// Memuat inti aplikasi
require_once 'config/config.php';
require_once 'app/helpers/helper.php';

// Memuat rute
$routes = require_once 'config/routes.php';

// Menangkap URL
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$url = filter_var($url, FILTER_SANITIZE_URL);
$urlParts = explode('/', $url);

$controllerName = 'HomeController';
$methodName = 'index';
$params = [];
$routeFound = false;

// Logika Router Dinamis: Mencocokkan dari URL terpanjang ke terpendek
// Contoh: Cek 'admin/login/process' dulu, baru cek 'admin/login'
if ($url !== '') {
    for ($i = count($urlParts); $i > 0; $i--) {
        $checkRoute = implode('/', array_slice($urlParts, 0, $i));
        
        if (array_key_exists($checkRoute, $routes)) {
            $controllerName = $routes[$checkRoute][0];
            $methodName = $routes[$checkRoute][1];
            // Sisa URL jadikan parameter
            $params = array_slice($urlParts, $i);
            $routeFound = true;
            break;
        }
    }

    // Jika tidak terdaftar di config/routes.php (Auto Routing)
    if (!$routeFound) {
        $controllerName = ucfirst($urlParts[0]) . 'Controller';
        $methodName = $urlParts[1] ?? 'index';
        $params = array_slice($urlParts, 2);
    }
}

// Memuat Controller
$controllerFile = 'app/controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerName();

    // Cek apakah method ada di controller
    if (method_exists($controller, $methodName)) {
        call_user_func_array([$controller, $methodName], $params);
    } else {
        die("Error: Method '{$methodName}' tidak ditemukan di {$controllerName}.");
    }
} else {
    die("Error: Controller '{$controllerName}' tidak ditemukan (404 Not Found).");
}
