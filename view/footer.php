</div> <!-- /.container -->
</div> <!-- /.main-content -->

<footer class="mt-auto">
    <div class="container text-center">
        <p class="mb-1 fw-medium text-dark">
            SmartLab &copy; <?= date('Y') ?> - Sistem Manajemen Inventaris dan Peminjaman Alat Laboratorium
        </p>
        <p class="text-muted small mb-0">
            Dibuat untuk Tugas Praktikum 7 (TP 7) Desain Pemrograman Berorientasi Objek &bull; 
            <strong>Najib Nurohman</strong> (NIM: 2509653) &bull; Kelas C1 FPMIPA UPI
        </p>
    </div>
</footer>

<!-- Bootstrap 5.3 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<!-- Custom Modal Helper Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Modal Edit Kategori
    const editCategoryModal = document.getElementById('editCategoryModal');
    if (editCategoryModal) {
        editCategoryModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            document.getElementById('edit_cat_id').value = button.getAttribute('data-id');
            document.getElementById('edit_cat_code').value = button.getAttribute('data-code');
            document.getElementById('edit_cat_name').value = button.getAttribute('data-name');
            document.getElementById('edit_cat_description').value = button.getAttribute('data-description');
        });
    }

    // Modal Edit Peralatan
    const editEquipmentModal = document.getElementById('editEquipmentModal');
    if (editEquipmentModal) {
        editEquipmentModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            document.getElementById('edit_eq_id').value = button.getAttribute('data-id');
            document.getElementById('edit_eq_category_id').value = button.getAttribute('data-category_id');
            document.getElementById('edit_eq_code').value = button.getAttribute('data-code');
            document.getElementById('edit_eq_name').value = button.getAttribute('data-name');
            document.getElementById('edit_eq_brand').value = button.getAttribute('data-brand');
            document.getElementById('edit_eq_stock').value = button.getAttribute('data-stock');
            document.getElementById('edit_eq_condition').value = button.getAttribute('data-condition');
            document.getElementById('edit_eq_location').value = button.getAttribute('data-location');
        });
    }

    // Modal Edit Peminjaman
    const editBorrowingModal = document.getElementById('editBorrowingModal');
    if (editBorrowingModal) {
        editBorrowingModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            document.getElementById('edit_borrow_id').value = button.getAttribute('data-id');
            document.getElementById('edit_borrow_equipment_id').value = button.getAttribute('data-equipment_id');
            document.getElementById('edit_borrower_name').value = button.getAttribute('data-borrower_name');
            document.getElementById('edit_borrower_id').value = button.getAttribute('data-borrower_id');
            document.getElementById('edit_borrower_contact').value = button.getAttribute('data-borrower_contact');
            document.getElementById('edit_borrow_date').value = button.getAttribute('data-borrow_date');
            document.getElementById('edit_return_date').value = button.getAttribute('data-return_date') || '';
            document.getElementById('edit_borrow_status').value = button.getAttribute('data-status');
            document.getElementById('edit_borrow_notes').value = button.getAttribute('data-notes');
        });
    }

    // Modal Kembalikan Alat
    const returnModal = document.getElementById('returnModal');
    if (returnModal) {
        returnModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            document.getElementById('return_borrow_id').value = button.getAttribute('data-id');
            document.getElementById('return_borrower_name_text').textContent = button.getAttribute('data-borrower_name');
            document.getElementById('return_equipment_name_text').textContent = button.getAttribute('data-equipment_name');
        });
    }

    // Modal Konfirmasi Hapus Universal
    const deleteModal = document.getElementById('deleteConfirmModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const targetUrl = button.getAttribute('data-url');
            const itemName = button.getAttribute('data-item-name');
            document.getElementById('deleteItemName').textContent = itemName;
            document.getElementById('deleteConfirmBtn').setAttribute('href', targetUrl);
        });
    }
});
</script>
</body>
</html>
