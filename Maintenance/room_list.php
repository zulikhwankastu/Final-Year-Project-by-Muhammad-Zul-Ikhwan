<?php require '../_db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Room List</title>
    <link rel="stylesheet" href="room_list.css">
</head>
<body>

<h2>Room List</h2>

<div class="room-list-container">
    <table>
        <thead>
            <tr>
                <th>Room Type</th>
                <th>Maintenance</th>
                <th>Report</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // Fetch distinct room types and their IDs
        $sql = "SELECT DISTINCT r.id, r.name 
                FROM rooms r
                ORDER BY r.name ASC";
        $result = $dbc->query($sql);

        if ($result && $result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
                $roomId = $row['id'];
                $roomType = $row['name'];
        ?>
            <tr>
                <td><?= htmlspecialchars($roomType) ?></td>
                <td><a href="maintenance_DSS.php?roomId=<?= $roomId ?>">Schedule Maintenance</a></td>
                <td><a href="view_report_DSS.php">View Report</a></td>
            </tr>
        <?php
            endwhile;
        else:
        ?>
            <tr><td colspan="3">No rooms found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<div style="text-align:center;">
    <a href="../manager_dashboard.php" class="back-button">← Back to Dashboard</a>
</div>

</body>
</html>
