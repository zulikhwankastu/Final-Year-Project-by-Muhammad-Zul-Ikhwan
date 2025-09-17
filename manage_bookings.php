<?php
require '_db.php';

// Fetch all available rooms
$query = "SELECT id, name, image FROM rooms WHERE available = 1 ORDER BY name ASC";
$result = $dbc->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Rooms</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4ed;
            margin: 0; padding: 0;
            color: #2d2d2a;
        }
        header {
            background-color: #4caf50;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .btn-back {
            display: inline-block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #4caf50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn-back:hover {
            background-color: #388e3c;
        }
        table {
            width: 60%;
            margin: 10px auto 40px auto;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            vertical-align: middle;
            text-align: center;
        }
        th {
            background-color: #8bc34a;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        img.room-thumb {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
        }
        a {
            color: #4caf50;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background-color: #4caf50;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <h1>Manage Rooms</h1>
        <p>Click on a room to view or manage its bookings</p>
    </header>

    <div style="text-align:center;">
        <a href="manager_dashboard.php" class="btn-back">← Back to Dashboard</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($room = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php if (!empty($room['image'])): 
                                $images = explode(',', $room['image']);
                                $firstImage = trim($images[0]);
                            ?>
                                <img src="<?= htmlspecialchars($firstImage) ?>" alt="<?= htmlspecialchars($room['name']) ?>" class="room-thumb">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($room['name']) ?></td>
                        <td>
                           <a href="room_details_stayed.php?roomId=<?= urlencode($room['id']) ?>">View Details</a>

                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="3" style="text-align:center;">No rooms found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <footer>
        <p>&copy; 2025 Homestay Management System. All Rights Reserved.</p>
    </footer>
</body>
</html>
