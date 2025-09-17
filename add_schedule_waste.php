<?php
// Include the database connection
require_once '_db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $area = $_POST['area'];
    $collection_date = $_POST['collection_date'];

    // Insert new schedule into the database
    $sql = "INSERT INTO waste_collection_schedule (area, collection_date, status) VALUES (?, ?, 'Pending')";
    if ($stmt = $dbc->prepare($sql)) {
        $stmt->bind_param("ss", $area, $collection_date);
        if ($stmt->execute()) {
            header("Location: waste_collection.php"); // Redirect after successful insertion
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
    <title>Add Waste Collection Schedule</title>
    <link rel="stylesheet" href="add_schedule_waste.css"> <!-- Add the CSS here -->
</head>
<body>
    <div class="container">
        <h1>Add Waste Collection Schedule</h1>
        <form action="add_schedule_waste.php" method="POST">
            <div class="form-group">
                <label for="area">Area:</label>
                <input type="text" name="area" id="area" placeholder="Enter the collection area" required>
            </div>
            <div class="form-group">
                <label for="collection_date">Collection Date:</label>
                <input type="datetime-local" name="collection_date" id="collection_date" required>
            </div>
            <button type="submit" class="btn">Add Schedule</button>
        </form>
         <!-- Back Button -->
         <button class="back-button" onclick="window.history.back();">Back</button>
    </div>
</body>
</html>
