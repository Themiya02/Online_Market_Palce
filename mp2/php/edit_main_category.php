<?php
include '../connection/connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $category = $conn->query("SELECT * FROM MainCategories WHERE id = $id")->fetch_assoc();

    if (isset($_POST['update'])) {
        $name = $conn->real_escape_string(trim($_POST['name']));
        $conn->query("UPDATE MainCategories SET name = '$name' WHERE id = $id");
        header("Location: manage_main_category.php");
        exit();
    }
} else {
    header("Location: manage_main_categories.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Main Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1>Edit Main Category</h1>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" name="update">Update Category</button>
        </form>
    </div>
</body>
</html>
