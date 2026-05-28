<?php

$pageTitle = "Data Ruangan";
$currentPage = "rooms";
$baseUrl = "../../";

require_once "../../config/database.php";

$search = $_GET['search'] ?? "";
$status = $_GET['status'] ?? "";

$sql = "SELECT * FROM rooms WHERE 1=1";
$params = [];

if ($search !== "") {
    $sql .= " AND (name ILIKE :search OR location ILIKE :search OR facilities ILIKE :search)";
    $params['search'] = "%$search%";
}

if ($status !== "") {
    $sql .= " AND status = :status";
    $params['status'] = $status;
}

$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rooms = $stmt->fetchAll();

?>

<?php include "../../includes/header.php"; ?>

<div class="app-wrapper">
    <?php include "../../includes/sidebar.php"; ?>

    <main class="main-content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h1>Data Ruangan</h1>
                <p>Kelola data ruangan yang tersedia untuk reservasi.</p>
            </div>

            <a href="create.php" class="btn btn-primary">
                + Tambah Ruangan
            </a>
        </div>

        <div class="card content-card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-8">
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Cari nama ruangan, lokasi, atau fasilitas..."
                            value="<?= htmlspecialchars($search); ?>"
                        >
                    </div>

                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="available" <?= $status === 'available' ? 'selected' : ''; ?>>Available</option>
                            <option value="maintenance" <?= $status === 'maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                            <option value="unavailable" <?= $status === 'unavailable' ? 'selected' : ''; ?>>Unavailable</option>
                        </select>
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
                                <th>Nama Ruangan</th>
                                <th>Lokasi</th>
                                <th>Kapasitas</th>
                                <th>Fasilitas</th>
                                <th>Status</th>
                                <th width="180">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($rooms) > 0): ?>
                                <?php foreach ($rooms as $index => $room): ?>
                                    <tr>
                                        <td><?= $index + 1; ?></td>
                                        <td><?= htmlspecialchars($room['name']); ?></td>
                                        <td><?= htmlspecialchars($room['location']); ?></td>
                                        <td><?= htmlspecialchars($room['capacity']); ?> orang</td>
                                        <td><?= htmlspecialchars($room['facilities']); ?></td>
                                        <td>
                                            <span class="badge-soft badge-room-<?= $room['status']; ?>">
                                                <?= ucfirst($room['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="edit.php?id=<?= $room['id']; ?>" class="btn btn-warning btn-sm">
                                                Edit
                                            </a>

                                            <a 
                                                href="delete.php?id=<?= $room['id']; ?>" 
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin mau hapus ruangan ini?')"
                                            >
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        Data ruangan tidak ditemukan.
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