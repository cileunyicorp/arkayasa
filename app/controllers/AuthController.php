<?php
require_once __DIR__ . '/../models/UserModel.php';

class AuthController {
    private UserModel $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    // Menampilkan Halaman Login
    public function login() {
        // Jika sudah login sebagai admin/operator, langsung ke dashboard
        if (isset($_SESSION['user_id']) && in_array($_SESSION['role_id'], [1, 2])) {
            redirect('admin/dashboard');
        }

        // Tampilkan view login dari folder admin
        require_once __DIR__ . '/../../admin/login.php';
    }

    // Memproses data POST dari form login
    public function process() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $_SESSION['error'] = "Email dan password wajib diisi!";
                redirect('admin/login');
            }

            $user = $this->userModel->findByEmail($email);

            // Verifikasi user ada dan password cocok
            if ($user && password_verify($password, $user['password'])) {
                // Cek apakah user adalah Admin (1) atau Operator (2)
                if (in_array($user['role_id'], [1, 2])) {
                    // Mencegah Session Fixation
                    session_regenerate_id(true);

                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role_id'] = $user['role_id'];
                    $_SESSION['user_name'] = $user['name'];

                    redirect('admin/dashboard');
                } else {
                    $_SESSION['error'] = "Anda tidak memiliki akses ke halaman admin.";
                    redirect('admin/login');
                }
            } else {
                $_SESSION['error'] = "Email atau password salah.";
                redirect('admin/login');
            }
        } else {
            redirect('admin/login');
        }
    }

    // Menangani Logout
    public function logout() {
        session_unset();
        session_destroy();
        
        // Hapus cookie session jika ada
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Start session baru untuk pesan sukses logout
        session_start();
        $_SESSION['success'] = "Anda berhasil logout.";
        redirect('admin/login');
    }

        // Timpa method dashboard yang lama dengan yang ini
    public function dashboard() {
        require_once __DIR__ . '/../models/CarModel.php';
        require_once __DIR__ . '/../models/BookingModel.php';
        
        $carModel = new CarModel();
        $bookingModel = new BookingModel();
        $userModel = new UserModel();

        // Siapkan data untuk view
        $totalCars = $carModel->getTotalCars();
        $totalBookings = $bookingModel->getTotalBookings();
        $totalCustomers = $userModel->getTotalCustomers();
        $totalRevenue = $bookingModel->getTotalRevenue();
        $todayBookings = $bookingModel->getTodayBookings();

        // Panggil view
        require_once __DIR__ . '/../../admin/dashboard.php';
    }

}
