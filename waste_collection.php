<?php
// Include the database connection
require_once '_db.php';

// Fetch waste collection schedules
$sql = "SELECT * FROM waste_collection_schedule ORDER BY collection_date DESC";
$result = $dbc->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Collection Management</title>
    <link rel="stylesheet" href="waste_collection.css">
</head>
<body>
    <div class="container">
        <h1>Waste Collection Management</h1>
        
        <!-- Back Button -->
        <a href="waste_collection_home.php" class="back-button">Back </a>
        
        <table>
            <thead>
                <tr>
                    <th>Area</th>
                    <th>Collection Date</th>
                    <th>Status</th>
                  
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['area']); ?></td>
                        <td><?php echo date('Y-m-d H:i:s', strtotime($row['collection_date'])); ?></td>
                        <td>
                            <form action="update_status_waste.php" method="POST">
                                <select name="status" onchange="this.form.submit()">
                                    <option value="Pending" <?php echo $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="In Progress" <?php echo $row['status'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                                    <option value="Completed" <?php echo $row['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                </select>
                                <input type="hidden" name="schedule_id" value="<?php echo $row['id']; ?>" />
                            </form>
                        </td>
                       
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
