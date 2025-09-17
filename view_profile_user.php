<?php
// Start session to check if the user is logged in
session_start();

// Include the database connection file
require_once '_db.php'; 

// Check if the user is logged in (i.e., user_id is stored in the session)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Fetch user data from the database
$sql = "SELECT * FROM users WHERE id = ?";
if ($stmt = $dbc->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        // Handle case where user data is not found
        echo "User not found.";
        exit();
    }

    $stmt->close();
}

// Close the database connection
$dbc->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <link rel="stylesheet" href="view_profile_user.css">
</head>
<body>

    <!-- Back to Dashboard Button (inline style) -->
    <a href="booking/booking_dashboard.php" 
       style="position: absolute; top: 20px; left: 20px; background-color: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-weight: bold;">
       🏠 Back to Dashboard
    </a>

    <div class="profile-container">
        <h1>Your Profile</h1>

        <!-- Profile Picture -->
        <div class="profile-picture">
            <img src="<?php echo !empty($user['profile_picture']) ? 'uploads/' . htmlspecialchars($user['profile_picture']) : 'assets/default-avatar.png'; ?>" 
                 alt="Profile Picture" 
                 class="profile-img">
        </div>

        <!-- Profile Information -->
        <div class="profile-info">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['full_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
        </div>

        <!-- Buttons -->
        <div class="profile-buttons">
            <a href="edit_profile_user.php" class="btn">Edit Profile</a>
            <a href="login.php" class="btn logout-btn">Logout</a>
        </div>
    </div>
</body>
</html>
