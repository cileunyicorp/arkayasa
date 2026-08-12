<?php

require_once __DIR__ . '/../models/CarModel.php';
require_once __DIR__ . '/../models/BookingModel.php';
require_once __DIR__ . '/../models/CustomerModel.php';
require_once __DIR__ . '/../models/FinanceModel.php';

class AdminDashboardController
{
    private CarModel $carModel;
    private BookingModel $bookingModel;
    private CustomerModel $customerModel;
    private FinanceModel $financeModel;

    public function __construct()
    {
        $this->carModel      = new CarModel();
        $this->bookingModel  = new BookingModel();
        $this->customerModel = new CustomerModel();
        $this->financeModel  = new FinanceModel();
    }

    public function index()
    {
        $totalCars      = $this->carModel->getTotalCars();
        $totalBookings  = $this->bookingModel->getTotalBookings();
        $totalCustomers = $this->customerModel->getTotal();
        $financeSummary = $this->financeModel->getSummary();

        // Status Armada
        $carStatusSummary = $this->carModel->getStatusSummary();

        // 5 Transaksi Terbaru
        $recentBookings = $this->bookingModel->getRecentBookings();

        // Data Grafik 6 Bulan Terakhir
        $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];
        $chartData   = [5, 8, 12, 10, 15, 20]; // nilai default/ditarik dari database

        require_once __DIR__ . '/../../admin/dashboard.php';
    }
}
