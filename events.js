document.addEventListener("DOMContentLoaded", () => {
    // Search Filter Function
    document.getElementById("searchBar").addEventListener("keyup", filterEvents);

    function filterEvents() {
        let input = document.getElementById("searchBar").value.toLowerCase().trim();
        let eventCards = document.querySelectorAll(".event-card");
        let filteredEvents = [];
        let otherEvents = [];

        eventCards.forEach(card => {
            let eventName = card.getAttribute("data-name");
            if (eventName.includes(input)) {
                filteredEvents.push(card);
            } else {
                otherEvents.push(card);
            }
        });

        let eventsList = document.getElementById("eventsList");
        eventsList.innerHTML = ""; // Clear the section

        // Append filtered events at the top
        filteredEvents.forEach(card => eventsList.appendChild(card));

        // Append other events below
        otherEvents.forEach(card => eventsList.appendChild(card));
    }

    // Check Login Before Registering
    window.handleRegistration = function(eventId, isLoggedIn) {
        if (!isLoggedIn) {
            alert("You need to log in to continue registration.");
            window.location.href = "userlogin.php"; // Redirect to login page
            return;
        }

        // If logged in, open the registration popup
        document.getElementById("event_id").value = eventId;
        document.getElementById("registerPopup").style.display = "block";
    };

    document.querySelector(".close").addEventListener("click", function() {
        document.getElementById("registerPopup").style.display = "none";
    });

    document.getElementById("registrationForm").addEventListener("submit", function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch("register_event.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === "success") {
                document.getElementById("registerPopup").style.display = "none";
            }
        });
    });  
});

document.addEventListener("DOMContentLoaded", () => {
    let searchInput = document.getElementById("searchBar");
    let searchButton = document.getElementById("searchButton");

    // Function to send search term to track_search.php
    function trackSearch() {
        let sport = searchInput.value.trim().toLowerCase();

        if (sport.length > 2) {  // Ensure it's a valid search term
            fetch("track_search.php", {
                method: "POST",
                body: JSON.stringify({ sport: sport }),
                headers: { "Content-Type": "application/json" }
            })
            .then(response => response.json())
            .then(data => console.log("Search stored:", data.message))
            .catch(error => console.error("Error tracking search:", error));
        }
    }

    // Store search when Enter key is pressed
    searchInput.addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            event.preventDefault(); // Prevent form submission
            trackSearch();
        }
    });

    // Store search when clicking the search button
    searchButton.addEventListener("click", function () {
        trackSearch();
    });
});


function trackRegistration(eventId, sportName) {
    fetch("track_registration.php", {
        method: "POST",
        body: JSON.stringify({ sport: sportName }),
        headers: { "Content-Type": "application/json" }
    })
    .then(response => response.json())
    .then(data => {
        console.log(data.message);
        updateRecommendedSports(); // Refresh recommendations
    })
    .catch(error => console.error("Error tracking registration:", error));
}
