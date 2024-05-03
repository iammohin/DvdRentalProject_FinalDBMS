<?php
require '../db.php';  // Ensure the path to db.php is correct

$sql = "SELECT actor_id, first_name, last_name FROM actor";
$stmt = $pdo->query($sql);

echo "<h1>Actor List</h1>";
echo "<a href='add.php'>Add New Actor</a><br><br>";
echo "<table border='1'>";
echo "<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Actions</th></tr>";
while ($row = $stmt->fetch()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['actor_id']) . "</td>";
    echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
    echo "<td>";
    echo "<a href='view.php?id=" . $row['actor_id'] . "'>View</a> ";
    echo "<a href='edit.php?id=" . $row['actor_id'] . "'>Edit</a> ";
    echo "<a href='delete.php?id=" . $row['actor_id'] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
    echo "</td>";
    echo "</tr>";
}
echo "</table>";
?>
