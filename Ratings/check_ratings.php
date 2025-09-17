<?php
require_once '../_db.php';

// Handle actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    switch ($action) {
        case 'flag':
            $dbc->query("UPDATE ratings_DSS SET flagged = 1 WHERE id = $id");
            break;
        case 'unflag':
            $dbc->query("UPDATE ratings_DSS SET flagged = 0 WHERE id = $id");
            break;
        case 'hide':
            $dbc->query("UPDATE ratings_DSS SET status = 'hidden' WHERE id = $id");
            break;
        case 'unhide':
            $dbc->query("UPDATE ratings_DSS SET status = 'visible' WHERE id = $id");
            break;
        case 'delete':
            $dbc->query("DELETE FROM ratings_DSS WHERE id = $id");
            break;
    }
    header("Location: check_ratings.php");
    exit;
}

// Fetch by category
$visible = $dbc->query("SELECT * FROM ratings_DSS WHERE flagged = 0 AND (status = 'visible' OR status IS NULL) ORDER BY created_at DESC");
$flagged = $dbc->query("SELECT * FROM ratings_DSS WHERE flagged = 1 ORDER BY created_at DESC");
$hidden = $dbc->query("SELECT * FROM ratings_DSS WHERE status = 'hidden' ORDER BY created_at DESC");

// Function to render a table
function renderRatingsTable($result) {
    if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Status</th>
                    <th>Flagged</th>
                    <th>Actions</th>
                    <th>Respond</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['user_name']) ?></td>
                        <td><?= str_repeat("★", $row['rating']) ?></td>
                        <td><?= htmlspecialchars($row['comment']) ?></td>
                        <td><?= htmlspecialchars($row['status'] ?? 'visible') ?></td>
                        <td><?= $row['flagged'] ? 'Yes' : 'No' ?></td>
                        <td>
                            <?php if ($row['flagged']): ?>
                                <a href="?action=unflag&id=<?= $row['id'] ?>">Unflag</a> |
                            <?php else: ?>
                                <a href="?action=flag&id=<?= $row['id'] ?>">Flag</a> |
                            <?php endif; ?>

                            <?php if ($row['status'] == 'hidden'): ?>
                                <a href="?action=unhide&id=<?= $row['id'] ?>">Unhide</a> |
                            <?php else: ?>
                                <a href="?action=hide&id=<?= $row['id'] ?>">Hide</a> |
                            <?php endif; ?>

                            <a href="?action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Delete this rating?')">Delete</a>
                        </td>
                        <td>
                            <a href="respond_review_DSS.php?id=<?= $row['id'] ?>">Respond</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No ratings found in this category.</p>
    <?php endif;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Check Ratings</title>
    <link rel="stylesheet" href="check_ratings.css">
</head>
<body>
<div class="container">
    <h2>Visible Ratings</h2>
    <?php renderRatingsTable($visible); ?>

    <h2>Flagged Ratings</h2>
    <?php renderRatingsTable($flagged); ?>

    <h2>Hidden Ratings</h2>
    <?php renderRatingsTable($hidden); ?>
</div>

<div style="text-align: center; margin-top: 20px;">
    <a href="../manager_dashboard.php" class="back-button">← Back to Dashboard</a>
</div>
</body>
</html>
