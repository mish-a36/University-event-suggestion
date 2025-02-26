<?php
session_start();
$conn = new mysqli("localhost", "root", "", "university_sports_club");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['token'] = bin2hex(random_bytes(32)); // Generate secure session token

            header("Location: events.php"); // Redirect to events page
            exit();
        } else {
            $error = "Invalid username or password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Login</title>
    <link rel="stylesheet" href="css/user.css">
</head>
<body>
    <div class="login-container">
        <h2>User Login</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        </form>
        <p><a href="forgot_password.php" style="color:rgb(26, 27, 28);">Forgot Password?</a></p>
        <p style="color: white">New User? <a href="register.php" style="color:rgb(0, 195, 255);">Register here</a></p>
    </div>
</body>
</html>
