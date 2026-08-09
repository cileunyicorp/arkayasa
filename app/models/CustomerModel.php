<?php
require_once __DIR__ . '/BaseModel.php';

class CustomerModel extends BaseModel
{
    protected string $table = 'customers';

    // Ambil semua pelanggan (Join users & customers)
    public function getAllWithUsers(): array
    {
        $sql = "SELECT c.id as customer_id, c.nik, c.driver_license_number, 
                       u.id as user_id, u.name, u.email, u.phone, u.address, u.created_at
                FROM customers c
                JOIN users u ON c.user_id = u.id
                WHERE u.role_id = 3
                ORDER BY u.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Ambil detail 1 pelanggan
    public function getByIdWithUser(int $customer_id): ?array
    {
        $sql = "SELECT c.id as customer_id, c.nik, c.driver_license_number, 
                       u.id as user_id, u.name, u.email, u.phone, u.address 
                FROM customers c
                JOIN users u ON c.user_id = u.id
                WHERE c.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $customer_id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    // Cek apakah Email sudah dipakai (saat tambah/edit)
    public function isEmailExists(string $email, int $excludeUserId = 0): bool
    {
        $sql = "SELECT id FROM users WHERE email = :email AND id != :excludeId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email, 'excludeId' => $excludeUserId]);
        return $stmt->rowCount() > 0;
    }

    // Cek apakah NIK sudah dipakai
    public function isNikExists(string $nik, int $excludeCustomerId = 0): bool
    {
        $sql = "SELECT id FROM customers WHERE nik = :nik AND id != :excludeId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['nik' => $nik, 'excludeId' => $excludeCustomerId]);
        return $stmt->rowCount() > 0;
    }
    //
    public function getTotal(): int
    {
        $stmt = $this->db->query("SELECT COUNT(id) as total FROM {$this->table}");
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }
}
