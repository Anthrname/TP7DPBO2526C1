<?php
/**
 * Janji:
 * Saya Najib Nurohman dengan NIM 2509653 mengerjakan Tugas Praktikum 7 dalam mata kuliah
 * Desain Pemrograman Berorientasi Objek (DPBO) untuk keberkahan-Nya maka saya tidak akan
 * melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.
 * 
 * Class Borrowing
 * Bertanggung jawab mengelola Transaksi Peminjaman dan Pengembalian Alat
 * Memiliki relasi Foreign Key ke tabel equipments
 * Menggunakan PDO Prepared Statement dan Database Transaction untuk menjaga konsistensi stok
 * File: class/Borrowing.php
 */

class Borrowing {
    private $db;
    private $table = "borrowings";

    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }

    /**
     * Mengambil seluruh data peminjaman beserta informasi alat dan kategori (JOIN)
     * @return array
     */
    public function getAll() {
        $sql = "SELECT b.*, 
                       e.code AS equipment_code, 
                       e.name AS equipment_name, 
                       e.brand AS equipment_brand,
                       c.name AS category_name
                FROM {$this->table} b
                JOIN equipments e ON b.equipment_id = e.id
                JOIN categories c ON e.category_id = c.id
                ORDER BY b.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Mengambil beberapa data peminjaman terkini untuk dashboard
     * @param int $limit
     * @return array
     */
    public function getRecent($limit = 5) {
        $sql = "SELECT b.*, 
                       e.code AS equipment_code, 
                       e.name AS equipment_name,
                       c.name AS category_name
                FROM {$this->table} b
                JOIN equipments e ON b.equipment_id = e.id
                JOIN categories c ON e.category_id = c.id
                ORDER BY b.id DESC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Mengambil satu data peminjaman berdasarkan ID
     * @param int $id
     * @return array|false
     */
    public function getById($id) {
        $sql = "SELECT b.*, 
                       e.code AS equipment_code, 
                       e.name AS equipment_name,
                       e.stock AS equipment_stock
                FROM {$this->table} b
                JOIN equipments e ON b.equipment_id = e.id
                WHERE b.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Mencatat peminjaman baru dan otomatis mengurangi stok alat sebesar 1 (Database Transaction)
     * @param int $equipment_id
     * @param string $borrower_name
     * @param string $borrower_id
     * @param string $borrower_contact
     * @param string $borrow_date
     * @param string $notes
     * @return bool
     * @throws Exception
     */
    public function create($equipment_id, $borrower_name, $borrower_id, $borrower_contact, $borrow_date, $notes) {
        try {
            $this->db->beginTransaction();

            // 1. Cek stok alat saat ini dengan Prepared Statement
            $checkStmt = $this->db->prepare("SELECT stock, condition_status FROM equipments WHERE id = :id FOR UPDATE");
            $checkStmt->execute([':id' => $equipment_id]);
            $eq = $checkStmt->fetch();

            if (!$eq) {
                throw new Exception("Perangkat yang dipilih tidak ditemukan dalam sistem.");
            }
            if ($eq['stock'] <= 0) {
                throw new Exception("Stok perangkat sedang habis, tidak dapat dipinjam.");
            }
            if ($eq['condition_status'] === 'Rusak') {
                throw new Exception("Perangkat dalam kondisi rusak dan tidak dapat dipinjam.");
            }

            // 2. Insert ke tabel borrowings
            $insertSql = "INSERT INTO {$this->table} 
                          (equipment_id, borrower_name, borrower_id, borrower_contact, borrow_date, return_date, status, notes) 
                          VALUES (:equipment_id, :borrower_name, :borrower_id, :borrower_contact, :borrow_date, NULL, 'Dipinjam', :notes)";
            $insertStmt = $this->db->prepare($insertSql);
            $insertStmt->execute([
                ':equipment_id'      => $equipment_id,
                ':borrower_name'     => trim($borrower_name),
                ':borrower_id'       => trim($borrower_id),
                ':borrower_contact'  => trim($borrower_contact),
                ':borrow_date'       => $borrow_date,
                ':notes'             => trim($notes)
            ]);

            // 3. Kurangi stok alat sebesar 1
            $updateStockStmt = $this->db->prepare("UPDATE equipments SET stock = stock - 1 WHERE id = :id");
            $updateStockStmt->execute([':id' => $equipment_id]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Memproses pengembalian alat (Status menjadi Dikembalikan dan stok bertambah 1)
     * @param int $id
     * @param string $return_date
     * @return bool
     * @throws Exception
     */
    public function returnEquipment($id, $return_date) {
        try {
            $this->db->beginTransaction();

            // Ambil data peminjaman saat ini
            $currStmt = $this->db->prepare("SELECT equipment_id, status FROM {$this->table} WHERE id = :id FOR UPDATE");
            $currStmt->execute([':id' => $id]);
            $current = $currStmt->fetch();

            if (!$current) {
                throw new Exception("Data peminjaman tidak ditemukan.");
            }

            if ($current['status'] === 'Dikembalikan') {
                throw new Exception("Perangkat ini sudah dikembalikan sebelumnya.");
            }

            // Update status peminjaman menjadi 'Dikembalikan'
            $updateSql = "UPDATE {$this->table} SET status = 'Dikembalikan', return_date = :return_date WHERE id = :id";
            $updateStmt = $this->db->prepare($updateSql);
            $updateStmt->execute([
                ':return_date' => $return_date,
                ':id'          => $id
            ]);

            // Tambah stok alat kembali sebesar 1
            $stockStmt = $this->db->prepare("UPDATE equipments SET stock = stock + 1 WHERE id = :eq_id");
            $stockStmt->execute([':eq_id' => $current['equipment_id']]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Memperbarui detail data peminjaman
     * @param int $id
     * @param int $equipment_id
     * @param string $borrower_name
     * @param string $borrower_id
     * @param string $borrower_contact
     * @param string $borrow_date
     * @param string|null $return_date
     * @param string $status
     * @param string $notes
     * @return bool
     * @throws Exception
     */
    public function update($id, $equipment_id, $borrower_name, $borrower_id, $borrower_contact, $borrow_date, $return_date, $status, $notes) {
        try {
            $this->db->beginTransaction();

            $currStmt = $this->db->prepare("SELECT equipment_id, status FROM {$this->table} WHERE id = :id FOR UPDATE");
            $currStmt->execute([':id' => $id]);
            $current = $currStmt->fetch();

            if (!$current) {
                throw new Exception("Data peminjaman tidak ditemukan.");
            }

            $oldEqId = (int) $current['equipment_id'];
            $newEqId = (int) $equipment_id;
            $oldStatus = $current['status'];
            $newStatus = $status;

            // Penyesuaian stok jika status atau alat berubah
            if ($oldEqId === $newEqId) {
                if ($oldStatus === 'Dipinjam' && $newStatus === 'Dikembalikan') {
                    // Dikembalikan -> stok +1
                    $sStmt = $this->db->prepare("UPDATE equipments SET stock = stock + 1 WHERE id = :id");
                    $sStmt->execute([':id' => $newEqId]);
                } elseif ($oldStatus === 'Dikembalikan' && $newStatus === 'Dipinjam') {
                    // Dipinjam lagi -> stok -1
                    $sStmt = $this->db->prepare("UPDATE equipments SET stock = stock - 1 WHERE id = :id");
                    $sStmt->execute([':id' => $newEqId]);
                }
            } else {
                // Alat berubah: kembalikan stok alat lama jika status sebelumnya 'Dipinjam'
                if ($oldStatus === 'Dipinjam') {
                    $sStmt1 = $this->db->prepare("UPDATE equipments SET stock = stock + 1 WHERE id = :id");
                    $sStmt1->execute([':id' => $oldEqId]);
                }
                // Kurangi stok alat baru jika status baru 'Dipinjam'
                if ($newStatus === 'Dipinjam') {
                    $sStmt2 = $this->db->prepare("UPDATE equipments SET stock = stock - 1 WHERE id = :id");
                    $sStmt2->execute([':id' => $newEqId]);
                }
            }

            // Jika status 'Dipinjam', pastikan return_date null atau sesuai
            $finalReturnDate = ($newStatus === 'Dikembalikan') ? ($return_date ?: date('Y-m-d')) : null;

            $sql = "UPDATE {$this->table} 
                    SET equipment_id     = :equipment_id,
                        borrower_name    = :borrower_name,
                        borrower_id      = :borrower_id,
                        borrower_contact = :borrower_contact,
                        borrow_date      = :borrow_date,
                        return_date      = :return_date,
                        status           = :status,
                        notes            = :notes
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':id'               => $id,
                ':equipment_id'     => $newEqId,
                ':borrower_name'    => trim($borrower_name),
                ':borrower_id'      => trim($borrower_id),
                ':borrower_contact' => trim($borrower_contact),
                ':borrow_date'      => $borrow_date,
                ':return_date'      => $finalReturnDate,
                ':status'           => $newStatus,
                ':notes'            => trim($notes)
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Menghapus catatan peminjaman
     * Jika saat dihapus statusnya masih 'Dipinjam', stok alat dikembalikan (+1)
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function delete($id) {
        try {
            $this->db->beginTransaction();

            $currStmt = $this->db->prepare("SELECT equipment_id, status FROM {$this->table} WHERE id = :id FOR UPDATE");
            $currStmt->execute([':id' => $id]);
            $current = $currStmt->fetch();

            if ($current && $current['status'] === 'Dipinjam') {
                $stockStmt = $this->db->prepare("UPDATE equipments SET stock = stock + 1 WHERE id = :id");
                $stockStmt->execute([':id' => $current['equipment_id']]);
            }

            $delStmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
            $delStmt->execute([':id' => $id]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Menghitung total seluruh peminjaman
     * @return int
     */
    public function countAll() {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM {$this->table}");
        $stmt->execute();
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }

    /**
     * Menghitung total peminjaman aktif (masih dipinjam)
     * @return int
     */
    public function countActive() {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM {$this->table} WHERE status = 'Dipinjam'");
        $stmt->execute();
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }

    /**
     * Menghitung total peminjaman yang sudah selesai (dikembalikan)
     * @return int
     */
    public function countCompleted() {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM {$this->table} WHERE status = 'Dikembalikan'");
        $stmt->execute();
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }
}
