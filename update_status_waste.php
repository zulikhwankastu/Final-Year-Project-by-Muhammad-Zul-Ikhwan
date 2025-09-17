<?php
// Include the database connection
require_once '_db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];
    $schedule_id = $_POST['schedule_id'];

    // Update the status of the collection schedule
    $sql = "UPDATE waste_collection_schedule SET status = ? WHERE id = ?";
    if ($stmt = $dbc->prepare($sql)) {
        $stmt->bind_param("si", $status, $schedule_id);
        if ($stmt->execute()) {
            header("Location: waste_collection.php"); // Redirect back to the schedule page
            exit();
        } else {
            echo "Error: " . $dbc->error;
        }
    }
}
?>
