<?php

require_once "../../config/database.php";

$id = $_GET['id'] ?? null;
$status = $_GET['status'] ?? null;

$allowedStatus = ['pending', 'approved', 'rejected', 'completed'];

if ($id && in_array($status, $allowedStatus)) {
    $stmt = $pdo->prepare("
        UPDATE reservations
        SET status = :status,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = :id
    ");

    $stmt->execute([
        'status' => $status,
        'id' => $id
    ]);
}

header("Location: index.php");
exit;