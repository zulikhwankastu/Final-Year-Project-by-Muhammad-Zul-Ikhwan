<?php
session_start();

// Check if the manager is logged in

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Collection Dashboard</title>
    <link rel="stylesheet" href="waste_collection_home.css">
</head>
<body>
    <h1>Waste Collection Dashboard</h1>
    
    <!-- Dashboard Links -->
    <a href="waste_collection.php">View Waste Collection Schedules</a>
    <a href="add_schedule_waste.php">Add Waste Collection Schedules</a>

    <!-- Back Button -->
    <div class="container">
    <a href="manager_dashboard.php" class="back-button">Back </a>
    </div>
</body>
</html>
