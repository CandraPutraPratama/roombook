<?php
$currentPage = $currentPage ?? "";
?>

<div class="sidebar">
    <div class="sidebar-brand">
        <h4>RoomBook</h4>
        <p>Reservasi Kampus</p>
    </div>

    <nav class="sidebar-menu">
        <a href="<?= $baseUrl; ?>index.php" class="menu-link <?= $currentPage === 'dashboard' ? 'active' : ''; ?>">
            Dashboard
        </a>

        <a href="<?= $baseUrl; ?>pages/rooms/index.php" class="menu-link <?= $currentPage === 'rooms' ? 'active' : ''; ?>">
            Data Ruangan
        </a>

        <a href="<?= $baseUrl; ?>pages/reservations/index.php" class="menu-link <?= $currentPage === 'reservations' ? 'active' : ''; ?>">
            Reservasi
        </a>

        <a href="<?= $baseUrl; ?>pages/schedules/index.php" class="menu-link <?= $currentPage === 'schedules' ? 'active' : ''; ?>">
            Jadwal Ruangan
        </a>

        <a href="<?= $baseUrl; ?>pages/history/index.php" class="menu-link <?= $currentPage === 'history' ? 'active' : ''; ?>">
            Riwayat
        </a>
    </nav>
</div>