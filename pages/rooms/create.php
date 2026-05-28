<?php

$pageTitle = "Tambah Ruangan";
$currentPage = "rooms";
$baseUrl = "../../";

require_once "../../config/database.php";

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
            INSERT INTO rooms (name, location, capacity, facilities, status)
            VALUES (:name, :location, :capacity, :facilities, :status)
        ");

        $stmt->execute([
            'name' => $name,
            'location' => $location,
            'capacity' => $capacity,
            'facilities' => $facilities,
            'status' => $status
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
            <h1>Tambah Ruangan</h1>
            <p>Tambahkan data ruangan baru untuk kebutuhan reservasi.</p>
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
                            placeholder="Contoh: Ruang Kelas A101"
                            value="<?= htmlspecialchars($_POST['name'] ?? ''); ?>"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <input 
                            type="text" 
                            name="location" 
                            class="form-control" 
                            placeholder="Contoh: Gedung A Lantai 1"
                            value="<?= htmlspecialchars($_POST['location'] ?? ''); ?>"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kapasitas</label>
                        <input 
                            type="number" 
                            name="capacity" 
                            class="form-control" 
                            placeholder="Contoh: 40"
                            value="<?= htmlspecialchars($_POST['capacity'] ?? ''); ?>"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fasilitas</label>
                        <textarea 
                            name="facilities" 
                            class="form-control" 
                            rows="4"
                            placeholder="Contoh: AC, Proyektor, Whiteboard"
                        ><?= htmlspecialchars($_POST['facilities'] ?? ''); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Ruangan</label>
                        <select name="status" class="form-select">
                            <option value="available">Available</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            Simpan
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