<?php
require_once __DIR__ . '/../models/FinanceModel.php';

class AdminFinanceController {
    private FinanceModel $financeModel;

    public function __construct() {
        $this->financeModel = new FinanceModel();
    }

    public function index() {
        // Ambil metrik untuk kartu di bagian atas
        $summary = $this->financeModel->getSummary();
        require_once __DIR__ . '/../../admin/keuangan/index.php';
    }

    public function get_all() {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $records = $this->financeModel->findAll();
            
            foreach ($records as &$r) {
                $r['date_format'] = date('d M Y', strtotime($r['transaction_date']));
                
                if ($r['type'] === 'Pemasukan') {
                    $r['amount_format'] = "<span class='text-emerald-600 dark:text-emerald-400 font-extrabold'>+ Rp " . number_format($r['amount'], 0, ',', '.') . "</span>";
                    $r['type_html'] = "<span class='bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider'>Pemasukan</span>";
                } else {
                    $r['amount_format'] = "<span class='text-rose-600 dark:text-rose-400 font-extrabold'>- Rp " . number_format($r['amount'], 0, ',', '.') . "</span>";
                    $r['type_html'] = "<span class='bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-300 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider'>Pengeluaran</span>";
                }
            }

            echo json_encode(['data' => $records]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function get_by_id($id) {
        header('Content-Type: application/json; charset=utf-8');
        $record = $this->financeModel->findById((int)$id);
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
            
            $transaction_date = $_POST['transaction_date'] ?? date('Y-m-d');
            $type = $_POST['type'] ?? 'Masuk';
            $category = htmlspecialchars($_POST['category'] ?? '');
            $amount = (float)preg_replace('/[^0-9]/', '', $_POST['amount'] ?? '0');
            $description = htmlspecialchars($_POST['description'] ?? '');

            $sql = "INSERT INTO finances (transaction_date, type, category, amount, description) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([$transaction_date, $type, $category, $amount, $description]);

            echo json_encode(['status' => true, 'message' => 'Catatan keuangan berhasil ditambahkan!']);
        } catch (Exception $e) {
            echo json_encode(['status' => false, 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    public function update($id) {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $db = Database::getConnection();
            
            $transaction_date = $_POST['transaction_date'] ?? date('Y-m-d');
            $type = $_POST['type'] ?? 'Masuk';
            $category = htmlspecialchars($_POST['category'] ?? '');
            $amount = (float)preg_replace('/[^0-9]/', '', $_POST['amount'] ?? '0');
            $description = htmlspecialchars($_POST['description'] ?? '');

            $sql = "UPDATE finances SET transaction_date=?, type=?, category=?, amount=?, description=? WHERE id=?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$transaction_date, $type, $category, $amount, $description, $id]);

            echo json_encode(['status' => true, 'message' => 'Data keuangan berhasil diperbarui!']);
        } catch (Exception $e) {
            echo json_encode(['status' => false, 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }


    public function delete($id) {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $this->financeModel->delete((int)$id);
            echo json_encode(['status' => true, 'message' => 'Transaksi dihapus!']);
        } catch (Exception $e) {
            echo json_encode(['status' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()]);
        }
    }
}
