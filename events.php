<?php
session_start();
$conn = new mysqli("localhost", "root", "", "university_sports_club");

$loggedIn = isset($_SESSION['user_id']); // Check if user is logged in
$user_name = "Guest"; // Default name for guests
$user_id = $loggedIn ? $_SESSION['user_id'] : null;

// Fetch user name if logged in
if ($loggedIn) {
    $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_name = $user['name']; // Store user name
    }
}

// Fetch all events
$events = $conn->query("SELECT * FROM events");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Events</title>
    <link rel="stylesheet" href="css/events.css">
</head>
<body>

<header>
    <h1>Upcoming Sports Events</h1>
    <h2><?php echo "Welcome, " . $user_name; ?></h2>
    <nav>
        <input type="text" id="searchBar" placeholder="Search events..." onkeyup="filterEvents()">
        <button id="searchButton" style="width: 40px; height: 40px; background: white; border: none; cursor: pointer;">
    <img src="images/search_icon.png" alt="Search" style="width: 100%; height: 100%;">
</button>
    </button>
        <?php if ($loggedIn) { ?>
            <a href="logout.php" class="logout-btn">Logout</a>
        <?php } else { ?>
            <a href="userlogin.php" class="login-btn">Login</a>
        <?php } ?>
    </nav>
</header>

<!-- Suggested Sports Section -->
<section class="recommended-sports" style="box-shadow: #333; padding: 20px; margin: 20px 0; background-color:rgba(227, 30, 30, 0.23); border-radius: 10px;">
    <h2 style="font-size: 24px; margin-bottom: 20px; color: #333;">Suggested Sports</h2>
    <div id="suggestedSportsList" style="display: flex;">
        <?php
        if ($loggedIn) {
            // Fetch unique sport recommendations for the user
            $rec_query = $conn->query("SELECT DISTINCT sport_name FROM user_recommendations WHERE user_id='$user_id' ORDER BY id DESC");
            if ($rec_query->num_rows > 0) {
                while ($row = $rec_query->fetch_assoc()) {
                    $sport_name = htmlspecialchars($row['sport_name']);
        ?>
        <div style="display: flex; gap: 10px;">
        <?php
                    // Fetch ALL events that match this sport name
                    $event_query = $conn->prepare("SELECT * FROM events WHERE name = ?");
                    $event_query->bind_param("s", $sport_name);
                    $event_query->execute();
                    $event_result = $event_query->get_result();

                    if ($event_result->num_rows > 0) {

                        while ($event = $event_result->fetch_assoc()) {
                            echo "<div class='event-card'>";
                            echo "<img src='uploads/" . $event['image'] . "' alt='Event Image'>";
                            echo "<h3>" . $event['name'] . "</h3>";
                            echo "<p>Organizer: " . $event['organizer'] . "</p>";
                            echo "<p>Date and Time: " . $event['event_date'] . " " . $event['event_time'] . "</p>";
                            echo "<p>Location: " . $event['location'] . "</p>";
                            echo "<p>Price: ₹" . $event['price'] . "</p>";
                            echo "<button class='register-btn' data-id='" . $event['id'] . "'>Register</button>";
                            echo "</div>";
                        }
                    }
                }
            } else {
                echo "<p>No suggestions yet.</p>";
            }
        } else {
            echo "<p>Login to get personalized recommendations.</p>";
        }
        ?>
    </div>
    </div>
</section>

<!-- All Events Section -->
<h2 style="font-size: 24px; margin-bottom: 20px; color: white; background:rgba(0, 0, 0, 0.97); width: 100%; padding: 10px;">All Events</h2>
 <section class="events-container" id="eventsList">
    <div id="suggestedSportsList">
    <?php while ($event = $events->fetch_assoc()) { ?>
        <div class="event-card" data-name="<?php echo strtolower($event['name']); ?>">
            <img src="uploads/<?php echo $event['image']; ?>" alt="Event Image">
            <h3><?php echo $event['name']; ?></h3>
            <p>Organizer: <?php echo $event['organizer']; ?></p>
            <p>Date and time: <?php echo $event['event_date'] . ' ' . $event['event_time']; ?></p>
            <p>Location: <?php echo $event['location']; ?></p>
            <p>Price: ₹<?php echo $event['price']; ?></p>
            <button class="register-btn" data-id="<?php echo $event['id']; ?>" 
                onclick="handleRegistration(<?php echo $event['id']; ?>, <?php echo $loggedIn ? 'true' : 'false'; ?>)">
                Register
            </button>
        </div>
        </div>
    <?php } ?>
</section>


<!-- Registration Popup Form -->
<div id="registerPopup" class="popup">
    <div class="popup-content">
        <span class="close">&times;</span>
        <h2>Register for Event</h2>
        <form id="registrationForm">
            <input type="hidden" id="event_id" name="event_id">
            <input type="text" id="name" name="name" value="<?php echo $loggedIn ? $_SESSION['username'] : ''; ?>" readonly>
            <input type="email" id="email" name="email" placeholder="Your Email" required>
            <input type="text" id="phone" name="phone" placeholder="Phone Number" required>
            <button type="submit">Submit</button>
        </form>
    </div>
</div>

<script src="events.js"></script>
</body>
</html>
