<?php
require_once __DIR__ . '/../models/MaintenanceModel.php';
require_once __DIR__ . '/../models/CarModel.php';

class AdminMaintenanceController {
    private MaintenanceModel $maintenanceModel;
    private CarModel $carModel;

    public function __construct() {
        $this->maintenanceModel = new MaintenanceModel();
        $this->carModel = new CarModel();
    }

    public function index() {
        $cars = $this->carModel->findAll(); // Untuk dropdown pilihan mobil
        require_once __DIR__ . '/../../admin/perawatan/index.php';
    }

    public function get_all() {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $records = $this->maintenanceModel->getAllWithCar();
            
            foreach ($records as &$r) {
                $statusClass = 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400'; // Scheduled
                if ($r['status'] === 'In Progress') $statusClass = 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300';
                if ($r['status'] === 'Completed') $statusClass = 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300';
                
                $r['status_html'] = "<span class=\"px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide rounded-lg {$statusClass}\">" . htmlspecialchars($r['status']) . "</span>";
                $r['cost_format'] = "Rp " . number_format($r['cost'], 0, ',', '.');
                $r['date_format'] = date('d M Y', strtotime($r['maintenance_date']));
            }

            echo json_encode(['data' => $records]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function get_by_id($id) {
        header('Content-Type: application/json; charset=utf-8');
        $record = $this->maintenanceModel->getByIdWithCar((int)$id);
        if ($record) {
            echo json_encode(['status' => true, 'data' => $record]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Data tidak ditemukan!']);
        }
    }

    public function store() {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $db = Database::getConnection();
            $db->beginTransaction();
            
            $car_id = (int)($_POST['car_id'] ?? 0);
            $maintenance_date = $_POST['maintenance_date'] ?? date('Y-m-d');
            $title = htmlspecialchars($_POST['title'] ?? '');
            $description = htmlspecialchars($_POST['description'] ?? '');
            $cost = (float)($_POST['cost'] ?? 0);
            $status = $_POST['status'] ?? 'Scheduled';

            $sql = "INSERT INTO maintenances (car_id, maintenance_date, title, description, cost, status) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([$car_id, $maintenance_date, $title, $description, $cost, $status]);

            // Smart Logic: Update status mobil
            $this->updateCarStatus($car_id, $status, $db);

            $db->commit();
            echo json_encode(['status' => true, 'message' => 'Data perawatan berhasil dicatat!']);
        } catch (Exception $e) {
            if (isset($db)) $db->rollBack();
            echo json_encode(['status' => false, 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    public function update($id) {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $db = Database::getConnection();
            $db->beginTransaction();
            
            $car_id = (int)($_POST['car_id'] ?? 0);
            $maintenance_date = $_POST['maintenance_date'] ?? date('Y-m-d');
            $title = htmlspecialchars($_POST['title'] ?? '');
            $description = htmlspecialchars($_POST['description'] ?? '');
            $cost = (float)($_POST['cost'] ?? 0);
            $status = $_POST['status'] ?? 'Scheduled';

            $sql = "UPDATE maintenances SET car_id=?, maintenance_date=?, title=?, description=?, cost=?, status=? WHERE id=?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$car_id, $maintenance_date, $title, $description, $cost, $status, $id]);

            // Smart Logic: Update status mobil
            $this->updateCarStatus($car_id, $status, $db);

            $db->commit();
            echo json_encode(['status' => true, 'message' => 'Data perawatan berhasil diperbarui!']);
        } catch (Exception $e) {
            if (isset($db)) $db->rollBack();
            echo json_encode(['status' => false, 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    public function delete($id) {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $this->maintenanceModel->delete((int)$id);
            echo json_encode(['status' => true, 'message' => 'Catatan perawatan dihapus!']);
        } catch (Exception $e) {
            echo json_encode(['status' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()]);
        }
    }

    // Helper untuk mengubah status mobil otomatis berdasarkan status perbaikan
    private function updateCarStatus($car_id, $maintenance_status, $db) {
        if ($maintenance_status === 'In Progress') {
            $stmt = $db->prepare("UPDATE cars SET status = 'Maintenance' WHERE id = ?");
            $stmt->execute([$car_id]);
        } elseif ($maintenance_status === 'Completed') {
            // Cek apakah mobil masih berstatus maintenance, jika ya kembalikan ke Tersedia
            $stmt = $db->prepare("UPDATE cars SET status = 'Tersedia' WHERE id = ? AND status = 'Maintenance'");
            $stmt->execute([$car_id]);
        }
    }
}
