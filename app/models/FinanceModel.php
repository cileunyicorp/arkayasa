<?php
require_once __DIR__ . '/BaseModel.php';

class FinanceModel extends BaseModel {
    protected string $table = 'finances';

    public function getSummary(): array {
        $sql = "SELECT 
                    SUM(CASE WHEN type = 'Pemasukan' THEN amount ELSE 0 END) as total_income,
                    SUM(CASE WHEN type = 'Pengeluaran' THEN amount ELSE 0 END) as total_expense
                FROM {$this->table}";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        
        $income = (float)($result['total_income'] ?? 0);
        $expense = (float)($result['total_expense'] ?? 0);
        
        return [
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense
        ];
    }
}
