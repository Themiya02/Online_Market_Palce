<?php
session_start();
include '../connection/connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $quantity = 1; // Default quantity is 1

    // Check if the product already exists in the user's cart
    $stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If the product exists, update the quantity
        $cartItem = $result->fetch_assoc();
        $new_quantity = $cartItem['quantity'] + $quantity;

        $update_stmt = $conn->prepare("UPDATE cart SET quantity = ?, updated_at = NOW() WHERE user_id = ? AND product_id = ?");
        $update_stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        // If the product does not exist, insert a new row
        $insert_stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $insert_stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $insert_stmt->execute();
        $insert_stmt->close();
    }

    $stmt->close();

    // Redirect to view_posts.php or cart page
    header("Location: view_posts.php?cart_success=1");
    exit();
} else {
    echo "Invalid product ID.";
}
?>
