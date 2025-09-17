<?php
require_once '../_db.php';

// Handle new task submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_no = $_POST['room_no'];
    $task_description = $_POST['task_description'];
    $scheduled_date = $_POST['scheduled_date'];
    $status = 'Scheduled'; // default status

    $stmt = $dbc->prepare("INSERT INTO maintenance_tasks_dss (room_no, task_description, scheduled_date, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $room_no, $task_description, $scheduled_date, $status);
    $stmt->execute();
    $stmt->close();
}

// Handle status updates
if (isset($_GET['update']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $new_status = $_GET['update'];

    $stmt = $dbc->prepare("UPDATE maintenance_tasks_dss SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all tasks
$result = $dbc->query("SELECT * FROM maintenance_tasks_dss ORDER BY scheduled_date ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Maintenance Scheduler - Deluxe Super Single</title>
    <link rel="stylesheet" href="maintenance_DSS.css">
</head>
<body>

<h2>Maintenance Management </h2>

<div class="form-container">
    <form method="POST">
        <label>Room Number:</label><br>
        <input type="text" name="room_no" required><br><br>

        <label>Task Description:</label><br>
        <textarea name="task_description" required rows="3" cols="50"></textarea><br><br>

        <label>Scheduled Date:</label><br>
        <input type="date" name="scheduled_date" required><br><br>

        <button type="submit">Add Task</button>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>Room No</th>
            <th>Description</th>
            <th>Scheduled Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['room_no']) ?></td>
            <td><?= htmlspecialchars($row['task_description']) ?></td>
            <td><?= htmlspecialchars($row['scheduled_date']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td class="status-btn">
                <?php if ($row['status'] === 'Scheduled'): ?>
                    <a href="?update=In Progress&id=<?= $row['id'] ?>">Start</a>
                <?php elseif ($row['status'] === 'In Progress'): ?>
                    <a href="?update=Completed&id=<?= $row['id'] ?>">Complete</a>
                <?php elseif ($row['status'] === 'Completed'): ?>
                    <a href="report_DSS.php?id=<?= $row['id'] ?>">Report</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<div class="back-button">
    <a href="../manager_dashboard.php">← Back to Dashboard</a>
</div>

</body>
</html>
