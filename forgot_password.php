<?php
session_start();

// Include the database connection
require_once '_db.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
        $email = $_POST['email'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        // Validate the form fields
        if (empty($email) || empty($newPassword) || empty($confirmPassword)) {
            $errorMessage = "All fields are required.";
        } elseif ($newPassword != $confirmPassword) {
            $errorMessage = "Passwords do not match.";
        } else {
            // Check if the email exists in the database
            $sql = "SELECT id FROM users WHERE email = ?";
            if ($stmt = $dbc->prepare($sql)) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    // Hash the new password
                    $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                    // Update the password in the database
                    $sql = "UPDATE users SET password = ? WHERE email = ?";
                    if ($stmt = $dbc->prepare($sql)) {
                        $stmt->bind_param("ss", $newHashedPassword, $email);
                        if ($stmt->execute()) {
                            $successMessage = "Your password has been updated successfully!";
                        } else {
                            $errorMessage = "Failed to update the password. Please try again.";
                        }
                        $stmt->close();
                    }
                } else {
                    $errorMessage = "No user found with that email address.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="forgot_password.css">
</head>
<body>
    <div class="center">
        <h1 class="title">Reset Your Password</h1>

        <!-- Display success or error message -->
        <?php if (isset($successMessage)): ?>
            <div class="success"><?php echo $successMessage; ?></div>
        <?php elseif (isset($errorMessage)): ?>
            <div class="error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <!-- Reset password form -->
        <form action="forgot_password.php" method="POST">
            <div class="inputf">
                <input type="email" name="email" class="input" placeholder="Enter Your Email" required />
                <span class="label">Email</span>
            </div>
            <div class="inputf">
                <input type="password" name="new_password" class="input" placeholder="New Password" required />
                <span class="label">New Password</span>
            </div>
            <div class="inputf">
                <input type="password" name="confirm_password" class="input" placeholder="Confirm New Password" required />
                <span class="label">Confirm New Password</span>
            </div>
            <div class="button-container">
                <button type="submit" class="btn">Reset Password</button>
                <a href="login.php" class="btn go-back">Go Back</a>
            </div>
        </form>
    </div>
</body>
</html>
