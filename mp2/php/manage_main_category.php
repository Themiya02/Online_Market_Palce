<?php include '../connection/connection.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/manage_main_category.css">
    <title>Manage Main Categories</title>
    
</head>
<body>

    <!-- Header -->
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
                        <a class="nav-link" href="upload_product.php">Add Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="create_main_category.php">Add categories</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="create_subcategory.php">Add Subcategories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_subcategory.php">Edit sub categories</a>
                    </li>
                    
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <h1>Manage Main Categories</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $categories = $conn->query("SELECT * FROM MainCategories ORDER BY created_at DESC");
                    while ($category = $categories->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td class="text-center">' . $category['id'] . '</td>';
                        echo '<td>' . htmlspecialchars($category['name']) . '</td>';
                        echo '<td class="text-center">';
                        echo '<a href="edit_main_category.php?id=' . $category['id'] . '" class="btn btn-warning btn-sm">Edit</a> ';
                        echo '<a href="delete_main_category.php?id=' . $category['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this category?\')">Delete</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Marketplace. All rights reserved.</p>
        <p><a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
