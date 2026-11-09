<?php
/**
 * View Manajemen Peminjaman dan Pengembalian Alat
 * File: view/borrowings.php
 */
?>
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h2 class="fw-bold text-dark mb-1">
            <i class="bi bi-arrow-left-right text-primary me-2"></i> Transaksi Peminjaman & Pengembalian
        </h2>
        <p class="text-muted mb-0">
            Catat peminjaman perangkat laboratorium oleh mahasiswa, dosen, atau asisten praktikum.
        </p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <?php if (empty($availableEquipments)): ?>
            <button type="button" class="btn btn-secondary btn-action shadow-sm" disabled title="Tidak ada alat dengan stok tersedia">
                <i class="bi bi-plus-lg"></i> Catat Peminjaman Baru
            </button>
        <?php else: ?>
            <button type="button" class="btn btn-primary btn-action shadow-sm" data-bs-toggle="modal" data-bs-target="#addBorrowingModal">
                <i class="bi bi-plus-lg"></i> Catat Peminjaman Baru
            </button>
        <?php endif; ?>
    </div>
</div>

<?php if (empty($availableEquipments)): ?>
    <div class="alert alert-info d-flex align-items-center shadow-sm" role="alert">
        <i class="bi bi-info-circle-fill fs-4 me-3"></i>
        <div>
            <strong>Informasi:</strong> Saat ini belum ada perangkat yang tersedia untuk dipinjam (stok 0 atau kondisi rusak). Anda dapat menambahkan alat baru atau menambah stok di menu <a href="index.php?page=equipments" class="alert-link">Perangkat Lab</a>.
        </div>
    </div>
<?php endif; ?>

<div class="card content-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fw-semibold">Riwayat Seluruh Peminjaman</h5>
        <div class="d-flex gap-2">
            <span class="badge bg-warning-subtle text-warning-emphasis fw-medium px-3 py-2 rounded-pill">
                Dipinjam: <?= $activeCount ?>
            </span>
            <span class="badge bg-success-subtle text-success fw-medium px-3 py-2 rounded-pill">
                Dikembalikan: <?= $completedCount ?>
            </span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Data Peminjam</th>
                        <th>Perangkat Lab (FK)</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th class="text-center" style="width: 140px;">Status</th>
                        <th>Catatan Keperluan</th>
                        <th style="width: 160px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($borrowingsList)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-journal-x fs-2 d-block mb-1"></i>
                                Belum ada riwayat transaksi peminjaman.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($borrowingsList as $idx => $b): ?>
                            <tr>
                                <td><?= $idx + 1 ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($b['borrower_name']) ?></div>
                                    <div class="small text-muted">
                                        <i class="bi bi-person-badge me-1"></i><?= htmlspecialchars($b['borrower_id']) ?>
                                        <?php if ($b['borrower_contact']): ?>
                                            &bull; <i class="bi bi-telephone me-1"></i><?= htmlspecialchars($b['borrower_contact']) ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark"><?= htmlspecialchars($b['equipment_name']) ?></div>
                                    <div class="small">
                                        <span class="badge bg-light text-secondary border font-monospace"><?= htmlspecialchars($b['equipment_code']) ?></span>
                                        <span class="text-muted ms-1">(<?= htmlspecialchars($b['category_name']) ?>)</span>
                                    </div>
                                </td>
                                <td><?= date('d/m/Y', strtotime($b['borrow_date'])) ?></td>
                                <td>
                                    <?php if ($b['return_date']): ?>
                                        <span class="text-success fw-medium">
                                            <i class="bi bi-calendar-check me-1"></i><?= date('d/m/Y', strtotime($b['return_date'])) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
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
                                <td class="small text-secondary">
                                    <?= htmlspecialchars($b['notes'] ?: '-') ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <?php if ($b['status'] === 'Dipinjam'): ?>
                                            <button type="button" 
                                                    class="btn btn-outline-success" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#returnModal"
                                                    data-id="<?= $b['id'] ?>"
                                                    data-borrower_name="<?= htmlspecialchars($b['borrower_name']) ?>"
                                                    data-equipment_name="<?= htmlspecialchars($b['equipment_name']) ?>"
                                                    title="Kembalikan Alat">
                                                <i class="bi bi-arrow-return-left"></i>
                                            </button>
                                        <?php endif; ?>
                                        <button type="button" 
                                                class="btn btn-outline-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editBorrowingModal"
                                                data-id="<?= $b['id'] ?>"
                                                data-equipment_id="<?= $b['equipment_id'] ?>"
                                                data-borrower_name="<?= htmlspecialchars($b['borrower_name']) ?>"
                                                data-borrower_id="<?= htmlspecialchars($b['borrower_id']) ?>"
                                                data-borrower_contact="<?= htmlspecialchars($b['borrower_contact']) ?>"
                                                data-borrow_date="<?= $b['borrow_date'] ?>"
                                                data-return_date="<?= $b['return_date'] ?>"
                                                data-status="<?= $b['status'] ?>"
                                                data-notes="<?= htmlspecialchars($b['notes']) ?>"
                                                title="Edit Peminjaman">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteConfirmModal"
                                                data-url="index.php?page=borrowings&action=delete&id=<?= $b['id'] ?>"
                                                data-item-name="Peminjaman: <?= htmlspecialchars($b['borrower_name']) ?> (Alat: <?= htmlspecialchars($b['equipment_name']) ?>)"
                                                title="Hapus Catatan">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Peminjaman -->
<div class="modal fade" id="addBorrowingModal" tabindex="-1" aria-labelledby="addBorrowingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="index.php?page=borrowings&action=add" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="addBorrowingModalLabel">
                    <i class="bi bi-plus-circle text-primary me-2"></i> Form Peminjaman Alat Lab
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="borrow_equipment_id" class="form-label fw-semibold">Pilih Perangkat Lab <span class="text-danger">*</span></label>
                        <select class="form-select" id="borrow_equipment_id" name="equipment_id" required>
                            <option value="">-- Pilih Perangkat Tersedia --</option>
                            <?php foreach ($availableEquipments as $item): ?>
                                <option value="<?= $item['id'] ?>">
                                    [<?= htmlspecialchars($item['code']) ?>] <?= htmlspecialchars($item['name']) ?> (Sisa Stok: <?= $item['stock'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Hanya menampilkan alat dengan stok tersedia & kondisi baik/perlu cek.</small>
                    </div>
                    <div class="col-md-6">
                        <label for="borrower_name" class="form-label fw-semibold">Nama Peminjam <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="borrower_name" name="borrower_name" placeholder="Nama Mahasiswa/Dosen" required maxlength="150">
                    </div>
                    <div class="col-md-6">
                        <label for="borrower_id" class="form-label fw-semibold">NIM / NIP / ID Anggota <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="borrower_id" name="borrower_id" placeholder="Misal: 2509653" required maxlength="50">
                    </div>
                    <div class="col-md-6">
                        <label for="borrower_contact" class="form-label fw-semibold">No. HP / WhatsApp</label>
                        <input type="text" class="form-control" id="borrower_contact" name="borrower_contact" placeholder="08xxxxxxxxxx" maxlength="50">
                    </div>
                    <div class="col-md-6">
                        <label for="borrow_date" class="form-label fw-semibold">Tanggal Peminjaman <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="borrow_date" name="borrow_date" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-12">
                        <label for="notes" class="form-label fw-semibold">Catatan / Keperluan Peminjaman</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Contoh: Praktikum Jaringan Komputer Modul 4 di Lab A"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan & Kurangi Stok</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Kembalikan Alat -->
<div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="index.php?page=borrowings&action=return" method="POST" class="modal-content">
            <input type="hidden" name="id" id="return_borrow_id">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-success" id="returnModalLabel">
                    <i class="bi bi-arrow-return-left me-2"></i> Pengembalian Perangkat Lab
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Konfirmasi pengembalian perangkat untuk:</p>
                <div class="card bg-light border-0 p-3 mb-3">
                    <div class="mb-1"><small class="text-muted">Peminjam:</small> <strong id="return_borrower_name_text">-</strong></div>
                    <div><small class="text-muted">Perangkat:</small> <strong id="return_equipment_name_text">-</strong></div>
                </div>
                <div class="mb-3">
                    <label for="return_date" class="form-label fw-semibold">Tanggal Dikembalikan <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="return_date" name="return_date" value="<?= date('Y-m-d') ?>" required>
                </div>
                <small class="text-muted">Stok perangkat akan otomatis bertambah kembali 1 unit.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success fw-semibold">Konfirmasi Pengembalian</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Peminjaman -->
<div class="modal fade" id="editBorrowingModal" tabindex="-1" aria-labelledby="editBorrowingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="index.php?page=borrowings&action=edit" method="POST" class="modal-content">
            <input type="hidden" name="id" id="edit_borrow_id">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="editBorrowingModalLabel">
                    <i class="bi bi-pencil-square text-warning me-2"></i> Edit Data Peminjaman
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="edit_borrow_equipment_id" class="form-label fw-semibold">Perangkat Lab <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_borrow_equipment_id" name="equipment_id" required>
                            <?php foreach ($allEquipments as $item): ?>
                                <option value="<?= $item['id'] ?>">
                                    [<?= htmlspecialchars($item['code']) ?>] <?= htmlspecialchars($item['name']) ?> (Stok: <?= $item['stock'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="edit_borrower_name" class="form-label fw-semibold">Nama Peminjam <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_borrower_name" name="borrower_name" required maxlength="150">
                    </div>
                    <div class="col-md-6">
                        <label for="edit_borrower_id" class="form-label fw-semibold">NIM / NIP / ID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_borrower_id" name="borrower_id" required maxlength="50">
                    </div>
                    <div class="col-md-6">
                        <label for="edit_borrower_contact" class="form-label fw-semibold">No. HP / Kontak</label>
                        <input type="text" class="form-control" id="edit_borrower_contact" name="borrower_contact" maxlength="50">
                    </div>
                    <div class="col-md-6">
                        <label for="edit_borrow_status" class="form-label fw-semibold">Status Peminjaman <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_borrow_status" name="status" required>
                            <option value="Dipinjam">Dipinjam</option>
                            <option value="Dikembalikan">Dikembalikan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="edit_borrow_date" class="form-label fw-semibold">Tanggal Pinjam <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="edit_borrow_date" name="borrow_date" required>
                    </div>
                    <div class="col-md-6">
                        <label for="edit_return_date" class="form-label fw-semibold">Tanggal Pengembalian</label>
                        <input type="date" class="form-control" id="edit_return_date" name="return_date">
                    </div>
                    <div class="col-12">
                        <label for="edit_borrow_notes" class="form-label fw-semibold">Catatan / Keperluan</label>
                        <textarea class="form-control" id="edit_borrow_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning text-dark fw-semibold">Perbarui Data</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Universal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-danger fw-bold">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Penghapusan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="mb-2">Apakah Anda yakin ingin menghapus data peminjaman berikut?</p>
                <div class="fw-bold fs-6 text-dark bg-light p-2 rounded mb-3" id="deleteItemName"></div>
                <small class="text-danger">Jika saat ini statusnya 'Dipinjam', stok unit alat akan otomatis dikembalikan ke inventaris.</small>
            </div>
            <div class="modal-footer border-0 justify-content-center pt-0">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                <a href="#" id="deleteConfirmBtn" class="btn btn-danger px-4">Ya, Hapus Data</a>
            </div>
        </div>
    </div>
</div>
