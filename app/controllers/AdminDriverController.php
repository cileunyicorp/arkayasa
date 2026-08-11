<?php

require_once __DIR__ . '/../models/DriverModel.php';

class AdminDriverController {
    private DriverModel $driverModel;

    public function __construct() {
        $this->driverModel = new DriverModel();
    }

    public function index() {
        require_once __DIR__ . '/../../admin/driver/index.php';
    }

    public function get_all() {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $drivers = $this->driverModel->findAll();
            
            foreach ($drivers as &$d) {
                $statusClass = 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300';
                if ($d['status'] === 'Disewa') $statusClass = 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300';
                if ($d['status'] === 'Nonaktif') $statusClass = 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-400';
                
                $d['status_html'] = "<span class=\"px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide rounded-lg {$statusClass}\">" . htmlspecialchars($d['status']) . "</span>";
                $d['price_format'] = "Rp " . number_format((float)($d['price_per_day'] ?? 0), 0, ',', '.');
                $d['photo_url'] = !empty($d['photo']) ? base_url('admin/assets/uploads/drivers/' . htmlspecialchars($d['photo'])) : '';
            }

            echo json_encode(['data' => $drivers]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function get_by_id($id) {
        header('Content-Type: application/json; charset=utf-8');
        $driver = $this->driverModel->findById((int)$id);
        if ($driver) {
            $driver['photo_url'] = !empty($driver['photo']) ? base_url('admin/assets/uploads/drivers/' . htmlspecialchars($driver['photo'])) : '';
            echo json_encode(['status' => true, 'data' => $driver]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Data sopir tidak ditemukan!']);
        }
    }

    public function store() {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $db = Database::getConnection();
            
            $name = htmlspecialchars($_POST['name'] ?? '');
            $phone = htmlspecialchars($_POST['phone'] ?? '');
            $nik = htmlspecialchars($_POST['nik'] ?? '');
            $sim = htmlspecialchars($_POST['driver_license_number'] ?? '');
            
            // Clean Input Rupiah
            $price_per_day = (float)preg_replace('/[^0-9]/', '', $_POST['price_per_day'] ?? '0');
            $status = $_POST['status'] ?? 'Tersedia';

            if ($this->driverModel->isNikExists($nik)) {
                throw new Exception("NIK '$nik' sudah terdaftar.");
            }

            $photo = $this->handleUpload();

            $sql = "INSERT INTO drivers (name, phone, nik, driver_license_number, price_per_day, status, photo) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([$name, $phone, $nik, $sim, $price_per_day, $status, $photo]);

            echo json_encode(['status' => true, 'message' => 'Sopir baru berhasil ditambahkan!']);
        } catch (Exception $e) {
            echo json_encode(['status' => false, 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    public function update($id) {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $db = Database::getConnection();
            
            $name = htmlspecialchars($_POST['name'] ?? '');
            $phone = htmlspecialchars($_POST['phone'] ?? '');
            $nik = htmlspecialchars($_POST['nik'] ?? '');
            $sim = htmlspecialchars($_POST['driver_license_number'] ?? '');
            
            // Clean Input Rupiah
            $price_per_day = (float)preg_replace('/[^0-9]/', '', $_POST['price_per_day'] ?? '0');
            $status = $_POST['status'] ?? 'Tersedia';

            if ($this->driverModel->isNikExists($nik, (int)$id)) {
                throw new Exception("NIK '$nik' sudah terdaftar pada sopir lain.");
            }

            // Handle photo update
            $newPhoto = $this->handleUpload();
            if ($newPhoto) {
                // Hapus foto lama
                $oldData = $this->driverModel->findById((int)$id);
                if ($oldData && $oldData['photo']) {
                    $oldPath = __DIR__ . '/../../admin/assets/uploads/drivers/' . $oldData['photo'];
                    if (file_exists($oldPath)) unlink($oldPath);
                }
                
                $sql = "UPDATE drivers SET name=?, phone=?, nik=?, driver_license_number=?, price_per_day=?, status=?, photo=? WHERE id=?";
                $stmt = $db->prepare($sql);
                $stmt->execute([$name, $phone, $nik, $sim, $price_per_day, $status, $newPhoto, $id]);
            } else {
                $sql = "UPDATE drivers SET name=?, phone=?, nik=?, driver_license_number=?, price_per_day=?, status=? WHERE id=?";
                $stmt = $db->prepare($sql);
                $stmt->execute([$name, $phone, $nik, $sim, $price_per_day, $status, $id]);
            }

            echo json_encode(['status' => true, 'message' => 'Data sopir berhasil diperbarui!']);
        } catch (Exception $e) {
            echo json_encode(['status' => false, 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    public function delete($id) {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $driver = $this->driverModel->findById((int)$id);
            if ($driver) {
                if ($driver['photo']) {
                    $filePath = __DIR__ . '/../../admin/assets/uploads/drivers/' . $driver['photo'];
                    if (file_exists($filePath)) unlink($filePath);
                }
                $this->driverModel->delete((int)$id);
            }
            echo json_encode(['status' => true, 'message' => 'Data sopir berhasil dihapus!']);
        } catch (Exception $e) {
            echo json_encode(['status' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()]);
        }
    }

    private function handleUpload(): ?string {
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
            $uploadDir = __DIR__ . '/../../admin/assets/uploads/drivers/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (in_array($_FILES['photo']['type'], $allowedTypes) && $_FILES['photo']['size'] < 3000000) {
                $fileExt = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                $newFileName = uniqid('driver_') . '_' . time() . '.' . $fileExt;
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $newFileName)) {
                    return $newFileName;
                }
            }
        }
        return null;
    }
}
