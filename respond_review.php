<?php
require_once '_db.php';

if (!isset($_GET['id'])) {
    echo "No review ID provided.";
    exit;
}

$id = intval($_GET['id']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = $dbc->real_escape_string($_POST['manager_response']);

    $dbc->query("UPDATE reviews SET manager_response = '$response' WHERE id = $id");

    header("Location: check_reviews.php");
    exit;
}

// Fetch the selected review
$result = $dbc->query("SELECT * FROM reviews WHERE id = $id LIMIT 1");
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            padding: 30px;
            margin: 0;
        }
        .container {
            max-width: 700px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .review-box {
            background: #e9ecef;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }
        textarea {
            width: 100%;
            font-size: 14px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            resize: vertical;
        }
        button {
            background-color: #28a745;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 15px;
        }
        button:hover {
            background-color: #218838;
        }
        .back-button {
            margin-left: 15px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
        .back-button:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Respond to Guest Review</h2>

    <div class="review-box">
        <p><strong>User:</strong> <?= htmlspecialchars($review['name']) ?></p>
        <p><strong>Rating:</strong> <?= str_repeat("★", intval($review['rating'])) ?></p>
        <p><strong>Comment:</strong> <?= nl2br(htmlspecialchars($review['comment'])) ?></p>
        <p><strong>Submitted at:</strong> <?= htmlspecialchars($review['created_at']) ?></p>
    </div>

    <form method="POST">
        <label for="manager_response">Your Response:</label><br>
        <textarea name="manager_response" id="manager_response" rows="6" required><?= htmlspecialchars($review['manager_response'] ?? '') ?></textarea><br><br>

        <button type="submit">Submit Response</button>
        <a href="check_reviews.php" class="back-button">Cancel</a>
    </form>
</div>
</body>
</html>
