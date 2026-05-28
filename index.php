<?php

$pageTitle = "Dashboard";
$currentPage = "dashboard";
$baseUrl = "";

require_once "config/database.php";

$totalRooms = $pdo->query("SELECT COUNT(*) AS total FROM rooms")->fetch()['total'];
$totalAvailableRooms = $pdo->query("SELECT COUNT(*) AS total FROM rooms WHERE status = 'available'")->fetch()['total'];
$totalMaintenanceRooms = $pdo->query("SELECT COUNT(*) AS total FROM rooms WHERE status = 'maintenance'")->fetch()['total'];
$totalUnavailableRooms = $pdo->query("SELECT COUNT(*) AS total FROM rooms WHERE status = 'unavailable'")->fetch()['total'];

$totalReservations = $pdo->query("SELECT COUNT(*) AS total FROM reservations")->fetch()['total'];
$totalPending = $pdo->query("SELECT COUNT(*) AS total FROM reservations WHERE status = 'pending'")->fetch()['total'];
$totalApproved = $pdo->query("SELECT COUNT(*) AS total FROM reservations WHERE status = 'approved'")->fetch()['total'];
$totalRejected = $pdo->query("SELECT COUNT(*) AS total FROM reservations WHERE status = 'rejected'")->fetch()['total'];
$totalCompleted = $pdo->query("SELECT COUNT(*) AS total FROM reservations WHERE status = 'completed'")->fetch()['total'];

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

$todaySchedules = $pdo->query("
    SELECT 
        reservations.id,
        reservations.borrower_name,
        reservations.activity_name,
        reservations.start_time,
        reservations.end_time,
        reservations.status,
        rooms.name AS room_name
    FROM reservations
    JOIN rooms ON reservations.room_id = rooms.id
    WHERE reservations.reservation_date = CURRENT_DATE
    AND reservations.status IN ('pending', 'approved')
    ORDER BY reservations.start_time ASC
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
                        <small class="text-muted">Semua data ruangan</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <p class="stat-label">Ruangan Tersedia</p>
                        <h2 class="stat-value"><?= $totalAvailableRooms; ?></h2>
                        <small class="text-muted">Status available</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <p class="stat-label">Total Reservasi</p>
                        <h2 class="stat-value"><?= $totalReservations; ?></h2>
                        <small class="text-muted">Semua pengajuan</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <p class="stat-label">Menunggu</p>
                        <h2 class="stat-value"><?= $totalPending; ?></h2>
                        <small class="text-muted">Butuh konfirmasi</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-8">
                <div class="card content-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="mb-1">Reservasi Terbaru</h5>
                                <p class="text-muted mb-0">Daftar pengajuan reservasi terakhir.</p>
                            </div>

                            <a href="pages/reservations/index.php" class="btn btn-primary btn-sm">
                                Lihat Semua
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Peminjam</th>
                                        <th>Kegiatan</th>
                                        <th>Ruangan</th>
                                        <th>Tanggal</th>
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
                                                    <span class="badge-soft badge-<?= $reservation['status']; ?>">
                                                        <?= ucfirst($reservation['status']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                Belum ada data reservasi.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card content-card h-100">
                    <div class="card-body">
                        <h5 class="mb-1">Status Reservasi</h5>
                        <p class="text-muted mb-3">Ringkasan status pengajuan.</p>

                        <div class="summary-list">
                            <div class="summary-item">
                                <span>Pending</span>
                                <strong><?= $totalPending; ?></strong>
                            </div>

                            <div class="summary-item">
                                <span>Approved</span>
                                <strong><?= $totalApproved; ?></strong>
                            </div>

                            <div class="summary-item">
                                <span>Rejected</span>
                                <strong><?= $totalRejected; ?></strong>
                            </div>

                            <div class="summary-item">
                                <span>Completed</span>
                                <strong><?= $totalCompleted; ?></strong>
                            </div>
                        </div>

                        <hr>

                        <h5 class="mb-1">Status Ruangan</h5>
                        <p class="text-muted mb-3">Ringkasan ketersediaan ruangan.</p>

                        <div class="summary-list">
                            <div class="summary-item">
                                <span>Available</span>
                                <strong><?= $totalAvailableRooms; ?></strong>
                            </div>

                            <div class="summary-item">
                                <span>Maintenance</span>
                                <strong><?= $totalMaintenanceRooms; ?></strong>
                            </div>

                            <div class="summary-item">
                                <span>Unavailable</span>
                                <strong><?= $totalUnavailableRooms; ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card content-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-1">Jadwal Hari Ini</h5>
                        <p class="text-muted mb-0">Reservasi aktif untuk hari ini.</p>
                    </div>

                    <a href="pages/schedules/index.php" class="btn btn-dark btn-sm">
                        Buka Jadwal
                    </a>
                </div>

                <?php if (count($todaySchedules) > 0): ?>
                    <div class="schedule-list">
                        <?php foreach ($todaySchedules as $schedule): ?>
                            <div class="schedule-item">
                                <div class="schedule-time">
                                    <strong>
                                        <?= date('H:i', strtotime($schedule['start_time'])); ?> -
                                        <?= date('H:i', strtotime($schedule['end_time'])); ?>
                                    </strong>
                                </div>

                                <div class="schedule-detail">
                                    <h6 class="mb-1">
                                        <?= htmlspecialchars($schedule['activity_name']); ?>
                                    </h6>

                                    <p class="mb-1">
                                        <?= htmlspecialchars($schedule['room_name']); ?>
                                    </p>

                                    <small class="text-muted">
                                        Peminjam: <?= htmlspecialchars($schedule['borrower_name']); ?>
                                    </small>
                                </div>

                                <div class="schedule-status">
                                    <span class="badge-soft badge-<?= $schedule['status']; ?>">
                                        <?= ucfirst($schedule['status']); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <h5>Belum ada jadwal hari ini</h5>
                        <p class="text-muted mb-0">
                            Tidak ada reservasi aktif untuk hari ini.
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<?php include "includes/footer.php"; ?>