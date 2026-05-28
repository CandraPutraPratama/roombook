<?php

require_once "config/database.php";

$query = $pdo->query("SELECT COUNT(*) AS total_rooms FROM rooms");
$result = $query->fetch();

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoomBook - Dashboard</title>
</head>
<body>
    <h1>RoomBook</h1>
    <p>Sistem Reservasi Ruangan Kampus</p>

    <hr>

    <h2>Tes Koneksi Database</h2>
    <p>Total ruangan: <?= $result['total_rooms']; ?></p>
</body>
</html>