<?php
include '../db.php';
$stmt = $pdo->query("SELECT actor_id, first_name, last_name FROM actor");
?>
<a href="manage.php">Add New Actor</a>
<table>
    <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $stmt->fetch()): ?>
    <tr>
        <td><?= htmlspecialchars($row['actor_id']) ?></td>
        <td><?= htmlspecialchars($row['first_name']) ?></td>
        <td><?= htmlspecialchars($row['last_name']) ?></td>
        <td>
            <a href="manage.php?id=<?= $row['actor_id'] ?>">Edit</a>
            <a href="process.php?id=<?= $row['actor_id'] ?>&action=delete" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
