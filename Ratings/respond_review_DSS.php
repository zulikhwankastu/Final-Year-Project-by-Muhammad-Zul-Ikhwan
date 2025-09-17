<?php
require_once '../_db.php';

if (!isset($_GET['id'])) {
    echo "No review ID provided.";
    exit;
}

$id = intval($_GET['id']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = $dbc->real_escape_string($_POST['manager_response']);

    $dbc->query("UPDATE ratings_DSS SET manager_response = '$response' WHERE id = $id");

    header("Location: check_ratings.php");
    exit;
}

// Fetch the selected review
$result = $dbc->query("SELECT * FROM ratings_DSS WHERE id = $id LIMIT 1");
if ($result->num_rows !== 1) {
    echo "Review not found.";
    exit;
}
$review = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Respond to Review</title>
    <link rel="stylesheet" href="check_ratings.css">
</head>
<body>
    <div class="container">
        <h2>Respond to Guest Review</h2>

        <div class="review-box">
            <p><strong>User:</strong> <?= htmlspecialchars($review['user_name']) ?></p>
            <p><strong>Rating:</strong> <?= str_repeat("★", $review['rating']) ?></p>
            <p><strong>Comment:</strong> <?= nl2br(htmlspecialchars($review['comment'])) ?></p>
            <p><strong>Submitted at:</strong> <?= $review['submitted_at'] ?></p>
        </div>

        <form method="POST">
            <label for="manager_response"><strong>Your Response:</strong></label><br>
            <textarea name="manager_response" id="manager_response" rows="6" cols="60" required><?= htmlspecialchars($review['manager_response'] ?? '') ?></textarea><br><br>

            <button type="submit">Submit Response</button>
            <a href="check_ratings.php" class="back-button" style="margin-left:10px;">Cancel</a>
        </form>
    </div>
</body>
</html>
