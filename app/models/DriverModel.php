<?php

require_once __DIR__ . '/BaseModel.php';

class DriverModel extends BaseModel 
{
    protected string $table = 'drivers';

    /**
     * Cek apakah NIK sudah digunakan oleh driver lain
     */
    public function isNikExists(string $nik, int $excludeId = 0): bool 
    {
        $sql = "SELECT id FROM {$this->table} WHERE nik = :nik AND id != :excludeId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['nik' => $nik, 'excludeId' => $excludeId]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Ambil seluruh driver aktif (status bukan 'Nonaktif') untuk dropdown pilihan booking
     */
    public function getAllAvailable(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE status != 'Nonaktif' ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Hitung total seluruh driver yang terdaftar
     */
    public function getTotal(): int
    {
        $stmt = $this->db->query("SELECT COUNT(id) as total FROM {$this->table}");
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }
}
