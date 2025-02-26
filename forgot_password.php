<?php
$conn = new mysqli("localhost", "root", "", "university_sports_club");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $query = "UPDATE users SET password='$new_password' WHERE username='$username'";
    
    if ($conn->query($query)) {
        echo "Password updated successfully!";
        header("Location: userlogin.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/user.css">
</head>
<body>
    <div class="login-container">
        <h2>Reset Password</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Enter Username" required>
            <input type="password" name="new_password" placeholder="New Password" required>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
