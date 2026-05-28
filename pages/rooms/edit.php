<?php

$pageTitle = "Edit Ruangan";
$currentPage = "rooms";
$baseUrl = "../../";

require_once "../../config/database.php";

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = :id");
$stmt->execute(['id' => $id]);
$room = $stmt->fetch();

if (!$room) {
    header("Location: index.php");
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? "");
    $location = trim($_POST['location'] ?? "");
    $capacity = trim($_POST['capacity'] ?? "");
    $facilities = trim($_POST['facilities'] ?? "");
    $status = $_POST['status'] ?? "available";

    if ($name === "") {
        $errors[] = "Nama ruangan wajib diisi.";
    }

    if ($location === "") {
        $errors[] = "Lokasi ruangan wajib diisi.";
    }

    if ($capacity === "" || !is_numeric($capacity) || $capacity <= 0) {
        $errors[] = "Kapasitas harus berupa angka lebih dari 0.";
    }

    if (!in_array($status, ['available', 'maintenance', 'unavailable'])) {
        $errors[] = "Status ruangan tidak valid.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            UPDATE rooms
            SET 
                name = :name,
                location = :location,
                capacity = :capacity,
                facilities = :facilities,
                status = :status,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ");

        $stmt->execute([
            'name' => $name,
            'location' => $location,
            'capacity' => $capacity,
            'facilities' => $facilities,
            'status' => $status,
            'id' => $id
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
            <h1>Edit Ruangan</h1>
            <p>Perbarui data ruangan yang sudah terdaftar.</p>
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
                        <label class="form-label">Nama Ruangan</label>
                        <input 
                            type="text" 
                            name="name" 
                            class="form-control"
                            value="<?= htmlspecialchars($_POST['name'] ?? $room['name']); ?>"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <input 
                            type="text" 
                            name="location" 
                            class="form-control"
                            value="<?= htmlspecialchars($_POST['location'] ?? $room['location']); ?>"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kapasitas</label>
                        <input 
                            type="number" 
                            name="capacity" 
                            class="form-control"
                            value="<?= htmlspecialchars($_POST['capacity'] ?? $room['capacity']); ?>"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fasilitas</label>
                        <textarea 
                            name="facilities" 
                            class="form-control" 
                            rows="4"
                        ><?= htmlspecialchars($_POST['facilities'] ?? $room['facilities']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Ruangan</label>
                        <?php $selectedStatus = $_POST['status'] ?? $room['status']; ?>

                        <select name="status" class="form-select">
                            <option value="available" <?= $selectedStatus === 'available' ? 'selected' : ''; ?>>
                                Available
                            </option>
                            <option value="maintenance" <?= $selectedStatus === 'maintenance' ? 'selected' : ''; ?>>
                                Maintenance
                            </option>
                            <option value="unavailable" <?= $selectedStatus === 'unavailable' ? 'selected' : ''; ?>>
                                Unavailable
                            </option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            Simpan Perubahan
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