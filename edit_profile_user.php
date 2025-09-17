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
        echo "User not found.";
        exit();
    }

    $stmt->close();
}

// Handle profile picture upload and other form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $profile_picture = $user['profile_picture'];  // Default to current picture

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $temp_name = $_FILES['profile_picture']['tmp_name'];
        $new_name = uniqid('profile_') . '.' . pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);

        // Check if the uploaded file is an image
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['profile_picture']['type'], $allowed_types)) {
            if (move_uploaded_file($temp_name, $upload_dir . $new_name)) {
                $profile_picture = $new_name; // Update profile picture name
            } else {
                echo "Failed to upload profile picture.";
            }
        } else {
            echo "Invalid file type. Only JPG, PNG, and GIF are allowed.";
        }
    }

    // Update user data in the database
    $update_sql = "UPDATE users SET full_name = ?, email = ?, phone = ?, profile_picture = ? WHERE id = ?";
    if ($update_stmt = $dbc->prepare($update_sql)) {
        $update_stmt->bind_param("ssssi", $full_name, $email, $phone, $profile_picture, $user_id);
        $update_stmt->execute();

        if ($update_stmt->affected_rows > 0) {
            echo "Profile updated successfully!";
            header("Location: view_profile_user.php"); // Redirect to view profile page
            exit();
        } else {
            echo "No changes made or error occurred.";
        }

        $update_stmt->close();
    }
}

// Close the database connection
$dbc->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="edit_profile_user.css">
</head>
<body>
    <div class="profile-container">
        <h1>Edit Profile</h1>

        <!-- Profile Picture -->
        <div class="profile-picture">
            <img src="<?php echo !empty($user['profile_picture']) ? 'uploads/' . htmlspecialchars($user['profile_picture']) : 'assets/default-avatar.png'; ?>" alt="Profile Picture" class="profile-img">
        </div>

        <!-- Edit Profile Form -->
        <form action="edit_profile_user.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" name="full_name" id="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
            </div>

            <div class="form-group">
                <label for="profile_picture">Profile Picture</label>
                <input type="file" name="profile_picture" id="profile_picture">
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn">Save Changes</button>
                <a href="view_profile_user.php" class="btn cancel-btn">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
