<?php
include '../connection/connection.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve product details from query parameters
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : ($_GET['id']);

// Fetch product details from the database
$product = null;
if ($product_id > 0) {
    $result = $conn->query("SELECT name, price, image FROM Products WHERE id = $product_id");
    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();
    }
}

// Handle order placement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $buyer_id = $_SESSION['user_id'];
    $total_amount = $product['price'];

    // Insert order into database
    $sql = "INSERT INTO Orders (user_id, total_amount) VALUES ('$buyer_id', '$total_amount')";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success mt-3'>Order placed successfully! Order ID: " . $conn->insert_id . "</div>";
    } else {
        echo "<div class='alert alert-danger mt-3'>Error placing order: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/user_checkout.css">
    <title>Checkout</title>
    
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
        <li class="nav-item"><img src="../uploads/logo1.png" width="50px" height="50px"></li>
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
    <div class="container">
        <h1>Checkout</h1>
        <?php if ($product): ?>
            <div class="card">
                <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">
                <div class="card-body">
                    <h5 class="card-title">Product Details</h5>
                    <p><strong>Product Name:</strong> <?php echo htmlspecialchars($product['name']); ?></p>
                    <p><strong>Price:</strong> Rs. <?php echo number_format($product['price'], 2); ?></p>
                </div>
            </div>
            <form method="POST" action="checkout.php?product_id=<?php echo $product_id; ?>">
                <input type="hidden" name="total_amount" value="<?php echo $product['price']; ?>">
                <button type="submit" class="btn btn-success mt-4" name="place_order">Place Order</button>
            </form>
        <?php else: ?>
            <div class="alert alert-danger">Product not found. Please go back and try again.</div>
        <?php endif; ?>
    </div>
    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Marketplace. All rights reserved.</p>
        <p><a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>