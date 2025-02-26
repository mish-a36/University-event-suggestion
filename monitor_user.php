<?php
session_start();
$conn = new mysqli("localhost", "root", "", "university_sports_club");

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Monitor Users</title>
    <link rel="stylesheet" href="css/monitor.css">
</head>
<body>

<header>
    <h1>Monitor Users</h1>
    <nav>
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="monitor_users.php">Monitor Users</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<section class="user-management">
    <h2>Registered Users</h2>
    <table id="userTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Course</th>
                <th>Year</th>
                <th>University</th>
                <th>Registered Sports</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</section>

<script src="js/admin.js"></script>
<script>
    function fetchUsers() {
        fetch("fetch_users.php")
        .then(response => response.json())
        .then(data => {
            let tbody = document.querySelector("#userTable tbody");
            tbody.innerHTML = "";

            data.forEach(user => {
                let row = document.createElement("tr");
                row.id = "user_" + user.id;
                row.innerHTML = `
                    <td>${user.name}</td>
                    <td>${user.username}</td>
                    <td>${user.course}</td>
                    <td>${user.year}</td>
                    <td>${user.university}</td>
                    <td id="sports_${user.id}">${user.sports || 'None'}</td>
                    <td><button class="delete-btn" data-id="${user.id}">Delete</button></td>
                `;
                tbody.appendChild(row);
            });

            document.querySelectorAll(".delete-btn").forEach(button => {
                button.addEventListener("click", function() {
                    const userId = this.getAttribute("data-id");
                    deleteUser(userId);
                });
            });
        })
        .catch(error => console.error("Error fetching users:", error));
    }

    function deleteUser(userId) {
        if (!confirm("Are you sure you want to delete this user?")) return;

        fetch("delete_user.php", {
            method: "POST",
            body: JSON.stringify({ id: userId }),
            headers: { "Content-Type": "application/json" }
        })
        .then(response => response.text())
        .then(message => {
            alert(message);
            fetchUsers(); // Refresh users after deletion
        });
    }

    fetchUsers();
    setInterval(fetchUsers, 5000);
</script>

</body>
</html>
