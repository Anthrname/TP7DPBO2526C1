<?php
/**
 * SmartLab - Sistem Informasi Manajemen Inventaris & Peminjaman Alat Laboratorium Komputer
 * Front Controller & Routing Utama
 * Mata Kuliah: Desain dan Pemrograman Berorientasi Objek (DPBO)
 * Mahasiswa: Najib Nurohman (NIM: 2509653) - Kelas C1
 * File: index.php
 */

session_start();

// Import file konfigurasi database dan class OOP
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/class/Category.php';
require_once __DIR__ . '/class/Equipment.php';
require_once __DIR__ . '/class/Borrowing.php';

// Inisialisasi Database dan Model
$database = new Database();
$pdo = $database->getConnection();

$categoryModel = new Category($pdo);
$equipmentModel = new Equipment($pdo);
$borrowingModel = new Borrowing($pdo);

// Parameter Routing
$page = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? null;

// ============================================================================
// 1. HANDLER AKSI CRUD (CREATE, UPDATE, DELETE)
// ============================================================================

// --- CRUD KATEGORI ---
if ($page === 'categories') {
    if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $code = trim($_POST['code'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (empty($code) || empty($name)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Kode dan Nama Kategori wajib diisi!'];
        } elseif ($categoryModel->isCodeExists($code)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => "Kode Kategori '{$code}' sudah digunakan! Gunakan kode lain."];
        } else {
            $created = $categoryModel->create($code, $name, $description);
            $_SESSION['flash'] = $created 
                ? ['type' => 'success', 'message' => "Kategori '{$name}' berhasil ditambahkan."]
                : ['type' => 'danger', 'message' => 'Gagal menambahkan kategori. Silakan coba lagi.'];
        }
        header("Location: index.php?page=categories");
        exit;
    }

    if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int) ($_POST['id'] ?? 0);
        $code = trim($_POST['code'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($id <= 0 || empty($code) || empty($name)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Data kategori tidak valid.'];
        } elseif ($categoryModel->isCodeExists($code, $id)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => "Kode Kategori '{$code}' sudah digunakan kategori lain."];
        } else {
            $updated = $categoryModel->update($id, $code, $name, $description);
            $_SESSION['flash'] = $updated 
                ? ['type' => 'success', 'message' => "Data kategori '{$name}' berhasil diperbarui."]
                : ['type' => 'danger', 'message' => 'Gagal memperbarui kategori.'];
        }
        header("Location: index.php?page=categories");
        exit;
    }

    if ($action === 'delete') {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id > 0) {
            $deleted = $categoryModel->delete($id);
            $_SESSION['flash'] = $deleted 
                ? ['type' => 'success', 'message' => 'Kategori dan seluruh perangkat terkait berhasil dihapus.']
                : ['type' => 'danger', 'message' => 'Gagal menghapus kategori.'];
        }
        header("Location: index.php?page=categories");
        exit;
    }
}

// --- CRUD PERALATAN (EQUIPMENTS) ---
if ($page === 'equipments') {
    if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $category_id = (int) ($_POST['category_id'] ?? 0);
        $code = trim($_POST['code'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $brand = trim($_POST['brand'] ?? '');
        $stock = (int) ($_POST['stock'] ?? 1);
        $condition_status = $_POST['condition_status'] ?? 'Baik';
        $location = trim($_POST['location'] ?? '');

        if ($category_id <= 0 || empty($code) || empty($name)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Kategori, Kode Alat, dan Nama Alat wajib diisi!'];
        } elseif ($equipmentModel->isCodeExists($code)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => "Kode Alat '{$code}' sudah ada! Gunakan kode unik lain."];
        } else {
            $created = $equipmentModel->create($category_id, $code, $name, $brand, $stock, $condition_status, $location);
            $_SESSION['flash'] = $created 
                ? ['type' => 'success', 'message' => "Perangkat '{$name}' berhasil didaftarkan."]
                : ['type' => 'danger', 'message' => 'Gagal menambahkan perangkat ke inventaris.'];
        }
        header("Location: index.php?page=equipments");
        exit;
    }

    if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int) ($_POST['id'] ?? 0);
        $category_id = (int) ($_POST['category_id'] ?? 0);
        $code = trim($_POST['code'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $brand = trim($_POST['brand'] ?? '');
        $stock = (int) ($_POST['stock'] ?? 0);
        $condition_status = $_POST['condition_status'] ?? 'Baik';
        $location = trim($_POST['location'] ?? '');

        if ($id <= 0 || $category_id <= 0 || empty($code) || empty($name)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Data input perangkat tidak lengkap.'];
        } elseif ($equipmentModel->isCodeExists($code, $id)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => "Kode Alat '{$code}' sudah digunakan oleh perangkat lain."];
        } else {
            $updated = $equipmentModel->update($id, $category_id, $code, $name, $brand, $stock, $condition_status, $location);
            $_SESSION['flash'] = $updated 
                ? ['type' => 'success', 'message' => "Data perangkat '{$name}' berhasil diperbarui."]
                : ['type' => 'danger', 'message' => 'Gagal memperbarui data perangkat.'];
        }
        header("Location: index.php?page=equipments");
        exit;
    }

    if ($action === 'delete') {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id > 0) {
            $deleted = $equipmentModel->delete($id);
            $_SESSION['flash'] = $deleted 
                ? ['type' => 'success', 'message' => 'Perangkat dan catatan peminjaman terkait berhasil dihapus.']
                : ['type' => 'danger', 'message' => 'Gagal menghapus perangkat.'];
        }
        header("Location: index.php?page=equipments");
        exit;
    }
}

// --- CRUD PEMINJAMAN (BORROWINGS) ---
if ($page === 'borrowings') {
    if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $equipment_id = (int) ($_POST['equipment_id'] ?? 0);
        $borrower_name = trim($_POST['borrower_name'] ?? '');
        $borrower_id = trim($_POST['borrower_id'] ?? '');
        $borrower_contact = trim($_POST['borrower_contact'] ?? '');
        $borrow_date = $_POST['borrow_date'] ?? date('Y-m-d');
        $notes = trim($_POST['notes'] ?? '');

        if ($equipment_id <= 0 || empty($borrower_name) || empty($borrower_id)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Perangkat, Nama Peminjam, dan NIM/NIP wajib diisi!'];
        } else {
            try {
                $borrowingModel->create($equipment_id, $borrower_name, $borrower_id, $borrower_contact, $borrow_date, $notes);
                $_SESSION['flash'] = ['type' => 'success', 'message' => "Peminjaman atas nama {$borrower_name} berhasil dicatat. Stok alat berkurang 1 unit."];
            } catch (Exception $e) {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal memproses peminjaman: ' . $e->getMessage()];
            }
        }
        header("Location: index.php?page=borrowings");
        exit;
    }

    if ($action === 'return' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int) ($_POST['id'] ?? 0);
        $return_date = $_POST['return_date'] ?? date('Y-m-d');

        if ($id <= 0) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'ID peminjaman tidak valid.'];
        } else {
            try {
                $borrowingModel->returnEquipment($id, $return_date);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Pengembalian perangkat berhasil dicatat. Stok alat bertambah 1 unit.'];
            } catch (Exception $e) {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal memproses pengembalian: ' . $e->getMessage()];
            }
        }
        header("Location: index.php?page=borrowings");
        exit;
    }

    if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int) ($_POST['id'] ?? 0);
        $equipment_id = (int) ($_POST['equipment_id'] ?? 0);
        $borrower_name = trim($_POST['borrower_name'] ?? '');
        $borrower_id = trim($_POST['borrower_id'] ?? '');
        $borrower_contact = trim($_POST['borrower_contact'] ?? '');
        $borrow_date = $_POST['borrow_date'] ?? date('Y-m-d');
        $return_date = !empty($_POST['return_date']) ? $_POST['return_date'] : null;
        $status = $_POST['status'] ?? 'Dipinjam';
        $notes = trim($_POST['notes'] ?? '');

        if ($id <= 0 || $equipment_id <= 0 || empty($borrower_name) || empty($borrower_id)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Data peminjaman tidak valid atau belum lengkap.'];
        } else {
            try {
                $borrowingModel->update($id, $equipment_id, $borrower_name, $borrower_id, $borrower_contact, $borrow_date, $return_date, $status, $notes);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Data transaksi peminjaman berhasil diperbarui.'];
            } catch (Exception $e) {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal memperbarui peminjaman: ' . $e->getMessage()];
            }
        }
        header("Location: index.php?page=borrowings");
        exit;
    }

    if ($action === 'delete') {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id > 0) {
            try {
                $borrowingModel->delete($id);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Catatan transaksi peminjaman berhasil dihapus.'];
            } catch (Exception $e) {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal menghapus data: ' . $e->getMessage()];
            }
        }
        header("Location: index.php?page=borrowings");
        exit;
    }
}

// ============================================================================
// 2. DATA BINDING UNTUK VIEW
// ============================================================================

switch ($page) {
    case 'categories':
        $pageTitle = 'Manajemen Kategori';
        $categoriesList = $categoryModel->getAll();
        $viewFile = 'view/categories.php';
        break;

    case 'equipments':
        $pageTitle = 'Inventaris Perangkat Lab';
        $equipmentsList = $equipmentModel->getAll();
        $categoriesList = $categoryModel->getAll();
        $viewFile = 'view/equipments.php';
        break;

    case 'borrowings':
        $pageTitle = 'Transaksi Peminjaman';
        $borrowingsList = $borrowingModel->getAll();
        $availableEquipments = $equipmentModel->getAvailableForBorrow();
        $allEquipments = $equipmentModel->getAll();
        $activeCount = $borrowingModel->countActive();
        $completedCount = $borrowingModel->countCompleted();
        $viewFile = 'view/borrowings.php';
        break;

    case 'dashboard':
    default:
        $pageTitle = 'Dashboard Utama';
        $totalCategories = $categoryModel->countAll();
        $totalEquipments = $equipmentModel->countAll();
        $totalStock = $equipmentModel->countTotalStock();
        $activeLoans = $borrowingModel->countActive();
        $recentBorrowings = $borrowingModel->getRecent(5);
        $viewFile = 'view/dashboard.php';
        break;
}

// ============================================================================
// 3. RENDER TAMPILAN MODULAR
// ============================================================================
require_once __DIR__ . '/view/header.php';
require_once __DIR__ . '/view/navbar.php';
require_once __DIR__ . '/' . $viewFile;
require_once __DIR__ . '/view/footer.php';
