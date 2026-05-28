<?php

require_once "../../config/database.php";

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

header("Location: index.php");
exit;