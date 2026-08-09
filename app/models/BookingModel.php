<?php
require_once __DIR__ . '/BaseModel.php';

class BookingModel extends BaseModel
{
    protected string $table = 'bookings';

    // Dipakai di Dashboard
    public function getTotalBookings(): int
    {
        $stmt = $this->db->query("SELECT COUNT(id) as total FROM {$this->table}");
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }

    public function getTotalRevenue(): float
    {
        $stmt = $this->db->query("SELECT SUM(total_price) as total FROM {$this->table} WHERE status = 'Selesai'");
        $row = $stmt->fetch();
        return (float) ($row['total'] ?? 0);
    }

    public function getTodayBookings(): array
    {
        $today = date('Y-m-d');
        $sql = "SELECT b.*, u.name as customer_name, c.name as car_name 
                FROM {$this->table} b
                JOIN customers cust ON b.customer_id = cust.id
                JOIN users u ON cust.user_id = u.id
                JOIN cars c ON b.car_id = c.id
                WHERE DATE(b.created_at) = :today
                ORDER BY b.id DESC LIMIT 5";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['today' => $today]);
        return $stmt->fetchAll();
    }

    // --- BARU: Dipakai di Modul Admin Booking ---
    public function getAllWithDetails(): array
    {
        $sql = "SELECT b.*, u.name as customer_name, u.phone as customer_phone, 
                       c.name as car_name, c.plate_number as car_plate
                FROM {$this->table} b
                JOIN customers cust ON b.customer_id = cust.id
                JOIN users u ON cust.user_id = u.id
                JOIN cars c ON b.car_id = c.id
                ORDER BY b.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByIdWithDetails(int $id): ?array
    {
        $sql = "SELECT b.*, u.name as customer_name, u.phone as customer_phone, u.email as customer_email, u.address as customer_address,
                       cust.nik as customer_nik, cust.driver_license_number as customer_sim,
                       c.name as car_name, c.plate_number as car_plate, c.brand as car_brand, c.price_per_day as car_price
                FROM {$this->table} b
                JOIN customers cust ON b.customer_id = cust.id
                JOIN users u ON cust.user_id = u.id
                JOIN cars c ON b.car_id = c.id
                WHERE b.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status = :status WHERE id = :id");
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }

    public function getRecentBookings(): array
    {
        // KOREKSI: Tambahkan alias "c.plate_number as car_plate" pada query
        $sql = "SELECT b.*, u.name as customer_name, c.name as car_name, c.plate_number as car_plate
                FROM {$this->table} b
                JOIN customers cust ON b.customer_id = cust.id
                JOIN users u ON cust.user_id = u.id
                JOIN cars c ON b.car_id = c.id
                ORDER BY b.created_at DESC LIMIT 5";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
