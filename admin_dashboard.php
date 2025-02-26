<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
$conn = new mysqli("localhost", "root", "", "university_sports_club");

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Load PHPMailer
require 'vendor/autoload.php';

// Function to Send Notification Emails
function sendNotificationEmail($to, $eventName, $eventDate, $eventTime, $location) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Change to your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'mishafathima36@gmail.com'; // Your email
        $mail->Password = 'hejg fhvg abpy flsc'; // Use an App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('mishafathima36@gmail.com', 'University Sports Club');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = "New Event: $eventName";
        $mail->Body = "
            <h3>New Event Available</h3>
            <p><strong>Event:</strong> $eventName</p>
            <p><strong>Date:</strong> $eventDate</p>
            <p><strong>Time:</strong> $eventTime</p>
            <p><strong>Location:</strong> $location</p>
            <p>Login to register now!</p>
            <br><br>
            <p><a href='http://yourwebsite.com/events.php'>Visit our page</a></p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Handle Event Addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_event'])) {
    $name = $_POST['name'];
    $organizer = $_POST['organizer'];
    $email = $_POST['organizer_email'];
    $location = $_POST['location'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $price = $_POST['price'];

    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    // Insert Event into Database
    $query = $conn->prepare("INSERT INTO events (name, organizer, organizer_email, image, location, event_date, event_time, price) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $query->bind_param("ssssssss", $name, $organizer, $email, $image, $location, $event_date, $event_time, $price);
    $query->execute();

    // Notify Users Who Are Interested in This Sport
    $recommendation_query = $conn->prepare("SELECT DISTINCT users.email 
                                            FROM users 
                                            JOIN user_recommendations ON users.id = user_recommendations.user_id 
                                            WHERE user_recommendations.sport_name = ?");
    $recommendation_query->bind_param("s", $name);
    $recommendation_query->execute();
    $result = $recommendation_query->get_result();

    while ($row = $result->fetch_assoc()) {
        sendNotificationEmail($row['email'], $name, $event_date, $event_time, $location);
    }

    echo "<script>alert('Event added successfully and notifications sent!');</script>";
}

// Fetch All Events
$events = $conn->query("SELECT * FROM events");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/admin_dashboard.css">
</head>
<body>

<header>
    <h1>Welcome, Admin</h1>
    <nav>
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="monitor_user.php">Monitor Users</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<section class="event-management">
    <h2>Manage Events</h2>

    <!-- Add Event Form -->
    <form id="eventForm" method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Event Name" required>
        <input type="text" name="organizer" placeholder="Organizer Name" required>
        <input type="email" name="organizer_email" placeholder="Organizer Email" required>
        <input type="text" name="location" placeholder="Location" required>
        <input type="date" name="event_date" required>
        <input type="time" name="event_time" required>
        <input type="number" name="price" placeholder="Price to Register" required>
        <input type="file" name="image" required>
        <button type="submit" name="add_event">Add Event</button>
    </form>

    <h3>Existing Events</h3>
    <div class="events-container">
        <?php while ($event = $events->fetch_assoc()) { ?>
            <div class="event-card" id="event_<?php echo $event['id']; ?>">
                <img src="uploads/<?php echo $event['image']; ?>" alt="Event Image">
                <h3><?php echo $event['name']; ?></h3>
                <p>Organizer: <?php echo $event['organizer']; ?></p>
                <p>Email: <?php echo $event['organizer_email']; ?></p>
                <p>Location: <?php echo $event['location']; ?></p>
                <p>Date: <?php echo $event['event_date']; ?></p>
                <p>Time: <?php echo $event['event_time']; ?></p>
                <p>Price: ₹<?php echo $event['price']; ?></p>
                <button class="edit-btn" data-id="<?php echo $event['id']; ?>">Edit</button>
                <button class="delete-btn" data-id="<?php echo $event['id']; ?>">Delete</button>
            </div>
        <?php } ?>
    </div>
    
    <!-- Edit Event Modal (Popup Form) -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Event</h2>
            <form id="editEventForm">
                <input type="hidden" id="edit_event_id" name="event_id">
                <input type="text" id="edit_name" name="name" placeholder="Event Name" required>
                <input type="text" id="edit_organizer" name="organizer" placeholder="Organizer Name" required>
                <input type="email" id="edit_email" name="organizer_email" placeholder="Organizer Email" required>
                <input type="text" id="edit_location" name="location" placeholder="Location" required>
                <input type="date" id="edit_event_date" name="event_date" placeholder = EventDate required>
                <input type="time" id="edit_event_time" name="event_time" placeholder = EventTime required>
                <input type="number" id="edit_price" name="price" placeholder = price required>
                <input type="file" name="image" required>
                <button type="submit">Update Event</button>
                
            </form>
        </div>
    </div>

</section>
<script src="admin.js"></script>
</body>
</html>
