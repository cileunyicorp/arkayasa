CREATE DATABASE IF NOT EXISTS rental_mobil;
USE rental_mobil;

-- Tabel Roles
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO roles (name) VALUES ('Admin'), ('Operator'), ('Pelanggan');

-- Tabel Users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT
);

-- Insert Default Admin (Password: admin123)
INSERT INTO users (role_id, name, email, password) 
VALUES (1, 'Super Admin', 'admin@arkayasa.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Tabel Categories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Cars
CREATE TABLE cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    price_per_day DECIMAL(10,2) NOT NULL,
    year INT(4) NOT NULL,
    capacity INT(2) NOT NULL,
    transmission ENUM('Manual', 'Automatic') NOT NULL,
    status ENUM('Tersedia', 'Disewa', 'Maintenance') DEFAULT 'Tersedia',
    description TEXT,
    features TEXT, -- Bisa disimpan dalam bentuk JSON
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
);

-- Tabel Car Images
CREATE TABLE car_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    car_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
);

-- Tabel Customers (Untuk detail spesifik pelanggan jika diperlukan, bisa dihubungkan ke users)
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nik VARCHAR(20) UNIQUE NOT NULL,
    driver_license_number VARCHAR(30),
    driver_license_image VARCHAR(255),
    id_card_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabel Bookings
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_code VARCHAR(20) UNIQUE NOT NULL,
    customer_id INT NOT NULL,
    car_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_days INT NOT NULL,
    total_price DECIMAL(12,2) NOT NULL,
    status ENUM('Menunggu Pembayaran', 'Approve', 'Reject', 'Dipinjam', 'Dikembalikan', 'Selesai') DEFAULT 'Menunggu Pembayaran',
    payment_receipt VARCHAR(255) NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE RESTRICT,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE RESTRICT
);

-- Tabel Articles
CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    content TEXT NOT NULL,
    thumbnail VARCHAR(255),
    status ENUM('Draft', 'Published') DEFAULT 'Draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT
);

-- Tabel Settings
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) UNIQUE NOT NULL,
    setting_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO settings (setting_key, setting_value) VALUES 
('site_name', 'Arkayasa Rent Car'),
('email', 'info@arkayasa.com'),
('whatsapp', '6281234567890');

INSERT INTO categories (name, slug) VALUES 
('SUV', 'suv'), 
('MPV', 'mpv'), 
('Sedan', 'sedan'), 
('Hatchback', 'hatchback'), 
('Minibus', 'minibus');

CREATE TABLE drivers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    nik VARCHAR(20) UNIQUE NOT NULL,
    driver_license_number VARCHAR(30) NOT NULL,
    price_per_day DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    status ENUM('Tersedia', 'Disewa', 'Nonaktif') DEFAULT 'Tersedia',
    photo VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert data dummy sopir
INSERT INTO drivers (name, phone, nik, driver_license_number, price_per_day, status) 
VALUES ('Ahmad Subarjo', '081299998888', '3201111122223333', 'A-999888777', 150000.00, 'Tersedia');



CREATE TABLE IF NOT EXISTS maintenances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    car_id INT NOT NULL,
    maintenance_date DATE NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    cost DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    STATUS ENUM('Scheduled', 'In Progress', 'Completed') DEFAULT 'Scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data Dummy Perawatan
INSERT INTO maintenances (car_id, maintenance_date, title, description, cost, STATUS)
VALUES (1, CURDATE(), 'Ganti Oli Rutin & Tune Up', 'Ganti oli mesin 10W-40, filter oli, pengecekan busi.', 650000.00, 'Completed');


CREATE TABLE IF NOT EXISTS finances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_date DATE NOT NULL,
    TYPE ENUM('Pemasukan', 'Pengeluaran') NOT NULL,
    category VARCHAR(100) NOT NULL,
    amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    description TEXT,
    reference_id VARCHAR(50) DEFAULT NULL, -- Opsional: Untuk mencatat kode booking atau ID perawatan
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data Dummy Awal
INSERT INTO finances (transaction_date, TYPE, category, amount, description) VALUES 
(CURDATE(), 'Pemasukan', 'Sewa Kendaraan', 1500000.00, 'Pembayaran Lunas TRX-001 Budi Santoso'),
(CURDATE(), 'Pengeluaran', 'Operasional', 150000.00, 'Beli Token Listrik Kantor');


-- 1. Buat Tabel Clients / Mitra Kerja
CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    logo VARCHAR(255) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Input Data Logo Mitra Awal (Mengacu ke folder assets/images/clients/)
INSERT INTO clients (name, logo, is_active) VALUES 
('TNI Angkatan Darat', 'army.png', 1),
('PT Pertamina', 'army.png', 1),
('Bank Mandiri', 'army.png', 1),
('PT Telkom Indonesia', 'army.png', 1);
