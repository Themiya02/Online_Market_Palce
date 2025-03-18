<?php
session_start();
include '../connection/connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if product_id is provided
if (!isset($_GET['product_id']) || empty($_GET['product_id'])) {
    echo "<div class='alert alert-danger'>Invalid product. Please go back to your cart.</div>";
    exit();
}

$product_id = intval($_GET['product_id']);

// Fetch product details from the database
$stmt = $conn->prepare("SELECT name, price, image FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='alert alert-danger'>Product not found. Please go back to your cart.</div>";
    exit();
}

$product = $result->fetch_assoc();

// Handle order placement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = 1; // Default quantity, or fetch it from cart if required
    $total_amount = $product['price'] * $quantity;

    // Insert order into the database
    $stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, quantity, total_amount) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $user_id, $product_id, $quantity, $total_amount);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success mt-3'>Order placed successfully! Order ID: " . $stmt->insert_id . "</div>";
        // Optionally, remove the item from the cart
        $deleteCartStmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $deleteCartStmt->bind_param("ii", $user_id, $product_id);
        $deleteCartStmt->execute();
    } else {
        echo "<div class='alert alert-danger mt-3'>Error placing order: " . $stmt->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/checkout.css">
    <title>Checkout</title>
    
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <li class="nav-item"><img src="../uploads/logo1.png" width="120" height="50" alt="Logo"></li>
            <a class="navbar-brand" href="view_posts.php">MARKETPLACE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="view_posts.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="upload_product.php">Add Products</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container checkout-container">
        <div class="checkout-header">
            <h1>Checkout</h1>
        </div>

        <div class="product-card">
            <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">
            <div class="product-details">
                <h5><?php echo htmlspecialchars($product['name']); ?></h5>
                <p><strong>Price:</strong> Rs. <?php echo number_format($product['price'], 2); ?></p>
            </div>
        </div>

        <!-- Order Confirmation Form -->
        <form method="POST">
            <button type="submit" class="btn btn-primary">Place Order</button>
        </form>

        <!-- Back to Cart Button -->
        <a href="cart.php" class="btn btn-secondary">Back to Cart</a>
    </div>

    <footer>
        <p>&copy; 2024 Marketplace. All rights reserved.</p>
        <p><a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
