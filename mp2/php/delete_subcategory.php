<?php
include '../connection/connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM Subcategories WHERE id = $id");
}

header("Location: manage_subcategory.php");
exit();
?>
