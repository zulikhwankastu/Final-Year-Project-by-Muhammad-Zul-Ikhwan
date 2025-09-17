<?php
require '_db.php';

// Validate room ID from URL
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    echo "Invalid room ID.";
    exit;
}

$room_id = $_GET['id'];

// Get room details along with property info
$sql = "SELECT r.*, p.name AS property_name 
        FROM rooms r 
        LEFT JOIN properties p ON r.property_id = p.id 
        WHERE r.id = ?";
$stmt = $dbc->prepare($sql);
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Room not found.";
    exit;
}

$room = $result->fetch_assoc();
$stmt->close();
$dbc->close();

// Handle image(s)
$images = array_map('trim', explode(',', $room['image']));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($room['name']) ?> - Room Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 20px auto;
            background: #f5f5f5;
            padding: 20px;
            color: #333;
        }

        h1, h2 {
            color: #2c3e50;
        }

        .image-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        .image-gallery img {
            width: 280px;
            height: 180px;
            object-fit: cover;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .room-details p {
            font-size: 16px;
            margin: 10px 0;
        }

        .back-button {
            margin-top: 30px;
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

        .price-tag {
            font-weight: bold;
            font-size: 18px;
            color: #27ae60;
        }

        .label {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h1><?= htmlspecialchars($room['name']) ?></h1>

    <div class="image-gallery">
        <?php foreach ($images as $img): 
            $imgPath = '';
            if (preg_match('#^(uploads/|images/)#i', $img)) {
                $imgPath = $img;
            } elseif (!empty($img)) {
                $imgPath = 'uploads/' . $img;
            } else {
                $imgPath = 'Images/default-room.jpg';
            }
        ?>
            <img src="<?= htmlspecialchars($imgPath) ?>" alt="<?= htmlspecialchars($room['name']) ?>">
        <?php endforeach; ?>
    </div>

    <div class="room-details">
        <p><span class="label">Property:</span> <?= htmlspecialchars($room['property_name'] ?? 'Unknown') ?></p>
        <p><span class="label">Price per night:</span> <span class="price-tag">RM<?= number_format($room['price'], 2) ?></span></p>
        <p><span class="label">Capacity:</span> <?= $room['capacity'] ?? 'Not specified' ?> person(s)</p>
        <p><span class="label">Available:</span> <?= $room['available'] ? 'Yes' : 'No' ?></p>
        <p><span class="label">Description:</span><br><?= nl2br(htmlspecialchars($room['description'])) ?></p>
    </div>

    <a href="index.php#rooms" class="back-button">← Back to Rooms</a>

</body>
</html>
