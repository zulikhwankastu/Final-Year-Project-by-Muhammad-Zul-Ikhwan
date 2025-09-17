<?php
require '_db.php';

// Validate form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id = intval($_POST['room_id']);
    $name = trim($_POST['name']);
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);

    // Simple validation
    if (empty($name) || $rating < 1 || $rating > 5 || empty($comment)) {
        echo "Invalid input. Please go back and try again.";
        exit;
    }

    // Insert into database
    $stmt = $dbc->prepare("INSERT INTO reviews (room_id, name, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("isis", $room_id, $name, $rating, $comment);

    if ($stmt->execute()) {
        header("Location: room_details_cust.php?room_id=" . $room_id . "&review=success");
    } else {
        echo "Failed to submit review.";
    }

    $stmt->close();
    $dbc->close();
} else {
    echo "Invalid request.";
}
?>
