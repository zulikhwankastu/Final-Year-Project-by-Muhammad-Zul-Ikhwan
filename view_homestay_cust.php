<?php
require '_db.php';

// Check if 'id' is provided in URL and is a valid integer
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    echo "Invalid homestay ID.";
    exit;
}

$homestay_id = $_GET['id'];

// Fetch homestay details
$stmt = $dbc->prepare("SELECT * FROM properties WHERE id = ?");
$stmt->bind_param("i", $homestay_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Homestay not found.";
    exit;
}

$homestay = $result->fetch_assoc();
$stmt->close();

// Fetch available rooms for this homestay
$room_stmt = $dbc->prepare("SELECT id, name, price, image FROM rooms WHERE property_id = ? AND available = 1");
$room_stmt->bind_param("i", $homestay_id);
$room_stmt->execute();
$room_result = $room_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($homestay['name']) ?> - Homestay Details</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 20px auto;
            background: #f9f9f9;
            color: #333;
            max-width: 900px;
        }
        h1, h2 {
            color: #2c3e50;
        }
        .image-container img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .details p {
            line-height: 1.6;
            margin-bottom: 12px;
        }
        .back-button, .book-button {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 18px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .back-button:hover, .book-button:hover {
            background-color: #0056b3;
        }
        .section-title {
            font-weight: 700;
            margin-top: 20px;
            margin-bottom: 8px;
            font-size: 1.2rem;
            color: #34495e;
        }
        /* Rooms grid */
        .rooms-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 15px;
        }
        .room-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.1);
            width: 250px;
            padding: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .room-card img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            border-radius: 6px;
            margin-bottom: 10px;
        }
        .room-card h3 {
            margin: 0 0 5px;
            font-size: 1.1rem;
            color: #2c3e50;
        }
        .room-card p {
            margin: 0 0 10px;
            font-weight: 600;
            color: #27ae60;
        }
    </style>
</head>
<body>

    <h1><?= htmlspecialchars($homestay['name']) ?></h1>

    <div class="image-container">
        <?php if (!empty($homestay['image'])): ?>
            <img src="uploads/<?= htmlspecialchars($homestay['image']) ?>" alt="<?= htmlspecialchars($homestay['name']) ?>">
        <?php else: ?>
            <img src="Images/default.jpg" alt="No Image Available">
        <?php endif; ?>
    </div>

    <div class="details">
        <p><strong>Address:</strong> <?= htmlspecialchars($homestay['address']) ?>, <?= htmlspecialchars($homestay['city']) ?>, <?= htmlspecialchars($homestay['state']) ?> <?= htmlspecialchars($homestay['zip_code']) ?></p>

        <div>
            <div class="section-title">Description</div>
            <p><?= nl2br(htmlspecialchars($homestay['description'])) ?></p>
        </div>

        <div>
            <div class="section-title">Amenities</div>
            <p><?= htmlspecialchars($homestay['amenities']) ?></p>
        </div>
    </div>

    

   <h2>Available Rooms</h2>
<div class="rooms-container">
    <?php if ($room_result->num_rows > 0): ?>
        <?php while ($room = $room_result->fetch_assoc()): ?>
            <?php
                $images = explode(',', $room['image']);
                $first_image = trim($images[0]);
                $room_image = !empty($first_image) ? $first_image : 'Images/default-room.jpg';
            ?>
            <div class="room-card">
                <img src="<?= htmlspecialchars($room_image) ?>" alt="<?= htmlspecialchars($room['name']) ?>">
                <h3><?= htmlspecialchars($room['name']) ?></h3>
                <p>Price: RM<?= number_format($room['price'], 2) ?> / night</p>
                <a class="book-button" href="room_list.php?homestay_id=<?= $homestay_id ?>">View All Rooms</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No rooms available currently.</p>
    <?php endif; ?>
</div>


<a href="index.php#homestays" class="back-button">← Back to Homestays</a>
</body>
</html>

<?php
$room_stmt->close();
$dbc->close();
?>
