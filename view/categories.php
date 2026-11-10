<?php
/**
 * Janji:
 * Saya Najib Nurohman dengan NIM 2509653 mengerjakan Tugas Praktikum 7 dalam mata kuliah
 * Desain Pemrograman Berorientasi Objek (DPBO) untuk keberkahan-Nya maka saya tidak akan
 * melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.
 * 
 * View Manajemen Kategori
 * File: view/categories.php
 */
?>
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h2 class="fw-bold text-dark mb-1">
            <i class="bi bi-tags text-primary me-2"></i> Manajemen Kategori Perangkat
        </h2>
        <p class="text-muted mb-0">
            Kelola klasifikasi dan pengelompokan perangkat laboratorium komputer.
        </p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <button type="button" class="btn btn-primary btn-action shadow-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="bi bi-plus-lg"></i> Tambah Kategori
        </button>
    </div>
</div>

<div class="card content-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fw-semibold">Daftar Kategori</h5>
        <span class="badge bg-primary-subtle text-primary fw-medium px-3 py-2 rounded-pill">
            Total: <?= count($categoriesList) ?> Kategori
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th style="width: 140px;">Kode Kategori</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th style="width: 150px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categoriesList)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-folder-x fs-2 d-block mb-1"></i>
                                Belum ada kategori yang ditambahkan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categoriesList as $idx => $cat): ?>
                            <tr>
                                <td><?= $idx + 1 ?></td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-dark border font-monospace px-2 py-1">
                                        <?= htmlspecialchars($cat['code']) ?>
                                    </span>
                                </td>
                                <td class="fw-semibold text-dark">
                                    <?= htmlspecialchars($cat['name']) ?>
                                </td>
                                <td class="text-secondary small">
                                    <?= htmlspecialchars($cat['description'] ?: '-') ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" 
                                                class="btn btn-outline-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editCategoryModal"
                                                data-id="<?= $cat['id'] ?>"
                                                data-code="<?= htmlspecialchars($cat['code']) ?>"
                                                data-name="<?= htmlspecialchars($cat['name']) ?>"
                                                data-description="<?= htmlspecialchars($cat['description']) ?>"
                                                title="Edit Kategori">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteConfirmModal"
                                                data-url="index.php?page=categories&action=delete&id=<?= $cat['id'] ?>"
                                                data-item-name="Kategori: <?= htmlspecialchars($cat['name']) ?> (Perangkat terkait juga akan terhapus!)"
                                                title="Hapus Kategori">
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

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="index.php?page=categories&action=add" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="addCategoryModalLabel">
                    <i class="bi bi-plus-circle text-primary me-2"></i> Tambah Kategori Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="cat_code" class="form-label fw-semibold">Kode Kategori <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="cat_code" name="code" placeholder="Misal: NET-01, IOT-02" required maxlength="20">
                    <small class="text-muted">Kode unik sebagai identifier kategori.</small>
                </div>
                <div class="mb-3">
                    <label for="cat_name" class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="cat_name" name="name" placeholder="Misal: Perangkat Jaringan" required maxlength="100">
                </div>
                <div class="mb-3">
                    <label for="cat_description" class="form-label fw-semibold">Deskripsi</label>
                    <textarea class="form-control" id="cat_description" name="description" rows="3" placeholder="Penjelasan singkat mengenai kategori alat ini..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kategori -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="index.php?page=categories&action=edit" method="POST" class="modal-content">
            <input type="hidden" name="id" id="edit_cat_id">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="editCategoryModalLabel">
                    <i class="bi bi-pencil-square text-warning me-2"></i> Edit Data Kategori
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="edit_cat_code" class="form-label fw-semibold">Kode Kategori <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="edit_cat_code" name="code" required maxlength="20">
                </div>
                <div class="mb-3">
                    <label for="edit_cat_name" class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="edit_cat_name" name="name" required maxlength="100">
                </div>
                <div class="mb-3">
                    <label for="edit_cat_description" class="form-label fw-semibold">Deskripsi</label>
                    <textarea class="form-control" id="edit_cat_description" name="description" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning text-dark fw-semibold">Perbarui Kategori</button>
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
                <p class="mb-2">Apakah Anda yakin ingin menghapus data berikut?</p>
                <div class="fw-bold fs-6 text-dark bg-light p-2 rounded mb-3" id="deleteItemName"></div>
                <small class="text-danger">Tindakan ini tidak dapat dibatalkan!</small>
            </div>
            <div class="modal-footer border-0 justify-content-center pt-0">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                <a href="#" id="deleteConfirmBtn" class="btn btn-danger px-4">Ya, Hapus Data</a>
            </div>
        </div>
    </div>
</div>
