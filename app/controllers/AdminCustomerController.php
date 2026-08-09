<?php
require_once __DIR__ . '/../models/CustomerModel.php';
require_once __DIR__ . '/../models/UserModel.php';

class AdminCustomerController {
    private CustomerModel $customerModel;

    public function __construct() {
        $this->customerModel = new CustomerModel();
    }

    public function index() {
        require_once __DIR__ . '/../../admin/pelanggan/index.php';
    }

    public function get_all() {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $customers = $this->customerModel->getAllWithUsers();
            echo json_encode(['data' => $customers]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function get_by_id($id) {
        header('Content-Type: application/json; charset=utf-8');
        $customer = $this->customerModel->getByIdWithUser((int)$id);
        if ($customer) {
            echo json_encode(['status' => true, 'data' => $customer]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Data tidak ditemukan!']);
        }
    }

    public function store() {
        header('Content-Type: application/json; charset=utf-8');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $db = Database::getConnection();
                
                $name = htmlspecialchars($_POST['name'] ?? '');
                $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
                $password = $_POST['password'] ?? '';
                $phone = htmlspecialchars($_POST['phone'] ?? '');
                $address = htmlspecialchars($_POST['address'] ?? '');
                $nik = htmlspecialchars($_POST['nik'] ?? '');
                $sim = htmlspecialchars($_POST['driver_license_number'] ?? '');

                // Validasi Unik
                if ($this->customerModel->isEmailExists($email)) {
                    throw new Exception("Email '$email' sudah digunakan akun lain.");
                }
                if ($this->customerModel->isNikExists($nik)) {
                    throw new Exception("NIK '$nik' sudah terdaftar.");
                }

                $db->beginTransaction();

                // 1. Insert ke tabel users (role_id = 3 untuk Pelanggan)
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmtUser = $db->prepare("INSERT INTO users (role_id, name, email, password, phone, address) VALUES (3, ?, ?, ?, ?, ?)");
                $stmtUser->execute([$name, $email, $hashedPassword, $phone, $address]);
                $user_id = $db->lastInsertId();

                // 2. Insert ke tabel customers
                $stmtCust = $db->prepare("INSERT INTO customers (user_id, nik, driver_license_number) VALUES (?, ?, ?)");
                $stmtCust->execute([$user_id, $nik, $sim]);

                $db->commit();
                echo json_encode(['status' => true, 'message' => 'Pelanggan berhasil ditambahkan!']);
            } catch (Exception $e) {
                if (isset($db) && $db->inTransaction()) $db->rollBack();
                echo json_encode(['status' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function update($customer_id) {
        header('Content-Type: application/json; charset=utf-8');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $db = Database::getConnection();
                
                // Ambil user_id dari customer ini
                $customerData = $this->customerModel->getByIdWithUser((int)$customer_id);
                if (!$customerData) throw new Exception("Data pelanggan tidak valid.");
                $user_id = $customerData['user_id'];

                $name = htmlspecialchars($_POST['name'] ?? '');
                $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
                $password = $_POST['password'] ?? '';
                $phone = htmlspecialchars($_POST['phone'] ?? '');
                $address = htmlspecialchars($_POST['address'] ?? '');
                $nik = htmlspecialchars($_POST['nik'] ?? '');
                $sim = htmlspecialchars($_POST['driver_license_number'] ?? '');

                // Validasi Unik
                if ($this->customerModel->isEmailExists($email, $user_id)) {
                    throw new Exception("Email '$email' sudah digunakan akun lain.");
                }
                if ($this->customerModel->isNikExists($nik, $customer_id)) {
                    throw new Exception("NIK '$nik' sudah terdaftar.");
                }

                $db->beginTransaction();

                // 1. Update tabel users
                if (!empty($password)) {
                    // Update dengan password baru
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmtUser = $db->prepare("UPDATE users SET name=?, email=?, password=?, phone=?, address=? WHERE id=?");
                    $stmtUser->execute([$name, $email, $hashedPassword, $phone, $address, $user_id]);
                } else {
                    // Update tanpa mengganti password
                    $stmtUser = $db->prepare("UPDATE users SET name=?, email=?, phone=?, address=? WHERE id=?");
                    $stmtUser->execute([$name, $email, $phone, $address, $user_id]);
                }

                // 2. Update tabel customers
                $stmtCust = $db->prepare("UPDATE customers SET nik=?, driver_license_number=? WHERE id=?");
                $stmtCust->execute([$nik, $sim, $customer_id]);

                $db->commit();
                echo json_encode(['status' => true, 'message' => 'Data pelanggan berhasil diperbarui!']);
            } catch (Exception $e) {
                if (isset($db) && $db->inTransaction()) $db->rollBack();
                echo json_encode(['status' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function delete($customer_id) {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $db = Database::getConnection();
            $customerData = $this->customerModel->getByIdWithUser((int)$customer_id);
            if (!$customerData) throw new Exception("Data tidak ditemukan.");

            $user_id = $customerData['user_id'];

            // Karena ada ON DELETE CASCADE di tabel customers, kita cukup menghapus user-nya
            $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$user_id]);

            echo json_encode(['status' => true, 'message' => 'Pelanggan berhasil dihapus!']);
        } catch (PDOException $e) {
            echo json_encode(['status' => false, 'message' => 'Pelanggan tidak bisa dihapus karena memiliki riwayat pemesanan.']);
        } catch (Exception $e) {
            echo json_encode(['status' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()]);
        }
    }
}
