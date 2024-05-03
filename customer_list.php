<?php
include 'db.php';
$customers = $pdo->query("SELECT customer_id, first_name, last_name, email FROM customer")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Customers</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background-color: #f2f2f2; }
    a { color: #06c; text-decoration: none; }
</style>
</head>
<body>
<h1>Customer List</h1>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Edit</th>
    </tr>
    <?php foreach ($customers as $customer): ?>
    <tr>
        <td><?= htmlspecialchars($customer['customer_id']) ?></td>
        <td><?= htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']) ?></td>
        <td><?= htmlspecialchars($customer['email']) ?></td>
        <td><a href="manage_customer.php?id=<?= $customer['customer_id'] ?>">Edit</a></td>
    </tr>
    <?php endforeach; ?>
</table>
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
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    form label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    input[type="text"], input[type="email"], input[type="number"], select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        line-height: 1.5;
    }

    input[type="checkbox"] {
        margin-top: 10px;
    }

    button {
        grid-column: span 2;
        padding: 10px 20px;
        background-color: #0275d8;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
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

