<?php
// Include the database connection
require_once '../_db.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = trim($_POST['user_name']);
    $rating = (int) $_POST['rating'];
    $comment = trim($_POST['comment']);

    // Basic validation
    if (!empty($user_name) && $rating >= 1 && $rating <= 5) {
        // Prepare SQL statement to insert rating into the database
        $sql = "INSERT INTO ratings_DSS (user_name, rating, comment) VALUES (?, ?, ?)";

        if ($stmt = $dbc->prepare($sql)) {
            $stmt->bind_param("sis", $user_name, $rating, $comment);

            // Execute the statement
            if ($stmt->execute()) {
                $successMessage = "Thank you for your rating!";
                header("refresh:2;url=rate_room.php"); // Redirect after 2 seconds
            } else {
                $errorMessage = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $errorMessage = "Error: " . $dbc->error;
        }
    } else {
        $errorMessage = "Please fill in all required fields and select a valid rating.";
    }
}

// Close DB connection
if (isset($dbc)) {
    $dbc->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rate This Room</title>
    <link rel="stylesheet" href="give_ratings_DSS.css">
</head>
<body>
    <div class="container">
        <h2>Rate This Room</h2>

        <?php if (!empty($successMessage)) echo "<p class='success'>$successMessage</p>"; ?>
        <?php if (!empty($errorMessage)) echo "<p class='error'>$errorMessage</p>"; ?>

        <form method="POST" action="">
            <label>Your Name:</label><br>
            <input type="text" name="user_name" required><br><br>

            <label>Rating (1 to 5):</label><br>
            <div class="stars">
                <input type="radio" name="rating" id="star1" value="1"><label for="star1">&#9733;</label>
                <input type="radio" name="rating" id="star2" value="2"><label for="star2">&#9733;</label>
                <input type="radio" name="rating" id="star3" value="3"><label for="star3">&#9733;</label>
                <input type="radio" name="rating" id="star4" value="4"><label for="star4">&#9733;</label>
                <input type="radio" name="rating" id="star5" value="5"><label for="star5">&#9733;</label>
            </div>

            <label>Comment:</label><br>
            <textarea name="comment" rows="4" cols="40"></textarea><br><br>

            <button type="submit">Submit Rating</button>
        </form>

        <div style="text-align:center;">
            <a href="../viewRoomDeluxeSuperSingle.php" class="back-button">← Back to Room Page</a>
        </div>
    </div>
</body>
</html>
