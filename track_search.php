<?php
session_start();
$conn = new mysqli("localhost", "root", "", "university_sports_club");

header("Content-Type: application/json");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit();
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);
$sport = trim($data['sport']);

// Validate input
if (empty($sport)) {
    echo json_encode(["status" => "error", "message" => "Invalid sport"]);
    exit();
}

// Check if sport exists in search history
$check_query = $conn->prepare("SELECT search_count FROM user_searches WHERE user_id = ? AND sport_name = ?");
$check_query->bind_param("is", $user_id, $sport);
$check_query->execute();
$result = $check_query->get_result();

if ($result->num_rows > 0) {
    // If sport exists, update the count
    $update_query = $conn->prepare("UPDATE user_searches SET search_count = search_count + 1 WHERE user_id = ? AND sport_name = ?");
    $update_query->bind_param("is", $user_id, $sport);
    if (!$update_query->execute()) {
        echo json_encode(["status" => "error", "message" => "Failed to update search count: " . $conn->error]);
        exit();
    }
} else {
    // If sport doesn't exist, insert a new record
    $insert_query = $conn->prepare("INSERT INTO user_searches (user_id, sport_name, search_count) VALUES (?, ?, 1)");
    $insert_query->bind_param("is", $user_id, $sport);
    if (!$insert_query->execute()) {
        echo json_encode(["status" => "error", "message" => "Failed to insert search record: " . $conn->error]);
        exit();
    }
}

// Retrieve updated search count
$search_result = $conn->prepare("SELECT search_count FROM user_searches WHERE user_id = ? AND sport_name = ?");
$search_result->bind_param("is", $user_id, $sport);
$search_result->execute();
$search = $search_result->get_result()->fetch_assoc();

// If search count is 3 or more, recommend the event
if ($search['search_count'] >= 3) {
    $recommend_query = $conn->prepare("INSERT INTO user_recommendations (user_id, sport_name) VALUES (?, ?) 
                                       ON DUPLICATE KEY UPDATE notified = FALSE");
    $recommend_query->bind_param("is", $user_id, $sport);
    if (!$recommend_query->execute()) {
        echo json_encode(["status" => "error", "message" => "Failed to insert recommendation: " . $conn->error]);
        exit();
    }
}

echo json_encode(["status" => "success", "message" => "Search tracked successfully"]);
?>
