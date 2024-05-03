<?php
include 'db.php';

// Assuming `inventory_id` is passed as a GET parameter
$inventory_id = $_GET['inventory_id'] ?? 0;
$customer_id = $_POST['customer_id'] ?? 0;
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $customer_id) {
    $staff_id = 1; // Assume staff_id is set to 1 or is obtained through login session
    $rental_sql = "INSERT INTO rental (rental_date, inventory_id, customer_id, staff_id) VALUES (NOW(), ?, ?, ?)";
    $stmt = $pdo->prepare($rental_sql);
    $stmt->execute([$inventory_id, $customer_id, $staff_id]);
    $message = 'DVD rented successfully.';
}

$customers = $pdo->query("SELECT customer_id, first_name, last_name FROM customer")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Rent DVD</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; }
    input, button { padding: 10px; margin-right: 5px; }
    select { padding: 10px; }
</style>
</head>
<body>
<h1>Rent DVD</h1>
<?php if ($message): ?>
<p><?= htmlspecialchars($message) ?></p>
<?php else: ?>
<form method="post">
    <label for="customer_id">Choose a customer:</label>
    <select name="customer_id" required>
        <option value="">Select Customer</option>
        <?php foreach ($customers as $customer): ?>
        <option value="<?= $customer['customer_id'] ?>"><?= htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']) ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Rent</button>
</form>
<?php endif; ?>
<a href="index.php">Back to Search</a>
</body>
</html>
