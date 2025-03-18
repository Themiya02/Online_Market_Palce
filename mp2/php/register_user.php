<?php include '../connection/connection.php'; ?>
<?php
$message = ""; // Initialize the message variable

if (isset($_POST['register'])) {
    // Sanitize and validate inputs
    $username = $conn->real_escape_string(trim($_POST['username']));
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
    $email = $conn->real_escape_string(trim($_POST['email']));
    $user_type = $conn->real_escape_string($_POST['user_type']);

    // Check if the username or email already exists
    $check_user = $conn->query("SELECT * FROM Users WHERE username = '$username' OR email = '$email'");
    if ($check_user->num_rows > 0) {
        $message = "<div class='alert alert-danger'>Username or email already exists.</div>";
    } else {
        // Insert the new user into the database
        $sql = "INSERT INTO Users (username, password, email, user_type) 
                VALUES ('$username', '$password', '$email', '$user_type')";
        if ($conn->query($sql) === TRUE) {
            $message = "<div class='alert alert-success'>User registered successfully! Redirecting to login page...</div>";
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'login.php';
                }, 2000); // Redirect after 2 seconds
            </script>";
        } else {
            $message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/register_user.css">
    
</head>
<body>
    <div class="container">
        <h2>User Registration</h2>
        <form action="register_user.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter a secure password" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label for="user_type" class="form-label">User Type</label>
                <select class="form-select" id="user_type" name="user_type" required>
                    <option value="" disabled selected>Select user type</option>
                    <option value="buyer">Buyer</option>
                    <option value="seller">Seller</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100" name="register">Register</button>
            <p class="text">Do you have an account?<a href="login.php">login here</a></p>
        </form>
    </div>

    <div id="message-container">
        <?php echo $message; ?>
    </div>

   

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
