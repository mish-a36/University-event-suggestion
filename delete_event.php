<?php
$conn = new mysqli("localhost", "root", "", "university_sports_club");

$data = json_decode(file_get_contents("php://input"), true);
$eventId = $data['id'];

$conn->query("DELETE FROM events WHERE id=$eventId");
?>
