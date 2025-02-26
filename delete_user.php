<?php
$conn = new mysqli("localhost", "root", "", "university_sports_club");

$data = json_decode(file_get_contents("php://input"), true);
$user_id = isset($data['id']) ? intval($data['id']) : 0;

if (!$user_id) {
    echo "Error: Invalid User ID";
    exit();
}

// Delete related data first
$conn->query("DELETE FROM event_registrations WHERE user_id=$user_id");
$conn->query("DELETE FROM user_sports WHERE user_id=$user_id");
$conn->query("DELETE FROM users WHERE id=$user_id");

if ($conn->affected_rows > 0) {
    echo "User deleted successfully!";
} else {
    echo "Error: User not found!";
}
?>