<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - University Sports Club</title>
    <link rel="stylesheet" href="css/about.css">
</head>
<body>

    <!-- Hero Section -->
    <header class="hero">
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="about.php" class="active">About Us</a></li>
                <li><a href="user.php">User</a></li>
                <li><a href="contact.php">Get in Touch</a></li>
            </ul>
        </nav>
        <h1>About University Sports Club</h1>
        <p>Empowering students through sports excellence</p>
    </header>

    <!-- Mission & Vision -->
    <section class="mission">
    <h2>Our Mission & Vision</h2>
    <p>Our mission is to promote sportsmanship, teamwork, and a healthy lifestyle among students. We aim to nurture talent and provide professional training to aspiring athletes.We believe in the power of sports to transform lives and build strong communities. Our vision is to create an inclusive environment where every student has the opportunity to participate in sports, regardless of their background or skill level. Through our programs, we strive to instill values such as discipline, perseverance, and respect. We are committed to supporting our athletes in achieving their personal and professional goals, both on and off the field. Join us in our journey to foster a culture of excellence and make a positive impact on the lives of our students. Together, we can achieve greatness and inspire the next generation of sports leaders.</p>
</section>

    <!-- History & Achievements -->
    <section class="history">
        <h2>Our Legacy & Achievements</h2>
        <div class="history-content">
            <p>Founded in 1995, our sports club has produced national-level athletes and won multiple championships in football, cricket, and athletics.</p>
            <ul>
                <li>🏆 Inter-University Football Champions (2024)</li>
                <li>🥇 National-Level Athletics Champions (2023)</li>
                <li>⚽ Hosted State-Level Football League (2022)</li>
                <li>🏀 Basketball Tournament Finalists (2021)</li>
            </ul>
        </div>
    </section>

    <!-- Training & Facilities -->
    <section class="facilities">
        <h2>World-Class Training & Facilities</h2>
        <div class="facility-grid">
            <div class="facility">
                <img src="images/sportsmeet_home.jpg" alt="Stadium">
                <h3>Modern Stadium</h3>
                <p>Equipped with state-of-the-art infrastructure for all major sports.</p>
            </div>
            <div class="facility">
                <img src="images/Gym_about.jpg" alt="Gym">
                <h3>Advanced Gym</h3>
                <p>Specialized fitness programs to enhance athletic performance.</p>
            </div>
            <div class="facility">
                <img src="images/Swimming_pool.jpg" alt="Swimming Pool">
                <h3>Olympic Swimming Pool</h3>
                <p>Professional training sessions for swimmers.</p>
            </div>
        </div>
    </section>

    <!-- Why Join Us? -->
    <section class="why-join">
        <h2>Why Join Our Sports Club?</h2>
        <ul>
            <li>✅ Professional coaching from experienced trainers.</li>
            <li>✅ Access to state-of-the-art sports facilities.</li>
            <li>✅ Opportunities to compete at national and international levels.</li>
            <li>✅ Scholarships and sports career guidance.</li>
        </ul>
    </section>

    <!-- Testimonials -->
    <section class="testimonials">
        <h2>What Our Athletes Say</h2>
        <div class="testimonial">
            <p>"The training sessions helped me achieve my dream of becoming a national football player!"</p>
            <span>- Rohit Kumar, Football Team Captain</span>
        </div>
        <div class="testimonial">
            <p>"The sports club provided me with opportunities I never imagined. Amazing experience!"</p>
            <span>- Ananya Sharma, Basketball Player</span>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>© 2025 University Sports Club | All Rights Reserved</p>
        <ul class="footer-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="about.php">About Us</a></li>
        </ul>
    </footer>

</body>
<script>
   document.addEventListener("DOMContentLoaded", function () {
    const sections = document.querySelectorAll("section");
    const showSection = () => {
        sections.forEach((section) => {
            if (section.getBoundingClientRect().top < window.innerHeight * 0.85) {
                section.classList.add("show-section");
            }
        });
    };
    showSection();
    window.addEventListener("scroll", showSection);
});</script>


</html>
