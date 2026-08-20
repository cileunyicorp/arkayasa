<?php

require_once __DIR__ . '/BaseModel.php';

class PartnerModel extends BaseModel
{
    protected string $table = 'rent_partners';

    /**
     * Ambil seluruh mitra yang berstatus 'Aktif'
     */
    public function getActivePartners(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE status = 'Aktif' ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTotal(): int
    {
        $stmt = $this->db->query("SELECT COUNT(id) as total FROM {$this->table}");
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }
}
