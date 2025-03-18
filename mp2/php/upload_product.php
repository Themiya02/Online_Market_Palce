<?php include '../connection/connection.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/upload_product.css">
    <title>Upload Product</title>
    
</head>
<body>
    <!-- Navbar -->
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
                        <a class="nav-link" href="create_main_category.php">Add Categories</a>
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
        <?php
        // Display success or error message at the top
        if (isset($_POST['submit'])) {
            $name = $conn->real_escape_string($_POST['name']);
            $description = $conn->real_escape_string($_POST['description']);
            $price = $_POST['price'];
            $category_id = intval($_POST['category_id']);
            $subcategory_id = intval($_POST['subcategory_id']);
            $image = $_FILES['image']['name'];
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($image);

            // Validate and move image
            $check = getimagesize($_FILES['image']['tmp_name']);
            if ($check !== false) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    // Insert product into the database
                    $sql = "INSERT INTO Products (name, description, price, image, category_id, subcategory_id)
                            VALUES ('$name', '$description', '$price', '$image', $category_id, $subcategory_id)";
                    if ($conn->query($sql) === TRUE) {
                        echo "<div class='alert alert-success'>Product uploaded successfully!</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Error uploading image. Please try again.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>File is not an image.</div>";
            }
        }
        ?>

        <h1 class="text-center">Add New Product</h1>
        <form action="upload_product.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" required>
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">Main Category</label>
                <select class="form-select" id="category" name="category_id" required>
                    <option value="">Select Main Category</option>
                    <?php
                    $main_categories = $conn->query("SELECT * FROM MainCategories ORDER BY name");
                    while ($category = $main_categories->fetch_assoc()) {
                        echo '<option value="' . $category['id'] . '">' . htmlspecialchars($category['name']) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="subcategory" class="form-label">Subcategory</label>
                <select class="form-select" id="subcategory" name="subcategory_id" required>
                    <option value="">Select Subcategory</option>
                    <?php
                    $subcategories = $conn->query("SELECT * FROM Subcategories ORDER BY name");
                    while ($subcategory = $subcategories->fetch_assoc()) {
                        echo '<option value="' . $subcategory['id'] . '">' . htmlspecialchars($subcategory['name']) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Upload Image</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>

            <button type="submit" class="btn btn-primary w-100" name="submit">Upload Product</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>