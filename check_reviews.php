<?php
require_once '_db.php';

// Handle actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    switch ($action) {
        case 'flag':
            $dbc->query("UPDATE reviews SET flagged = 1 WHERE id = $id");
            break;
        case 'unflag':
            $dbc->query("UPDATE reviews SET flagged = 0 WHERE id = $id");
            break;
        case 'hide':
            $dbc->query("UPDATE reviews SET status = 'hidden' WHERE id = $id");
            break;
        case 'unhide':
            $dbc->query("UPDATE reviews SET status = 'visible' WHERE id = $id");
            break;
        case 'delete':
            $dbc->query("DELETE FROM reviews WHERE id = $id");
            break;
        case 'approve':
            $dbc->query("UPDATE reviews SET status = 'visible' WHERE id = $id");
            break;
    }
    header("Location: check_reviews.php");
    exit;
}

// Fetch by category
$visible = $dbc->query("SELECT * FROM reviews WHERE flagged = 0 AND (status = 'pending' OR status IS NULL OR status = 'visible') ORDER BY created_at DESC");
$flagged = $dbc->query("SELECT * FROM reviews WHERE flagged = 1 ORDER BY created_at DESC");
$hidden = $dbc->query("SELECT * FROM reviews WHERE status = 'hidden' ORDER BY created_at DESC");

// Function to render a table
function renderReviewsTable($result) {
    if ($result && $result->num_rows > 0): ?>
        <table class="review-table">
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
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= str_repeat("★", $row['rating']) ?></td>
                        <td><?= htmlspecialchars($row['comment']) ?></td>
                        <td><?= htmlspecialchars($row['status'] ?? 'visible') ?></td>
                        <td><?= $row['flagged'] ? 'Yes' : 'No' ?></td>
                        <td>
                            <?php if ($row['flagged']): ?>
                                <a href="?action=unflag&id=<?= $row['id'] ?>" class="action-link">Unflag</a> |
                            <?php else: ?>
                                <a href="?action=flag&id=<?= $row['id'] ?>" class="action-link">Flag</a> |
                            <?php endif; ?>

                            <?php if ($row['status'] == 'hidden'): ?>
                                <a href="?action=unhide&id=<?= $row['id'] ?>" class="action-link">Unhide</a> |
                            <?php else: ?>
                                <a href="?action=hide&id=<?= $row['id'] ?>" class="action-link">Hide</a> |
                            <?php endif; ?>

                            <?php if ($row['status'] == 'Pending'): ?>
                                <a href="?action=approve&id=<?= $row['id'] ?>" class="action-link" style="color: green;">Approve</a> |
                            <?php endif; ?>

                            <a href="?action=delete&id=<?= $row['id'] ?>" class="action-link delete" onclick="return confirm('Delete this review?')">Delete</a>
                        </td>
                        <td>
                            <a href="respond_review.php?id=<?= $row['id'] ?>" class="respond-btn">Respond</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No reviews found in this category.</p>
    <?php endif;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Check Reviews</title>
    <style>
      body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f7f7f7;
    padding: 30px 20px;
    margin: 0;
    color: #333;
}

h2 {
    margin-top: 40px;
    color: #222;
    font-weight: 700;
    font-size: 1.8rem;
    border-bottom: 2px solid #ddd;
    padding-bottom: 8px;
}

.container {
    max-width: 1200px;
    margin: auto;
    background: #fff;
    padding: 30px 35px;
    border-radius: 10px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
}

.review-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    font-size: 0.95rem;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.review-table th, .review-table td {
    border: 1px solid #ddd;
    padding: 14px 12px;
    text-align: left;
    vertical-align: middle;
}

.review-table th {
    background-color: #f9fafb;
    color: #555;
    font-weight: 600;
    letter-spacing: 0.03em;
}

.review-table tr:nth-child(even) {
    background-color: #fbfcfd;
    transition: background-color 0.3s ease;
}

.review-table tr:hover {
    background-color: #e6f2ff;
}

.action-link {
    color: #2a7a2a; /* dark green */
    text-decoration: none;
    font-size: 0.9rem;
    margin-right: 8px;
    padding: 3px 6px;
    border-radius: 4px;
    transition: background-color 0.2s ease;
}

.action-link:hover {
    text-decoration: none;
    background-color: #c7f0c7; /* light green */
    color: #1b4d1b; /* darker green */
}

.action-link.delete {
    color: #a94442; /* dark reddish brown for delete to keep caution */
}

.action-link.delete:hover {
    background-color: #f8d7da; /* light red */
    color: #842029;
}

.respond-btn {
    background-color: #28a745; /* bootstrap green */
    color: white;
    padding: 7px 14px;
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 600;
    display: inline-block;
    transition: background-color 0.3s ease;
}

.respond-btn:hover {
    background-color: #218838; /* darker green */
}


.back-button {
    display: inline-block;
    margin-top: 25px;
    text-decoration: none;
    color: #444;
    font-weight: 600;
    font-size: 1rem;
    padding: 8px 16px;
    border-radius: 6px;
    border: 1px solid transparent;
    transition: all 0.3s ease;
}

.back-button:hover {
    text-decoration: none;
    border-color: #999;
    background-color: #f0f0f0;
}

td:nth-child(4) { /* Status */
    font-weight: 600;
    color: #444;
}

td:nth-child(5) { /* Flagged */
    font-weight: 600;
    color: #d9534f;
}

@media (max-width: 900px) {
    .review-table th, .review-table td {
        padding: 10px 8px;
        font-size: 0.85rem;
    }

    .respond-btn, .action-link {
        font-size: 0.85rem;
        padding: 5px 10px;
    }
}

@media (max-width: 600px) {
    .container {
        padding: 20px 15px;
    }
    
    .review-table {
        font-size: 0.8rem;
    }

    .review-table th, .review-table td {
        padding: 8px 6px;
    }
    
    .respond-btn, .action-link {
        padding: 4px 8px;
        font-size: 0.75rem;
    }
}
    </style>
</head>
<body>
<div class="container">
    <h2>Pending / Visible Reviews</h2>
    <?php renderReviewsTable($visible); ?>

    <h2>Flagged Reviews</h2>
    <?php renderReviewsTable($flagged); ?>

    <h2>Hidden Reviews</h2>
    <?php renderReviewsTable($hidden); ?>
</div>

<div style="text-align: center;">
    <a href="manager_dashboard.php" class="back-button">← Back to Dashboard</a>
</div>
</body>
</html>
