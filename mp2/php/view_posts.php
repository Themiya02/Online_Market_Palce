<?php include '../connection/connection.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/view_posts.css">
    <title>Marketplace Products</title>
    
</head>
<body>

    <!-- Header / Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <img src="../uploads/logo1.png" width="120" height="50" alt="Logo">
            <a class="navbar-brand" href="#">ONLINE MARKETPLACE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="view_posts.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="upload_product.php">Add Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="create_main_category.php">Add Categories</a></li>
                    <li class="nav-item"><a class="nav-link" href="create_subcategory.php">Add Subcategories</a></li>
                    <li class="nav-item"><a class="nav-link" href="cart.php"><img src="../uploads/cart.png" width="40" height="30" alt="Cart"></a></li>
                    <li class="nav-item"><a class="btn btn-danger text-white" href="login.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row">
            <!-- Products Section -->
            <div class="col-lg-12">
                <form method="GET" action="view_posts.php" class="search-bar mb-4">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search by product name, category, or subcategory..." name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button class="btn" type="submit">Search</button>
                    </div>
                </form>
                <div class="row">
                    <?php
                    $sql = "SELECT Products.id, Products.name, Products.description, Products.price, Products.image, 
                                   maincategories.name AS category_name, Subcategories.name AS subcategory_name
                            FROM Products
                            JOIN maincategories ON Products.category_id = maincategories.id
                            JOIN Subcategories ON Products.subcategory_id = Subcategories.id
                            WHERE 1"; // Base query

                    // Add search functionality
                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                        $search = $conn->real_escape_string($_GET['search']);
                        $sql .= " AND (Products.name LIKE '%$search%' 
                                    OR maincategories.name LIKE '%$search%' 
                                    OR Subcategories.name LIKE '%$search%')";
                    }

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="col-lg-4 col-md-6 mb-4">';
                            echo '<div class="card h-100">';
                            echo '<img src="../uploads/' . htmlspecialchars($row['image']) . '" class="card-img-top" alt="' . htmlspecialchars($row['name']) . '">';
                            echo '<div class="card-body">';
                            echo '<h5 class="card-title">' . htmlspecialchars($row['name']) . '</h5>';
                            echo '<p class="card-text">' . htmlspecialchars($row['description']) . '</p>';
                            echo '<p><strong>Category:</strong> ' . htmlspecialchars($row['category_name']) . '</p>';
                            echo '<p><strong>Subcategory:</strong> ' . htmlspecialchars($row['subcategory_name']) . '</p>';
                            echo '<p><strong>Price:</strong> Rs.' . htmlspecialchars($row['price']) . '</p>';
                            echo '</div>';
                            echo '<div class="card-footer text-center">';
                            echo '<a href="add_to_cart.php?id=' . $row['id'] . '" class="btn btn-primary">Add to cart</a>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p class="text-center">No products found for this search.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2024 Marketplace. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>