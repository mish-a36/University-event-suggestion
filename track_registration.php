<?php
session_start();
$conn = new mysqli("localhost", "root", "", "university_sports_club");

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit();
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);
$sport = trim($data['sport']);

if (empty($sport)) {
    echo json_encode(["status" => "error", "message" => "Invalid sport"]);
    exit();
}

// Check if the sport exists in the user's search history
$check_query = $conn->prepare("SELECT search_count FROM user_searches WHERE user_id=? AND sport_name=?");
$check_query->bind_param("is", $user_id, $sport);
$check_query->execute();
$result = $check_query->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $new_count = $row['search_count'] + 1;

    // Update the search count
    $update_query = $conn->prepare("UPDATE user_searches SET search_count=? WHERE user_id=? AND sport_name=?");
    $update_query->bind_param("iis", $new_count, $user_id, $sport);
    $update_query->execute();

    // Only add to recommendations if searched more than 2 times
    if ($new_count == 3) {
        $recommend_query = $conn->prepare("INSERT INTO user_recommendations (user_id, sport_name, recommended_by) 
                                           VALUES (?, ?, 'search') 
                                           ON DUPLICATE KEY UPDATE recommended_by='search'");
        $recommend_query->bind_param("is", $user_id, $sport);
        $recommend_query->execute();
    }
} else {
    // Insert a new entry in user_searches table
    $insert_query = $conn->prepare("INSERT INTO user_searches (user_id, sport_name, search_count) VALUES (?, ?, 1)");
    $insert_query->bind_param("is", $user_id, $sport);
    $insert_query->execute();
}

echo json_encode(["status" => "success", "message" => "Search tracked"]);
?>
