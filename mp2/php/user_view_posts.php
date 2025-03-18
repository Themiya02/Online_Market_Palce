<?php include '../connection/connection.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/user_view_posts.css">
    <title>Marketplace Products</title>
    
</head>
<body>

    <!-- Header / Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">ONLINE MARKETPLACE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="view_posts.php"><b>Home</b></a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php"><b>About</b></a></li>
                    <li class="nav-item"><a class="nav-link" href="upload_product.php"><b>Add Products</b></a></li>
                    <li class="nav-item"><a class="nav-link" href="cart.php"><img src="../uploads/cart.png" width="40px" height="30px"></a></li>
                    <a href="login.php"><button class="logout" type="#">LOGOUT</button></a>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row">
            <!-- Sidebar for Filters -->
            <div class="col-lg-3 filters-sidebar">
                <h4>Filter by Category</h4>
                <ul class="list-group">
                    <li class="list-group-item">
                        <a href="view_posts.php" class="<?php echo !isset($_GET['category_id']) && !isset($_GET['subcategory_id']) ? 'active' : ''; ?>">All Categories</a>
                    </li>
                    <?php
                    $categories = $conn->query("SELECT * FROM maincategories");
                    while ($category = $categories->fetch_assoc()) {
                        $activeClass = (isset($_GET['category_id']) && $_GET['category_id'] == $category['id']) ? 'active' : '';
                        echo '<li class="list-group-item ' . $activeClass . '"><a href="view_posts.php?category_id=' . $category['id'] . '">' . htmlspecialchars($category['name']) . '</a></li>';
                    }
                    ?>
                </ul>
                <h4 class="mt-4">Filter by Subcategory</h4>
                <ul class="list-group">
                    <?php
                    $subcategories = $conn->query("SELECT Subcategories.id, Subcategories.name, maincategories.name AS category_name 
                                                   FROM Subcategories 
                                                   JOIN maincategories ON Subcategories.main_category_id = maincategories.id");
                    while ($subcategory = $subcategories->fetch_assoc()) {
                        $activeClass = (isset($_GET['subcategory_id']) && $_GET['subcategory_id'] == $subcategory['id']) ? 'active' : '';
                        echo '<li class="list-group-item ' . $activeClass . '"><a href="view_posts.php?subcategory_id=' . $subcategory['id'] . '">' . htmlspecialchars($subcategory['name']) . ' (' . htmlspecialchars($subcategory['category_name']) . ')</a></li>';
                    }
                    ?>
                </ul>
            </div>

            <!-- Main Content (Scrollable Products Section) -->
            <div class="col-lg-9 products-container">
                <!-- Search Bar -->
                <form method="GET" action="view_posts.php" class="search-bar">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search for products, categories, or subcategories..." name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button class="btn btn-primary" type="submit">Search</button>
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

                    // Add filtering conditions
                    if (isset($_GET['category_id'])) {
                        $category_id = intval($_GET['category_id']);
                        $sql .= " AND Products.category_id = $category_id";
                    } elseif (isset($_GET['subcategory_id'])) {
                        $subcategory_id = intval($_GET['subcategory_id']);
                        $sql .= " AND Products.subcategory_id = $subcategory_id";
                    }

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
                            echo '<div class="col-lg-4 col-md-6 col-sm-12 mb-4">';
                            echo '<div class="card h-100">';
                            echo '<img src="../uploads/' . $row['image'] . '" class="card-img-top" alt="' . htmlspecialchars($row['name']) . '">';
                            echo '<div class="card-body d-flex flex-column">';
                            echo '<h5 class="card-title text-truncate">' . htmlspecialchars($row['name']) . '</h5>';
                            echo '<p class="card-text text-truncate">' . htmlspecialchars($row['description']) . '</p>';
                            echo '<p><strong>Category:</strong> ' . htmlspecialchars($row['category_name']) . '</p>';
                            echo '<p><strong>Subcategory:</strong> ' . htmlspecialchars($row['subcategory_name']) . '</p>';
                            echo '<p class="text-success"><strong>Price:</strong> $' . htmlspecialchars($row['price']) . '</p>';
                            echo '<a href="add_to_cart.php?id=' . $row['id'] . '" class="btn btn-primary mt-auto">Buy</a>';
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
        <p>&copy; 2024 Marketplace. All rights reserved.</p>
        <p><a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
