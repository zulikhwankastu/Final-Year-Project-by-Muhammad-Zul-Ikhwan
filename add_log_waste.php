<?php
// Include the database connection
require_once '_db.php';

// Get the schedule ID from URL
$schedule_id = $_GET['schedule_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $schedule_id) {
    $action = $_POST['action'];
    $action_date = date('Y-m-d H:i:s');

    // Insert action into the log
    $sql = "INSERT INTO waste_collection_logs (schedule_id, action, action_date) VALUES (?, ?, ?)";
    if ($stmt = $dbc->prepare($sql)) {
        $stmt->bind_param("iss", $schedule_id, $action, $action_date);
        if ($stmt->execute()) {
            header("Location: waste_collection.php"); // Redirect back to the schedule page
            exit();
        } else {
            echo "Error: " . $dbc->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Waste Collection Action</title>
</head>
<body>
    <h1>Log Action for Waste Collection</h1>
    <form action="add_log_waste.php?schedule_id=<?php echo $schedule_id; ?>" method="POST">
        <label for="action">Action:</label>
        <input type="text" name="action" id="action" placeholder="Enter action (e.g., Collection Started)" required>
        <button type="submit">Log Action</button>
    </form>
</body>
</html>
