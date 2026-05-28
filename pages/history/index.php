<?php

$pageTitle = "Riwayat Reservasi";
$currentPage = "history";
$baseUrl = "../../";

require_once "../../config/database.php";

$search = $_GET['search'] ?? "";
$status = $_GET['status'] ?? "";
$date = $_GET['date'] ?? "";

$sql = "
    SELECT 
        reservations.*,
        rooms.name AS room_name,
        rooms.location AS room_location
    FROM reservations
    JOIN rooms ON reservations.room_id = rooms.id
    WHERE reservations.status IN ('rejected', 'completed')
";

$params = [];

if ($search !== "") {
    $sql .= " AND (
        reservations.borrower_name ILIKE :search OR
        reservations.borrower_contact ILIKE :search OR
        reservations.activity_name ILIKE :search OR
        rooms.name ILIKE :search
    )";
    $params['search'] = "%$search%";
}

if ($status !== "") {
    $sql .= " AND reservations.status = :status";
    $params['status'] = $status;
}

if ($date !== "") {
    $sql .= " AND reservations.reservation_date = :reservation_date";
    $params['reservation_date'] = $date;
}

$sql .= " ORDER BY reservations.reservation_date DESC, reservations.start_time ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$histories = $stmt->fetchAll();

?>

<?php include "../../includes/header.php"; ?>

<div class="app-wrapper">
    <?php include "../../includes/sidebar.php"; ?>

    <main class="main-content">
        <div class="page-header">
            <h1>Riwayat Reservasi</h1>
            <p>Data reservasi yang sudah selesai atau ditolak.</p>
        </div>

        <div class="card content-card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-5">
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Cari peminjam, kegiatan, kontak, atau ruangan..."
                            value="<?= htmlspecialchars($search); ?>"
                        >
                    </div>

                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="completed" <?= $status === 'completed' ? 'selected' : ''; ?>>
                                Completed
                            </option>
                            <option value="rejected" <?= $status === 'rejected' ? 'selected' : ''; ?>>
                                Rejected
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <input 
                            type="date" 
                            name="date" 
                            class="form-control"
                            value="<?= htmlspecialchars($date); ?>"
                        >
                    </div>

                    <div class="col-md-1">
                        <button type="submit" class="btn btn-dark w-100">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card content-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Peminjam</th>
                                <th>Kegiatan</th>
                                <th>Ruangan</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (count($histories) > 0): ?>
                                <?php foreach ($histories as $index => $history): ?>
                                    <tr>
                                        <td><?= $index + 1; ?></td>

                                        <td>
                                            <strong><?= htmlspecialchars($history['borrower_name']); ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($history['borrower_contact']); ?>
                                            </small>
                                        </td>

                                        <td><?= htmlspecialchars($history['activity_name']); ?></td>

                                        <td>
                                            <?= htmlspecialchars($history['room_name']); ?>
                                            <br>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($history['room_location']); ?>
                                            </small>
                                        </td>

                                        <td>
                                            <?= date('d M Y', strtotime($history['reservation_date'])); ?>
                                        </td>

                                        <td>
                                            <?= date('H:i', strtotime($history['start_time'])); ?> -
                                            <?= date('H:i', strtotime($history['end_time'])); ?>
                                        </td>

                                        <td>
                                            <span class="badge-soft badge-<?= $history['status']; ?>">
                                                <?= ucfirst($history['status']); ?>
                                            </span>
                                        </td>

                                        <td>
                                            <?= date('d M Y', strtotime($history['created_at'])); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        Belum ada data riwayat reservasi.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <small class="text-muted">
                        Riwayat hanya menampilkan reservasi dengan status rejected dan completed.
                    </small>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include "../../includes/footer.php"; ?>