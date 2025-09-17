<?php
require_once '../_db.php';

// Fetch all completed maintenance tasks
$query = "SELECT * FROM maintenance_tasks_dss WHERE status = 'Completed' ORDER BY scheduled_date DESC";
$result = $dbc->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Maintenance Reports</title>
    <link rel="stylesheet" href="view_report_DSS.css">
</head>
<body>

<h2>All Completed Maintenance Reports</h2>

<?php if ($result && $result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Room</th>
                <th>Task Description</th>
                <th>Scheduled Date</th>
                <th>Status</th>
                <th>Report</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['room_no']) ?></td>
                    <td><?= htmlspecialchars($row['task_description']) ?></td>
                    <td><?= htmlspecialchars($row['scheduled_date']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td><?= !empty($row['report']) ? nl2br(htmlspecialchars($row['report'])) : 'No report' ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No completed maintenance reports available.</p>
<?php endif; ?>

<div class="back-button">
    <a href="room_list.php">← Back to Room List</a>
</div>

</body>
</html>
