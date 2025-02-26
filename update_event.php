<?php
$conn = new mysqli("localhost", "root", "", "university_sports_club");

// Check if POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eventId = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;
    $name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : '';
    $organizer = isset($_POST['organizer']) ? $conn->real_escape_string($_POST['organizer']) : '';
    $email = isset($_POST['organizer_email']) ? $conn->real_escape_string($_POST['organizer_email']) : '';
    $location = isset($_POST['location']) ? $conn->real_escape_string($_POST['location']) : '';
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    $event_date = isset($_POST['event_date']) ? $conn->real_escape_string($_POST['event_date']) : '';
    $event_time = isset($_POST['event_time']) ? $conn->real_escape_string($_POST['event_time']) : '';

    // Check for missing values
    if (!$eventId || !$name || !$organizer || !$email || !$location || !$price || !$event_date || !$event_time) {
        echo "Error: All fields are required.";
        exit();
    }

    $query = "UPDATE events SET 
                name='$name', 
                organizer='$organizer', 
                organizer_email='$email', 
                location='$location', 
                price='$price', 
                event_date='$event_date', 
                event_time='$event_time'
              WHERE id=$eventId";

    if ($conn->query($query)) {
        echo "Event updated successfully!";
    } else {
        echo "Error updating event: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
