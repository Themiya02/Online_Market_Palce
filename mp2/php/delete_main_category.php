<?php
include '../connection/connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM MainCategories WHERE id = $id");
}

header("Location: manage_main_category.php");
exit();
?>
