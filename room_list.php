<?php
require '_db.php';

// Validate homestay_id
if (!isset($_GET['homestay_id']) || !filter_var($_GET['homestay_id'], FILTER_VALIDATE_INT)) {
    echo "Invalid homestay ID.";
    exit;
}

$homestay_id = $_GET['homestay_id'];

// Fetch homestay name (optional but nice)
$stmt = $dbc->prepare("SELECT name FROM properties WHERE id = ?");
$stmt->bind_param("i", $homestay_id);
$stmt->execute();
$homestay_result = $stmt->get_result();

if ($homestay_result->num_rows === 0) {
    echo "Homestay not found.";
    exit;
}
$homestay = $homestay_result->fetch_assoc();
$stmt->close();

// Fetch all rooms for this homestay
$room_stmt = $dbc->prepare("SELECT id, name, price, available, image FROM rooms WHERE property_id = ?");
$room_stmt->bind_param("i", $homestay_id);
$room_stmt->execute();
$rooms = $room_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($homestay['name']) ?> - Room List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 20px auto;
            max-width: 1000px;
            padding: 20px;
        }
        h1 {
            color: #2c3e50;
        }
        .room-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .room-card {
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            width: 280px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .room-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .room-card h3 {
            margin: 0 0 10px;
            font-size: 1.2em;
        }
        .room-card p {
            margin: 4px 0;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 5px;
        }
        .back-btn:hover {
            background-color: #0056b3;
        }
        .view-details-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 14px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
        .view-details-btn:hover {
             background-color: #218838;
        }

    </style>
</head>
<body>

<h1><?= htmlspecialchars($homestay['name']) ?> – All Rooms</h1>

<div class="room-list">
    <?php if ($rooms->num_rows > 0): ?>
        <?php while ($room = $rooms->fetch_assoc()): ?>
            <?php
                $images = explode(',', $room['image']);
                $first_image = trim($images[0]);
                $room_image = !empty($first_image) ? $first_image : 'Images/default-room.jpg';
            ?>
          <div class="room-card">
             <img src="<?= htmlspecialchars($room_image) ?>" alt="<?= htmlspecialchars($room['name']) ?>">
             <h3><?= htmlspecialchars($room['name']) ?></h3>
             <p>Price: RM<?= number_format($room['price'], 2) ?> / night</p>
             <p>Status: <?= $room['available'] ? '<span style="color:green;">Available</span>' : '<span style="color:red;">Unavailable</span>' ?></p>
             <a href="room_details_cust.php?room_id=<?= $room['id'] ?>" class="view-details-btn">View Details</a>
         </div>

        <?php endwhile; ?>
    <?php else: ?>
        <p>No rooms found for this homestay.</p>
    <?php endif; ?>
</div>

<a href="javascript:history.back()" class="back-btn">← Back</a>

</body>
</html>

<?php
$room_stmt->close();
$dbc->close();
?>
