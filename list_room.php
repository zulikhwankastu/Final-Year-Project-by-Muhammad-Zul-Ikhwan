<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'bookingsungaiserai';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get all rooms
$query = "SELECT * FROM rooms LIMIT 10"; // Limit to 10 rooms, you can adjust this
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room List</title>
    <link rel="stylesheet" href="list_room.css">
</head>
<body>
    <div class="container">
        <section class="room-list">
            <h1>All Available Rooms</h1>
            
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="room">';
                    echo '<img src="' . htmlspecialchars($row['image']) . '" alt="Room Image" style="width: 100%; max-width: 300px;">';
                    echo '<div class="room-info">';
                    echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                    echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                    echo '<p><strong>Price:</strong> MYR ' . number_format($row['price'], 2) . ' per night</p>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No rooms available at the moment.</p>';
            }
            ?>

        </section>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
