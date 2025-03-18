<?php
include '../connection/connection.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to place an order.";
    exit();
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $buyer_id = $_SESSION['user_id'];
    $total_amount = floatval($_POST['total_amount']); // Assume this comes from the frontend

    // Insert the order into the Orders table
    $sql = "INSERT INTO Orders (buyer_id, total_amount) VALUES ('$user_id', '$total_amount')";
    if ($conn->query($sql) === TRUE) {
        $order_id = $conn->insert_id; // Get the last inserted order ID
        echo "Order placed successfully! Your order ID is: $order_id";
    } else {
        echo "Error placing order: " . $conn->error;
    }
}
?>
