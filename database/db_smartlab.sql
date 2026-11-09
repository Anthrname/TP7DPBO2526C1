-- Database: db_smartlab
-- Sistem Manajemen Inventaris dan Peminjaman Alat Laboratorium Komputer
-- Mata Kuliah: Desain dan Pemrograman Berorientasi Objek (DPBO)
-- Mahasiswa: Najib Nurohman (NIM: 2509653) - Kelas C1

CREATE DATABASE IF NOT EXISTS `db_smartlab` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_smartlab`;

-- --------------------------------------------------------
-- 1. Struktur Tabel `categories` (Kategori Perangkat Lab)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `borrowings`;
DROP TABLE IF EXISTS `equipments`;
DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(20) NOT NULL UNIQUE,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 2. Struktur Tabel `equipments` (Data Perangkat / Alat Lab)
-- Memiliki relasi Foreign Key ke `categories`
-- --------------------------------------------------------
CREATE TABLE `equipments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `code` VARCHAR(50) NOT NULL UNIQUE,
    `name` VARCHAR(150) NOT NULL,
    `brand` VARCHAR(100) NULL,
    `stock` INT NOT NULL DEFAULT 1,
    `condition_status` ENUM('Baik', 'Perlu Perbaikan', 'Rusak') DEFAULT 'Baik',
    `location` VARCHAR(100) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_equipment_category` 
        FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) 
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 3. Struktur Tabel `borrowings` (Data Peminjaman Alat Lab)
-- Memiliki relasi Foreign Key ke `equipments`
-- --------------------------------------------------------
CREATE TABLE `borrowings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `equipment_id` INT NOT NULL,
    `borrower_name` VARCHAR(150) NOT NULL,
    `borrower_id` VARCHAR(50) NOT NULL,
    `borrower_contact` VARCHAR(50) NULL,
    `borrow_date` DATE NOT NULL,
    `return_date` DATE NULL,
    `status` ENUM('Dipinjam', 'Dikembalikan') DEFAULT 'Dipinjam',
    `notes` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_borrowing_equipment` 
        FOREIGN KEY (`equipment_id`) REFERENCES `equipments` (`id`) 
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Data Dummy Awal (Seed Data)
-- --------------------------------------------------------

-- Insert Data Kategori
INSERT INTO `categories` (`code`, `name`, `description`) VALUES
('NET-01', 'Perangkat Jaringan', 'Peralatan untuk konfigurasi routing, switching, dan transmisi data jaringan.'),
('IOT-02', 'IoT & Mikrokontroler', 'Modul mikrokontroler, kit sensor, aktuator, dan komponen eksperimen embedded systems.'),
('SRV-03', 'Server & Komputasi', 'Komponen workstation, server mini, mini PC, dan perangkat keras komputasi tinggi.'),
('AVM-04', 'Audio & Visual Lab', 'Proyektor, webcam resolusi tinggi, microfon konferensi, dan capture card presentasi.');

-- Insert Data Alat Lab (Equipments)
INSERT INTO `equipments` (`category_id`, `code`, `name`, `brand`, `stock`, `condition_status`, `location`) VALUES
(1, 'EQ-NET-001', 'Router MikroTik RB951Ui-2HnD', 'MikroTik', 8, 'Baik', 'Rak Jaringan Lab A'),
(1, 'EQ-NET-002', 'Managed Switch Catalyst 2960 24-Port', 'Cisco', 4, 'Baik', 'Rak Server Lab A'),
(2, 'EQ-IOT-001', 'Arduino Mega 2560 R3 Kit', 'Arduino Official', 14, 'Baik', 'Loker IoT 01 Lab B'),
(2, 'EQ-IOT-002', 'Raspberry Pi 4 Model B (8GB RAM)', 'Raspberry Pi Foundation', 6, 'Baik', 'Loker IoT 02 Lab B'),
(3, 'EQ-SRV-001', 'Intel NUC 11 Pro Mini PC (Core i7)', 'Intel', 3, 'Baik', 'Meja Riset Lab C'),
(4, 'EQ-AVM-001', 'Logitech C922 Pro Stream Webcam', 'Logitech', 5, 'Baik', 'Lemari Multimedia Lab A');

-- Insert Data Peminjaman (Borrowings)
INSERT INTO `borrowings` (`equipment_id`, `borrower_name`, `borrower_id`, `borrower_contact`, `borrow_date`, `return_date`, `status`, `notes`) VALUES
(1, 'Fajar Nugraha', '2501234', '081234567890', '2026-11-01', '2026-11-05', 'Dikembalikan', 'Praktikum Jaringan Komputer Modul 3. Alat dikembalikan lengkap.'),
(4, 'Siti Aisyah', '2508765', '085798765432', '2026-11-06', NULL, 'Dipinjam', 'Pengerjaan Proyek Akhir Mata Kuliah IoT & Smart System.'),
(2, 'Dimas Pratama', '2504321', '082112345678', '2026-11-07', NULL, 'Dipinjam', 'Eksperimen VLAN dan Inter-VLAN Routing.');
