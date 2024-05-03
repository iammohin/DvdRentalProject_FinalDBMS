<?php
include 'db.php';

$message = '';
$display_section = $_GET['view'] ?? 'search'; // Control display based on 'view' GET parameter

// Search DVDs
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    $search = $_POST['search'];
    $sql = "SELECT film.title, store.store_id, COUNT(DISTINCT inventory.inventory_id) AS available_copies
    FROM film
    JOIN inventory ON film.film_id = inventory.film_id
    JOIN store ON inventory.store_id = store.store_id
    LEFT JOIN rental ON inventory.inventory_id = rental.inventory_id AND rental.return_date IS NULL
    WHERE film.title ILIKE ?
    GROUP BY film.title, store.store_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$search%"]);
    $films = $stmt->fetchAll();
    if (!$films) {
        $message = 'No available DVDs found for this title.';
    }
}

// Display all currently rented DVDs
if ($display_section == 'rentals') {
    $sql = "SELECT rental.rental_id, film.title, customer.first_name, customer.last_name, rental.rental_date, rental.return_date
            FROM rental
            JOIN inventory ON rental.inventory_id = inventory.inventory_id
            JOIN film ON inventory.film_id = film.film_id
            JOIN customer ON rental.customer_id = customer.customer_id
            WHERE rental.return_date IS NULL";
    $rentals = $pdo->query($sql)->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>DVD Rental Store</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background-color: #f2f2f2; }
    form { margin-top: 20px; }
    input, button { padding: 10px; margin-right: 5px; }
    a { color: #06c; text-decoration: none; }
</style>
</head>
<body>
<h1>DVD Rental Store</h1>
<nav>
    <a href="index.php?view=search">Search DVDs</a> |
    <a href="index.php?view=rentals">View Rentals</a> |
    <a href="manage_customer.php">Add New Customer</a> | <!-- Link to add a new customer -->
    <a href="customer_list.php">Manage Customers</a> <!-- Link to edit existing customers, if you decide to implement a customer listing page -->
</nav>

<?php if ($display_section == 'search'): ?>
    <form method="post">
        Search for DVD: <input type="text" name="search" required>
        <button type="submit">Search</button>
    </form>
    <?php if ($message): ?>
    <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <?php if (!empty($films)): ?>
<table>
    <tr>
        <th>Title</th>
        <th>Store ID</th>
        <th>Available Copies</th>
        <th>Rent</th>
    </tr>
    <?php foreach ($films as $film): ?>
    <tr>
        <td><?= htmlspecialchars($film['title']) ?></td>
        <td><?= htmlspecialchars($film['store_id']) ?></td>
        <td><?= htmlspecialchars($film['available_copies']) ?></td>
        <td><a href="rent.php?inventory_id=<?= $film['store_id'] ?>&title=<?= urlencode($film['title']) ?>">Rent DVD</a></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>
<?php elseif ($display_section == 'rentals'): ?>
    <table>
        <tr>
            <th>Title</th>
            <th>Customer Name</th>
            <th>Rental Date</th>
            <th>Return</th>
        </tr>
        <?php foreach ($rentals as $rental): ?>
        <tr>
            <td><?= htmlspecialchars($rental['title']) ?></td>
            <td><?= htmlspecialchars($rental['first_name'] . ' ' . $rental['last_name']) ?></td>
            <td><?= htmlspecialchars($rental['rental_date']) ?></td>
            <td><a href="return.php?rental_id=<?= $rental['rental_id'] ?>">Mark as Returned</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

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

    nav a {
        color: #0275d8;
        padding: 10px;
        background-color: #f4f4f4;
        border: 1px solid #ddd;
        border-radius: 5px;
        transition: background-color 0.3s, color 0.3s;
    }

    nav a:hover, nav a:focus {
        background-color: #e9ecef;
        color: #0056b3;
        text-decoration: none;
    }

    /* Table Styles */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    }

    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f8f9fa;
        color: #495057;
    }

    /* Form Styles */
    form {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        margin-top: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    input[type="text"] {
        flex-grow: 1;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        line-height: 1.5;
    }

    button {
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
</style>
