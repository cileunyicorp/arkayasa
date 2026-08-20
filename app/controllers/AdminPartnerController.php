<?php

require_once __DIR__ . '/../models/PartnerModel.php';

class AdminPartnerController
{
    private PartnerModel $partnerModel;

    public function __construct()
    {
        $this->partnerModel = new PartnerModel();
    }

    public function index()
    {
        require_once __DIR__ . '/../../admin/partner/index.php';
    }

    public function get_all()
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $partners = $this->partnerModel->findAll();

            foreach ($partners as &$p) {
                $statusClass = ($p['status'] === 'Aktif')
                    ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300'
                    : 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-400';

                $p['status_html'] = "<span class=\"px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide rounded-lg {$statusClass}\">" . htmlspecialchars($p['status']) . "</span>";
            }

            echo json_encode(['data' => $partners]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function get_by_id($id)
    {
        header('Content-Type: application/json; charset=utf-8');
        $partner = $this->partnerModel->findById((int)$id);
        if ($partner) {
            echo json_encode(['status' => true, 'data' => $partner]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Data mitra tidak ditemukan!']);
        }
    }

    public function store()
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $db = Database::getConnection();

            $name            = htmlspecialchars($_POST['name'] ?? '');
            $company_name    = htmlspecialchars($_POST['company_name'] ?? '');
            $phone           = htmlspecialchars($_POST['phone'] ?? '');
            $email           = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $address         = htmlspecialchars($_POST['address'] ?? '');
            $status          = $_POST['status'] ?? 'Aktif';

            $sql = "INSERT INTO rent_partners (name, company_name, phone, email, address,status) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([$name, $company_name, $phone, $email, $address, $status]);

            echo json_encode(['status' => true, 'message' => 'Mitra baru berhasil ditambahkan!']);
        } catch (Exception $e) {
            echo json_encode(['status' => false, 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    public function update($id)
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $db = Database::getConnection();

            $name            = htmlspecialchars($_POST['name'] ?? '');
            $company_name    = htmlspecialchars($_POST['company_name'] ?? '');
            $phone           = htmlspecialchars($_POST['phone'] ?? '');
            $email           = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $address         = htmlspecialchars($_POST['address'] ?? '');
            $status          = $_POST['status'] ?? 'Aktif';

            $sql = "UPDATE rent_partners SET name=?, company_name=?, phone=?, email=?, address=?,status=? WHERE id=?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$name, $company_name, $phone, $email, $address, $status, $id]);

            echo json_encode(['status' => true, 'message' => 'Data mitra berhasil diperbarui!']);
        } catch (Exception $e) {
            echo json_encode(['status' => false, 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $this->partnerModel->delete((int)$id);
            echo json_encode(['status' => true, 'message' => 'Mitra berhasil dihapus!']);
        } catch (Exception $e) {
            echo json_encode(['status' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()]);
        }
    }
}
