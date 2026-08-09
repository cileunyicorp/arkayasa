<?php
require_once __DIR__ . '/../models/CarModel.php';
require_once __DIR__ . '/../models/ClientModel.php';

class HomeController {
    private CarModel $carModel;
    private ClientModel $clientModel;

    public function __construct() {
        $this->carModel = new CarModel();
        $this->clientModel = new ClientModel();
    }
    
    public function index() {
        // 1. Ambil Data Mobil Siap Pakai
        $allCars = $this->carModel->getAllWithDetails();
        $availableCars = array_filter($allCars, function($car) {
            return $car['status'] === 'Tersedia';
        });
        $featuredCars = array_slice($availableCars, 0, 4);

        // 2. Ambil Data Client Aktif dari ClientModel (Menggunakan OOP BaseModel)
        $clients = $this->clientModel->getActiveClients();

        $data = [
            'title' => 'Sewa Mobil Premium & Eksklusif - ' . APP_NAME,
            'cars' => $featuredCars,
            'clients' => $clients
        ];

        view('home/index', $data);
    }
}
