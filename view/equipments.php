<?php
/**
 * Janji:
 * Saya Najib Nurohman dengan NIM 2509653 mengerjakan Tugas Praktikum 7 dalam mata kuliah
 * Desain Pemrograman Berorientasi Objek (DPBO) untuk keberkahan-Nya maka saya tidak akan
 * melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.
 * 
 * View Manajemen Peralatan / Perangkat Lab
 * File: view/equipments.php
 */
?>
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h2 class="fw-bold text-dark mb-1">
            <i class="bi bi-hdd-network text-primary me-2"></i> Inventaris Perangkat Laboratorium
        </h2>
        <p class="text-muted mb-0">
            Kelola data perangkat keras, mikrokontroler, modul jaringan, dan peralatan lab lainnya.
        </p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <?php if (empty($categoriesList)): ?>
            <button type="button" class="btn btn-secondary btn-action shadow-sm" disabled title="Tambahkan kategori terlebih dahulu">
                <i class="bi bi-plus-lg"></i> Tambah Perangkat
            </button>
        <?php else: ?>
            <button type="button" class="btn btn-primary btn-action shadow-sm" data-bs-toggle="modal" data-bs-target="#addEquipmentModal">
                <i class="bi bi-plus-lg"></i> Tambah Perangkat
            </button>
        <?php endif; ?>
    </div>
</div>

<?php if (empty($categoriesList)): ?>
    <div class="alert alert-warning d-flex align-items-center shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
        <div>
            <strong>Perhatian:</strong> Belum ada data Kategori. Silakan <a href="index.php?page=categories" class="alert-link">tambahkan minimal satu Kategori</a> terlebih dahulu sebelum menambahkan data perangkat.
        </div>
    </div>
<?php endif; ?>

<div class="card content-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fw-semibold">Daftar Seluruh Perangkat Lab</h5>
        <span class="badge bg-primary-subtle text-primary fw-medium px-3 py-2 rounded-pill">
            Total: <?= count($equipmentsList) ?> Jenis Alat
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th style="width: 140px;">Kode Alat</th>
                        <th>Nama Perangkat</th>
                        <th>Kategori (FK)</th>
                        <th>Merk</th>
                        <th class="text-center" style="width: 90px;">Stok</th>
                        <th class="text-center" style="width: 130px;">Kondisi</th>
                        <th>Lokasi Simpan</th>
                        <th style="width: 130px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($equipmentsList)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="bi bi-cpu fs-2 d-block mb-1"></i>
                                Belum ada perangkat lab yang terdaftar.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($equipmentsList as $idx => $eq): ?>
                            <tr>
                                <td><?= $idx + 1 ?></td>
                                <td>
                                    <span class="badge bg-light text-dark border font-monospace px-2 py-1">
                                        <?= htmlspecialchars($eq['code']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark"><?= htmlspecialchars($eq['name']) ?></div>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary">
                                        <?= htmlspecialchars($eq['category_name']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($eq['brand'] ?: '-') ?></td>
                                <td class="text-center">
                                    <?php if ($eq['stock'] > 0): ?>
                                        <span class="badge bg-success-subtle text-success px-2 py-1 fs-6">
                                            <?= $eq['stock'] ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger px-2 py-1">
                                            Habis
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($eq['condition_status'] === 'Baik'): ?>
                                        <span class="badge-status badge-condition-good">Baik</span>
                                    <?php elseif ($eq['condition_status'] === 'Perlu Perbaikan'): ?>
                                        <span class="badge-status badge-condition-repair">Perlu Cek</span>
                                    <?php else: ?>
                                        <span class="badge-status badge-condition-broken">Rusak</span>
                                    <?php endif; ?>
                                </td>
                                <td class="small text-muted">
                                    <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($eq['location'] ?: 'Lab Utama') ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" 
                                                class="btn btn-outline-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editEquipmentModal"
                                                data-id="<?= $eq['id'] ?>"
                                                data-category_id="<?= $eq['category_id'] ?>"
                                                data-code="<?= htmlspecialchars($eq['code']) ?>"
                                                data-name="<?= htmlspecialchars($eq['name']) ?>"
                                                data-brand="<?= htmlspecialchars($eq['brand']) ?>"
                                                data-stock="<?= $eq['stock'] ?>"
                                                data-condition="<?= $eq['condition_status'] ?>"
                                                data-location="<?= htmlspecialchars($eq['location']) ?>"
                                                title="Edit Perangkat">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteConfirmModal"
                                                data-url="index.php?page=equipments&action=delete&id=<?= $eq['id'] ?>"
                                                data-item-name="Perangkat: <?= htmlspecialchars($eq['name']) ?> (Kode: <?= htmlspecialchars($eq['code']) ?>)"
                                                title="Hapus Perangkat">
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

<!-- Modal Tambah Perangkat -->
<div class="modal fade" id="addEquipmentModal" tabindex="-1" aria-labelledby="addEquipmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="index.php?page=equipments&action=add" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="addEquipmentModalLabel">
                    <i class="bi bi-plus-circle text-primary me-2"></i> Tambah Perangkat Lab Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="eq_category_id" class="form-label fw-semibold">Kategori Perangkat <span class="text-danger">*</span></label>
                        <select class="form-select" id="eq_category_id" name="category_id" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($categoriesList as $c): ?>
                                <option value="<?= $c['id'] ?>">
                                    [<?= htmlspecialchars($c['code']) ?>] <?= htmlspecialchars($c['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="eq_code" class="form-label fw-semibold">Kode Perangkat <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="eq_code" name="code" placeholder="Misal: EQ-NET-003" required maxlength="50">
                    </div>
                    <div class="col-md-8">
                        <label for="eq_name" class="form-label fw-semibold">Nama Perangkat <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="eq_name" name="name" placeholder="Misal: Switch Cisco Catalyst 2960" required maxlength="150">
                    </div>
                    <div class="col-md-4">
                        <label for="eq_brand" class="form-label fw-semibold">Merk / Produsen</label>
                        <input type="text" class="form-control" id="eq_brand" name="brand" placeholder="Misal: Cisco, MikroTik, Asus" maxlength="100">
                    </div>
                    <div class="col-md-4">
                        <label for="eq_stock" class="form-label fw-semibold">Jumlah Stok Awal <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="eq_stock" name="stock" value="1" min="0" required>
                    </div>
                    <div class="col-md-4">
                        <label for="eq_condition" class="form-label fw-semibold">Kondisi Fisik <span class="text-danger">*</span></label>
                        <select class="form-select" id="eq_condition" name="condition_status" required>
                            <option value="Baik" selected>Baik (Siap Pakai)</option>
                            <option value="Perlu Perbaikan">Perlu Perbaikan / Pengecekan</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="eq_location" class="form-label fw-semibold">Lokasi Penyimpanan</label>
                        <input type="text" class="form-control" id="eq_location" name="location" placeholder="Misal: Rak Lab A, Lemari 02" maxlength="100">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perangkat</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Perangkat -->
<div class="modal fade" id="editEquipmentModal" tabindex="-1" aria-labelledby="editEquipmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="index.php?page=equipments&action=edit" method="POST" class="modal-content">
            <input type="hidden" name="id" id="edit_eq_id">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="editEquipmentModalLabel">
                    <i class="bi bi-pencil-square text-warning me-2"></i> Edit Data Perangkat Lab
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="edit_eq_category_id" class="form-label fw-semibold">Kategori Perangkat <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_eq_category_id" name="category_id" required>
                            <?php foreach ($categoriesList as $c): ?>
                                <option value="<?= $c['id'] ?>">
                                    [<?= htmlspecialchars($c['code']) ?>] <?= htmlspecialchars($c['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="edit_eq_code" class="form-label fw-semibold">Kode Perangkat <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_eq_code" name="code" required maxlength="50">
                    </div>
                    <div class="col-md-8">
                        <label for="edit_eq_name" class="form-label fw-semibold">Nama Perangkat <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_eq_name" name="name" required maxlength="150">
                    </div>
                    <div class="col-md-4">
                        <label for="edit_eq_brand" class="form-label fw-semibold">Merk / Produsen</label>
                        <input type="text" class="form-control" id="edit_eq_brand" name="brand" maxlength="100">
                    </div>
                    <div class="col-md-4">
                        <label for="edit_eq_stock" class="form-label fw-semibold">Jumlah Stok <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="edit_eq_stock" name="stock" min="0" required>
                    </div>
                    <div class="col-md-4">
                        <label for="edit_eq_condition" class="form-label fw-semibold">Kondisi Fisik <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_eq_condition" name="condition_status" required>
                            <option value="Baik">Baik (Siap Pakai)</option>
                            <option value="Perlu Perbaikan">Perlu Perbaikan / Pengecekan</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="edit_eq_location" class="form-label fw-semibold">Lokasi Penyimpanan</label>
                        <input type="text" class="form-control" id="edit_eq_location" name="location" maxlength="100">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning text-dark fw-semibold">Perbarui Perangkat</button>
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
                <p class="mb-2">Apakah Anda yakin ingin menghapus data perangkat berikut?</p>
                <div class="fw-bold fs-6 text-dark bg-light p-2 rounded mb-3" id="deleteItemName"></div>
                <small class="text-danger">Riwayat peminjaman terkait alat ini juga akan terhapus!</small>
            </div>
            <div class="modal-footer border-0 justify-content-center pt-0">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                <a href="#" id="deleteConfirmBtn" class="btn btn-danger px-4">Ya, Hapus Data</a>
            </div>
        </div>
    </div>
</div>
