<?php
require_once '../_db.php';

if (isset($_GET['id'])) {
    $task_id = intval($_GET['id']);
    $result = $dbc->query("SELECT * FROM maintenance_tasks_dss WHERE id = $task_id");
    $task = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Example of updating the report after submission
    $report = $_POST['report'];
    $stmt = $dbc->prepare("UPDATE maintenance_tasks_dss SET report = ? WHERE id = ?");
    $stmt->bind_param("si", $report, $task_id);
    $stmt->execute();
    header("Location: view_report_DSS.php?id=$task_id");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Maintenance Report</title>
    <link rel="stylesheet" href="report_DSS.css">

</head>
<body>

<h2>Maintenance Report for Task #<?= htmlspecialchars($task['id']) ?></h2>

<form method="POST">
<textarea name="report" rows="6" cols="50"><?= htmlspecialchars($task['report'] ?? '') ?></textarea>

    <button type="submit">Submit Report</button>
</form>



<div class="back-button">
<a href="maintenance_DSS.php">← Back to Maintenance Tasks</a>
</div>

</body>
</html>
