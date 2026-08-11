<?php

require_once __DIR__ . '/BaseModel.php';

class BookingModel extends BaseModel
{
    protected string $table = 'bookings';

    /**
     * Total seluruh transaksi booking (untuk statistik dashboard)
     */
    public function getTotalBookings(): int
    {
        $stmt = $this->db->query("SELECT COUNT(id) as total FROM {$this->table}");
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }

    /**
     * Total pendapatan dari transaksi berstatus 'Selesai'
     */
    public function getTotalRevenue(): float
    {
        $stmt = $this->db->query("SELECT SUM(total_price) as total FROM {$this->table} WHERE status = 'Selesai'");
        $row = $stmt->fetch();
        return (float) ($row['total'] ?? 0);
    }

    /**
     * Ambil booking hari ini untuk widget dashboard
     */
    public function getTodayBookings(): array
    {
        $today = date('Y-m-d');
        $sql = "SELECT b.*, u.name as customer_name, c.name as car_name, d.name as driver_name 
                FROM {$this->table} b
                JOIN customers cust ON b.customer_id = cust.id
                JOIN users u ON cust.user_id = u.id
                JOIN cars c ON b.car_id = c.id
                LEFT JOIN drivers d ON b.driver_id = d.id
                WHERE DATE(b.created_at) = :today
                ORDER BY b.id DESC LIMIT 5";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['today' => $today]);
        return $stmt->fetchAll();
    }

    /**
     * Ambil seluruh transaksi booking beserta detail pelanggan, mobil, dan driver
     */
    public function getAllWithDetails(): array
    {
        $sql = "SELECT b.*, u.name as customer_name, u.phone as customer_phone, 
                       c.name as car_name, c.plate_number as car_plate,
                       d.name as driver_name, d.phone as driver_phone
                FROM {$this->table} b
                JOIN customers cust ON b.customer_id = cust.id
                JOIN users u ON cust.user_id = u.id
                JOIN cars c ON b.car_id = c.id
                LEFT JOIN drivers d ON b.driver_id = d.id
                ORDER BY b.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Ambil detail 1 transaksi booking lengkap (dipakai di modal detail & cetak invoice)
     */
    public function getByIdWithDetails(int $id): ?array
    {
        $sql = "SELECT b.*, u.name as customer_name, u.phone as customer_phone, u.email as customer_email, u.address as customer_address,
                       cust.nik as customer_nik, cust.driver_license_number as customer_sim,
                       c.name as car_name, c.plate_number as car_plate, c.brand as car_brand, 
                       c.price_per_day as car_price, c.price_per_day as price_per_day,
                       d.name as driver_name, d.phone as driver_phone, d.price_per_day as driver_rate
                FROM {$this->table} b
                JOIN customers cust ON b.customer_id = cust.id
                JOIN users u ON cust.user_id = u.id
                JOIN cars c ON b.car_id = c.id
                LEFT JOIN drivers d ON b.driver_id = d.id
                WHERE b.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Update status alur transaksi booking
     */
    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status = :status WHERE id = :id");
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }

    /**
     * Ambil 5 transaksi terbaru untuk tabel ringkasan dashboard
     */
    public function getRecentBookings(): array
    {
        $sql = "SELECT b.*, u.name as customer_name, c.name as car_name, c.plate_number as car_plate, d.name as driver_name
                FROM {$this->table} b
                JOIN customers cust ON b.customer_id = cust.id
                JOIN users u ON cust.user_id = u.id
                JOIN cars c ON b.car_id = c.id
                LEFT JOIN drivers d ON b.driver_id = d.id
                ORDER BY b.created_at DESC LIMIT 5";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
