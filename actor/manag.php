<?php
include '../db.php';
$actor_id = $_GET['id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    if ($actor_id) {
        // Update existing actor
        $sql = "UPDATE actor SET first_name = ?, last_name = ? WHERE actor_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$first_name, $last_name, $actor_id]);
    } else {
        // Add new actor
        $sql = "INSERT INTO actor (first_name, last_name) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$first_name, $last_name]);
    }
    header('Location: index.php');
    exit;
}

// Fetch actor for edit
$actor = null;
if ($actor_id) {
    $stmt = $pdo->prepare("SELECT * FROM actor WHERE actor_id = ?");
    $stmt->execute([$actor_id]);
    $actor = $stmt->fetch();
}
?>
<form method="post">
    First Name: <input type="text" name="first_name" value="<?= $actor['first_name'] ?? '' ?>" required><br>
    Last Name: <input type="text" name="last_name" value="<?= $actor['last_name'] ?? '' ?>" required><br>
    <input type="submit" value="Save">
</form>
