<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if the user is not logged in
    header("Location: login.php");
    exit();
}

// Include the database connection (if needed for additional data)
require_once '_db.php';

// Fetch user data from the session or database
$userId = $_SESSION['user_id'];

// Example: Fetch user data (you can modify this as per your requirement)
$sql = "SELECT full_name, email, phone FROM users WHERE id = ?";
if ($stmt = $dbc->prepare($sql)) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($fullName, $email, $phone);
    $stmt->fetch();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Your Dashboard</title>
    <link rel="stylesheet" href="homepage_user.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="navbar">
            <h1>Welcome, <?php echo htmlspecialchars($fullName); ?>!</h1>
            <nav>
                <ul>
                    <li><a href="view_profile_user.php">View Profile</a></li>
                    <li><a href="bookings.php">My Bookings</a></li>
                    <li><a href="extend_booking.php">Extend a Booking</a></li>
                    <li><a href="index.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <section class="user-info">
                <h2>Your Information</h2>
                <div class="info-box">
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                    <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($phone); ?></p>
                </div>
            </section>

            <section class="welcome-message">
                <p>We're glad to have you here! Use the menu to navigate to your profile, manage your bookings, or log out.</p>
            </section>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Your Company. All rights reserved.</p>
    </footer>
</body>
</html>
