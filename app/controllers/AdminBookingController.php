<?php
require_once __DIR__ . '/../models/BookingModel.php';
require_once __DIR__ . '/../models/CarModel.php';
require_once __DIR__ . '/../models/CustomerModel.php';

class AdminBookingController
{
    private BookingModel $bookingModel;
    private CarModel $carModel;
    private CustomerModel $customerModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->carModel = new CarModel();
        $this->customerModel = new CustomerModel();
    }

    // Tampilkan View Utama
    public function index()
    {
        $cars = $this->carModel->getAllWithDetails();
        $customers = $this->customerModel->getAllWithUsers();
        require_once __DIR__ . '/../../admin/booking/index.php';
    }

    // API: Get All Bookings dengan Format Datetime untuk Tampilan Tabel
    public function get_all()
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $bookings = $this->bookingModel->getAllWithDetails();

            foreach ($bookings as &$b) {
                $badgeClass = 'bg-slate-100 text-slate-600 dark:bg-slate-800/40 dark:text-slate-400';
                if ($b['status'] === 'Menunggu Pembayaran') $badgeClass = 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300';
                if ($b['status'] === 'Approve') $badgeClass = 'bg-teal-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300';
                if ($b['status'] === 'Reject') $badgeClass = 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-300';
                if ($b['status'] === 'Dipinjam') $badgeClass = 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300';
                if (in_array($b['status'], ['Selesai', 'Dikembalikan'])) $badgeClass = 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300';

                $b['status_html'] = "<span class=\"px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide rounded-lg {$badgeClass}\">" . htmlspecialchars($b['status']) . "</span>";
                $b['total_price_format'] = "Rp " . number_format($b['total_price'], 0, ',', '.');

                // Format Tampilan Tanggal & Jam (Contoh: 10 Aug 2026, 14:00)
                $startDateFormatted = date('d M Y, H:i', strtotime($b['start_date']));
                $endDateFormatted   = date('d M Y, H:i', strtotime($b['end_date']));

                $b['dates_format'] = "<b>{$startDateFormatted}</b><br><span class='text-xs text-slate-400'>s/d {$endDateFormatted} ({$b['total_days']} Hari)</span>";
            }

            echo json_encode(['data' => $bookings]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // API: Get 1 Booking Detail (Juga Menyiapkan Format Datetime untuk Input HTML)
    public function get_by_id($id)
    {
        header('Content-Type: application/json; charset=utf-8');
        $booking = $this->bookingModel->getByIdWithDetails((int)$id);
        if ($booking) {
            $booking['total_price_format'] = "Rp " . number_format($booking['total_price'], 0, ',', '.');
            $booking['start_date_format'] = date('d F Y, H:i', strtotime($booking['start_date']));
            $booking['end_date_format']   = date('d F Y, H:i', strtotime($booking['end_date']));

            // Format khusus untuk elemen <input type="datetime-local"> (YYYY-MM-DDTHH:MM)
            $booking['start_date_raw'] = date('Y-m-d\TH:i', strtotime($booking['start_date']));
            $booking['end_date_raw']   = date('Y-m-d\TH:i', strtotime($booking['end_date']));

            echo json_encode(['status' => true, 'data' => $booking]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Data pemesanan tidak ditemukan.']);
        }
    }

    // Simpan Booking Baru dengan Kalkulasi Jam Presisi
    public function store()
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $db = Database::getConnection();
            $db->beginTransaction();

            $customer_id = (int)($_POST['customer_id'] ?? 0);
            $car_id      = (int)($_POST['car_id'] ?? 0);
            $driver_id   = !empty($_POST['driver_id']) ? (int)$_POST['driver_id'] : null;

            $start_raw   = $_POST['start_date'] ?? '';
            $end_raw     = $_POST['end_date'] ?? '';
            $status      = $_POST['status'] ?? 'Reservasi';
            $notes       = htmlspecialchars($_POST['notes'] ?? '');

            // Clean Input Nominal
            $price_per_day = (float)preg_replace('/[^0-9]/', '', $_POST['price_per_day'] ?? '0');
            $driver_fee    = (float)preg_replace('/[^0-9]/', '', $_POST['driver_fee'] ?? '0');
            $discount      = (float)preg_replace('/[^0-9]/', '', $_POST['discount'] ?? '0');
            $deposit       = (float)preg_replace('/[^0-9]/', '', $_POST['deposit'] ?? '0');

            if ($customer_id === 0 || $car_id === 0 || empty($start_raw) || empty($end_raw)) {
                throw new Exception("Seluruh kolom wajib bertanda bintang (*) harus diisi!");
            }

            $start_date = str_replace('T', ' ', $start_raw);
            $end_date   = str_replace('T', ' ', $end_raw);

            // Hitung Durasi Hari
            $dStart = new DateTime($start_date);
            $dEnd   = new DateTime($end_date);
            $diff   = $dStart->diff($dEnd);
            $total_hours = ($diff->days * 24) + $diff->h + ($diff->i > 0 ? 1 : 0);
            $total_days  = (int)ceil($total_hours / 24);
            if ($total_days <= 0) $total_days = 1;

            // Hitung Biaya
            $biaya_sewa  = ($price_per_day * $total_days) + ($driver_fee * $total_days);
            $total_price = max(0, $biaya_sewa - $discount);

            $booking_code = 'TRX-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

            $sql = "INSERT INTO bookings (booking_code, customer_id, car_id, driver_id, start_date, end_date, total_days, total_price, driver_fee, discount, deposit, status, notes) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([$booking_code, $customer_id, $car_id, $driver_id, $start_date, $end_date, $total_days, $total_price, $driver_fee, $discount, $deposit, $status, $notes]);

            // Update status mobil
            if (in_array($status, ['Approve', 'Reservasi', 'Dipinjam'])) {
                $carStatus = ($status === 'Dipinjam') ? 'Disewa' : 'Reservasi';
                $stmtCar   = $db->prepare("UPDATE cars SET status = ? WHERE id = ?");
                $stmtCar->execute([$carStatus, $car_id]);
            }

            // Jika driver dipilih & disewa
            if ($driver_id && in_array($status, ['Dipinjam', 'Approve', 'Reservasi'])) {
                $stmtDrv = $db->prepare("UPDATE drivers SET status = 'Disewa' WHERE id = ?");
                $stmtDrv->execute([$driver_id]);
            }

            $db->commit();
            echo json_encode(['status' => true, 'message' => 'Pemesanan baru berhasil disimpan!']);
        } catch (Exception $e) {
            if (isset($db)) $db->rollBack();
            echo json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }


    // Edit Booking dengan Kalkulasi Jam Presisi
    public function update($id)
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $db = Database::getConnection();
            $db->beginTransaction();

            $customer_id = (int)($_POST['customer_id'] ?? 0);
            $car_id      = (int)($_POST['car_id'] ?? 0);
            $start_raw   = $_POST['start_date'] ?? '';
            $end_raw     = $_POST['end_date'] ?? '';
            $rate_type   = $_POST['rate_type'] ?? 'weekday';
            $status      = $_POST['status'] ?? 'Menunggu Pembayaran';
            $notes       = htmlspecialchars($_POST['notes'] ?? '');

            $start_date = str_replace('T', ' ', $start_raw);
            $end_date   = str_replace('T', ' ', $end_raw);

            $car = $this->carModel->findById($car_id);
            if (!$car) throw new Exception("Armada mobil tidak valid!");

            $dStart = new DateTime($start_date);
            $dEnd   = new DateTime($end_date);
            $diff   = $dStart->diff($dEnd);

            $total_hours = ($diff->days * 24) + $diff->h + ($diff->i > 0 ? 1 : 0);
            $total_days  = (int)ceil($total_hours / 24);
            if ($total_days <= 0) $total_days = 1;

            $price_unit = (float)$car['price_per_day'];
            if ($rate_type === 'weekend') $price_unit = (float)$car['price_per_weekend'];
            if ($rate_type === 'week')    $price_unit = (float)$car['price_per_week'] / 7;
            if ($rate_type === 'month')   $price_unit = (float)$car['price_per_month'] / 30;

            if ($price_unit <= 0) $price_unit = (float)$car['price_per_day'];

            $total_price = round($price_unit * $total_days);

            $oldBooking = $this->bookingModel->findById((int)$id);
            if ($oldBooking && $oldBooking['car_id'] != $car_id) {
                $stmtReset = $db->prepare("UPDATE cars SET status = 'Tersedia' WHERE id = ?");
                $stmtReset->execute([$oldBooking['car_id']]);
            }

            $sql = "UPDATE bookings SET customer_id=?, car_id=?, start_date=?, end_date=?, total_days=?, total_price=?, status=?, notes=? WHERE id=?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$customer_id, $car_id, $start_date, $end_date, $total_days, $total_price, $status, $notes, $id]);

            $carStatus = 'Tersedia';
            if ($status === 'Approve') $carStatus = 'Reservasi';
            if ($status === 'Dipinjam') $carStatus = 'Disewa';
            if (in_array($status, ['Selesai', 'Reject', 'Dikembalikan'])) $carStatus = 'Tersedia';

            $stmtCar = $db->prepare("UPDATE cars SET status = ? WHERE id = ?");
            $stmtCar->execute([$carStatus, $car_id]);

            $db->commit();
            echo json_encode(['status' => true, 'message' => 'Transaksi pemesanan berhasil diperbarui!']);
        } catch (Exception $e) {
            if (isset($db)) $db->rollBack();
            echo json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    // Hapus Pemesanan
    public function delete($id)
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $db = Database::getConnection();
            $db->beginTransaction();

            $booking = $this->bookingModel->findById((int)$id);
            if ($booking) {
                // Kembalikan status mobil yang bersangkutan menjadi Tersedia
                $stmtCar = $db->prepare("UPDATE cars SET status = 'Tersedia' WHERE id = ?");
                $stmtCar->execute([$booking['car_id']]);

                // Hapus data booking
                $this->bookingModel->delete((int)$id);
            }

            $db->commit();
            echo json_encode(['status' => true, 'message' => 'Transaksi pemesanan berhasil dihapus!']);
        } catch (Exception $e) {
            if (isset($db)) $db->rollBack();
            echo json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    // Update Status Approval (Fungsi lama tetap dipertahankan)
    public function update_status()
    {
        header('Content-Type: application/json; charset=utf-8');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $db = Database::getConnection();
                $db->beginTransaction();

                $id = (int)($_POST['id'] ?? 0);
                $status = $_POST['status'] ?? '';

                $booking = $this->bookingModel->findById($id);
                if (!$booking) throw new Exception("Pemesanan tidak valid.");

                $this->bookingModel->updateStatus($id, $status);

                $carId = $booking['car_id'];
                $carStatus = 'Tersedia';
                if ($status === 'Approve') $carStatus = 'Reservasi';
                if ($status === 'Dipinjam') $carStatus = 'Disewa';
                if ($status === 'Selesai' || $status === 'Reject' || $status === 'Dikembalikan') $carStatus = 'Tersedia';

                $stmt = $db->prepare("UPDATE cars SET status = ? WHERE id = ?");
                $stmt->execute([$carStatus, $carId]);

                $db->commit();
                echo json_encode(['status' => true, 'message' => 'Status pemesanan berhasil diperbarui menjadi ' . $status]);
            } catch (Exception $e) {
                if (isset($db)) $db->rollBack();
                echo json_encode(['status' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    // Menampilkan Halaman Cetak Invoice
    public function invoice($id)
    {
        $booking = $this->bookingModel->getByIdWithDetails((int)$id);

        if (!$booking) {
            die("Data pesanan tidak ditemukan.");
        }

        // Hitung subtotal dan pajak (opsional, jika Anda ingin menampilkan pajak)
        // Kita asumsikan total_price adalah harga final (sudah termasuk semuanya)

        require_once __DIR__ . '/../../admin/booking/invoice.php';
    }
}
