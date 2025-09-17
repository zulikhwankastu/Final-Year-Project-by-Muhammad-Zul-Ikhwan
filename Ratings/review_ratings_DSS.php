<?php
require_once '../_db.php';

// Only select reviews that are not flagged and are visible
$sql = "SELECT * FROM ratings_DSS WHERE flagged = 0 AND (status = 'visible' OR status IS NULL) ORDER BY submitted_at DESC";
$result = $dbc->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Room Reviews</title>
    <link rel="stylesheet" href="review_ratings_DSS.css">
</head>
<body>

<h2>Room Reviews</h2>

<div class="review-container">
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="review-card">
                <h3><?= htmlspecialchars($row['user_name']) ?></h3>
                <div class="stars">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <?= $i <= $row['rating'] ? '&#9733;' : '&#9734;' ?>
                    <?php endfor; ?>
                </div>
                <?php if (!empty($row['comment'])): ?>
                    <p><?= htmlspecialchars($row['comment']) ?></p>
                <?php endif; ?>
                <p class="timestamp">Submitted: <?= date("F j, Y, g:i a", strtotime($row['submitted_at'])) ?></p>

                <?php if (!empty($row['manager_response'])): ?>
                    <div class="manager-response">
                        <br>
                        <strong>From Us:</strong>
                        <p><?= nl2br(htmlspecialchars($row['manager_response'])) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center;">No reviews have been submitted yet.</p>
    <?php endif; ?>
</div>

<div style="text-align:center;">
    <a href="../viewRoomDeluxeSuperSingle.php" class="back-button">← Back to Room Page</a>
</div>

</body>
</html>
