<?php
require_once __DIR__ . '/BaseModel.php';

class DriverModel extends BaseModel {
    protected string $table = 'drivers';

    public function isNikExists(string $nik, int $excludeId = 0): bool {
        $sql = "SELECT id FROM {$this->table} WHERE nik = :nik AND id != :excludeId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['nik' => $nik, 'excludeId' => $excludeId]);
        return $stmt->rowCount() > 0;
    }
}
