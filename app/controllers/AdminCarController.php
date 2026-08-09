<?php
require_once __DIR__ . '/../models/CarModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';

class AdminCarController
{
    private CarModel $carModel;
    private CategoryModel $categoryModel;

    public function __construct()
    {
        $this->carModel = new CarModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $categories = $this->categoryModel->findAll();
        require_once __DIR__ . '/../../admin/mobil/index.php';
    }

    public function get_all()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $cars = $this->carModel->getAllWithDetails();

            foreach ($cars as &$car) {
                // Pewarnaan Badge Status
                $statusClass = 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300';
                if ($car['status'] === 'Reservasi') $statusClass = 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300';
                if ($car['status'] === 'Disewa') $statusClass = 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300';
                if ($car['status'] === 'Maintenance') $statusClass = 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-300';
                if ($car['status'] === 'Nonaktif') $statusClass = 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-400';

                $car['status_html'] = "<span class=\"px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide rounded-lg {$statusClass}\">" . htmlspecialchars($car['status']) . "</span>";

                // KOREKSI: Gunakan Null Coalescing (??) dan konversi tipe data (float) agar aman dari Deprecated di PHP 8.1
                $price_day = (float)($car['price_per_day'] ?? 0);
                $price_weekend = (float)($car['price_per_weekend'] ?? 0);

                // Format Harga
                $car['price_format'] = "W: Rp " . number_format($price_day, 0, ',', '.') . "<br><span class='text-[10px] text-slate-500'>WE: Rp " . number_format($price_weekend, 0, ',', '.') . "</span>";

                $img = !empty($car['primary_image']) ? base_url('admin/assets/uploads/cars/' . htmlspecialchars($car['primary_image'])) : '';
                $car['image_url'] = $img;
            }

            echo json_encode(['data' => $cars]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Kesalahan Database: ' . $e->getMessage()]);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Kesalahan Sistem: ' . $e->getMessage()]);
        }
    }


    public function get_by_id($id)
    {
        header('Content-Type: application/json');
        $car = $this->carModel->findById((int)$id);
        if ($car) {
            echo json_encode(['status' => true, 'data' => $car]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Data tidak ditemukan!']);
        }
    }

    public function store()
    {
        header('Content-Type: application/json');
        try {
            $db = Database::getConnection();
            $db->beginTransaction();

            $name = htmlspecialchars($_POST['name'] ?? '');
            $brand = htmlspecialchars($_POST['brand'] ?? '');
            $plate_number = htmlspecialchars($_POST['plate_number'] ?? '');
            $category_id = (int)($_POST['category_id'] ?? 0);

            // Harga Baru
            $price_per_day     = (float)preg_replace('/[^0-9]/', '', $_POST['price_per_day'] ?? '0');
            $price_per_weekend = (float)preg_replace('/[^0-9]/', '', $_POST['price_per_weekend'] ?? '0');
            $price_per_week    = (float)preg_replace('/[^0-9]/', '', $_POST['price_per_week'] ?? '0');
            $price_per_month   = (float)preg_replace('/[^0-9]/', '', $_POST['price_per_month'] ?? '0');

            $year = (int)($_POST['year'] ?? date('Y'));
            $capacity = (int)($_POST['capacity'] ?? 4);
            $transmission = $_POST['transmission'] ?? 'Manual';
            $fuel_type = $_POST['fuel_type'] ?? 'Bensin';
            $status = $_POST['status'] ?? 'Tersedia';
            $description = htmlspecialchars($_POST['description'] ?? '');
            $features = htmlspecialchars($_POST['features'] ?? '');

            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name))) . '-' . time();

            $sql = "INSERT INTO cars (category_id, name, brand, plate_number, slug, price_per_day, price_per_weekend, price_per_week, price_per_month, year, capacity, transmission, fuel_type, status, description, features) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([$category_id, $name, $brand, $plate_number, $slug, $price_per_day, $price_per_weekend, $price_per_week, $price_per_month, $year, $capacity, $transmission, $fuel_type, $status, $description, $features]);

            $car_id = $db->lastInsertId();
            $this->handleUploads($car_id, $db);

            $db->commit();
            echo json_encode(['status' => true, 'message' => 'Mobil baru berhasil ditambahkan!']);
        } catch (Exception $e) {
            if (isset($db)) $db->rollBack();
            echo json_encode(['status' => false, 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    public function update($id)
    {
        header('Content-Type: application/json');
        try {
            $db = Database::getConnection();

            $name = htmlspecialchars($_POST['name'] ?? '');
            $brand = htmlspecialchars($_POST['brand'] ?? '');
            $plate_number = htmlspecialchars($_POST['plate_number'] ?? '');
            $category_id = (int)($_POST['category_id'] ?? 0);

            // Harga Baru
            $price_per_day     = (float)preg_replace('/[^0-9]/', '', $_POST['price_per_day'] ?? '0');
            $price_per_weekend = (float)preg_replace('/[^0-9]/', '', $_POST['price_per_weekend'] ?? '0');
            $price_per_week    = (float)preg_replace('/[^0-9]/', '', $_POST['price_per_week'] ?? '0');
            $price_per_month   = (float)preg_replace('/[^0-9]/', '', $_POST['price_per_month'] ?? '0');

            $year = (int)($_POST['year'] ?? date('Y'));
            $capacity = (int)($_POST['capacity'] ?? 4);
            $transmission = $_POST['transmission'] ?? 'Manual';
            $fuel_type = $_POST['fuel_type'] ?? 'Bensin';
            $status = $_POST['status'] ?? 'Tersedia';
            $description = htmlspecialchars($_POST['description'] ?? '');
            $features = htmlspecialchars($_POST['features'] ?? '');

            $sql = "UPDATE cars SET category_id=?, name=?, brand=?, plate_number=?, price_per_day=?, price_per_weekend=?, price_per_week=?, price_per_month=?, year=?, capacity=?, transmission=?, fuel_type=?, status=?, description=?, features=? WHERE id=?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$category_id, $name, $brand, $plate_number, $price_per_day, $price_per_weekend, $price_per_week, $price_per_month, $year, $capacity, $transmission, $fuel_type, $status, $description, $features, $id]);

            if (isset($_FILES['images']) && count($_FILES['images']['name']) > 0 && $_FILES['images']['error'][0] != 4) {
                $this->handleUploads($id, $db);
            }

            echo json_encode(['status' => true, 'message' => 'Data armada berhasil diperbarui!']);
        } catch (Exception $e) {
            echo json_encode(['status' => false, 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        header('Content-Type: application/json');
        try {
            $db = Database::getConnection();
            $db->beginTransaction();

            $images = $this->carModel->getImages($id);
            $uploadDir = __DIR__ . '/../../admin/assets/uploads/cars/';

            foreach ($images as $img) {
                $filePath = $uploadDir . $img['image_path'];
                if (file_exists($filePath) && is_file($filePath)) unlink($filePath);
            }
            $this->carModel->delete($id);

            $db->commit();
            echo json_encode(['status' => true, 'message' => 'Mobil beserta foto terhapus!']);
        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode(['status' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()]);
        }
    }

    private function handleUploads($car_id, $db)
    {
        $uploadDir = __DIR__ . '/../../admin/assets/uploads/cars/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        if (isset($_FILES['images']) && count($_FILES['images']['name']) > 0 && $_FILES['images']['error'][0] != 4) {
            $totalFiles = count($_FILES['images']['name']);
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];

            for ($i = 0; $i < $totalFiles; $i++) {
                if ($_FILES['images']['error'][$i] === 0 && in_array($_FILES['images']['type'][$i], $allowedTypes) && $_FILES['images']['size'][$i] < 3000000) {
                    $fileExt = pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION);
                    $newFileName = uniqid('car_') . '_' . time() . '.' . $fileExt;

                    if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $uploadDir . $newFileName)) {
                        $is_primary = ($i === 0) ? 1 : 0;
                        $stmtImg = $db->prepare("INSERT INTO car_images (car_id, image_path, is_primary) VALUES (?, ?, ?)");
                        $stmtImg->execute([$car_id, $newFileName, $is_primary]);
                    }
                }
            }
        }
    }
}
