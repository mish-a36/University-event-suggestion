<?php
session_start();
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "university_sports_club");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "You must be logged in to register."]);
    exit();
}

$user_id = $_SESSION['user_id'];
$event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : "";

// Validate input
if (!$event_id || empty($phone)) {
    echo json_encode(["status" => "error", "message" => "Invalid event or missing phone number."]);
    exit();
}

// Fetch the event name (assuming the event name is the sport)
$sport_query = $conn->prepare("SELECT name FROM events WHERE id = ?");
$sport_query->bind_param("i", $event_id);
$sport_query->execute();
$sport_result = $sport_query->get_result();
$sport = $sport_result->fetch_assoc();
$sport_name = $sport['name'];

// Check if already registered for this event
$check_query = $conn->prepare("SELECT * FROM event_registrations WHERE user_id=? AND event_id=?");
$check_query->bind_param("ii", $user_id, $event_id);
$check_query->execute();
$result = $check_query->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "You are already registered for this event."]);
    exit();
}

// Insert into event registrations table
$query = $conn->prepare("INSERT INTO event_registrations (user_id, event_id, phone) VALUES (?, ?, ?)");
$query->bind_param("iis", $user_id, $event_id, $phone);

if ($query->execute()) {
    // Track registration for recommendations
    $track_url = "track_registration.php";
    $track_data = json_encode(["sport" => $sport_name]);

    $ch = curl_init($track_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $track_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_exec($ch);
    curl_close($ch);

    echo json_encode(["status" => "success", "message" => "Successfully registered for the event!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error registering for event: " . $conn->error]);
}
?>
