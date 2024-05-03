<?php
include 'db.php';

$rental_id = $_GET['rental_id'] ?? 0;
if ($rental_id) {
    $sql = "UPDATE rental SET return_date = NOW() WHERE rental_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$rental_id]);
    $message = 'DVD returned successfully.';
} else {
    $message = 'No rental ID provided.';
}

header("Location: index.php?view=rentals&message=" . urlencode($message));
