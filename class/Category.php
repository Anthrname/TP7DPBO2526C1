<?php
/**
 * Class Category
 * Bertanggung jawab mengelola data Kategori Perangkat Laboratorium
 * Menerapkan prinsip OOP dan seluruh query menggunakan PDO Prepared Statement
 * File: class/Category.php
 */

class Category {
    private $db;
    private $table = "categories";

    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }

    /**
     * Mengambil seluruh data kategori
     * @return array
     */
    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Mengambil satu data kategori berdasarkan ID
     * @param int $id
     * @return array|false
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Menambahkan data kategori baru
     * @param string $code
     * @param string $name
     * @param string $description
     * @return bool
     */
    public function create($code, $name, $description) {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (code, name, description) VALUES (:code, :name, :description)");
        return $stmt->execute([
            ':code'        => trim($code),
            ':name'        => trim($name),
            ':description' => trim($description)
        ]);
    }

    /**
     * Memperbarui data kategori
     * @param int $id
     * @param string $code
     * @param string $name
     * @param string $description
     * @return bool
     */
    public function update($id, $code, $name, $description) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET code = :code, name = :name, description = :description WHERE id = :id");
        return $stmt->execute([
            ':id'          => $id,
            ':code'        => trim($code),
            ':name'        => trim($name),
            ':description' => trim($description)
        ]);
    }

    /**
     * Menghapus data kategori berdasarkan ID
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Menghitung total data kategori
     * @return int
     */
    public function countAll() {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM {$this->table}");
        $stmt->execute();
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }

    /**
     * Cek apakah kode kategori sudah pernah digunakan
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
