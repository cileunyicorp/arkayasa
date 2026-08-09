<?php
require_once __DIR__ . '/../models/CarModel.php';
require_once __DIR__ . '/../models/BookingModel.php';
require_once __DIR__ . '/../models/CustomerModel.php';
require_once __DIR__ . '/../models/FinanceModel.php';

class AdminDashboardController {
    public function index() {
        $carModel = new CarModel();
        $bookingModel = new BookingModel();
        $customerModel = new CustomerModel();
        $financeModel = new FinanceModel();

        // Kumpulkan metrik data Real-Time
        $totalCars = $carModel->getTotalCars();
        $totalBookings = $bookingModel->getTotalBookings();
        $totalCustomers = $customerModel->getTotal();
        $financeSummary = $financeModel->getSummary();
        $recentBookings = $bookingModel->getRecentBookings();

        // Panggil View Dashboard
        require_once __DIR__ . '/../../admin/dashboard.php';
    }
}
