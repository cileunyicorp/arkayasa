<?php
$routes = [
    ''                    => ['HomeController', 'index'],
    'home'                => ['HomeController', 'index'],

    // Auth & Dashboard
    'admin'               => ['AuthController', 'login'],
    'admin/login'         => ['AuthController', 'login'],
    'admin/login/process' => ['AuthController', 'process'],
    'admin/logout'        => ['AuthController', 'logout'],
    'admin/dashboard'     => ['AdminDashboardController', 'index'],

    // Rute CRUD Mobil Utama & AJAX
    'admin/mobil'                 => ['AdminCarController', 'index'],
    'admin/mobil/api/get_all'     => ['AdminCarController', 'get_all'],
    'admin/mobil/api/get_by_id'   => ['AdminCarController', 'get_by_id'],
    'admin/mobil/store'           => ['AdminCarController', 'store'],
    'admin/mobil/update'          => ['AdminCarController', 'update'],
    'admin/mobil/delete'          => ['AdminCarController', 'delete'],

    // --- MODUL BOOKING (Pastikan ini lengkap) ---
    'admin/booking'                 => ['AdminBookingController', 'index'],
    'admin/booking/api/get_all'     => ['AdminBookingController', 'get_all'],
    'admin/booking/api/get_by_id'   => ['AdminBookingController', 'get_by_id'],
    'admin/booking/store'           => ['AdminBookingController', 'store'],
    'admin/booking/update'          => ['AdminBookingController', 'update'],
    'admin/booking/invoice'         => ['AdminBookingController', 'invoice'],

    // --- RUTE MODUL PELANGGAN (ADMIN) ---
    'admin/pelanggan'               => ['AdminCustomerController', 'index'],
    'admin/pelanggan/api/get_all'   => ['AdminCustomerController', 'get_all'],
    'admin/pelanggan/api/get_by_id' => ['AdminCustomerController', 'get_by_id'],
    'admin/pelanggan/store'         => ['AdminCustomerController', 'store'],
    'admin/pelanggan/update'        => ['AdminCustomerController', 'update'],
    'admin/pelanggan/delete'        => ['AdminCustomerController', 'delete'],

    // --- MODUL DRIVER (SOPIR) ---
    'admin/driver'               => ['AdminDriverController', 'index'],
    'admin/driver/api/get_all'   => ['AdminDriverController', 'get_all'],
    'admin/driver/api/get_by_id' => ['AdminDriverController', 'get_by_id'],
    'admin/driver/store'         => ['AdminDriverController', 'store'],
    'admin/driver/update'        => ['AdminDriverController', 'update'],
    'admin/driver/delete'        => ['AdminDriverController', 'delete'],

    // --- MODUL PERAWATAN MOBIL ---
    'admin/perawatan'               => ['AdminMaintenanceController', 'index'],
    'admin/perawatan/api/get_all'   => ['AdminMaintenanceController', 'get_all'],
    'admin/perawatan/api/get_by_id' => ['AdminMaintenanceController', 'get_by_id'],
    'admin/perawatan/store'         => ['AdminMaintenanceController', 'store'],
    'admin/perawatan/update'        => ['AdminMaintenanceController', 'update'],
    'admin/perawatan/delete'        => ['AdminMaintenanceController', 'delete'],

    // --- MODUL KEUANGAN ---
    'admin/keuangan'               => ['AdminFinanceController', 'index'],
    'admin/keuangan/api/get_all'   => ['AdminFinanceController', 'get_all'],
    'admin/keuangan/api/get_by_id' => ['AdminFinanceController', 'get_by_id'],
    'admin/keuangan/store'         => ['AdminFinanceController', 'store'],
    'admin/keuangan/update'        => ['AdminFinanceController', 'update'],
    'admin/keuangan/delete'        => ['AdminFinanceController', 'delete'],




];
return $routes;
