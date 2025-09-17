<?php require '_db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Homestay</title>
    <link rel="stylesheet" href="styles_index.css" />
</head>
<body>

<!-- Header -->
<header>
    <div class="navbar">
        <div class="logo">Homestay</div>
        <nav>
            <ul>
                <li><a href="#about">About</a></li>
                <li><a href="#homestays">Homestays</a></li>
                <li><a href="#rooms">Rooms</a></li>
                <li><a href="#testimonials">Testimonials</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="login.php" class="cta-button">Login</a></li>
            </ul>
        </nav>
    </div>
</header>

<!-- Hero -->
<section class="hero">
    <div class="hero-content">
        <h1>Welcome to Our Homestay</h1>
        <p>Relax, Unwind, and Explore Nature</p>
        <a href="#homestays" class="cta-button">Explore Now</a>
    </div>
</section>

<!-- About -->
<section id="about" class="about">
    <div class="container">
        <h2>About Us</h2>
        <p>Experience the serenity of nature with our homestay. Whether you’re here for a weekend getaway or a week-long retreat, we offer comfortable rooms and a warm, welcoming atmosphere. Let us make your stay memorable.</p>
    </div>
</section>

<!-- ✅ Homestay Section -->

<section id="homestays" class="homestays">
    <div class="container">
        <h2>Our Homestays</h2>
        <div class="homestay-list">
        <?php
$sql = "SELECT id AS property_id, name AS property_name, description, image AS property_image
        FROM properties
        ORDER BY id DESC
        LIMIT 3";

$result = $dbc->query($sql);

if ($result && $result->num_rows > 0):
    while ($row = $result->fetch_assoc()):
        $imageSrc = !empty($row['property_image']) ? 'uploads/' . $row['property_image'] : 'Images/default.jpg';
?>
    <div class="homestay-item">
        <img src="<?= htmlspecialchars($imageSrc) ?>" alt="Homestay Image">
        <h3><?= htmlspecialchars($row['property_name']) ?></h3>
        <p><?= htmlspecialchars(mb_strimwidth($row['description'], 0, 100, '...')) ?></p>
        <a href="view_homestay_cust.php?id=<?= $row['property_id'] ?>" class="cta-button">View Homestay</a>
    </div>
<?php 
    endwhile; 
else: ?>
    <p>No homestays available at the moment.</p>
<?php endif; ?>

        </div>
    </div>
</section>




<!-- Testimonials -->
<section id="testimonials" class="testimonials">
    <div class="container">
        <h2>What Our Guests Say</h2>
        <div class="testimonial-box">
            <div class="testimonial-item">
                <p>"The homestay was absolutely beautiful!"</p>
                <h3>- Sarah L.</h3>
            </div>
            <div class="testimonial-item">
                <p>"Perfect for a relaxing weekend getaway."</p>
                <h3>- John M.</h3>
            </div>
            <div class="testimonial-item">
                <p>"A hidden gem in the heart of nature."</p>
                <h3>- Emma G.</h3>
            </div>
            <div class="testimonial-item">
                <p>"We felt so welcomed and the views were amazing!"</p>
                <h3>- David K.</h3>
            </div>
        </div>
    </div>
</section>

<!-- Contact -->
<section id="contact" class="contact">
    <div class="container">
        <h2>Contact Us</h2>
        <p>We'd love to hear from you! Fill out the form below, and we'll get back to you shortly.</p>
        <form action="contact_form.php" method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Your Name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Your Email" required>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" placeholder="Write your message here..." required></textarea>
            </div>
            <button type="submit" class="cta-button">Send Message</button>
        </form>
    </div>
</section>

<!-- Footer -->
<footer>
    <p>&copy; 2024 Homestay. All rights reserved.</p>
</footer>

</body>
</html>
