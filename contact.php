<?php
session_start();
$admin_email = "admin@universitysportsclub.com"; // Set admin email here
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Contact Us</title>
    <link rel="stylesheet" href="css/contact.css">
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
        <h1>Get in Touch</h1>
    </header>

<section class="contact-container">
    <div class="contact-form">
        <h2>Get in Touch</h2>
        <form id="contactForm">
            <input type="text" id="name" name="name" placeholder="Your Name" required>
            <input type="email" id="email" name="email" placeholder="Your Email" required>
            <input type="text" id="subject" name="subject" placeholder="Subject" required>
            <textarea id="message" name="message" rows="5" placeholder="Your Message" required></textarea>
            <button type="button" onclick="sendEmail()">Send Message</button>
        </form>
    </div>
    <div class="contact-info">
        <h2>Contact Information</h2>
        <p><strong>📍 Address:</strong> 123 University St, City, Country</p>
        <p><strong>📞 Phone:</strong> +123 456 7890</p>
        <p><strong>✉ Email:</strong> support@universitysportsclub.com</p>

        <h3>Follow Us</h3>
        <div class="social-icons">
            <a href="#"><img src="images/facebook_icon.png" alt="Facebook"></a>
            <a href="#"><img src="images/twitter_icon.png" alt="X (Twitter)"></a>
            <a href="#"><img src="images/instagram_icon.png" alt="Instagram"></a>
            <a href="#"><img src="images/linkedin_icon.png" alt="LinkedIn"></a>
        </div>
    </div>
</section>

<!-- Google Map -->
<div class="map-container">
    <h2>Find Us Here</h2>
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.835434508264!2d144.95373531531883!3d-37.81627974211209!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad65d5df1a873f7%3A0xb04a3f1a3a013c50!2sUniversity!5e0!3m2!1sen!2sus!4v1601955462165!5m2!1sen!2sus" 
    width="100%" height="400" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
</div>

<script>
function sendEmail() {
    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;
    let subject = document.getElementById("subject").value;
    let message = document.getElementById("message").value;
    
    let adminEmail = "<?php echo $admin_email; ?>";
    
    // Auto-generate email content
    let mailtoLink = `mailto:${adminEmail}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent("Hello,\n\nMy name is " + name + ".\n\n" + message + "\n\nBest regards,\n" + name + "\n" + email)}`;
    
    // Open the user's email client
    window.location.href = mailtoLink;
}
</script>

</body>
</html>
