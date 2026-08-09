<?php
require_once __DIR__ . '/BaseModel.php';

class ClientModel extends BaseModel {
    protected string $table = 'clients';

    /**
     * Mengambil semua mitra/client yang berstatus aktif
     */
    public function getActiveClients(): array {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
