<?php
require_once __DIR__ . '/BaseModel.php';

class CarModel extends BaseModel
{
    protected string $table = 'cars';

    // Method sebelumnya (getTotalCars) biarkan saja...
    public function getTotalCars(): int
    {
        $stmt = $this->db->query("SELECT COUNT(id) as total FROM {$this->table}");
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }

    // --- TAMBAHKAN METHOD INI ---
    public function getAllWithDetails(): array
    {
        // Menggunakan LEFT JOIN untuk kategori dan Sub-Query untuk mengambil 1 gambar utama
        $sql = "SELECT c.*, cat.name as category_name, 
                       (SELECT image_path FROM car_images WHERE car_id = c.id AND is_primary = 1 LIMIT 1) as primary_image
                FROM {$this->table} c
                LEFT JOIN categories cat ON c.category_id = cat.id
                ORDER BY c.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Tambahkan method ini di dalam class CarModel
    public function getImages(int $car_id): array
    {
        $stmt = $this->db->prepare("SELECT * FROM car_images WHERE car_id = ?");
        $stmt->execute([$car_id]);
        return $stmt->fetchAll();
    }
}
