<?php
require_once __DIR__ . '/BaseModel.php';

class MaintenanceModel extends BaseModel {
    protected string $table = 'maintenances';

    public function getAllWithCar(): array {
        $sql = "SELECT m.*, c.name as car_name, c.plate_number, c.brand 
                FROM {$this->table} m
                JOIN cars c ON m.car_id = c.id
                ORDER BY m.maintenance_date DESC, m.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByIdWithCar(int $id): ?array {
        $sql = "SELECT m.*, c.name as car_name FROM {$this->table} m JOIN cars c ON m.car_id = c.id WHERE m.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
