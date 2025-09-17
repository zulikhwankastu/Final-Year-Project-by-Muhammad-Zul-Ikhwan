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

// Get roomId from URL
$roomId = isset($_GET['roomId']) ? $_GET['roomId'] : 0;

// Fetch room details based on roomId
$query = "SELECT * FROM rooms WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $roomId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $room = $result->fetch_assoc();
} else {
    echo "Room not found!";
    exit;
}

// Handle form submission (booking process)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $telephone = $_POST['telephone'];
    $checkIn = $_POST['checkIn'];
    $checkOut = $_POST['checkOut'];

    // Insert booking data into the database
    $insertQuery = "INSERT INTO bookings (roomId, name, telephone, checkIn, checkOut) VALUES (?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param("issss", $roomId, $name, $telephone, $checkIn, $checkOut);
    
    if ($insertStmt->execute()) {
        echo "Booking successful!";
    } else {
        echo "Error: " . $insertStmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Room</title>
</head>
<body>
    <h1>Book <?php echo htmlspecialchars($room['name']); ?></h1>
    <p><strong>Price:</strong> MYR <?php echo number_format($room['price'], 2); ?> per night</p>

    <form action="bookRoom.php?roomId=<?php echo $roomId; ?>" method="POST">
        <label for="name">Your Name:</label>
        <input type="text" name="name" id="name" required><br>

        <label for="telephone">Telephone:</label>
        <input type="text" name="telephone" id="telephone" required><br>

        <label for="checkIn">Check-in Date:</label>
        <input type="date" name="checkIn" id="checkIn" required><br>

        <label for="checkOut">Check-out Date:</label>
        <input type="date" name="checkOut" id="checkOut" required><br>

        <button type="submit">Confirm Booking</button>
    </form>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
