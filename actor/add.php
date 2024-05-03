<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['first_name']) && !empty($_POST['last_name'])) {
    require '../db.php';  // Ensure the path to db.php is correct

    $sql = "INSERT INTO actor (first_name, last_name) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_POST['first_name'], $_POST['last_name']]);

    header("Location: list.php");
    exit();
}

?>

<h1>Add New Actor</h1>
<form method="post">
    First Name: <input type="text" name="first_name" required><br>
    Last Name: <input type="text" name="last_name" required><br>
    <input type="submit" value="Add Actor">
</form>
