<?php
$conn = new mysqli("localhost", "root", "", "university_sports_club");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email']; // New Email Field
    $contact_number = $_POST['contact_number']; // New Contact Number Field
    $course = $_POST['course'];
    $year = $_POST['year'];
    $dob = $_POST['dob'];
    $college = $_POST['college'];
    $university = $_POST['university'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure hashing

    // Check if email or username already exists
    $check_query = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $check_query->bind_param("ss", $username, $email);
    $check_query->execute();
    $result = $check_query->get_result();

    if ($result->num_rows > 0) {
        echo "Error: Username or Email already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, username, email, contact_number, course, year, dob, college, university, password) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssisssss", $name, $username, $email, $contact_number, $course, $year, $dob, $college, $university, $password);

        if ($stmt->execute()) {
            echo "User registered successfully!";
            header("Location: userlogin.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Registration</title>
    <link rel="stylesheet" href="css/user.css">
</head>
<body style = "height: 150vh;">
    <div class="login-container">
        <h2>User Registration</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="text" name="contact_number" placeholder="Contact Number" required>
            <input type="text" name="course" placeholder="Course" required>
            <input type="number" name="year" placeholder="Year (1-4)" required min="1" max="4">
            <input type="date" name="dob" placeholder="Date of Birth" required>
            <input type="text" name="college" placeholder="College Name" required>
            <input type="text" name="university" placeholder="University Name" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
        <p style="color: white;">Already have an account? <a href="userlogin.php">Login here</a></p>
    </div>
</body>
</html>
