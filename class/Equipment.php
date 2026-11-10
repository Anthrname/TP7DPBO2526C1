<?php
/**
 * Janji:
 * Saya Najib Nurohman dengan NIM 2509653 mengerjakan Tugas Praktikum 7 dalam mata kuliah
 * Desain Pemrograman Berorientasi Objek (DPBO) untuk keberkahan-Nya maka saya tidak akan
 * melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.
 * 
 * Class Equipment
 * Bertanggung jawab mengelola data Perangkat/Alat Laboratorium
 * Memiliki relasi Foreign Key ke tabel categories
 * Seluruh query database menggunakan PDO Prepared Statement
 * File: class/Equipment.php
 */

class Equipment {
    private $db;
    private $table = "equipments";

    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }

    /**
     * Mengambil seluruh data peralatan beserta nama kategori (JOIN)
     * @return array
     */
    public function getAll() {
        $sql = "SELECT e.*, c.name AS category_name, c.code AS category_code 
                FROM {$this->table} e
                JOIN categories c ON e.category_id = c.id
                ORDER BY e.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Mengambil data peralatan berdasarkan ID
     * @param int $id
     * @return array|false
     */
    public function getById($id) {
        $sql = "SELECT e.*, c.name AS category_name, c.code AS category_code 
                FROM {$this->table} e
                JOIN categories c ON e.category_id = c.id
                WHERE e.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Mengambil daftar alat yang stoknya > 0 dan kondisinya bukan 'Rusak' untuk dipinjam
     * @return array
     */
    public function getAvailableForBorrow() {
        $sql = "SELECT e.*, c.name AS category_name 
                FROM {$this->table} e
                JOIN categories c ON e.category_id = c.id
                WHERE e.stock > 0 AND e.condition_status != 'Rusak'
                ORDER BY e.name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Menambahkan data peralatan baru
     * @param int $category_id
     * @param string $code
     * @param string $name
     * @param string $brand
     * @param int $stock
     * @param string $condition_status
     * @param string $location
     * @return bool
     */
    public function create($category_id, $code, $name, $brand, $stock, $condition_status, $location) {
        $sql = "INSERT INTO {$this->table} 
                (category_id, code, name, brand, stock, condition_status, location) 
                VALUES (:category_id, :code, :name, :brand, :stock, :condition_status, :location)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':category_id'      => $category_id,
            ':code'             => trim($code),
            ':name'             => trim($name),
            ':brand'            => trim($brand),
            ':stock'            => (int) $stock,
            ':condition_status' => $condition_status,
            ':location'         => trim($location)
        ]);
    }

    /**
     * Memperbarui data peralatan
     * @param int $id
     * @param int $category_id
     * @param string $code
     * @param string $name
     * @param string $brand
     * @param int $stock
     * @param string $condition_status
     * @param string $location
     * @return bool
     */
    public function update($id, $category_id, $code, $name, $brand, $stock, $condition_status, $location) {
        $sql = "UPDATE {$this->table} 
                SET category_id = :category_id,
                    code = :code,
                    name = :name,
                    brand = :brand,
                    stock = :stock,
                    condition_status = :condition_status,
                    location = :location
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'               => $id,
            ':category_id'      => $category_id,
            ':code'             => trim($code),
            ':name'             => trim($name),
            ':brand'            => trim($brand),
            ':stock'            => (int) $stock,
            ':condition_status' => $condition_status,
            ':location'         => trim($location)
        ]);
    }

    /**
     * Menghapus data peralatan berdasarkan ID
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Memperbarui jumlah stok alat (bisa bertambah atau berkurang)
     * @param int $id
     * @param int $change
     * @return bool
     */
    public function adjustStock($id, $change) {
        $sql = "UPDATE {$this->table} SET stock = stock + :change WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':change' => (int) $change, ':id' => $id]);
    }

    /**
     * Menghitung total varian/jenis peralatan
     * @return int
     */
    public function countAll() {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM {$this->table}");
        $stmt->execute();
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }

    /**
     * Menghitung total unit stok seluruh alat yang ada
     * @return int
     */
    public function countTotalStock() {
        $stmt = $this->db->prepare("SELECT SUM(stock) AS total_stock FROM {$this->table}");
        $stmt->execute();
        $row = $stmt->fetch();
        return (int) ($row['total_stock'] ?? 0);
    }

    /**
     * Memeriksa apakah kode alat sudah pernah digunakan
     * @param string $code
     * @param int|null $excludeId
     * @return bool
     */
    public function isCodeExists($code, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM {$this->table} WHERE code = :code AND id != :excludeId");
            $stmt->execute([':code' => trim($code), ':excludeId' => $excludeId]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM {$this->table} WHERE code = :code");
            $stmt->execute([':code' => trim($code)]);
        }
        $row = $stmt->fetch();
        return ($row['total'] ?? 0) > 0;
    }
}
