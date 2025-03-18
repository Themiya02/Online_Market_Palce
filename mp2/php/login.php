<?php include '../connection/connection.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/login.css">
    
</head>
<body>
    <div class="container">
        <h2>Welcome Back</h2>
        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="login">Login</button>
        </form>

        <?php
        if (isset($_POST['login'])) {
            // Retrieve user input
            $username = $conn->real_escape_string(trim($_POST['username']));
            $password = $_POST['password'];

            // Check if user exists in the database
            $sql = "SELECT * FROM Users WHERE username = '$username'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Start session and store user info
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['user_type'] = $user['user_type'];

                    // Redirect based on user type
                    if ($user['user_type'] === 'admin') {
                        header("Location: view_posts.php");
                    } elseif ($user['user_type'] === 'seller') {
                        header("Location: view_posts.php");
                    } else {
                        header("Location: user_view_posts.php");
                    }
                    exit();
                } else {
                    echo "<div class='alert alert-danger'>Invalid password. Please try again.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>No user found with that username.</div>";
            }
        }
        ?>

        <div class="footer">
            <p>Don't have an account? <a href="register_user.php">Sign up now</a></p>
        </div>
    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
