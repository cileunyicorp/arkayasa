<?php

require_once __DIR__ . '/../models/BookingModel.php';
require_once __DIR__ . '/../models/CarModel.php';
require_once __DIR__ . '/../models/CustomerModel.php';
require_once __DIR__ . '/../models/DriverModel.php';

class AdminBookingController
{
    private BookingModel $bookingModel;
    private CarModel $carModel;
    private CustomerModel $customerModel;
    private DriverModel $driverModel;

    public function __construct()
    {
        $this->bookingModel  = new BookingModel();
        $this->carModel      = new CarModel();
        $this->customerModel = new CustomerModel();
        $this->driverModel   = new DriverModel();
    }

    // Tampilkan View Utama
    public function index()
    {
        $cars      = $this->carModel->getAllWithDetails();
        $customers = $this->customerModel->getAllWithUsers();
        $drivers   = $this->driverModel->getAllAvailable();

        require_once __DIR__ . '/../../admin/booking/index.php';
    }

    // API: Get All Bookings untuk DataTables
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

                // Format Tampilan Tanggal & Jam
                $startDateFormatted = date('d M Y, H:i', strtotime($b['start_date']));
                $endDateFormatted   = date('d M Y, H:i', strtotime($b['end_date']));

                $b['dates_format'] = "<b>{$startDateFormatted}</b><br><span class='text-xs text-slate-400'>s/d {$endDateFormatted} ({$b['total_days']} Hari)</span>";
                
                // URL Berkas Jaminan
                $b['guarantee_file_url'] = !empty($b['guarantee_file']) 
                    ? base_url('admin/assets/uploads/bookings/' . htmlspecialchars($b['guarantee_file'])) 
                    : null;
            }

            echo json_encode(['data' => $bookings]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // API: Get 1 Booking Detail
    public function get_by_id($id)
    {
        header('Content-Type: application/json; charset=utf-8');
        $booking = $this->bookingModel->getByIdWithDetails((int)$id);
        if ($booking) {
            $booking['total_price_format'] = "Rp " . number_format($booking['total_price'], 0, ',', '.');
            $booking['start_date_format'] = date('d F Y, H:i', strtotime($booking['start_date']));
            $booking['end_date_format']   = date('d F Y, H:i', strtotime($booking['end_date']));

            // Format khusus untuk input datetime-local
            $booking['start_date_raw'] = date('Y-m-d\TH:i', strtotime($booking['start_date']));
            $booking['end_date_raw']   = date('Y-m-d\TH:i', strtotime($booking['end_date']));

            // URL Berkas Jaminan
            $booking['guarantee_file_url'] = !empty($booking['guarantee_file']) 
                ? base_url('admin/assets/uploads/bookings/' . htmlspecialchars($booking['guarantee_file'])) 
                : null;

            echo json_encode(['status' => true, 'data' => $booking]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Data pemesanan tidak ditemukan.']);
        }
    }

    // Simpan Booking Baru
    public function store()
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $db = Database::getConnection();

            $customer_id = (int)($_POST['customer_id'] ?? 0);
            $car_id      = (int)($_POST['car_id'] ?? 0);
            $driver_id   = (!empty($_POST['driver_id']) && (int)$_POST['driver_id'] > 0) ? (int)$_POST['driver_id'] : null;

            $start_raw   = $_POST['start_date'] ?? '';
            $end_raw     = $_POST['end_date'] ?? '';
            $status      = $_POST['status'] ?? 'Menunggu Pembayaran';
            $notes       = htmlspecialchars($_POST['notes'] ?? '');

            // Clean Input Nominal
            $price_per_day = (float)preg_replace('/[^0-9]/', '', $_POST['price_per_day'] ?? '0');
            $driver_fee    = (float)preg_replace('/[^0-9]/', '', $_POST['driver_fee'] ?? '0');
            $discount      = (float)preg_replace('/[^0-9]/', '', $_POST['discount'] ?? '0');
            $deposit       = (float)preg_replace('/[^0-9]/', '', $_POST['deposit'] ?? '0');

            if ($customer_id === 0 || $car_id === 0 || empty($start_raw) || empty($end_raw)) {
                throw new Exception("Seluruh kolom wajib bertanda bintang (*) harus diisi!");
            }

            // Process Upload Berkas Jaminan (Max 2MB)
            $guaranteeFile = $this->handleGuaranteeUpload();

            $db->beginTransaction();

            $start_date = str_replace('T', ' ', $start_raw);
            $end_date   = str_replace('T', ' ', $end_raw);

            // Hitung Durasi Hari Presisi
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

            $sql = "INSERT INTO bookings (booking_code, customer_id, car_id, driver_id, start_date, end_date, total_days, total_price, driver_fee, discount, deposit, status, notes, guarantee_file) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([$booking_code, $customer_id, $car_id, $driver_id, $start_date, $end_date, $total_days, $total_price, $driver_fee, $discount, $deposit, $status, $notes, $guaranteeFile]);

            // Update Status Mobil
            if (in_array($status, ['Approve', 'Reservasi', 'Dipinjam'])) {
                $carStatus = ($status === 'Dipinjam') ? 'Disewa' : 'Reservasi';
                $stmtCar   = $db->prepare("UPDATE cars SET status = ? WHERE id = ?");
                $stmtCar->execute([$carStatus, $car_id]);
            }

            // Update Status Driver
            if ($driver_id && in_array($status, ['Dipinjam', 'Approve', 'Reservasi'])) {
                $stmtDrv = $db->prepare("UPDATE drivers SET status = 'Disewa' WHERE id = ?");
                $stmtDrv->execute([$driver_id]);
            }

            $db->commit();
            echo json_encode(['status' => true, 'message' => 'Pemesanan baru berhasil disimpan!']);
        } catch (Exception $e) {
            if (isset($db) && $db->inTransaction()) $db->rollBack();
            echo json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    // Edit Pemesanan
    public function update($id)
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $db = Database::getConnection();

            $customer_id   = (int)($_POST['customer_id'] ?? 0);
            $car_id        = (int)($_POST['car_id'] ?? 0);
            $driver_id     = (!empty($_POST['driver_id']) && (int)$_POST['driver_id'] > 0) ? (int)$_POST['driver_id'] : null;

            $start_raw     = $_POST['start_date'] ?? '';
            $end_raw       = $_POST['end_date'] ?? '';
            $status        = $_POST['status'] ?? 'Menunggu Pembayaran';
            $notes         = htmlspecialchars($_POST['notes'] ?? '');

            $price_per_day = (float)preg_replace('/[^0-9]/', '', $_POST['price_per_day'] ?? '0');
            $driver_fee    = (float)preg_replace('/[^0-9]/', '', $_POST['driver_fee'] ?? '0');
            $discount      = (float)preg_replace('/[^0-9]/', '', $_POST['discount'] ?? '0');
            $deposit       = (float)preg_replace('/[^0-9]/', '', $_POST['deposit'] ?? '0');

            // Ambil data transaksi lama
            $oldBooking = $this->bookingModel->findById((int)$id);
            if (!$oldBooking) throw new Exception("Data transaksi tidak ditemukan!");

            // Process Upload Berkas Jaminan Baru jika ada
            $newGuaranteeFile = $this->handleGuaranteeUpload($oldBooking['guarantee_file'] ?? null);

            $db->beginTransaction();

            $start_date = str_replace('T', ' ', $start_raw);
            $end_date   = str_replace('T', ' ', $end_raw);

            $dStart = new DateTime($start_date);
            $dEnd   = new DateTime($end_date);
            $diff   = $dStart->diff($dEnd);

            $total_hours = ($diff->days * 24) + $diff->h + ($diff->i > 0 ? 1 : 0);
            $total_days  = (int)ceil($total_hours / 24);
            if ($total_days <= 0) $total_days = 1;

            $biaya_sewa  = ($price_per_day * $total_days) + ($driver_fee * $total_days);
            $total_price = max(0, $biaya_sewa - $discount);

            // Reset status mobil lama jika mobil diganti
            if ($oldBooking['car_id'] != $car_id) {
                $stmtResetCar = $db->prepare("UPDATE cars SET status = 'Tersedia' WHERE id = ?");
                $stmtResetCar->execute([$oldBooking['car_id']]);
            }

            // Reset status driver lama jika driver diganti/dicopot
            if ($oldBooking['driver_id'] && $oldBooking['driver_id'] != $driver_id) {
                $stmtResetDrv = $db->prepare("UPDATE drivers SET status = 'Tersedia' WHERE id = ?");
                $stmtResetDrv->execute([$oldBooking['driver_id']]);
            }

            $sql = "UPDATE bookings SET customer_id=?, car_id=?, driver_id=?, start_date=?, end_date=?, total_days=?, total_price=?, driver_fee=?, discount=?, deposit=?, status=?, notes=?, guarantee_file=? WHERE id=?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$customer_id, $car_id, $driver_id, $start_date, $end_date, $total_days, $total_price, $driver_fee, $discount, $deposit, $status, $notes, $newGuaranteeFile, $id]);

            // Update status mobil baru
            $carStatus = 'Tersedia';
            if (in_array($status, ['Approve', 'Reservasi'])) $carStatus = 'Reservasi';
            if ($status === 'Dipinjam') $carStatus = 'Disewa';
            if (in_array($status, ['Selesai', 'Reject', 'Dikembalikan'])) $carStatus = 'Tersedia';

            $stmtCar = $db->prepare("UPDATE cars SET status = ? WHERE id = ?");
            $stmtCar->execute([$carStatus, $car_id]);

            // Update status driver baru
            if ($driver_id) {
                $drvStatus = in_array($status, ['Selesai', 'Reject', 'Dikembalikan']) ? 'Tersedia' : 'Disewa';
                $stmtDrv = $db->prepare("UPDATE drivers SET status = ? WHERE id = ?");
                $stmtDrv->execute([$drvStatus, $driver_id]);
            }

            $db->commit();
            echo json_encode(['status' => true, 'message' => 'Transaksi pemesanan berhasil diperbarui!']);
        } catch (Exception $e) {
            if (isset($db) && $db->inTransaction()) $db->rollBack();
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
                // Hapus berkas fisik jaminan jika ada
                if (!empty($booking['guarantee_file'])) {
                    $filePath = __DIR__ . '/../../admin/assets/uploads/bookings/' . $booking['guarantee_file'];
                    if (file_exists($filePath) && is_file($filePath)) unlink($filePath);
                }

                // Kembalikan status mobil menjadi Tersedia
                $stmtCar = $db->prepare("UPDATE cars SET status = 'Tersedia' WHERE id = ?");
                $stmtCar->execute([$booking['car_id']]);

                // Kembalikan status driver menjadi Tersedia
                if ($booking['driver_id']) {
                    $stmtDrv = $db->prepare("UPDATE drivers SET status = 'Tersedia' WHERE id = ?");
                    $stmtDrv->execute([$booking['driver_id']]);
                }

                // Hapus data booking
                $this->bookingModel->delete((int)$id);
            }

            $db->commit();
            echo json_encode(['status' => true, 'message' => 'Transaksi pemesanan berhasil dihapus!']);
        } catch (Exception $e) {
            if (isset($db) && $db->inTransaction()) $db->rollBack();
            echo json_encode(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    // Update Status Approval / Alur Pemesanan
    public function update_status()
    {
        header('Content-Type: application/json; charset=utf-8');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $db = Database::getConnection();
                $db->beginTransaction();

                $id     = (int)($_POST['id'] ?? 0);
                $status = $_POST['status'] ?? '';

                $booking = $this->bookingModel->findById($id);
                if (!$booking) throw new Exception("Pemesanan tidak valid.");

                $this->bookingModel->updateStatus($id, $status);

                $carId = $booking['car_id'];
                $driverId = $booking['driver_id'];

                $carStatus = 'Tersedia';
                if ($status === 'Approve') $carStatus = 'Reservasi';
                if ($status === 'Dipinjam') $carStatus = 'Disewa';
                if (in_array($status, ['Selesai', 'Reject', 'Dikembalikan'])) $carStatus = 'Tersedia';

                $stmt = $db->prepare("UPDATE cars SET status = ? WHERE id = ?");
                $stmt->execute([$carStatus, $carId]);

                // Update Driver Status
                if ($driverId) {
                    $drvStatus = in_array($status, ['Selesai', 'Reject', 'Dikembalikan']) ? 'Tersedia' : 'Disewa';
                    $stmtDrv = $db->prepare("UPDATE drivers SET status = ? WHERE id = ?");
                    $stmtDrv->execute([$drvStatus, $driverId]);
                }

                $db->commit();
                echo json_encode(['status' => true, 'message' => 'Status pemesanan berhasil diperbarui menjadi ' . $status]);
            } catch (Exception $e) {
                if (isset($db) && $db->inTransaction()) $db->rollBack();
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

        require_once __DIR__ . '/../../admin/booking/invoice.php';
    }

    /**
     * Helper Privat untuk Pengunggahan Berkas Foto/Dokumen Jaminan (Max 2MB)
     */
    private function handleGuaranteeUpload(?string $existingFile = null): ?string
    {
        if (isset($_FILES['guarantee_file']) && $_FILES['guarantee_file']['error'] === 0) {
            $uploadDir = __DIR__ . '/../../admin/assets/uploads/bookings/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            // Batas Maksimal 2 MB (2,000,000 Byte)
            if ($_FILES['guarantee_file']['size'] > 2000000) {
                throw new Exception("Ukuran berkas jaminan terlalu besar! Maksimal 2 MB.");
            }

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
            $fileExt = strtolower(pathinfo($_FILES['guarantee_file']['name'], PATHINFO_EXTENSION));

            if (!in_array($fileExt, $allowedExtensions)) {
                throw new Exception("Format berkas jaminan tidak didukung! Format yang diperbolehkan: JPG, PNG, WEBP, atau PDF.");
            }

            // Hapus berkas lama jika diganti
            if (!empty($existingFile) && file_exists($uploadDir . $existingFile)) {
                unlink($uploadDir . $existingFile);
            }

            $newFileName = 'guarantee_' . uniqid() . '_' . time() . '.' . $fileExt;
            if (move_uploaded_file($_FILES['guarantee_file']['tmp_name'], $uploadDir . $newFileName)) {
                return $newFileName;
            }
        }

        return $existingFile;
    }
}
