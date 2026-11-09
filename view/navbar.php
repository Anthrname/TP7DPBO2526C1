<?php
// Mendeteksi halaman aktif untuk styling tab navigasi
$currentPage = $_GET['page'] ?? 'dashboard';
?>
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
            <i class="bi bi-cpu-fill fs-4 text-warning"></i>
            <span>SmartLab <span class="fw-light small text-white-50">| DPBO TP 7</span></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>" href="index.php?page=dashboard">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'categories' ? 'active' : '' ?>" href="index.php?page=categories">
                        <i class="bi bi-tags me-1"></i> Kategori Alat
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'equipments' ? 'active' : '' ?>" href="index.php?page=equipments">
                        <i class="bi bi-hdd-network me-1"></i> Perangkat Lab
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'borrowings' ? 'active' : '' ?>" href="index.php?page=borrowings">
                        <i class="bi bi-arrow-left-right me-1"></i> Peminjaman
                    </a>
                </li>
            </ul>
            <div class="d-flex align-items-center text-white-50 small">
                <i class="bi bi-person-circle me-1 text-white"></i> Najib Nurohman (2509653)
            </div>
        </div>
    </div>
</nav>

<div class="container my-4">
    <!-- Alert Notifikasi Flash Message -->
    <?php if (isset($_SESSION['flash'])): ?>
        <div class="alert alert-<?= htmlspecialchars($_SESSION['flash']['type']) ?> alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <?php if ($_SESSION['flash']['type'] === 'success'): ?>
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <?php elseif ($_SESSION['flash']['type'] === 'danger'): ?>
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <?php else: ?>
                    <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                <?php endif; ?>
                <div><?= htmlspecialchars($_SESSION['flash']['message']) ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
