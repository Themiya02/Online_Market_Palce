<?php include '../connection/connection.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/create_main_category.css">
    <title>Create Main Categories</title>
    
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
                    <li class="nav-item">
                        <a class="nav-link" href="create_subcategory.php">Add Subcategories</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="card">
            <h1 class="card-title text-center">Create Main Category</h1>
            <form action="create_main_category.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Category Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter category name" required>
                </div>
                <button type="submit" class="btn btn-primary w-100" name="submit">Create Category</button>
            </form>
        
            <?php
            if (isset($_POST['submit'])) {
                $name = $conn->real_escape_string(trim($_POST['name']));

                // Check if the category already exists
                $check = $conn->query("SELECT id FROM MainCategories WHERE name = '$name'");
                if ($check->num_rows > 0) {
                    echo "<div class='alert alert-danger mt-3'>Category already exists.</div>";
                } else {
                    // Insert the new category into the database
                    $sql = "INSERT INTO MainCategories (name) VALUES ('$name')";
                    if ($conn->query($sql) === TRUE) {
                        echo "<div class='alert alert-success mt-3'>Main category created successfully!</div>";
                    } else {
                        echo "<div class='alert alert-danger mt-3'>Error: " . $conn->error . "</div>";
                    }
                }
            }
            ?>
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