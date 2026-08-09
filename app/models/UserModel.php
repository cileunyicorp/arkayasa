<?php
require_once __DIR__ . '/BaseModel.php';

class UserModel extends BaseModel
{
    protected string $table = 'users';

    // Mencari user berdasarkan email (Aman dengan Prepared Statement)
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    // Tambahkan method ini di bawah findByEmail()
    public function getTotalCustomers(): int
    {
        $stmt = $this->db->query("SELECT COUNT(id) as total FROM {$this->table} WHERE role_id = 3");
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }
}
