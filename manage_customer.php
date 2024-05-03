<?php
include 'db.php';
$message = '';
$customer_id = $_GET['id'] ?? '';

// Fetching all stores for the dropdown
$stores = $pdo->query("SELECT store_id FROM store")->fetchAll(PDO::FETCH_ASSOC);

// Check if we're updating an existing customer
if ($customer_id) {
    $stmt = $pdo->prepare("SELECT customer.*, address.address, address.city_id, address.postal_code, address.phone FROM customer
    JOIN address ON customer.address_id = address.address_id
    WHERE customer.customer_id = ?");
$stmt->execute([$customer_id]);
$customer = $stmt->fetch();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $store_id = $_POST['store_id'] ?? ''; // Getting store_id from the form
    $active = isset($_POST['active']) ? 1 : 0;

     // Address fields
     $address = $_POST['address'] ?? '';
     $city_id = $_POST['city_id'] ?? '';
     $postal_code = $_POST['postal_code'] ?? '';
     $phone = $_POST['phone'] ?? '';
     $district = $_POST['district'] ?? ''; // Ensure this line is added

    if ($customer_id) {
        // Update existing customer
        try {
            $sql = "UPDATE customer SET first_name = ?, last_name = ?, email = ?, store_id = ?, active = ? WHERE customer_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$first_name, $last_name, $email, $store_id, $active, $customer_id]);
            $message = "Customer updated successfully!";
        } catch (PDOException $e) {
            $message = "Error updating customer: " . $e->getMessage();
        }
    } else {
        // Add new customer
        try {

            $pdo->beginTransaction();

            if (isset($customer['address_id']) && $customer['address_id']) {
                // Update existing address
                $address_sql = "UPDATE address SET address = ?, city_id = ?, district = ?, postal_code = ?, phone = ? WHERE address_id = ?";
                $address_stmt = $pdo->prepare($address_sql);
                $address_stmt->execute([$address, $city_id, $district, $postal_code, $phone, $customer['address_id']]);
            } else {
                // Insert new address
                $address_sql = "INSERT INTO address (address, city_id, district, postal_code, phone) VALUES (?, ?, ?, ?, ?)";
                $address_stmt = $pdo->prepare($address_sql);
                $address_stmt->execute([$address, $city_id, $district, $postal_code, $phone]);
                $address_id = $pdo->lastInsertId();
            }

            $sql = "INSERT INTO customer (first_name, last_name, email, store_id, address_id, active) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$first_name, $last_name, $email, $store_id, $address_id, $active]);
            $message = "Customer added successfully!";
        } catch (PDOException $e) {
            $message = "Error adding customer: " . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= $customer_id ? 'Edit' : 'Add' ?> Customer</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; }
    input, button { padding: 10px; margin-right: 5px; }
    select { padding: 10px; margin-right: 5px; }
</style>
</head>
<body>
<h1><?= $customer_id ? 'Edit' : 'Add' ?> Customer</h1>
<?php if ($message): ?>
<p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>
<form method="post">
    <label for="first_name">First Name:</label>
    <input type="text" id="first_name" name="first_name" required value="<?= $customer['first_name'] ?? '' ?>"><br><br>
    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name" required value="<?= $customer['last_name'] ?? '' ?>"><br><br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?= $customer['email'] ?? '' ?>"><br><br>
    <label for="store_id">Store:</label>
    <select id="store_id" name="store_id" required>
        <option value="">Select Store ID</option>
        <?php foreach ($stores as $store): ?>
    <option value="<?= $store['store_id'] ?>" <?= (isset($customer['store_id']) && $customer['store_id'] == $store['store_id']) ? 'selected' : '' ?>>
        <?= htmlspecialchars($store['store_id']) ?>
    </option>
    <?php endforeach; ?>
    </select><br><br>
    <label for="active">Active:</label>
    <input type="checkbox" id="active" name="active" value="1" <?= (isset($customer['active']) && $customer['active']) ? 'checked' : '' ?>><br><br>
    <!-- Add these fields within your existing form -->
    <label for="address">Address:</label>
    <input type="text" id="address" name="address" required value="<?= $customer['address'] ?? '' ?>">

    <label for="city_id">City ID:</label>
    <input type="number" id="city_id" name="city_id" required value="<?= $customer['city_id'] ?? '' ?>">
    <label for="district">District:</label>
    <input type="text" id="district" name="district" required value="<?= $customer['district'] ?? '' ?>">

    <label for="postal_code">Postal Code:</label>
    <input type="text" id="postal_code" name="postal_code" value="<?= $customer['postal_code'] ?? '' ?>">

    <label for="phone">Phone:</label>
    <input type="text" id="phone" name="phone" value="<?= $customer['phone'] ?? '' ?>">

    <button type="submit"><?= $customer_id ? 'Update' : 'Add' ?> Customer</button>
</form>

<a href="index.php">Back to Home</a>

</body>
</html>

<style>
    /* General Styles */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f9f9f9;
        color: #333;
        margin: 0;
        padding: 20px;
    }

    h1 {
        color: #0275d8;
    }

    /* Form Styles */
    form {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        margin-top: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        align-items: start; /* Align form elements to the start of the form */
        max-width: 700px; /* Maximum width for inputs */
    }

    form label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    input[type="text"], input[type="email"], input[type="number"], select {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        line-height: 1.5;
        width: auto; /* Allows the input to grow with the content up to a max width */
        max-width: 300px; /* Maximum width for inputs */
    }

    input[type="checkbox"] {
        margin-top: 10px;
    }

    button {
        padding: 10px 20px;
        background-color: #0275d8;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        max-width: 300px; /* Maximum width for the button */
    }

    button:hover {
        background-color: #025aa5;
    }

    /* Message Styling */
    p {
        padding: 10px;
        background-color: #dff0d8;
        border: 1px solid #d4edda;
        border-radius: 5px;
        color: #3c763d;
    }

    a {
        color: #0275d8;
        text-decoration: none;
        padding: 10px;
        background-color: #f4f4f4;
        border: 1px solid #ddd;
        border-radius: 5px;
        display: inline-block;
        margin-top: 20px;
    }

    a:hover, a:focus {
        background-color: #e9ecef;
        color: #0056b3;
        text-decoration: none;
    }
</style>
