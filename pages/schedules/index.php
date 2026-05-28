<?php

$pageTitle = "Jadwal Ruangan";
$currentPage = "schedules";
$baseUrl = "../../";

require_once "../../config/database.php";

$selectedDate = $_GET['date'] ?? date('Y-m-d');
$selectedRoom = $_GET['room_id'] ?? "";

$roomsStmt = $pdo->query("
    SELECT id, name, location 
    FROM rooms 
    ORDER BY name ASC
");

$rooms = $roomsStmt->fetchAll();

$sql = "
    SELECT 
        reservations.*,
        rooms.name AS room_name,
        rooms.location AS room_location
    FROM reservations
    JOIN rooms ON reservations.room_id = rooms.id
    WHERE reservations.reservation_date = :reservation_date
    AND reservations.status IN ('pending', 'approved')
";

$params = [
    'reservation_date' => $selectedDate
];

if ($selectedRoom !== "") {
    $sql .= " AND reservations.room_id = :room_id";
    $params['room_id'] = $selectedRoom;
}

$sql .= " ORDER BY reservations.start_time ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$schedules = $stmt->fetchAll();

?>

<?php include "../../includes/header.php"; ?>

<div class="app-wrapper">
    <?php include "../../includes/sidebar.php"; ?>

    <main class="main-content">
        <div class="page-header">
            <h1>Jadwal Ruangan</h1>
            <p>Lihat jadwal penggunaan ruangan berdasarkan tanggal dan ruangan.</p>
        </div>

        <div class="card content-card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">Tanggal</label>
                        <input 
                            type="date" 
                            name="date" 
                            class="form-control"
                            value="<?= htmlspecialchars($selectedDate); ?>"
                        >
                    </div>

                    <div class="col-md-5">
                        <label class="form-label">Ruangan</label>
                        <select name="room_id" class="form-select">
                            <option value="">Semua Ruangan</option>

                            <?php foreach ($rooms as $room): ?>
                                <option 
                                    value="<?= $room['id']; ?>"
                                    <?= $selectedRoom == $room['id'] ? 'selected' : ''; ?>
                                >
                                    <?= htmlspecialchars($room['name']); ?> 
                                    - <?= htmlspecialchars($room['location']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-dark w-100">
                            Tampilkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card content-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-1">
                            Jadwal Tanggal <?= date('d M Y', strtotime($selectedDate)); ?>
                        </h5>
                        <p class="text-muted mb-0">
                            Menampilkan reservasi dengan status pending dan approved.
                        </p>
                    </div>

                    <a href="../reservations/create.php" class="btn btn-primary btn-sm">
                        + Tambah Reservasi
                    </a>
                </div>

                <?php if (count($schedules) > 0): ?>
                    <div class="schedule-list">
                        <?php foreach ($schedules as $schedule): ?>
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
                                        <span class="text-muted">
                                            - <?= htmlspecialchars($schedule['room_location']); ?>
                                        </span>
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
                        <h5>Belum ada jadwal</h5>
                        <p class="text-muted mb-0">
                            Tidak ada reservasi aktif pada tanggal ini.
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<?php include "../../includes/footer.php"; ?>