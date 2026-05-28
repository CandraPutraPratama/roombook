<?php

$pageTitle = "Data Reservasi";
$currentPage = "reservations";
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
    WHERE 1=1
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
$reservations = $stmt->fetchAll();

?>

<?php include "../../includes/header.php"; ?>

<div class="app-wrapper">
    <?php include "../../includes/sidebar.php"; ?>

    <main class="main-content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h1>Data Reservasi</h1>
                <p>Kelola pengajuan reservasi ruangan kampus.</p>
            </div>

            <a href="create.php" class="btn btn-primary">
                + Tambah Reservasi
            </a>
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
                            <option value="pending" <?= $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="approved" <?= $status === 'approved' ? 'selected' : ''; ?>>Approved</option>
                            <option value="rejected" <?= $status === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                            <option value="completed" <?= $status === 'completed' ? 'selected' : ''; ?>>Completed</option>
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
                        <button type="submit" class="btn btn-dark w-100">Cari</button>
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
                                <th width="260">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($reservations) > 0): ?>
                                <?php foreach ($reservations as $index => $reservation): ?>
                                    <tr>
                                        <td><?= $index + 1; ?></td>

                                        <td>
                                            <strong><?= htmlspecialchars($reservation['borrower_name']); ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($reservation['borrower_contact']); ?>
                                            </small>
                                        </td>

                                        <td><?= htmlspecialchars($reservation['activity_name']); ?></td>

                                        <td>
                                            <?= htmlspecialchars($reservation['room_name']); ?>
                                            <br>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($reservation['room_location']); ?>
                                            </small>
                                        </td>

                                        <td>
                                            <?= date('d M Y', strtotime($reservation['reservation_date'])); ?>
                                        </td>

                                        <td>
                                            <?= date('H:i', strtotime($reservation['start_time'])); ?> -
                                            <?= date('H:i', strtotime($reservation['end_time'])); ?>
                                        </td>

                                        <td>
                                            <span class="badge-soft badge-<?= $reservation['status']; ?>">
                                                <?= ucfirst($reservation['status']); ?>
                                            </span>
                                        </td>

                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                <a href="edit.php?id=<?= $reservation['id']; ?>" class="btn btn-warning btn-sm">
                                                    Edit
                                                </a>

                                                <a 
                                                    href="delete.php?id=<?= $reservation['id']; ?>" 
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Yakin mau hapus reservasi ini?')"
                                                >
                                                    Hapus
                                                </a>

                                                <?php if ($reservation['status'] === 'pending'): ?>
                                                    <a 
                                                        href="update-status.php?id=<?= $reservation['id']; ?>&status=approved" 
                                                        class="btn btn-success btn-sm"
                                                        onclick="return confirm('Setujui reservasi ini?')"
                                                    >
                                                        Approve
                                                    </a>

                                                    <a 
                                                        href="update-status.php?id=<?= $reservation['id']; ?>&status=rejected" 
                                                        class="btn btn-outline-danger btn-sm"
                                                        onclick="return confirm('Tolak reservasi ini?')"
                                                    >
                                                        Reject
                                                    </a>
                                                <?php endif; ?>

                                                <?php if ($reservation['status'] === 'approved'): ?>
                                                    <a 
                                                        href="update-status.php?id=<?= $reservation['id']; ?>&status=completed" 
                                                        class="btn btn-info btn-sm"
                                                        onclick="return confirm('Tandai reservasi ini selesai?')"
                                                    >
                                                        Selesai
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        Data reservasi tidak ditemukan.
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

<?php include "../../includes/footer.php"; ?>