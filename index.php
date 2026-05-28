<?php

$pageTitle = "Dashboard";
$currentPage = "dashboard";
$baseUrl = "";
require_once "config/database.php";

$totalRooms = $pdo->query("SELECT COUNT(*) AS total FROM rooms")->fetch()['total'];
$totalReservations = $pdo->query("SELECT COUNT(*) AS total FROM reservations")->fetch()['total'];
$totalPending = $pdo->query("SELECT COUNT(*) AS total FROM reservations WHERE status = 'pending'")->fetch()['total'];
$totalApproved = $pdo->query("SELECT COUNT(*) AS total FROM reservations WHERE status = 'approved'")->fetch()['total'];

$latestReservations = $pdo->query("
    SELECT 
        reservations.id,
        reservations.borrower_name,
        reservations.activity_name,
        reservations.reservation_date,
        reservations.start_time,
        reservations.end_time,
        reservations.status,
        rooms.name AS room_name
    FROM reservations
    JOIN rooms ON reservations.room_id = rooms.id
    ORDER BY reservations.created_at DESC
    LIMIT 5
")->fetchAll();

?>

<?php include "includes/header.php"; ?>

<div class="app-wrapper">
    <?php include "includes/sidebar.php"; ?>

    <main class="main-content">
        <div class="page-header">
            <h1>Dashboard</h1>
            <p>Ringkasan data reservasi ruangan kampus.</p>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <p class="stat-label">Total Ruangan</p>
                        <h2 class="stat-value"><?= $totalRooms; ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <p class="stat-label">Total Reservasi</p>
                        <h2 class="stat-value"><?= $totalReservations; ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <p class="stat-label">Pending</p>
                        <h2 class="stat-value"><?= $totalPending; ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <p class="stat-label">Disetujui</p>
                        <h2 class="stat-value"><?= $totalApproved; ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="card content-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-1">Reservasi Terbaru</h5>
                        <p class="text-muted mb-0">Daftar pengajuan reservasi terakhir.</p>
                    </div>
                    <a href="pages/reservations/index.php" class="btn btn-primary btn-sm">Lihat Semua</a>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Peminjam</th>
                                <th>Kegiatan</th>
                                <th>Ruangan</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($latestReservations) > 0): ?>
                                <?php foreach ($latestReservations as $reservation): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($reservation['borrower_name']); ?></td>
                                        <td><?= htmlspecialchars($reservation['activity_name']); ?></td>
                                        <td><?= htmlspecialchars($reservation['room_name']); ?></td>
                                        <td><?= date('d M Y', strtotime($reservation['reservation_date'])); ?></td>
                                        <td>
                                            <?= date('H:i', strtotime($reservation['start_time'])); ?> -
                                            <?= date('H:i', strtotime($reservation['end_time'])); ?>
                                        </td>
                                        <td>
                                            <span class="badge-soft badge-<?= $reservation['status']; ?>">
                                                <?= ucfirst($reservation['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        Belum ada data reservasi.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </main>
</div>

<?php include "includes/footer.php"; ?>