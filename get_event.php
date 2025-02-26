<?php
$conn = new mysqli("localhost", "root", "", "university_sports_club");

$eventId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$eventId) {
    echo json_encode(["error" => "Invalid event ID"]);
    exit();
}

$result = $conn->query("SELECT * FROM events WHERE id=$eventId");

if ($result->num_rows > 0) {
    $event = $result->fetch_assoc();
    echo json_encode($event);
} else {
    echo json_encode(["error" => "Event not found"]);
}
?>
