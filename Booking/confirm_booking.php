<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require '../_db.php'; // Adjust path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roomId = $_POST['room_id'];
    $checkIn = $_POST['check_in'];
    $checkOut = $_POST['check_out'];
    $guests = intval($_POST['guests']);

    // Fetch room details
    $stmt = $dbc->prepare("SELECT * FROM rooms WHERE id = ?");
    $stmt->bind_param("i", $roomId);
    $stmt->execute();
    $result = $stmt->get_result();
    $room = $result->fetch_assoc();
    $stmt->close();

    if (!$room) {
        echo "<p>Room not found.</p>";
        exit;
    }

    // Calculate total nights and price
    $date1 = new DateTime($checkIn);
    $date2 = new DateTime($checkOut);
    $nights = $date1->diff($date2)->days;
    $totalPrice = $nights * $room['price'];
} else {
    echo "<p>Invalid request.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirm Booking</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f9f9f9; }
        .room-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 6px rgba(0,0,0,0.1); max-width: 600px; margin: auto; }
        .room-card img { width: 100%; height: auto; border-radius: 6px; }
        .btn { background: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 5px; margin-top: 15px; cursor: pointer; }
        .btn:hover { background: #0056b3; }
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            resize: vertical;
            min-height: 80px;
        }
        label {
            display: block;
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="room-card">
    <h2>Confirm Your Booking</h2>
    
    <h3><?= htmlspecialchars($room['name']) ?></h3>
    <p><?= htmlspecialchars($room['description']) ?></p>
    <p><strong>Price per night:</strong> RM<?= number_format($room['price'], 2) ?></p>
    <p><strong>Check-in:</strong> <?= $checkIn ?></p>
    <p><strong>Check-out:</strong> <?= $checkOut ?></p>
    <p><strong>Total Nights:</strong> <?= $nights ?></p>
    <p><strong>Number of Guests:</strong> <?= $guests ?></p>
    <p><strong>Total Price:</strong> RM<?= number_format($totalPrice, 2) ?></p>

    <form action="payment.php" method="POST">
        <input type="hidden" name="room_id" value="<?= $roomId ?>">
        <input type="hidden" name="check_in" value="<?= $checkIn ?>">
        <input type="hidden" name="check_out" value="<?= $checkOut ?>">
        <input type="hidden" name="guests" value="<?= $guests ?>">
        <input type="hidden" name="total_price" value="<?= $totalPrice ?>">

        <label for="special_request">Special Request (Optional):</label>
        <textarea name="special_request" id="special_request" placeholder="e.g. Early check-in, extra pillows, etc."></textarea>

        <button type="submit" class="btn">✅ Confirm Booking</button>
    </form>
</div>

</body>
</html>
