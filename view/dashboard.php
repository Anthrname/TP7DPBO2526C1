<?php
/**
 * View Dashboard
 * File: view/dashboard.php
 */
?>
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h2 class="fw-bold text-dark mb-1">
            <i class="bi bi-speedometer2 text-primary me-2"></i> Dashboard SmartLab
        </h2>
        <p class="text-muted mb-0">
            Sistem Informasi Inventaris dan Peminjaman Perangkat Laboratorium Komputer.
        </p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="index.php?page=borrowings" class="btn btn-primary btn-action shadow-sm">
            <i class="bi bi-plus-circle"></i> Catat Peminjaman
        </a>
    </div>
</div>

<!-- Statistik Ringkas -->
<div class="row g-3 mb-4">
    <!-- Card Kategori -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card border-0 bg-white shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-semibold">Kategori Alat</span>
                    <h3 class="fw-bold mt-2 mb-0 text-dark"><?= $totalCategories ?></h3>
                    <small class="text-primary"><a href="index.php?page=categories" class="text-decoration-none">Kelola Kategori &rarr;</a></small>
                </div>
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-tags-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Varian Alat -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card border-0 bg-white shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-semibold">Varian Perangkat</span>
                    <h3 class="fw-bold mt-2 mb-0 text-dark"><?= $totalEquipments ?></h3>
                    <small class="text-info"><a href="index.php?page=equipments" class="text-decoration-none">Lihat Inventaris &rarr;</a></small>
                </div>
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="bi bi-cpu-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Total Stok Unit -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card border-0 bg-white shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-semibold">Total Unit Fisik</span>
                    <h3 class="fw-bold mt-2 mb-0 text-dark"><?= $totalStock ?> Unit</h3>
                    <small class="text-muted">Siap Digunakan di Lab</small>
                </div>
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-boxes"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Peminjaman Aktif -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card border-0 bg-white shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-semibold">Peminjaman Aktif</span>
                    <h3 class="fw-bold mt-2 mb-0 text-warning"><?= $activeLoans ?></h3>
                    <small class="text-warning"><a href="index.php?page=borrowings" class="text-decoration-none">Perlu Pengembalian &rarr;</a></small>
                </div>
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Aktivitas Peminjaman Terkini -->
<div class="card content-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-clock-history text-primary fs-5"></i>
            <h5 class="card-title mb-0 fw-semibold">Riwayat Peminjaman Terkini</h5>
        </div>
        <a href="index.php?page=borrowings" class="btn btn-sm btn-outline-primary">
            Lihat Semua Peminjaman
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Peminjam</th>
                        <th>Perangkat Lab</th>
                        <th>Kategori</th>
                        <th>Tgl Pinjam</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentBorrowings)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-1"></i>
                                Belum ada riwayat peminjaman yang tercatat.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentBorrowings as $idx => $b): ?>
                            <tr>
                                <td><?= $idx + 1 ?></td>
                                <td>
                                    <div class="fw-bold"><?= htmlspecialchars($b['borrower_name']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($b['borrower_id']) ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border me-1"><?= htmlspecialchars($b['equipment_code']) ?></span>
                                    <strong><?= htmlspecialchars($b['equipment_name']) ?></strong>
                                </td>
                                <td><?= htmlspecialchars($b['category_name']) ?></td>
                                <td><?= date('d M Y', strtotime($b['borrow_date'])) ?></td>
                                <td>
                                    <?php if ($b['status'] === 'Dipinjam'): ?>
                                        <span class="badge-status badge-borrowed">
                                            <i class="bi bi-hourglass-split me-1"></i> Dipinjam
                                        </span>
                                    <?php else: ?>
                                        <span class="badge-status badge-returned">
                                            <i class="bi bi-check2-circle me-1"></i> Dikembalikan
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
