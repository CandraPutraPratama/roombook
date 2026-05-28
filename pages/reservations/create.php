<?php

$pageTitle = "Tambah Reservasi";
$currentPage = "reservations";
$baseUrl = "../../";

require_once "../../config/database.php";

$errors = [];

$roomsStmt = $pdo->query("
    SELECT * 
    FROM rooms 
    WHERE status = 'available' 
    ORDER BY name ASC
");

$rooms = $roomsStmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roomId = $_POST['room_id'] ?? "";
    $borrowerName = trim($_POST['borrower_name'] ?? "");
    $borrowerContact = trim($_POST['borrower_contact'] ?? "");
    $activityName = trim($_POST['activity_name'] ?? "");
    $reservationDate = $_POST['reservation_date'] ?? "";
    $startTime = $_POST['start_time'] ?? "";
    $endTime = $_POST['end_time'] ?? "";
    $purpose = trim($_POST['purpose'] ?? "");

    if ($roomId === "") {
        $errors[] = "Ruangan wajib dipilih.";
    }

    if ($borrowerName === "") {
        $errors[] = "Nama peminjam wajib diisi.";
    }

    if ($borrowerContact === "") {
        $errors[] = "Kontak peminjam wajib diisi.";
    }

    if ($activityName === "") {
        $errors[] = "Nama kegiatan wajib diisi.";
    }

    if ($reservationDate === "") {
        $errors[] = "Tanggal reservasi wajib diisi.";
    }

    if ($startTime === "") {
        $errors[] = "Jam mulai wajib diisi.";
    }

    if ($endTime === "") {
        $errors[] = "Jam selesai wajib diisi.";
    }

    if ($startTime !== "" && $endTime !== "" && $endTime <= $startTime) {
        $errors[] = "Jam selesai harus lebih besar dari jam mulai.";
    }

    if ($roomId !== "") {
        $roomCheck = $pdo->prepare("SELECT * FROM rooms WHERE id = :id AND status = 'available'");
        $roomCheck->execute(['id' => $roomId]);
        $selectedRoom = $roomCheck->fetch();

        if (!$selectedRoom) {
            $errors[] = "Ruangan tidak tersedia untuk reservasi.";
        }
    }

    if (empty($errors)) {
        $conflictStmt = $pdo->prepare("
            SELECT COUNT(*) AS total
            FROM reservations
            WHERE room_id = :room_id
            AND reservation_date = :reservation_date
            AND status IN ('pending', 'approved')
            AND (
                start_time < :end_time
                AND end_time > :start_time
            )
        ");

        $conflictStmt->execute([
            'room_id' => $roomId,
            'reservation_date' => $reservationDate,
            'start_time' => $startTime,
            'end_time' => $endTime
        ]);

        $conflict = $conflictStmt->fetch();

        if ($conflict['total'] > 0) {
            $errors[] = "Jadwal bentrok. Ruangan sudah dipakai pada tanggal dan jam tersebut.";
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            INSERT INTO reservations (
                room_id,
                borrower_name,
                borrower_contact,
                activity_name,
                reservation_date,
                start_time,
                end_time,
                purpose,
                status
            )
            VALUES (
                :room_id,
                :borrower_name,
                :borrower_contact,
                :activity_name,
                :reservation_date,
                :start_time,
                :end_time,
                :purpose,
                'pending'
            )
        ");

        $stmt->execute([
            'room_id' => $roomId,
            'borrower_name' => $borrowerName,
            'borrower_contact' => $borrowerContact,
            'activity_name' => $activityName,
            'reservation_date' => $reservationDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'purpose' => $purpose
        ]);

        header("Location: index.php");
        exit;
    }
}

?>

<?php include "../../includes/header.php"; ?>

<div class="app-wrapper">
    <?php include "../../includes/sidebar.php"; ?>

    <main class="main-content">
        <div class="page-header">
            <h1>Tambah Reservasi</h1>
            <p>Ajukan reservasi ruangan kampus.</p>
        </div>

        <div class="card content-card">
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Ruangan</label>
                        <select name="room_id" class="form-select">
                            <option value="">Pilih Ruangan</option>

                            <?php foreach ($rooms as $room): ?>
                                <option 
                                    value="<?= $room['id']; ?>"
                                    <?= ($_POST['room_id'] ?? '') == $room['id'] ? 'selected' : ''; ?>
                                >
                                    <?= htmlspecialchars($room['name']); ?> 
                                    - <?= htmlspecialchars($room['location']); ?> 
                                    - Kapasitas <?= htmlspecialchars($room['capacity']); ?> orang
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Peminjam</label>
                            <input 
                                type="text" 
                                name="borrower_name" 
                                class="form-control"
                                placeholder="Contoh: Candra"
                                value="<?= htmlspecialchars($_POST['borrower_name'] ?? ''); ?>"
                            >
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kontak Peminjam</label>
                            <input 
                                type="text" 
                                name="borrower_contact" 
                                class="form-control"
                                placeholder="Contoh: candra@email.com / 08xxxxxxxxxx"
                                value="<?= htmlspecialchars($_POST['borrower_contact'] ?? ''); ?>"
                            >
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Kegiatan</label>
                        <input 
                            type="text" 
                            name="activity_name" 
                            class="form-control"
                            placeholder="Contoh: Rapat Persiapan Seminar"
                            value="<?= htmlspecialchars($_POST['activity_name'] ?? ''); ?>"
                        >
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tanggal Reservasi</label>
                            <input 
                                type="date" 
                                name="reservation_date" 
                                class="form-control"
                                value="<?= htmlspecialchars($_POST['reservation_date'] ?? ''); ?>"
                            >
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Jam Mulai</label>
                            <input 
                                type="time" 
                                name="start_time" 
                                class="form-control"
                                value="<?= htmlspecialchars($_POST['start_time'] ?? ''); ?>"
                            >
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Jam Selesai</label>
                            <input 
                                type="time" 
                                name="end_time" 
                                class="form-control"
                                value="<?= htmlspecialchars($_POST['end_time'] ?? ''); ?>"
                            >
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keperluan</label>
                        <textarea 
                            name="purpose" 
                            class="form-control" 
                            rows="4"
                            placeholder="Jelaskan keperluan penggunaan ruangan..."
                        ><?= htmlspecialchars($_POST['purpose'] ?? ''); ?></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            Simpan Reservasi
                        </button>

                        <a href="index.php" class="btn btn-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php include "../../includes/footer.php"; ?>