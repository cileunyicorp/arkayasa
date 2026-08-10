<?php

require_once __DIR__ . '/../models/CustomerModel.php';
require_once __DIR__ . '/../models/UserModel.php';

class AdminCustomerController
{
    private CustomerModel $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
    }

    public function index()
    {
        require_once __DIR__ . '/../../admin/pelanggan/index.php';
    }

    public function get_all()
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $customers = $this->customerModel->getAllWithUsers();

            foreach ($customers as &$cust) {
                $cust['id_card_image_url'] = !empty($cust['id_card_image']) 
                    ? base_url('admin/assets/uploads/customers/' . htmlspecialchars($cust['id_card_image'])) 
                    : null;
                $cust['driver_license_image_url'] = !empty($cust['driver_license_image']) 
                    ? base_url('admin/assets/uploads/customers/' . htmlspecialchars($cust['driver_license_image'])) 
                    : null;
            }

            echo json_encode(['data' => $customers]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function get_by_id($id)
    {
        header('Content-Type: application/json; charset=utf-8');
        $customer = $this->customerModel->getByIdWithUser((int)$id);
        if ($customer) {
            $customer['id_card_image_url'] = !empty($customer['id_card_image']) 
                ? base_url('admin/assets/uploads/customers/' . htmlspecialchars($customer['id_card_image'])) 
                : null;
            $customer['driver_license_image_url'] = !empty($customer['driver_license_image']) 
                ? base_url('admin/assets/uploads/customers/' . htmlspecialchars($customer['driver_license_image'])) 
                : null;

            echo json_encode(['status' => true, 'data' => $customer]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Data tidak ditemukan!']);
        }
    }

    public function store()
    {
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

                // Upload Dokumen KTP & SIM Opsional
                $uploadedFiles = $this->handleUploads();

                $db->beginTransaction();

                // 1. Insert ke tabel users (role_id = 3 untuk Pelanggan)
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmtUser = $db->prepare("INSERT INTO users (role_id, name, email, password, phone, address) VALUES (3, ?, ?, ?, ?, ?)");
                $stmtUser->execute([$name, $email, $hashedPassword, $phone, $address]);
                $user_id = $db->lastInsertId();

                // 2. Insert ke tabel customers
                $stmtCust = $db->prepare("INSERT INTO customers (user_id, nik, driver_license_number, id_card_image, driver_license_image) VALUES (?, ?, ?, ?, ?)");
                $stmtCust->execute([$user_id, $nik, $sim, $uploadedFiles['id_card_image'], $uploadedFiles['driver_license_image']]);

                $db->commit();
                echo json_encode(['status' => true, 'message' => 'Pelanggan berhasil ditambahkan!']);
            } catch (Exception $e) {
                if (isset($db) && $db->inTransaction()) $db->rollBack();
                echo json_encode(['status' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function update($customer_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $db = Database::getConnection();

                // Ambil data pelanggan lama
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

                // Handle Upload Dokumen Baru jika ada
                $uploadedFiles = $this->handleUploads($customerData['id_card_image'], $customerData['driver_license_image']);

                $db->beginTransaction();

                // 1. Update tabel users
                if (!empty($password)) {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmtUser = $db->prepare("UPDATE users SET name=?, email=?, password=?, phone=?, address=? WHERE id=?");
                    $stmtUser->execute([$name, $email, $hashedPassword, $phone, $address, $user_id]);
                } else {
                    $stmtUser = $db->prepare("UPDATE users SET name=?, email=?, phone=?, address=? WHERE id=?");
                    $stmtUser->execute([$name, $email, $phone, $address, $user_id]);
                }

                // 2. Update tabel customers
                $stmtCust = $db->prepare("UPDATE customers SET nik=?, driver_license_number=?, id_card_image=?, driver_license_image=? WHERE id=?");
                $stmtCust->execute([$nik, $sim, $uploadedFiles['id_card_image'], $uploadedFiles['driver_license_image'], $customer_id]);

                $db->commit();
                echo json_encode(['status' => true, 'message' => 'Data pelanggan berhasil diperbarui!']);
            } catch (Exception $e) {
                if (isset($db) && $db->inTransaction()) $db->rollBack();
                echo json_encode(['status' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function delete($customer_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $db = Database::getConnection();
            $customerData = $this->customerModel->getByIdWithUser((int)$customer_id);
            if (!$customerData) throw new Exception("Data tidak ditemukan.");

            $user_id = $customerData['user_id'];
            $uploadDir = __DIR__ . '/../../admin/assets/uploads/customers/';

            // Hapus berkas fisik KTP dan SIM jika ada
            if (!empty($customerData['id_card_image'])) {
                $ktpPath = $uploadDir . $customerData['id_card_image'];
                if (file_exists($ktpPath) && is_file($ktpPath)) unlink($ktpPath);
            }
            if (!empty($customerData['driver_license_image'])) {
                $simPath = $uploadDir . $customerData['driver_license_image'];
                if (file_exists($simPath) && is_file($simPath)) unlink($simPath);
            }

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

    /**
     * Helper Privat untuk Mengunggah Berkas Foto KTP dan SIM
     */
    private function handleUploads(?string $existingKtp = null, ?string $existingSim = null): array
    {
        $uploadDir = __DIR__ . '/../../admin/assets/uploads/customers/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $ktpFilename = $existingKtp;
        $simFilename = $existingSim;
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

        // Upload Foto KTP
        if (isset($_FILES['id_card_image']) && $_FILES['id_card_image']['error'] === 0) {
            if (in_array($_FILES['id_card_image']['type'], $allowedTypes) && $_FILES['id_card_image']['size'] <= 3000000) {
                // Hapus file lama jika diganti
                if ($existingKtp && file_exists($uploadDir . $existingKtp)) {
                    unlink($uploadDir . $existingKtp);
                }
                $ext = pathinfo($_FILES['id_card_image']['name'], PATHINFO_EXTENSION);
                $ktpFilename = 'ktp_' . uniqid() . '_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['id_card_image']['tmp_name'], $uploadDir . $ktpFilename);
            }
        }

        // Upload Foto SIM
        if (isset($_FILES['driver_license_image']) && $_FILES['driver_license_image']['error'] === 0) {
            if (in_array($_FILES['driver_license_image']['type'], $allowedTypes) && $_FILES['driver_license_image']['size'] <= 3000000) {
                // Hapus file lama jika diganti
                if ($existingSim && file_exists($uploadDir . $existingSim)) {
                    unlink($uploadDir . $existingSim);
                }
                $ext = pathinfo($_FILES['driver_license_image']['name'], PATHINFO_EXTENSION);
                $simFilename = 'sim_' . uniqid() . '_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['driver_license_image']['tmp_name'], $uploadDir . $simFilename);
            }
        }

        return [
            'id_card_image' => $ktpFilename,
            'driver_license_image' => $simFilename
        ];
    }
}
