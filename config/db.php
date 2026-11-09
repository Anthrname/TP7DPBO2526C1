<?php
/**
 * Konfigurasi Database dan Koneksi PDO
 * Menggunakan pendekatan OOP (Object-Oriented Programming)
 * File: config/db.php
 */

class Database {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $dbname = "db_smartlab";
    private $port = 3306;
    private $pdo;

    public function __construct() {
        $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->dbname . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false, // Memastikan query menggunakan prepared statement native
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            // Tampilan error ramah jika database belum di-import
            die("<div style='font-family: Arial, sans-serif; padding: 20px; background-color: #fee2e2; color: #991b1b; border: 1px solid #f87171; border-radius: 8px; margin: 20px auto; max-width: 600px;'>
                    <h3 style='margin-top: 0;'>⚠️ Koneksi Database Gagal!</h3>
                    <p>Terjadi kesalahan saat menyambung ke MySQL database <strong>{$this->dbname}</strong>.</p>
                    <p><small>Pesan Error: " . htmlspecialchars($e->getMessage()) . "</small></p>
                    <hr style='border: none; border-top: 1px solid #fca5a5;'>
                    <p style='margin-bottom: 0;'><strong>Tips:</strong> Pastikan MySQL di Laragon/XAMPP sudah aktif (warna hijau) dan skrip SQL di <code>database/db_smartlab.sql</code> telah di-eksekusi/import.</p>
                 </div>");
        }
    }

    /**
     * Mendapatkan objek PDO
     * @return PDO
     */
    public function getConnection() {
        return $this->pdo;
    }
}
