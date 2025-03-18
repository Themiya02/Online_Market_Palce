<?php
session_start();
include '../connection/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart details for the logged-in user
$stmt = $conn->prepare("SELECT cart.product_id, cart.quantity, products.name, products.price, products.image 
                        FROM cart 
                        JOIN products ON cart.product_id = products.id 
                        WHERE cart.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/cart.css">
    
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
        <li class="nav-item"><img src="../uploads/logo1.png" width="120" height="50"></li>
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

    <div class="container py-5">
        <h1 class="mb-4">Your Shopping Cart</h1>

        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalPrice = 0;
                    while ($row = $result->fetch_assoc()) {
                        $total = $row['price'] * $row['quantity'];
                        $totalPrice += $total;
                        echo "<tr>";
                        echo "<td><img src='../uploads/{$row['image']}' width='80' height='80' alt='Product Image'></td>";
                        echo "<td class='align-middle'>{$row['name']}</td>";
                        echo "<td class='align-middle'>Rs. {$row['price']}</td>";
                        echo "<td class='align-middle'>{$row['quantity']}</td>";
                        echo "<td class='align-middle'>Rs. $total</td>";
                        echo "<td class='align-middle'>
                                <a href='checkout.php?product_id={$row['product_id']}' class='btn btn-checkout'>Checkout</a>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="total-container">
            <h3>Total: Rs. <?php echo $totalPrice; ?></h3>
            
        </div>
    </div>
    <footer>
        <p>&copy; 2024 Marketplace. All rights reserved.</p>
        <p><a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
