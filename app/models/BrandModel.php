<?php

require_once __DIR__ . '/BaseModel.php';

class BrandModel extends BaseModel
{
    protected string $table = 'car_brands';

    /**
     * Ambil seluruh brand yang berstatus Aktif
     */
    public function getActiveBrands(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE status = 'Aktif' ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Cek apakah nama brand sudah terdaftar
     */
    public function isNameExists(string $name, int $excludeId = 0): bool
    {
        $sql = "SELECT id FROM {$this->table} WHERE LOWER(name) = LOWER(:name) AND id != :excludeId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['name' => $name, 'excludeId' => $excludeId]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Hitung total brand
     */
    public function getTotal(): int
    {
        $stmt = $this->db->query("SELECT COUNT(id) as total FROM {$this->table}");
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }
}
