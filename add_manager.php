<?php
session_start();
require_once '_db.php'; // Include the database connection

// Check if the user is an admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in as admin
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // Insert admin data into the table
    $sql = "INSERT INTO admins (full_name, email, phone, password) VALUES (?, ?, ?, ?)";
    if ($stmt = $dbc->prepare($sql)) {
        $stmt->bind_param("ssss", $full_name, $email, $phone, $password);
        if ($stmt->execute()) {
            echo "<p>Admin added successfully.</p>";
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
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
    <title>Add Admin</title>
    <link rel="stylesheet" href="add_manager.css"> <!-- Link to external CSS -->
</head>
<body>
    <div class="container">
        <h1>Add New Admin</h1>
        
        <form action="add_admin.php" method="POST">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" name="full_name" id="full_name" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" name="phone" id="phone" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>

            <button type="submit" class="btn">Add Admin</button>
        </form>
    </div>
</body>
</html>
