<?php
$conn = new mysqli("localhost", "root", "", "university_sports_club");

$query = "SELECT users.*, 
          IFNULL(GROUP_CONCAT(DISTINCT events.name SEPARATOR ', '), 'None') AS sports 
          FROM users 
          LEFT JOIN event_registrations ON users.id = event_registrations.user_id 
          LEFT JOIN events ON event_registrations.event_id = events.id 
          GROUP BY users.id";

$result = $conn->query($query);

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);
?>
